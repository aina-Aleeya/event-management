<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Event;

class EventDetails extends Component
{
    public $event;

    public function mount($id)
    {
        $this->event = Event::with(['categories', 'customCategories'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.user.event-details');
    }
}