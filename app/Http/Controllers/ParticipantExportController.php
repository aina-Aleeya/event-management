<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\ParticipantsSheet; // Existing export class anda
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ParticipantExportController extends Controller
{
    /**
     * Export participants to Excel
     */
    public function export(Event $event)
    {
        $fileName = 'Participants_' . $event->title . '.xlsx';

        return Excel::download(new ParticipantsSheet($event), $fileName);
    }

    /**
     * Export participants to PDF
     */
    public function exportParticipantsPdf($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Get participants with pivot data (guna 'pesertas' relationship)
        $participants = $event->pesertas()
            ->orderBy('nama_penuh')
            ->get();
        
        // Count statistics
        $totalParticipants = $participants->count();
        $completedPayments = $participants->where('pivot.status_bayaran', 'complete')->count();
        $pendingPayments = $participants->where('pivot.status_bayaran', 'pending')->count();
        
        $data = [
            'event' => $event,
            'participants' => $participants,
            'totalParticipants' => $totalParticipants,
            'completedPayments' => $completedPayments,
            'pendingPayments' => $pendingPayments,
            'generatedDate' => now()->format('d/m/Y H:i:s')
        ];
        
        $pdf = Pdf::loadView('pdf.participants', $data);
        $pdf->setPaper('a4', 'landscape'); // Landscape untuk table yang lebih luas
        
        $filename = 'Participants_' . str_replace(' ', '_', $event->title) . '_' . now()->format('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }
}