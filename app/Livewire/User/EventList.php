<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventList extends Component
{
    use WithPagination;

    public $limit = null; 

    public function render()
    {
        $query = Event::latest();
        
        if ($this->limit) {
            $events = $query->take($this->limit)->get();
        } 
   
        else {
            $events = $query->paginate(12);
        }

        return view('livewire.user.event-list', compact('events'));
    }
}