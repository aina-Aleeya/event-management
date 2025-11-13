<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Penyertaan;
use App\Models\RankingReport;

class EventDashboardPage extends Component
{
    public $event;
    public $participants;

    // Dashboard summary data
    public $totalParticipants = 0;
    public $completedPayments = 0;
    public $pendingPayments = 0;
    public $totalRevenue = 0;

    // Ranking data
    public $topRankings = [];

    public function mount(Event $event)
    {
        $this->event = $event;

        // Fetch participants
        $this->participants = Penyertaan::with('peserta')
            ->where('event_id', $event->id)
            ->get();

        // Calculate summary
        $this->totalParticipants = $this->participants->count();
        $this->completedPayments = $this->participants->where('status_bayaran', 'complete')->count();
        $this->pendingPayments = $this->participants->where('status_bayaran', '!=', 'complete')->count();

        // Revenue (assume event table ada column entry_fee)
        $this->totalRevenue = ($event->entry_fee ?? 0) * $this->completedPayments;

        // Get top 3 rankings
        $this->topRankings = RankingReport::with('penyertaan.peserta')
            ->where('event_id', $event->id)
            ->orderBy('ranking')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.event-dashboard-page', [
            'event' => $this->event,
            'participants' => $this->participants,
            'totalParticipants' => $this->totalParticipants,
            'completedPayments' => $this->completedPayments,
            'pendingPayments' => $this->pendingPayments,
            'totalRevenue' => $this->totalRevenue,
            'topRankings' => $this->topRankings,
        ]);
    }
}
