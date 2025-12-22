<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventList extends Component
{
    use WithPagination;

    public $limit = null;
    public $perPage = 8; // Default items per page

    public function mount($limit = null, $perPage = 8)
    {
        $this->limit = $limit;
        $this->perPage = $perPage;
    }

    public function render()
    {
        $query = Event::latest();
        
        if ($this->limit) {
            // For homepage or limited display (no pagination)
            $events = $query->take($this->limit)->get();
        } else {
            // For full event page with pagination
            $events = $query->paginate($this->perPage);
        }

        return view('livewire.user.event-list', compact('events'));
    }

    // Reset pagination when needed
    public function updatingSearch()
    {
        $this->resetPage();
    }
}