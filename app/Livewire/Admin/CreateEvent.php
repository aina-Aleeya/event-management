<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class CreateEvent extends Component
{
    use WithFileUploads;

    public $description = '';

    // Basic info
    public $title;
    public $posters = [];
    public $event_type, $venue, $city, $contact_email, $contact_phone;

    // Dates
    public $start_date, $end_date, $start_time, $end_time, $registration_deadline, $time_zone = 'Asia/Kuala_Lumpur';

    // Categories
    public $selectedDefaultCategories = [];   // holds default category ids
    public $customCategoryList = [];          // holds array of custom names (strings)
    public $allCategories;

    // Advertisement
    public $ads_start_date, $ads_end_date, $featured = false;

    // Optional
    public $entry_fee, $max_participants;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'posters.*' => 'nullable|image|max:2048',
        'event_type' => 'nullable|string|max:100',
        'venue' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'contact_email' => 'nullable|email',
        'contact_phone' => 'nullable|string|max:30',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'start_time' => 'nullable',
        'end_time' => 'nullable',
        'registration_deadline' => 'nullable|date|before_or_equal:end_date',
        'selectedDefaultCategories' => 'array',
        'customCategoryList' => 'array',
        'ads_start_date' => 'nullable|date',
        'ads_end_date' => 'nullable|date|after_or_equal:ads_start_date',
        'entry_fee' => 'nullable|numeric|min:0',
        'max_participants' => 'nullable|integer|min:1',
    ];

    public function mount()
    {
        $this->allCategories = Category::all();
    }

    protected $listeners = [
        'updateDescription' => 'updateDescription'
    ];

    public function addCustomCategory()
    {
        $this->customCategoryList[] = '';
    }

    public function removeCustomCategory($index)
    {
        unset($this->customCategoryList[$index]);
        $this->customCategoryList = array_values($this->customCategoryList);
    }

    public function updateDescription($data)
    {
        $this->description = $data['value'] ?? '';
    }

    public function updatedPosters()
    {
        // This validates each file as it's uploaded
        $this->validate([
            'posters.*' => 'image|max:2048',
        ]);
    }

    public function save()
    {
        $this->validate();

        $posterPaths = [];

        if ($this->posters) {
            foreach ($this->posters as $image) {
                $posterPaths[] = $image->store('event_posters', 'public');
            }
        }

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'posters' => $posterPaths,
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
            'ads_start_date' => $this->ads_start_date,
            'ads_end_date' => $this->ads_end_date,
            'entry_fee' => $this->entry_fee,
            'max_participants' => $this->max_participants,
        ]);

        $event->event_link = url('/events/' . $event->id);
        $event->qr_code = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($event->event_link);
        $event->save();

        // Sync default categories (pivot)
        if (!empty($this->selectedDefaultCategories)) {
            $event->categories()->sync($this->selectedDefaultCategories);
        }

        // Create custom categories (hasMany)
        foreach ($this->customCategoryList as $name) {
            $name = trim($name);
            if ($name === '') continue;
            $event->customCategories()->create(['name' => $name]);
        }

        session()->flash('success', 'Event created successfully!');
        return redirect()->route('admin.dashboard');
    }

    public function removePoster($index)
    {
        if (isset($this->posters[$index])) {
            unset($this->posters[$index]);
            $this->posters = array_values($this->posters);
        }
    }

    public function render()
    {
        // Return WITHOUT layout - the blade file already has the layout
        return view('livewire.admin.create-event')
            ->layout('components.layouts.app.admin');
    }
}