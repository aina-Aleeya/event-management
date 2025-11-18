<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;


class HistoryPage extends Component
{
    public $historyEvent;

    public function mount(){
        $this->show();
    }

    public function show(){
        $userId = auth()->id();

        $this->historyEvent = DB::table('penyertaan')
        ->join('events', 'penyertaan.event_id', '=', 'events.id')
        ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
        ->select('events.id as event_id', 'events.title','penyertaan.pendaftar_id')
        ->selectRaw('COUNT(pesertas.id) as total')
        ->selectRaw('SUM(CASE WHEN penyertaan.status_bayaran = "pending" THEN 1 ELSE 0 END) as pending_count')
        ->selectRaw("CASE WHEN SUM(CASE WHEN penyertaan.status_bayaran = 'pending' THEN 1 ELSE 0 END) > 0
                    THEN 'Pending'
                    ELSE 'Complete'
                    END as payment_status")
        ->where('penyertaan.pendaftar_id', $userId)
        ->groupBy('events.id', 'events.title','penyertaan.pendaftar_id')
        ->get();
    }

    public function render()
    {
        return view('livewire.history-page');
    }
}
