<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateEvent extends Component
{
    use WithFileUploads;

    public $title;
    public $poster;
    public $start_date;
    public $end_date;
    public $location;
    public $ads_start;
    public $ads_end;

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'poster' => 'nullable|image|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'ads_start' => 'nullable|date',
            'ads_end' => 'nullable|date|after_or_equal:ads_start',
        ]);

        $posterPath = $this->poster ? $this->poster->store('poster', 'public') : null;

        $slug = Str::slug($this->title);
        $eventLink = url('/events/' . $slug);

        $qrImage = 'qr_code/' . $slug . '.png';
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($eventLink);
        Storage::disk('public')->put($qrImage, file_get_contents($qrUrl));

        $event = Event::create([
            'title' => $this->title,
            'poster' => $posterPath,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'ads_start' => $this->ads_start,
            'ads_end' => $this->ads_end,
            'qr_code' => $qrImage,
            'link' => $eventLink,
        ]);

        // redirect to the details page
        return redirect()->route('event.details', ['id' => $event->id]);
    }

    public function render()
    {
        return view('livewire.create-event');
    }
}