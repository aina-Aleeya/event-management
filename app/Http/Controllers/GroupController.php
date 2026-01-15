<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{

    /**
     * Get events user can access (owner, team member, or admin)
     */
    protected function getEventsForUser()
    {
        $user = auth()->user();
        
        // Admin sees all events
        if ($user->isAdmin()) {
            return Event::query();
        }
        
        // Get events where user is owner OR team member
        return Event::where('user_id', $user->id)
            ->orWhereHas('teamMembers', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });
    }

    public function groupingIndex(Request $request)
    {
        $search = $request->input('search');

        $query = $this->getEventsForUser();

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $events = $query->paginate(5);

        $allEventsQuery = $this->getEventsForUser();
        if ($search) {
            $allEventsQuery->where('title', 'like', '%' . $search . '%');
        }
        $allEvents = $allEventsQuery->get();

        $totalParticipants = 0;
        $totalGroups = 0;

        foreach ($events as $event) {
            $participants = DB::table('penyertaan')
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
                    DB::raw('COALESCE(categories.name, custom_categories.name) as category')
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

        foreach ($allEvents as $event) {
            $participantCount = DB::table('penyertaan')
                ->where('penyertaan.event_id', $event->id)
                ->count();
            $totalParticipants += $participantCount;

            $groupCount = DB::table('groups')
                ->where('event_id', $event->id)
                ->count();
            $totalGroups += $groupCount;
        }

        return view('admin.grouping_index', compact('events', 'totalParticipants', 'totalGroups'));
    }

    public function groups($eventId)
    {
        // Show event if user owns it (or admin)
        $event = $this->getEventsForUser()
            ->with(['groups.pesertas'])
            ->findOrFail($eventId);

        $category = request('category');

        $allParticipantsQuery = DB::table('penyertaan')
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
                DB::raw('COALESCE(categories.name, custom_categories.name) as category')
            );

        if ($category) {
            $allParticipantsQuery->whereRaw('COALESCE(categories.name, custom_categories.name) = ?', [$category]);
        }

        $allParticipants = $allParticipantsQuery->get()->map(function ($item) {
            $peserta = new Peserta((array)$item);
            $peserta->id = $item->id;
            $peserta->category = $item->category;
            return $peserta;
        });

        $assignedIds = DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->pluck('peserta_id')
            ->toArray();

        $participants = $allParticipants->whereNotIn('id', $assignedIds)->values();

        foreach ($event->groups as $group) {
            foreach ($group->pesertas as $peserta) {
                $categoryData = DB::table('penyertaan')
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
                    ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                    ->first();

                $peserta->category = $categoryData->category ?? 'Uncategorized';
            }
        }

        return view('admin.groups', compact('event', 'participants'));
    }

    public function storeGroup(Request $request, $eventId)
    {
        $event = $this->getEventsForUser()->findOrFail($eventId);

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
        $event = $this->getEventsForUser()->findOrFail($eventId);

        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'peserta_id' => 'required|exists:pesertas,id',
            'category' => 'nullable|string'
        ]);

        $group = Group::findOrFail($request->group_id);

        if ($group->event_id != $eventId) {
            abort(403, 'Unauthorized action.');
        }

        if ($group->capacity && $group->pesertas()->count() >= $group->capacity) {
            return back()->withErrors(['capacity' => 'Group capacity reached']);
        }

        $exists = DB::table('group_peserta')
            ->where('group_id', $group->id)
            ->where('peserta_id', $request->peserta_id)
            ->where('event_id', $eventId)
            ->exists();

        if (!$exists) {
            DB::table('group_peserta')->insert([
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
        // Verify user owns this event
        $event = $this->getEventsForUser()->findOrFail($eventId);

        $maxPerGroup = $request->max_per_group ?? 5;
        $category = $request->category;

        // Get participants grouped by their categories
        $participantsByCategory = DB::table('penyertaan')
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
                DB::raw('COALESCE(categories.name, custom_categories.name) as category_name')
            );

        // Filter by specific category if provided
        if ($category) {
            $participantsByCategory->whereRaw('COALESCE(categories.name, custom_categories.name) = ?', [$category]);
        }

        $participantsByCategory = $participantsByCategory->get()->groupBy('category_name');

        $groupNumber = 1;
        foreach ($participantsByCategory as $categoryName => $participants) {
            // Get unassigned participants only
            $assignedIds = DB::table('group_peserta')
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
                    DB::table('group_peserta')->insert([
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

    public function eventGrouping($eventId)
    {
        // Verify user owns this event
        $event = $this->getEventsForUser()
            ->with(['groups', 'pesertas'])
            ->findOrFail($eventId);

        // Get all categories for this event
        $categories = DB::table('penyertaan')
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
            ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
            ->distinct()
            ->pluck('category')
            ->sort();

        // Add category to pesertas
        $pesertas = DB::table('penyertaan')
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
                DB::raw('COALESCE(categories.name, custom_categories.name) as category')
            )
            ->get()
            ->map(function ($item) {
                $peserta = new Peserta((array)$item);
                $peserta->id = $item->id;
                $peserta->category = $item->category;
                return $peserta;
            });

        $event->pesertas = collect($pesertas);

        return view('admin.event-grouping', compact('event', 'categories'));
    }

    public function eventCategoryGrouping($eventId, $category)
    {
        // Verify user owns this event
        $event = $this->getEventsForUser()
            ->with(['groups.pesertas'])
            ->findOrFail($eventId);

        // Get all participants for this event and category
        $allParticipantsQuery = DB::table('penyertaan')
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
                DB::raw('COALESCE(categories.name, custom_categories.name) as category')
            )
            ->whereRaw('COALESCE(categories.name, custom_categories.name) = ?', [$category]);

        $allParticipants = $allParticipantsQuery->get()->map(function ($item) {
            $peserta = new Peserta((array)$item);
            $peserta->id = $item->id;
            $peserta->category = $item->category;
            return $peserta;
        });

        // Get assigned participant IDs
        $assignedIds = DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->pluck('peserta_id')
            ->toArray();

        // Filter unassigned participants
        $participants = $allParticipants->whereNotIn('id', $assignedIds)->values();

        // Filter groups that have participants from this category
        $filteredGroups = $event->groups->filter(function ($group) use ($category, $eventId) {
            // Add category to each participant in the group
            foreach ($group->pesertas as $peserta) {
                $categoryData = DB::table('penyertaan')
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
                    ->select(DB::raw('COALESCE(categories.name, custom_categories.name) as category'))
                    ->first();

                $peserta->category = $categoryData->category ?? 'Uncategorized';
            }

            return $group->pesertas->where('category', $category)->count() > 0;
        });

        return view('admin.event-category-grouping', compact('event', 'category', 'participants', 'allParticipants', 'filteredGroups'));
    }

    public function moveParticipant(Request $request, $eventId)
    {
        // Verify user owns this event
        $event = $this->getEventsForUser()->findOrFail($eventId);

        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'current_group_id' => 'required|exists:groups,id',
            'new_group_id' => 'required|exists:groups,id',
            'category' => 'nullable|string'
        ]);

        $newGroup = Group::findOrFail($request->new_group_id);

        // Verify both groups belong to this event
        if ($newGroup->event_id != $eventId) {
            abort(403, 'Unauthorized action.');
        }

        // Check capacity of new group
        if ($newGroup->capacity && $newGroup->pesertas()->count() >= $newGroup->capacity) {
            return back()->withErrors(['capacity' => 'Target group capacity reached']);
        }

        // Remove from current group
        DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->where('group_id', $request->current_group_id)
            ->where('peserta_id', $request->peserta_id)
            ->delete();

        // Add to new group
        DB::table('group_peserta')->insert([
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
        // Verify user owns this event
        $event = $this->getEventsForUser()->findOrFail($eventId);

        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'group_id' => 'required|exists:groups,id',
            'category' => 'nullable|string'
        ]);

        // Verify group belongs to this event
        $group = Group::findOrFail($request->group_id);
        if ($group->event_id != $eventId) {
            abort(403, 'Unauthorized action.');
        }

        DB::table('group_peserta')
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

