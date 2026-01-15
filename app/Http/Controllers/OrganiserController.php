<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class OrganiserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $events = Event::with('pesertas')->orderBy('created_at', 'desc')->get();
        } else {
            $events = Event::where('user_id', $user->id)
                ->with('pesertas')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $participantSummary = $events->map(function ($event) {
            return [
                'event_id' => $event->id,
                'title' => $event->title,
                'event_type' => $event->event_type,
                'total' => $event->pesertas->count(),
            ];
        });

        return view('admin.dashboard', compact('events', 'participantSummary'));
    }
}
