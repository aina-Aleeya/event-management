<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($id)
    {

        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function trackClick($id)
    {
        $event = Event::findOrFail($id);

        $event->increment('click_count');

        return redirect()->route('event.details', $event->id);
    }
}