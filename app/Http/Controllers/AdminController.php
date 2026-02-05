<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $events = $this->getEventsForUser()
            ->with('pesertas')
            ->orderBy('created_at', 'desc')
            ->get();

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

    protected function getEventsForUser()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            return Event::query();
        }
        
        return Event::where('user_id', $user->id);
    }

    public function participants($eventId)
    {
        $event = $this->getEventsForUser()->findOrFail($eventId);
        $participants = $event->pesertas;

        return view('admin.participants', compact('event', 'participants'));
    }

    public function viewParticipant($pesertaId)
    {
        if (Auth::user()->role === 'organiser') {
            $peserta = \App\Models\Peserta::whereHas('event', function($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($pesertaId);
        } else {
            $peserta = \App\Models\Peserta::findOrFail($pesertaId);
        }

        return view('admin.participants', compact('peserta'));
    }
}
