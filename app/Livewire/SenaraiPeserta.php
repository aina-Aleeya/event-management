<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Peserta;
use App\Models\Penyertaan;
use \App\Models\Event;

class SenaraiPeserta extends Component
{
    public $pesertas = [];
    public $event;

    public function mount($id)
    {
        $this->event = Event::find($id);
        $this->pesertas = Penyertaan::with('peserta')
            ->where('event_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.senarai-peserta');
    }
}