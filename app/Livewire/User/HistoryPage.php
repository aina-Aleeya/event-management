<?php

namespace App\Livewire\User;

use App\Models\Penyertaan;
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

        // $this->historyEvent = DB::table('penyertaan')
        // ->join('events', 'penyertaan.event_id', '=', 'events.id')
        // ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
        // ->select('events.id as event_id', 'events.title','penyertaan.pendaftar_id')
        // ->selectRaw('COUNT(pesertas.id) as total')
        // ->selectRaw('SUM(CASE WHEN penyertaan.status_bayaran = "pending" THEN 1 ELSE 0 END) as pending_count')
        // ->selectRaw("CASE WHEN SUM(CASE WHEN penyertaan.status_bayaran = 'pending' THEN 1 ELSE 0 END) > 0
        //             THEN 'Pending'
        //             ELSE 'Complete'
        //             END as payment_status")
        // ->where('penyertaan.pendaftar_id', $userId)
        // ->groupBy('events.id', 'events.title','penyertaan.pendaftar_id')
        // ->get();
        
        $this->historyEvent = Penyertaan::with(['event','peserta'])
        ->where('pendaftar_id', $userId)
        ->get()
        ->groupBy('event_id')
        ->map(function($penyertaan){
            $event = $penyertaan->first()->event;
            return (object)[
                'event_id'=> $event->id,
                'title' => $event->title,
                'pendaftar_id'=> $penyertaan->first()->pendaftar_id,
                'total' => $penyertaan->count(),
                'pending_count' => $penyertaan->where('status_bayaran', 'pending')->count(),
                'payment_status' => $penyertaan->where('status_bayaran', 'pending')->count() > 0 ? 'Pending' : 'Complete'
            ];
        })
        ->values();
    }

    public function render()
    {
        return view('livewire.user.history-page');
    }
}
