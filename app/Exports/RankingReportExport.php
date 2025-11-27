<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\RankingReport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RankingReportExport implements WithMultipleSheets
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Check Individu data exists
        $hasIndividu = RankingReport::where('event_id', $this->event->id)
            ->whereHas('penyertaan', fn($q) => $q->where('kategori', 'like', 'I%'))
            ->exists();

        // Check Group data exists
        $hasGroup = RankingReport::where('event_id', $this->event->id)
            ->whereHas('penyertaan', fn($q) => $q->where('kategori', 'like', 'G%'))
            ->exists();

        if ($hasIndividu) {
            $sheets['Individu'] = new Sheets\IndividuRankingSheet($this->event);
        }

        if ($hasGroup) {
            $sheets['Berkumpulan'] = new Sheets\GroupRankingSheet($this->event);
        }

        return $sheets;
    }
}
