<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group;
use App\Models\Category;
use App\Models\CustomCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ScoresheetController extends Controller
{
    // Export scoresheet for single group
    public function exportGroup($eventId, $groupId)
    {
        $event = Event::findOrFail($eventId);
        $group = Group::with('pesertas')->findOrFail($groupId);

        if (empty($group->qr_code)) {
            $group->qr_code = $this->generateQrCode($group);
            $group->save();
        }

        $group->pesertas->each(function ($peserta) use ($eventId) {
            $peserta->category = $this->getParticipantCategory($peserta->id, $eventId);
        });

        $pdf = PDF::loadView('pdf.scoresheet-group', [
            'event' => $event,
            'group' => $group,
            'participants' => $group->pesertas
        ])->setPaper('a4', 'landscape');

        $filename = $this->generateFilename($event->title, $group->name);

        return $pdf->download($filename);
    }

    // Export scoresheets for all groups 
    public function exportAllGroups(Request $request, $eventId)
    {
        $event = Event::with(['groups.pesertas'])->findOrFail($eventId);
        $categoryFilter = $request->input('category');

        $groups = $event->groups->map(function ($group) use ($eventId, $categoryFilter) {
            $group->pesertas->each(function ($peserta) use ($eventId) {
                $peserta->category = $this->getParticipantCategory($peserta->id, $eventId);
            });

            $group->filtered_pesertas = $categoryFilter
                ? $group->pesertas->filter(fn($peserta) => $peserta->category === $categoryFilter)
                : $group->pesertas;

            return $group;
        });

        if ($categoryFilter) {
            $groups = $groups->filter(fn($group) => $group->filtered_pesertas->isNotEmpty());
        }

        $pdf = PDF::loadView('pdf.scoresheet-all-groups', [
            'event' => $event,
            'groups' => $groups,
            'category' => $categoryFilter
        ])->setPaper('a4', 'landscape');

        $filename = $this->generateBulkFilename($event->title, $categoryFilter);

        return $pdf->download($filename);
    }

    // Generate QR code  
    public function store(Request $request)
    {
        $group = Group::create([
            'name' => $request->name,
            'token' => Str::random(32),
            'competition_id' => $request->competition_id,

        ]);

        $group->generateQrCode();

        return redirect()->back()->with('success', 'Group created successfully!');
    }

    // Get participant category 
    private function getParticipantCategory(int $pesertaId, int $eventId): string
    {
        $penyertaan = \DB::table('penyertaan')
            ->where('event_id', $eventId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$penyertaan) {
            return 'Uncategorized';
        }

        if ($penyertaan->categorizable_type === Category::class) {
            $category = Category::find($penyertaan->categorizable_id);
            return $category?->name ?? 'Uncategorized';
        }

        if ($penyertaan->categorizable_type === CustomCategory::class) {
            $category = CustomCategory::find($penyertaan->categorizable_id);
            return $category?->name ?? 'Uncategorized';
        }

        return 'Uncategorized';
    }

    // Generate filename
    private function generateFilename(string $eventTitle, string $groupName): string
    {
        $eventSlug = str_replace(' ', '_', $eventTitle);
        $groupSlug = str_replace(' ', '_', $groupName);

        return "{$eventSlug}_{$groupSlug}_Scoresheet.pdf";
    }

    // Generate filename
    private function generateBulkFilename(string $eventTitle, ?string $category): string
    {
        $eventSlug = str_replace(' ', '_', $eventTitle);
        $filename = "{$eventSlug}_All_Groups";

        if ($category) {
            $categorySlug = str_replace(' ', '_', $category);
            $filename .= "_{$categorySlug}";
        }

        $filename .= '_Scoresheet_' . now()->format('Y-m-d') . '.pdf';

        return $filename;
    }
}