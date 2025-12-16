<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventPage extends Component
{
    use WithPagination;

    public function render()
    {
        
        $banners = Event::latest()->take(4)->get();

       

        return view('livewire.user.event-page', [
            'banners' => $banners,

        ]);
    }
}
