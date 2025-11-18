<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Penyertaan;
use App\Models\RankingReport;

class LeaderboardPage extends Component
{
    public $eventId;
    public $topThree = [];
    public $allParticipants = [];

    public function mount($event)
    {
        $this->eventId = $event;
        $this->loadLeaderboard();
    }

    public function loadLeaderboard()
    {

        $rankings = RankingReport::with('penyertaan.peserta')
            ->whereHas('penyertaan', fn($q) => $q->where('event_id', $this->eventId))
            ->orderBy('ranking')
            ->get();

        $this->topThree = $rankings->take(3);


        $rankedPenyertaanIds = $rankings->pluck('penyertaan_id')->toArray();

        $this->allParticipants = Penyertaan::with('peserta')
            ->where('event_id', $this->eventId)
            ->whereNotIn('id', $rankedPenyertaanIds)
            ->get();
    }

    public function getRankStyle($rank)
    {
        $styles = [
            1 => [
                'height' => 'h-35 md:h-40',
                'bg' => 'bg-[#F87171]', 
                'border' => 'border-[#DC6A6A]',
                'avatar' => 'w-20 h-20 md:w-24 md:h-24',
                'ring' => 'ring-red-400',
                'nameText' => 'text-base',
            ],
            2 => [
                'height' => 'h-25 md:h-30',
                'bg' => 'bg-[#F87171]',
                'border' => 'border-[#DC6A6A]',
                'avatar' => 'w-16 h-16 md:w-20 md:h-20',
                'ring' => 'ring-red-400',
                'nameText' => 'text-sm',
            ],
            3 => [
                'height' => 'h-15 md:h-20',
                'bg' => 'bg-[#F87171]', 
                'border' => 'border-[#DC6A6A]',
                'avatar' => 'w-14 h-14 md:w-18 md:h-18',
                'ring' => 'ring-red-400',
                'nameText' => 'text-sm',
            ],
        ];

        return $styles[$rank] ?? $styles[3];
    }

    public function render()
    {
        $event = Event::find($this->eventId);
        return view('livewire.leader-board-page', [
            'event' => $event,
        ]);
    }
}
