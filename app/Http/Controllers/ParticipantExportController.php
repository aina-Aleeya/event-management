<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Exports\Sheets\ParticipantsSheet;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantExportController extends Controller
{
    public function export(Event $event)
    {
        $fileName = 'Participants_' . $event->title . '.xlsx';

        return Excel::download(new ParticipantsSheet($event), $fileName);
    }
}
