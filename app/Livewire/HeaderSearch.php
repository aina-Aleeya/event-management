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
        if (strlen(trim($this->query)) < 2) {
            $this->results = [];
            return;
        }


        $this->results = Event::where('title', 'like', '%' . $this->query . '%')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.header-search');
    }
}
