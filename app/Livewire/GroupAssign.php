<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupAssign extends Component
{
    public $eventId;
    public $event;
    public $groups = [];
    public $participants = [];
    public $selectedPeserta = []; // keep selected per group

    // Mount receives :event-id from Blade
    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $this->loadData();
    }

    // Load groups & participants (unassigned)
    public function loadData()
    {
        // load event with relationships
        $this->event = Event::with(['groups.pesertas', 'pesertas'])->find($this->eventId);

        if (! $this->event) {
            $this->groups = [];
            $this->participants = [];
            return;
        }

        // groups as array for blade
        $this->groups = $this->event->groups->map(function($g) {
            $g->pesertas = $g->pesertas->map(function($p) {
                return [
                    'id' => $p->id,
                    'nama_penuh' => $p->nama_penuh,
                    'category' => $p->category,
                ];
            })->toArray();

            return [
                'id' => $g->id,
                'name' => $g->name,
                'capacity' => $g->capacity,
                'pesertas' => $g->pesertas,
            ];
        })->toArray();

        // assigned peserta ids for this event
        $assignedIds = DB::table('group_peserta')
            ->where('event_id', $this->eventId)
            ->pluck('peserta_id')
            ->toArray();

        // unassigned participants as array
        $this->participants = $this->event->pesertas
            ->whereNotIn('id', $assignedIds)
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'nama_penuh' => $p->nama_penuh,
                    'category' => $p->category,
                ];
            })->values()->toArray();
    }

    // assign a single participant to group (called with group id)
    public function assign($groupId)
    {
        if (! isset($this->selectedPeserta[$groupId]) || ! $this->selectedPeserta[$groupId]) {
            session()->flash('error', 'Please select a participant first.');
            return;
        }

        $pesertaId = $this->selectedPeserta[$groupId];

        // check duplicate
        $exists = DB::table('group_peserta')
            ->where('event_id', $this->eventId)
            ->where('group_id', $groupId)
            ->where('peserta_id', $pesertaId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Participant already assigned to this group.');
            $this->selectedPeserta[$groupId] = null;
            $this->loadData();
            return;
        }

        // check capacity if set
        $group = Group::withCount('pesertas')->find($groupId);
        if ($group && $group->capacity && $group->pesertas_count >= $group->capacity) {
            session()->flash('error', 'Group capacity reached.');
            return;
        }

        DB::table('group_peserta')->insert([
            'event_id' => $this->eventId,
            'group_id' => $groupId,
            'peserta_id' => $pesertaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // reset selection for that group and reload lists
        $this->selectedPeserta[$groupId] = null;
        $this->loadData();
        session()->flash('success', 'Participant assigned.');
    }

    // unassign participant from group
    public function unassign($pesertaId, $groupId)
    {
        DB::table('group_peserta')
            ->where('event_id', $this->eventId)
            ->where('group_id', $groupId)
            ->where('peserta_id', $pesertaId)
            ->delete();

        $this->loadData();
        session()->flash('success', 'Participant unassigned.');
    }

    public function render()
    {
        return view('livewire.group-assign');
    }
}
