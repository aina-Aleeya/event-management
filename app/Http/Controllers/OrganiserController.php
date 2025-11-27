<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Http\Request;

class OrganiserController extends Controller
{
public function dashboard()
{
    $userId = auth()->id();

    // Events for logged-in user
    $events = Event::select('id', 'title', 'click_count', 'entry_fee')
        ->where('user_id', $userId)
        ->get();

    $events = Event::whereHas('status', function ($query) {
            $query->where('status', 'approved');
        })->get();

    // Total participants per event
    $participantSummary = \DB::table('penyertaan')
        ->join('events', 'penyertaan.event_id', '=', 'events.id')
        ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
        ->select('events.id as event_id', 'events.title', 'events.event_type')
        ->selectRaw('COUNT(pesertas.id) as total')
        ->where('events.user_id', $userId)
        ->groupBy('events.id', 'events.title', 'events.event_type')
        ->get();

    // Total tickets sold (status_bayaran = 'complete')
    $totalTicketSold = \DB::table('penyertaan')
        ->join('events', 'penyertaan.event_id', '=', 'events.id')
        ->where('events.user_id', $userId)
        ->where('penyertaan.status_bayaran', 'complete')
        ->count();

    // Total sales using entry_fee from events table
    $totalSales = \DB::table('penyertaan')
        ->join('events', 'penyertaan.event_id', '=', 'events.id')
        ->where('events.user_id', $userId)
        ->where('penyertaan.status_bayaran', 'complete')
        ->sum(\DB::raw('events.entry_fee'));

    return view('organiser.dashboard', compact(
        'events',
        'participantSummary',
        'totalTicketSold',
        'totalSales'
    ));
}


    public function participants($eventId)
    {
        $event = Event::with('pesertas.user')->findOrFail($eventId);
        $participants = $event->pesertas;

        return view('organiser.participants', compact('event', 'participants'));
    }

    public function viewParticipant($pesertaId)
    {
        $peserta = Peserta::findOrFail($pesertaId);

        return view('organiser.participant-details', compact('peserta'));
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

        return view('organiser.groups', compact('event', 'finalGroups'));
    }
}