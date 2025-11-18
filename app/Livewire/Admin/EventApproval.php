<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventStatus;

class EventApproval extends Component
{
    public $reason = [];

    public function approveEvent($eventId)
    {
        EventStatus::where('event_id', $eventId)->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        session()->flash('message', 'Event approved successfully.');
    }

    public function rejectEvent($eventId)
    {
        // Retrieve reason from bound array
        $reason = $this->reason[$eventId] ?? null;

        // Validate reason
        $this->validate([
            "reason.$eventId" => 'required|string|min:5',
        ], [
            "reason.$eventId.required" => 'Please provide a reason for rejection.',
            "reason.$eventId.min" => 'The reason must be at least 5 characters.',
        ]);

        // Update event status
        EventStatus::where('event_id', $eventId)->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Clear input
        unset($this->reason[$eventId]);

        // Flash message
        session()->flash('message', 'Event rejected successfully.');
    }

    // public function rejectEvent($eventId, $reason)
    // {
    //     $this->validate([
    //         'reason.' . $eventId => 'required|string|min:5',
    //     ], [
    //         'reason.' . $eventId . '.required' => 'Please provide a reason for rejection.',
    //         'reason.' . $eventId . '.min' => 'The reason must be at least 5 characters.',
    //     ]);

    //     EventStatus::where('event_id', $eventId)->update([
    //         'status' => 'rejected',
    //         'rejection_reason' => $reason,
    //     ]);

    //     // Optional: clear input after submission
    //     unset($this->reason[$eventId]);

    //     session()->flash('message', 'Event rejected successfully.');
    // }

    public function render()
    {
        return view('livewire.admin.event-approval', [
            'pendingEvents' => Event::whereHas('status', fn($q) => $q->where('status', 'pending'))->get(),
        ]);
    }
}
