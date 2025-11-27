<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Penyertaan;
use App\Models\RankingReport;

class LeaderboardPage extends Component
{
    public $eventId;
    public $category = 'Individu'; // Default tab
    public $topThree = [];
    public $rankList = [];

    public function mount($event)
    {
        $this->eventId = $event;
        $this->loadLeaderboard();
    }

    public function updatedCategory()
    {
        $this->loadLeaderboard();
    }

public function loadLeaderboard()
{
    $reports = RankingReport::with('penyertaan.peserta')
        ->whereHas('penyertaan', fn($q) =>
            $q->where('event_id', $this->eventId)
              ->where('kategori', 'like', $this->category === 'Individu' ? 'I%' : 'G%')
        )
        ->orderBy('ranking')
        ->get();

    if ($this->category === 'Berkumpulan') {
        $reports = $reports->map(function($report) {

            // Cari group peserta melalui pivot table
            $gp = \DB::table('group_peserta')
                ->where('event_id', $this->eventId)
                ->where('peserta_id', $report->penyertaan->peserta_id)
                ->first();

            if ($gp) {
                $group = \App\Models\Group::with('pesertas')->find($gp->group_id);
                if ($group) {
                    $report->group_name = $group->name;
                    $report->group_members = $group->pesertas->pluck('nama_penuh')->implode(', ');
                }
            }

            return $report;
        });
    }

    // TOP 3
    $this->topThree = $reports->take(3);

    // RANK LIST
    $this->rankList = $reports->skip(3)->values();
}


    public function render()
    {
        $event = Event::find($this->eventId);

        return view('livewire.leader-board-page', [
            'event' => $event,
        ]);
    }
}
