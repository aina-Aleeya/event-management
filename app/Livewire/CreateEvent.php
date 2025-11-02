<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CreateEvent extends Component
{
    use WithFileUploads;

    public $description = '';

    // Basic info
    public $title, $poster;
    public $event_type, $venue, $address, $city, $contact_email, $contact_phone;

    // Dates
    public $start_date, $end_date, $start_time, $end_time, $registration_deadline, $time_zone = 'Asia/Kuala_Lumpur';

    // Categories
    public $categories = [];

    // Advertisement
    public $ads_start_date, $ads_end_date, $featured = false;

    // Optional
    public $entry_fee, $max_participants;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'poster' => 'nullable|image|max:2048',
        'event_type' => 'nullable|string|max:100',
        'venue' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'city' => 'nullable|string|max:100',
        'contact_email' => 'nullable|email',
        'contact_phone' => 'nullable|string|max:30',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'start_time' => 'nullable',
        'end_time' => 'nullable',
        'registration_deadline' => 'nullable|date|before_or_equal:end_date',
        'categories' => 'array',
        'ads_start_date' => 'nullable|date',
        'ads_end_date' => 'nullable|date|after_or_equal:ads_start_date',
        'entry_fee' => 'nullable|numeric|min:0',
        'max_participants' => 'nullable|integer|min:1',
    ];

    // This lets TinyMCE send updates to Livewire
    protected $listeners = ['updateDescription' => 'updateDescription'];

    public function updateDescription($data)
    {
        $this->description = $data['value'] ?? '';
    }

    public function save()
    {
        $this->validate();

        $posterPath = $this->poster ? $this->poster->store('event_posters', 'public') : null;

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description, 
            'poster' => $posterPath,
            'event_type' => $this->event_type,
            'venue' => $this->venue,
            'city' => $this->city,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'registration_deadline' => $this->registration_deadline,
            'categories' => $this->categories,
            'ads_start_date' => $this->ads_start_date,
            'ads_end_date' => $this->ads_end_date,
            'entry_fee' => $this->entry_fee,
            'max_participants' => $this->max_participants,
        ]);

        $event->event_link = url('/events/' . $event->id);
        $event->qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($event->event_link);
        $event->save();

        session()->flash('success', 'Event created successfully!');
        $this->reset();

        // This reinitializes TinyMCE after reset
        $this->dispatch('refreshTinyMCE');
    }

    public function render()
    {
        return view('livewire.create-event');
    }
}
