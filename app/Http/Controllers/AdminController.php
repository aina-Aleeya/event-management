<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $events = Event::select('id', 'title', 'click_count')->get();

        // $participantSummary = Peserta::select('event_id', 'category')
        //     ->selectRaw('COUNT(*) as total')
        //     // ->selectRaw('SUM(payment_status = 1) as paid_count')
        //     ->groupBy('event_id', 'category')
        //     ->get();

$participantSummary = \DB::table('penyertaan')
    ->join('events', 'penyertaan.event_id', '=', 'events.id')
    ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
    ->select('events.id as event_id', 'events.title', 'events.event_type')
    ->selectRaw('COUNT(pesertas.id) as total')
    ->groupBy('events.id', 'events.title', 'events.event_type')
    ->get();


        return view('admin.dashboard', compact('events', 'participantSummary'));
    }

    public function participants($eventId)
    {
        $event = Event::with('pesertas.user')->findOrFail($eventId);
        $participants = $event->pesertas;

        return view('admin.participants', compact('event', 'participants'));
    }

    public function viewParticipant($pesertaId)
    {
        $peserta = Peserta::findOrFail($pesertaId);

        return view('admin.participant-details', compact('peserta'));
    }

    // public function viewParticipant($pesertaId)
    // {
    //     $peserta = Peserta::with(['events' => function ($query) {
    //         $query->withPivot('category'); // load category from pivot table
    //     }])->findOrFail($pesertaId);

    //     return view('admin.participant-details', compact('peserta'));
    // }

    public function groups($eventId)
    {
        $event = Event::findOrFail($eventId);

        $groupedByCategory = Peserta::where('event_id', $eventId)
            ->with('user')
            ->orderBy('id')
            ->get()
            ->groupBy('category');

        $finalGroups = [];

        foreach ($groupedByCategory as $category => $participants) {
            $finalGroups[$category] = $participants->chunk(5); // 5 per group
        }

        return view('admin.groups', compact('event', 'finalGroups'));
    }
}