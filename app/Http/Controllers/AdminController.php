<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Group;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return $this->dashboard();
        // $events = \App\Models\Event::all();
        // return view('admin.dashboard');
    }

    public function dashboard()
    {
        $events = Event::whereHas('status', function ($query) {
            $query->where('status', 'approved');
        })->get();

        $participantSummary = \DB::table('penyertaan')
            ->join('events', 'penyertaan.event_id', '=', 'events.id')
            ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
            ->select('events.id as event_id', 'events.title', 'events.event_type', 'pesertas.category')
            ->selectRaw('COUNT(pesertas.id) as total')
            ->groupBy('events.id', 'events.title', 'events.event_type', 'pesertas.category')
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
        $peserta = Peserta::with(['events' => function ($query) {
            $query->withPivot('unique_id', 'kategori');
        }])->findOrFail($pesertaId);

        return view('admin.participant-details', compact('peserta'));
    }

    public function groups($eventId)
    {
        $event = Event::with(['groups.pesertas'])->findOrFail($eventId);

        $allParticipants = $event->pesertas;

        $assignedIds = \DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->pluck('peserta_id')
            ->toArray();

        $participants = $allParticipants->whereNotIn('id', $assignedIds);

        return view('admin.groups', compact('event', 'participants'));
    }


    public function storeGroup(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1'
        ]);

        Group::create([
            'event_id' => $eventId,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return back()->with('success', 'Group created successfully');
    }

    public function assignToGroup(Request $request, $eventId)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'peserta_id' => 'required|exists:pesertas,id',
        ]);

        // Check if group capacity exceeded
        $group = \App\Models\Group::findOrFail($request->group_id);
        if ($group->capacity && $group->pesertas()->count() >= $group->capacity) {
            return back()->withErrors(['capacity' => 'Group capacity reached']);
        }

        // Avoid duplicate assignment
        $exists = \DB::table('group_peserta')
            ->where('group_id', $group->id)
            ->where('peserta_id', $request->peserta_id)
            ->exists();

        if (!$exists) {
            \DB::table('group_peserta')->insert([
                'group_id' => $group->id,
                'peserta_id' => $request->peserta_id,
                'event_id' => $eventId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Participant assigned to group');
    }

    public function autoGroup(Request $request, $eventId)
    {
        $maxPerGroup = $request->max_per_group ?? 5;
        $event = Event::with('pesertas')->findOrFail($eventId);

        $groupNumber = 1;
        foreach ($event->pesertas->groupBy('category') as $category => $participants) {
            $chunks = $participants->chunk($maxPerGroup);
            foreach ($chunks as $chunk) {
                $group = Group::create([
                    'event_id' => $eventId,
                    'name' => $category . ' Group ' . $groupNumber++,
                ]);
                foreach ($chunk as $p) {
                    $group->pesertas()->attach($p->id, ['event_id' => $eventId]);
                }
            }
        }

        return back()->with('success', 'Participants auto-grouped successfully');
    }

    public function groupingIndex()
    {
        $events = Event::whereHas('status', function ($query) {
            $query->where('status', 'approved');
        })->get();

        return view('admin.grouping_index', compact('events'));
    }
}
