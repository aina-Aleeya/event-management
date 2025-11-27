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
    public $latestParticipants = [];

    public $totalParticipants = 0;
    public $completedPayments = 0;
    public $pendingPayments = 0;
    public $totalRevenue = 0;
    public $clickCount = 0;

    public $topRankings = [];

    public function mount(Event $event)
    {
        $this->event = $event;

        $this->participants = Penyertaan::with('peserta')
            ->where('event_id', $event->id)
            ->get();

        $this->totalParticipants = $this->participants->count();
        $this->completedPayments = $this->participants->where('status_bayaran', 'complete')->count();
        $this->pendingPayments = $this->participants->where('status_bayaran', '!=', 'complete')->count();

        $this->totalRevenue = ($event->entry_fee ?? 0) * $this->completedPayments;

        $this->clickCount = $event->click_count ?? 0;

        $this->latestParticipants = $event->pesertas()
            ->withPivot('kategori', 'unique_id', 'status_bayaran', 'created_at')
            ->orderBy('penyertaan.created_at', 'asc') // lama daftar dulu
            ->take(5)
            ->get();

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
            'latestParticipants' => $this->latestParticipants,
            'totalParticipants' => $this->totalParticipants,
            'completedPayments' => $this->completedPayments,
            'pendingPayments' => $this->pendingPayments,
            'totalRevenue' => $this->totalRevenue,
            'topRankings' => $this->topRankings,
            'clickCount' => $this->clickCount,
        ]);
    }
}