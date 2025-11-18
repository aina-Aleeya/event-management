<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventList extends Component
{
    use WithPagination;

    public $limit = null; 

    public function render()
    {
        $query = Event::whereHas('status', function ($q) {
            $q->where('status', 'approved');
        })->latest();
        
        if ($this->limit) {
            $events = $query->take($this->limit)->get();
        } 
   
        else {
            $events = $query->paginate(12);
        }

        return view('livewire.event-list', compact('events'));
    }
}
