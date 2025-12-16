<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function moveParticipant(Request $request, $eventId)
    {
        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'current_group_id' => 'required|exists:groups,id',
            'new_group_id' => 'required|exists:groups,id',
            'category' => 'nullable|string'
        ]);

        // Validate that new_group_id is different from current
        if ($request->current_group_id == $request->new_group_id) {
            return back()->withErrors(['error' => 'Cannot move to the same group']);
        }

        $newGroup = Group::findOrFail($request->new_group_id);

        // Check capacity of new group
        if ($newGroup->capacity && $newGroup->pesertas()->count() >= $newGroup->capacity) {
            return back()->withErrors(['capacity' => 'Target group capacity reached']);
        }

        // Check if participant already exists in new group
        $exists = DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->where('group_id', $request->new_group_id)
            ->where('peserta_id', $request->peserta_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Participant already in target group']);
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
        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'group_id' => 'required|exists:groups,id',
            'category' => 'nullable|string'
        ]);

        $deleted = DB::table('group_peserta')
            ->where('event_id', $eventId)
            ->where('group_id', $request->group_id)
            ->where('peserta_id', $request->peserta_id)
            ->delete();

        if (!$deleted) {
            return back()->withErrors(['error' => 'Participant not found in this group']);
        }

        $redirectUrl = route('admin.groups', ['event' => $eventId]);
        if ($request->category) {
            $redirectUrl .= '?category=' . urlencode($request->category);
        }

        return redirect($redirectUrl)->with('success', 'Participant removed from group');
    }
}