<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Peserta;
use App\Models\Penyertaan;
use \App\Models\Event;
use Illuminate\Support\Facades\Auth;

class SenaraiPeserta extends Component
{
    public $pesertas = [];
    public $events;

    public function mount()
    {
        $user = Auth::user();
        $this->events = Penyertaan::with(['event', 'peserta'])
            ->where('pendaftar_id', $user->id)
            ->orderBy('event_id')
            ->get()
            ->groupBy('event_id');
    }

    public function render()
    {
        return view('livewire.senarai-peserta');
    }
}
