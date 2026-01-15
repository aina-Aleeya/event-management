<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Penyertaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class CertificateController extends Controller
{
    private function getParticipantCategory($eventId, $pesertaId)
    {
        try {
            $penyertaan = DB::table('penyertaan')
                ->where('event_id', $eventId)
                ->where('peserta_id', $pesertaId)
                ->first();

            if (!$penyertaan) {
                Log::warning('No penyertaan found', [
                    'event_id' => $eventId,
                    'peserta_id' => $pesertaId
                ]);
                return 'General';
            }

            if (!$penyertaan->categorizable_type || !$penyertaan->categorizable_id) {
                Log::info('No category assigned to participant', [
                    'event_id' => $eventId,
                    'peserta_id' => $pesertaId
                ]);
                return 'General';
            }

            $categoryName = null;

            if ($penyertaan->categorizable_type === 'App\\Models\\Category') {
                $category = DB::table('categories')
                    ->where('id', $penyertaan->categorizable_id)
                    ->first();
                $categoryName = $category ? $category->name : null;
            } elseif ($penyertaan->categorizable_type === 'App\\Models\\CustomCategory') {
                $customCategory = DB::table('custom_categories')
                    ->where('id', $penyertaan->categorizable_id)
                    ->first();
                $categoryName = $customCategory ? $customCategory->name : null;
            }

            if ($categoryName) {
                Log::info('Category found', [
                    'event_id' => $eventId,
                    'peserta_id' => $pesertaId,
                    'category' => $categoryName
                ]);
                return $categoryName;
            }

            Log::warning('Category not found in tables', [
                'event_id' => $eventId,
                'peserta_id' => $pesertaId,
                'categorizable_type' => $penyertaan->categorizable_type,
                'categorizable_id' => $penyertaan->categorizable_id
            ]);

            return 'General';
        } catch (\Exception $e) {
            Log::error('Error getting participant category', [
                'event_id' => $eventId,
                'peserta_id' => $pesertaId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 'General';
        }
    }

    public function exportSingle($eventId, $pesertaId)
    {
        try {
            $event = Event::findOrFail($eventId);
            $peserta = Peserta::findOrFail($pesertaId);

            $category = $this->getParticipantCategory($eventId, $pesertaId);

            Log::info('Generating single certificate', [
                'event' => $event->title,
                'participant' => $peserta->nama_penuh,
                'category' => $category
            ]);

            $templatePath = null;
            if (method_exists($event, 'getCertificateTemplatePath')) {
                $templatePath = $event->getCertificateTemplatePath($category);
                if ($templatePath) {
                    Log::info('Using custom template', ['path' => $templatePath]);
                }
            }

            $pdf = Pdf::loadView('pdf.certificate', [
                'event' => $event,
                'peserta' => $peserta,
                'category' => $category,
                'templatePath' => $templatePath,
                'date' => now()->format('F d, Y')
            ])->setPaper('a4', 'landscape');

            $filename = 'Certificate_' . str_replace(' ', '_', $peserta->nama_penuh) . '.pdf';

            Log::info('Certificate generated successfully');

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Certificate Export Error', [
                'event_id' => $eventId,
                'peserta_id' => $pesertaId,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->back()->with('error', 'Failed to generate certificate: ' . $e->getMessage());
        }
    }

    public function exportGroup($eventId, $groupId)
    {
        try {
            if (!class_exists('ZipArchive')) {
                return redirect()->back()->with('error', 'ZipArchive extension is not enabled. Please enable it in your php.ini file.');
            }

            $event = Event::findOrFail($eventId);
            $group = \App\Models\Group::with('pesertas')->findOrFail($groupId);

            if ($group->pesertas->isEmpty()) {
                return redirect()->back()->with('error', 'No participants found in this group.');
            }

            Log::info('Starting group certificate export', [
                'event' => $event->title,
                'group' => $group->name,
                'participant_count' => $group->pesertas->count()
            ]);

            $certificatesDir = storage_path('app/temp');
            if (!file_exists($certificatesDir)) {
                mkdir($certificatesDir, 0755, true);
            }

            $zipFilename = 'Certificates_' . str_replace(' ', '_', $group->name) . '_' . now()->timestamp . '.zip';
            $zipPath = $certificatesDir . '/' . $zipFilename;

            $zip = new ZipArchive();
            $zipStatus = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($zipStatus !== TRUE) {
                Log::error('Failed to create ZIP file. Status: ' . $zipStatus);
                return redirect()->back()->with('error', 'Could not create ZIP file. Error code: ' . $zipStatus);
            }

            $successCount = 0;
            $errors = [];

            foreach ($group->pesertas as $peserta) {
                try {
                    $category = $this->getParticipantCategory($eventId, $peserta->id);

                    $templatePath = null;
                    if (method_exists($event, 'getCertificateTemplatePath')) {
                        $templatePath = $event->getCertificateTemplatePath($category);
                    }

                    $pdf = Pdf::loadView('pdf.certificate', [
                        'event' => $event,
                        'peserta' => $peserta,
                        'category' => $category,
                        'templatePath' => $templatePath,
                        'date' => now()->format('F d, Y')
                    ])->setPaper('a4', 'landscape');

                    $certificateFilename = 'Certificate_' . str_replace(' ', '_', $peserta->nama_penuh) . '.pdf';
                    $zip->addFromString($certificateFilename, $pdf->output());
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = $peserta->nama_penuh;
                    Log::error('Failed to generate certificate for participant', [
                        'participant' => $peserta->nama_penuh,
                        'error' => $e->getMessage(),
                        'line' => $e->getLine()
                    ]);
                    continue;
                }
            }

            $zip->close();

            if ($successCount === 0) {
                return redirect()->back()->with('error', 'Failed to generate any certificates. Check logs for details.');
            }

            Log::info('Group certificate export completed', [
                'success_count' => $successCount,
                'failed_count' => count($errors)
            ]);

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Certificate Group Export Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->back()->with('error', 'Failed to generate certificates: ' . $e->getMessage());
        }
    }

    public function exportAllParticipants(Request $request, $eventId)
    {
        try {
            if (!class_exists('ZipArchive')) {
                return redirect()->back()->with('error', 'ZipArchive extension is not enabled. Please enable it in your php.ini file.');
            }

            $event = Event::with('pesertas')->findOrFail($eventId);
            $filterCategory = $request->input('category');

            $pesertas = $event->pesertas;

            if ($pesertas->isEmpty()) {
                return redirect()->back()->with('error', 'No participants found for this event.');
            }

            Log::info('Starting all participants certificate export', [
                'event' => $event->title,
                'total_participants' => $pesertas->count(),
                'filter_category' => $filterCategory ?? 'all'
            ]);

            $certificatesDir = storage_path('app/temp');
            if (!file_exists($certificatesDir)) {
                mkdir($certificatesDir, 0755, true);
            }

            $zipFilename = 'Certificates_' . str_replace(' ', '_', $event->title);
            if ($filterCategory) {
                $zipFilename .= '_' . str_replace(' ', '_', $filterCategory);
            }
            $zipFilename .= '_' . now()->timestamp . '.zip';
            $zipPath = $certificatesDir . '/' . $zipFilename;

            $zip = new ZipArchive();
            $zipStatus = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if ($zipStatus !== TRUE) {
                Log::error('Failed to create ZIP file. Status: ' . $zipStatus);
                return redirect()->back()->with('error', 'Could not create ZIP file. Error code: ' . $zipStatus);
            }

            $successCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($pesertas as $peserta) {
                try {
                    $participantCategory = $this->getParticipantCategory($eventId, $peserta->id);

                    if ($filterCategory && $participantCategory !== $filterCategory) {
                        $skippedCount++;
                        continue;
                    }

                    $templatePath = null;
                    if (method_exists($event, 'getCertificateTemplatePath')) {
                        $templatePath = $event->getCertificateTemplatePath($participantCategory);
                    }

                    $pdf = Pdf::loadView('pdf.certificate', [
                        'event' => $event,
                        'peserta' => $peserta,
                        'category' => $participantCategory,
                        'templatePath' => $templatePath,
                        'date' => now()->format('F d, Y')
                    ])->setPaper('a4', 'landscape');

                    $certificateFilename = 'Certificate_' . str_replace(' ', '_', $peserta->nama_penuh) . '.pdf';
                    $zip->addFromString($certificateFilename, $pdf->output());
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = $peserta->nama_penuh;
                    Log::error('Failed to generate certificate for participant', [
                        'participant' => $peserta->nama_penuh,
                        'error' => $e->getMessage(),
                        'line' => $e->getLine()
                    ]);
                    continue;
                }
            }

            $zip->close();

            if ($successCount === 0) {
                $message = 'No certificates were generated.';
                if ($filterCategory) {
                    $message .= " No participants found in category '{$filterCategory}'.";
                }
                return redirect()->back()->with('error', $message);
            }

            Log::info('All participants certificate export completed', [
                'success_count' => $successCount,
                'skipped_count' => $skippedCount,
                'failed_count' => count($errors)
            ]);

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Certificate All Export Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->back()->with('error', 'Failed to generate certificates: ' . $e->getMessage());
        }
    }

    public function managementPage($eventId)
    {
        $event = Event::with(['pesertas' => function ($query) {
            $query->with(['categorizable']);
        }])->findOrFail($eventId);

        $totalParticipants = $event->pesertas->count();

        $categoriesData = $event->pesertas->groupBy(function ($peserta) {
            if ($peserta->categorizable) {
                return $peserta->categorizable->name;
            }
            return 'General';
        })->map(function ($group, $categoryName) {
            return [
                'name' => $categoryName,
                'count' => $group->count()
            ];
        })->values()->toArray();

        $categories = $categoriesData;

        return view('admin.certificate-management', compact('event', 'totalParticipants', 'categories'));
    }

    public function updateSettings(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $validated = $request->validate([
            'certificates_enabled' => 'nullable|boolean',
            'certificates_available_from' => 'nullable|date',
            'certificate_message' => 'nullable|string|max:1000',
        ]);

        $event->update([
            'certificates_enabled' => $request->has('certificates_enabled'),
            'certificates_available_from' => $request->certificates_available_from,
            'certificate_message' => $request->certificate_message,
        ]);

        return redirect()->back()->with('success', 'Certificate settings updated successfully!');
    }

    public function participantCertificates($eventId)
    {
        $event = Event::findOrFail($eventId);

        if (!$event->areCertificatesAvailable()) {
            return view('participant.certificates-not-available', compact('event'));
        }

        return view('participant.certificates', compact('event'));
    }

    public function downloadParticipantCertificate(Request $request, $eventId)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $event = Event::findOrFail($eventId);

        if (!$event->areCertificatesAvailable()) {
            return redirect()->back()->with('error', 'Certificates are not available for this event yet.');
        }

        $peserta = Peserta::where('email', $request->email)->first();

        if (!$peserta) {
            return redirect()->back()->with('error', 'No participant found with this email address.');
        }

        $penyertaan = DB::table('penyertaan')
            ->where('event_id', $eventId)
            ->where('peserta_id', $peserta->id)
            ->first();

        if (!$penyertaan) {
            return redirect()->back()->with('error', 'You are not registered for this event.');
        }

        $category = $this->getParticipantCategory($eventId, $peserta->id);

        try {
            $templatePath = null;
            if (method_exists($event, 'getCertificateTemplatePath')) {
                $templatePath = $event->getCertificateTemplatePath($category);
            }

            $pdf = Pdf::loadView('pdf.certificate', [
                'event' => $event,
                'peserta' => $peserta,
                'category' => $category,
                'templatePath' => $templatePath,
                'date' => now()->format('F d, Y')
            ])->setPaper('a4', 'landscape');

            $filename = 'Certificate_' . str_replace(' ', '_', $peserta->nama_penuh) . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Participant Certificate Download Error', [
                'event_id' => $eventId,
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Failed to generate certificate. Please try again or contact the organizer.');
        }
    }
}
