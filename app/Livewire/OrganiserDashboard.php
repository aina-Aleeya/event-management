<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class OrganiserDashboard extends Component
{

    use WithPagination;

    public $events;
    public $totalEvents = 0;
    public $pendingEvents = 0;
    public $approvedEvents = 0;
    public $rejectedEvents = 0;

    public $status = "";

    public function mount()
    {
        $organiserId = Auth::id();

        $this->events = Event::where('user_id', $organiserId)->latest()->get();

        $this->totalEvents = $this->events->count();
        $this->pendingEvents = $this->events->where('status', 'pending')->count();
        $this->approvedEvents = $this->events->where('status', 'approved')->count();
        $this->rejectedEvents = $this->events->where('status', 'rejected')->count();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    // public function deleteEvent($id)
    // {
    //     Event::where('id', $id)->where('user_id', Auth::id())->delete();

    //     session()->flash('success', 'Event deleted successfully!');
    // }

    public function render()
    {
        $events = Event::where('user_id', Auth::id())
        ->when($this->status !== "", function ($query) {
            $query->whereHas('status', function ($q) {
                $q->where('status', $this->status);
            });
        })
        ->with('status')
        ->latest()
        ->paginate(10);

    return view('livewire.organiser-dashboard', compact('events'));
    }
}
