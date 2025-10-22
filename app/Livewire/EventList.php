<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventList extends Component
{
    public function render()
    {
        $events = Event::latest()->get();

        return view('livewire.event-list', compact('events'));
    }
}
