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
        $events = Event::all();

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
        $event = Event::findOrFail($eventId);

        // Use Eloquent relationship instead of raw query
        $participants = $event->pesertas()
            ->withPivot('unique_id', 'status_bayaran', 'categorizable_type', 'categorizable_id', 'created_at')
            ->get()
            ->map(function ($peserta) {
                // Add category name to pivot
                if ($peserta->pivot->categorizable_type === \App\Models\Category::class) {
                    $category = \App\Models\Category::find($peserta->pivot->categorizable_id);
                    $peserta->pivot->kategori_nama = $category?->name;
                } elseif ($peserta->pivot->categorizable_type === \App\Models\CustomCategory::class) {
                    $customCategory = \App\Models\CustomCategory::find($peserta->pivot->categorizable_id);
                    $peserta->pivot->kategori_nama = $customCategory?->name;
                } else {
                    $peserta->pivot->kategori_nama = '-';
                }

                return $peserta;
            });

        return view('admin.participants', compact('event', 'participants'));
    }

    public function viewParticipant($pesertaId)
    {
        $peserta = Peserta::with(['events' => function ($query) {
            $query->withPivot('unique_id', 'categorizable_type', 'categorizable_id', 'created_at');
        }])->findOrFail($pesertaId);

        foreach ($peserta->events as $event) {
            if ($event->pivot->categorizable_type === \App\Models\Category::class) {
                $event->pivot->category = \App\Models\Category::find($event->pivot->categorizable_id);
            } else {
                $event->pivot->category = \App\Models\CustomCategory::find($event->pivot->categorizable_id);
            }
        }

        return view('admin.participant-details', compact('peserta'));
    }

    public function groupingIndex()
    {
        $events = Event::all();

        // Add category information to each event's participants
        foreach ($events as $event) {
            $participants = \DB::table('penyertaan')
                ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
                ->leftJoin('categories', function ($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                        ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                })
                ->leftJoin('custom_categories', function ($join) {
                    $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                        ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                })
                ->where('penyertaan.event_id', $event->id)
                ->select(
                    'pesertas.*',
                    \DB::raw('COALESCE(categories.name, custom_categories.name) as category')
                )
                ->get()
                ->map(function ($item) {
                    $peserta = new Peserta((array)$item);
                    $peserta->id = $item->id;
                    $peserta->category = $item->category;
                    return $peserta;
                });

            $event->pesertas = collect($participants);
        }

        return view('admin.grouping_index', compact('events'));
    }

    public function groups($eventId)
    {
        $event = Event::with(['groups.pesertas'])->findOrFail($eventId);
        $category = request('category');

        // Get participants with their categories
        $allParticipantsQuery = \DB::table('penyertaan')
            ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
            ->leftJoin('categories', function ($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                    ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function ($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                    ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $eventId)
            ->select(
                'pesertas.*',
                \DB::raw('COALESCE(categories.name, custom_categories.name) as category')
            );

        // Filter by category if provided using WHERE instead of HAVING
        if ($category) {
            $allParticipantsQuery->whereRaw('COALESCE(categories.name, custom_categories.name) = ?', [$category]);
        }

        $allParticipants = $allParticipantsQuery->get()->map(function ($item) {
            $peserta = new Peserta((array)$item);
            $peserta->id = $item->id;
            $peserta->category = $item->category;
            return $peserta;
        });

        // Get assigned participant IDs
        $assignedIds = \DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->pluck('peserta_id')
            ->toArray();

        // Filter unassigned participants
        $participants = $allParticipants->whereNotIn('id', $assignedIds)->values();

        // Add category to each peserta in groups
        foreach ($event->groups as $group) {
            foreach ($group->pesertas as $peserta) {
                $categoryData = \DB::table('penyertaan')
                    ->leftJoin('categories', function ($join) {
                        $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                            ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
                    })
                    ->leftJoin('custom_categories', function ($join) {
                        $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                            ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
                    })
                    ->where('penyertaan.event_id', $eventId)
                    ->where('penyertaan.peserta_id', $peserta->id)
                    ->select(\DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                    ->first();

                $peserta->category = $categoryData->category ?? 'Uncategorized';
            }
        }

        return view('admin.groups', compact('event', 'participants'));
    }

    public function storeGroup(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'category' => 'nullable|string'
        ]);

        Group::create([
            'event_id' => $eventId,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        $redirectUrl = route('admin.groups', ['event' => $eventId]);
        if ($request->category) {
            $redirectUrl .= '?category=' . urlencode($request->category);
        }

        return redirect($redirectUrl)->with('success', 'Group created successfully');
    }

    public function assignToGroup(Request $request, $eventId)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'peserta_id' => 'required|exists:pesertas,id',
            'category' => 'nullable|string'
        ]);

        $group = Group::findOrFail($request->group_id);

        // Check capacity
        if ($group->capacity && $group->pesertas()->count() >= $group->capacity) {
            return back()->withErrors(['capacity' => 'Group capacity reached']);
        }

        // Check if already assigned
        $exists = \DB::table('group_peserta')
            ->where('group_id', $group->id)
            ->where('peserta_id', $request->peserta_id)
            ->where('event_id', $eventId)
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

        $redirectUrl = route('admin.groups', ['event' => $eventId]);
        if ($request->category) {
            $redirectUrl .= '?category=' . urlencode($request->category);
        }

        return redirect($redirectUrl)->with('success', 'Participant assigned to group');
    }

    public function autoGroup(Request $request, $eventId)
    {
        $maxPerGroup = $request->max_per_group ?? 5;
        $category = $request->category;

        // Get participants grouped by their categories
        $participantsByCategory = \DB::table('penyertaan')
            ->join('pesertas', 'penyertaan.peserta_id', '=', 'pesertas.id')
            ->leftJoin('categories', function ($join) {
                $join->on('penyertaan.categorizable_id', '=', 'categories.id')
                    ->where('penyertaan.categorizable_type', '=', \App\Models\Category::class);
            })
            ->leftJoin('custom_categories', function ($join) {
                $join->on('penyertaan.categorizable_id', '=', 'custom_categories.id')
                    ->where('penyertaan.categorizable_type', '=', \App\Models\CustomCategory::class);
            })
            ->where('penyertaan.event_id', $eventId)
            ->select(
                'pesertas.id as peserta_id',
                \DB::raw('COALESCE(categories.name, custom_categories.name) as category_name')
            );

        // Filter by specific category if provided using WHERE instead of HAVING
        if ($category) {
            $participantsByCategory->whereRaw('COALESCE(categories.name, custom_categories.name) = ?', [$category]);
        }

        $participantsByCategory = $participantsByCategory->get()->groupBy('category_name');

        $groupNumber = 1;
        foreach ($participantsByCategory as $categoryName => $participants) {
            // Get unassigned participants only
            $assignedIds = \DB::table('group_peserta')
                ->where('event_id', $eventId)
                ->pluck('peserta_id')
                ->toArray();

            $unassignedParticipants = $participants->whereNotIn('peserta_id', $assignedIds);

            if ($unassignedParticipants->isEmpty()) {
                continue;
            }

            $chunks = $unassignedParticipants->chunk($maxPerGroup);

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

        $redirectUrl = route('admin.groups', ['event' => $eventId]);
        if ($category) {
            $redirectUrl .= '?category=' . urlencode($category);
        }

        return redirect($redirectUrl)->with('success', 'Participants auto-grouped successfully');
    }

    public function moveParticipant(Request $request, $eventId)
    {
        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'current_group_id' => 'required|exists:groups,id',
            'new_group_id' => 'required|exists:groups,id',
            'category' => 'nullable|string'
        ]);

        $newGroup = Group::findOrFail($request->new_group_id);

        // Check capacity of new group
        if ($newGroup->capacity && $newGroup->pesertas()->count() >= $newGroup->capacity) {
            return back()->withErrors(['capacity' => 'Target group capacity reached']);
        }

        // Remove from current group
        \DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->where('group_id', $request->current_group_id)
            ->where('peserta_id', $request->peserta_id)
            ->delete();

        // Add to new group
        \DB::table('group_peserta')->insert([
            'group_id' => $request->new_group_id,
            'peserta_id' => $request->peserta_id,
            'event_id' => $eventId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $redirectUrl = route('admin.groups', ['event' => $eventId]);
        if ($request->category) {
            $redirectUrl .= '?category=' . urlencode($request->category);
        }

        return redirect($redirectUrl)->with('success', 'Participant moved successfully');
    }

    public function removeParticipant(Request $request, $eventId)
    {
        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'group_id' => 'required|exists:groups,id',
            'category' => 'nullable|string'
        ]);

        \DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->where('group_id', $request->group_id)
            ->where('peserta_id', $request->peserta_id)
            ->delete();

        $redirectUrl = route('admin.groups', ['event' => $eventId]);
        if ($request->category) {
            $redirectUrl .= '?category=' . urlencode($request->category);
        }

        return redirect($redirectUrl)->with('success', 'Participant removed from group');
    }
}
