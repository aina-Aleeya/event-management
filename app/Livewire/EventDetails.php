<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventDetails extends Component
{
    public $event;

    public function mount($id)
    {
        $this->event = Event::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.event-details');
    }
}