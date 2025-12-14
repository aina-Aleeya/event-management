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
    }

    public function dashboard()
    {
        $events = Event::whereHas('status', function ($query) {
            $query->where('status', 'approved');
        })->get();

        // Updated query - now gets categories from penyertaan with categorizable
        $participantSummary = \DB::table('penyertaan')
            ->join('events', 'penyertaan.event_id', '=', 'events.id')
            ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
            ->select('events.id as event_id', 'events.title', 'events.event_type')
            ->selectRaw('COUNT(penyertaan.id) as total')
            ->groupBy('events.id', 'events.title', 'events.event_type')
            ->get();

        return view('admin.dashboard', compact('events', 'participantSummary'));
    }

    public function participants($eventId)
    {
        $event = Event::with('pesertas.user')->findOrFail($eventId);
        
        // Get participants through penyertaan with categories
        $participants = \DB::table('penyertaan')
            ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
            ->leftJoin('categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $eventId)
            ->select(
                'pesertas.*',
                'penyertaan.unique_id',
                'penyertaan.categorizable_type',
                'penyertaan.categorizable_id',
                \DB::raw('COALESCE(categories.name, custom_categories.name) as category_name')
            )
            ->get();

        return view('admin.participants', compact('event', 'participants'));
    }

    public function viewParticipant($pesertaId)
    {
        $peserta = Peserta::with(['events' => function ($query) {
            $query->withPivot('unique_id', 'categorizable_type', 'categorizable_id','created_at');
        }])->findOrFail($pesertaId);

        // Load categories for each event participation
        foreach ($peserta->events as $event) {
            if ($event->pivot->categorizable_type === \App\Models\Category::class) {
                $event->pivot->category = \App\Models\Category::find($event->pivot->categorizable_id);
            } else {
                $event->pivot->category = \App\Models\CustomCategory::find($event->pivot->categorizable_id);
            }
        }

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

        $group = \App\Models\Group::findOrFail($request->group_id);
        if ($group->capacity && $group->pesertas()->count() >= $group->capacity) {
            return back()->withErrors(['capacity' => 'Group capacity reached']);
        }

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
        
        // Get participants grouped by their categories
        $participantsByCategory = \DB::table('penyertaan')
            ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
            ->leftJoin('categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                     ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $eventId)
            ->select(
                'pesertas.id as peserta_id',
                \DB::raw('COALESCE(categories.name, custom_categories.name) as category_name')
            )
            ->get()
            ->groupBy('category_name');

        $groupNumber = 1;
        foreach ($participantsByCategory as $categoryName => $participants) {
            $chunks = $participants->chunk($maxPerGroup);
            foreach ($chunks as $chunk) {
                $group = Group::create([
                    'event_id' => $eventId,
                    'name' => $categoryName . ' Group ' . $groupNumber++,
                ]);
                foreach ($chunk as $p) {
                    \DB::table('group_peserta')->insert([
                        'group_id' => $group->id,
                        'peserta_id' => $p->peserta_id,
                        'event_id' => $eventId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
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