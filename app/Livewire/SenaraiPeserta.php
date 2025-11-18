<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Peserta;
use App\Models\Penyertaan;
use \App\Models\Event;
use Illuminate\Support\Facades\Auth;

class SenaraiPeserta extends Component
{
    public $registrations;
    public $eventId;

    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $userId = Auth::id();
        
        $this->registrations = Penyertaan::with(['event', 'peserta'])
            ->where('pendaftar_id', $userId)
            ->where('event_id', $eventId)
            ->orderBy('id')
            ->get();
    }

    public function render()
    {
        return view('livewire.senarai-peserta');
    }
}
