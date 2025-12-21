<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // Use Tailwind pagination styling

    public function render()
    {
        // Get 4 latest events for banner carousel
        $banners = Event::latest()->take(4)->get();
        
        // Get paginated events (4 per page) - this is handled by the EventList component
        // So we don't need to pass events here anymore

        return view('livewire.user.event-page', [
            'banners' => $banners,
        ]);
    }
}