<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\EventStatus;
use Illuminate\Support\Facades\Auth;

class EditEvent extends Component
{
    use WithFileUploads;

    public $event;
    public $description;
    public $posters;
    public $newPoster;

    // Basic info
    public $title, $event_type, $venue, $city, $contact_email, $contact_phone;

    // Dates
    public $start_date, $end_date, $start_time, $end_time, $registration_deadline;

    // Categories
    public $categories = [];

    // Optional
    public $entry_fee, $max_participants;

    // Ads
    public $ads_start_date, $ads_end_date;

    public function mount($eventId)
    {
        $this->event = Event::findOrFail($eventId);

        // pre-fill form
        $this->fill([
            'title' => $this->event->title,
            'description' => $this->event->description,
            'posters' => $this->event->posters,
            'event_type' => $this->event->event_type,
            'venue' => $this->event->venue,
            'city' => $this->event->city,
            'contact_email' => $this->event->contact_email,
            'contact_phone' => $this->event->contact_phone,
            'start_date' => optional($this->event->start_date)->format('Y-m-d'),
            'end_date' => optional($this->event->end_date)->format('Y-m-d'),
            'registration_deadline' => optional($this->event->registration_deadline)->format('Y-m-d'),
            'start_time' => $this->event->start_time,
            'end_time' => $this->event->end_time,
            'registration_deadline' => $this->event->registration_deadline,
            'categories' => $this->event->categories ?? [],
            'entry_fee' => $this->event->entry_fee,
            'max_participants' => $this->event->max_participants,
            'ads_start_date' => optional($this->event->ads_start_date)->format('Y-m-d'),
            'ads_end_date' => optional($this->event->ads_end_date)->format('Y-m-d'),
        ]);
    }

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'newPoster' => 'nullable|image|max:2048',
        'event_type' => 'nullable|string|max:100',
        'venue' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'contact_email' => 'nullable|email',
        'contact_phone' => 'nullable|string|max:30',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'registration_deadline' => 'nullable|date|before_or_equal:end_date',
        'categories' => 'array',
        'entry_fee' => 'nullable|numeric|min:0',
        'max_participants' => 'nullable|integer|min:1',
        'ads_start_date' => 'nullable|date',
        'ads_end_date' => 'nullable|date|after_or_equal:ads_start_date',
    ];

    public function update()
    {
        $this->validate();

        $posterPath = $this->posters;
        if ($this->newPoster) {
            $posterPath = $this->newPoster->store('event_posters', 'public');
        }

        $this->event->update([
            'title' => $this->title,
            'description' => $this->description,
            'posters' => $posterPath,
            'event_type' => $this->event_type,
            'venue' => $this->venue,
            'city' => $this->city,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'start_date' => $this->start_date ? \Carbon\Carbon::parse($this->start_date) : null,
            'end_date' => $this->end_date ? \Carbon\Carbon::parse($this->end_date) : null,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'registration_deadline' => $this->registration_deadline ? \Carbon\Carbon::parse($this->registration_deadline) : null,
            'categories' => $this->categories,
            'entry_fee' => $this->entry_fee,
            'max_participants' => $this->max_participants,
            'ads_start_date' => $this->ads_start_date ? \Carbon\Carbon::parse($this->ads_start_date) : null,
            'ads_end_date' => $this->ads_end_date ? \Carbon\Carbon::parse($this->ads_end_date) : null,
        ]);

        // Reset status to pending for re-approval
        $status = EventStatus::firstOrNew(['event_id' => $this->event->id]);
        $status->status = 'pending';
        $status->rejection_reason = null;
        $status->save();

        session()->flash('message', 'Event updated and resubmitted for admin approval.');
        return redirect()->route('organiser.dashboard');
    }

    public function render()
    {
        return view('livewire.edit-event');
    }
}
