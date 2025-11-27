<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RankingReportExport;

class RankingExportController extends Controller
{
    public function export(Event $event)
    {
        $filename = 'Ranking_Report_' . $event->title . '.xlsx';

        return Excel::download(new RankingReportExport($event), $filename);
    }
}
