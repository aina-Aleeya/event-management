<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class HeaderSearch extends Component
{
    public $query = '';
    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) > 1) {
            $this->results = Event::where('title', 'like', "%{$this->query}%")
                ->orderBy('event_date', 'desc')
                ->limit(5)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.header-search');
    }
}
