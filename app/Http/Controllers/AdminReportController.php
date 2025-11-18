<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function generate(Event $event)
    {
        // Load event with participants & pivot data
        $event->load(['pesertas' => function ($query) {
            $query->withPivot(['kategori', 'status_bayaran']);
        }]);

        $pdf = Pdf::loadView('admin.report', [
            'event' => $event,
            'participants' => $event->pesertas,
        ]);

        return $pdf->download('event_report_' . $event->id . '.pdf');
    }
}
