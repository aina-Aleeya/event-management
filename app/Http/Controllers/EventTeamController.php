<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\EventTeamMember;
use Illuminate\Http\Request;

class EventTeamController extends Controller
{
    /**
     * Show team members for an event
     */
    public function index($eventId)
    {
        $event = Event::with('teamMembers.user')->findOrFail($eventId);

        // Only event owner or admin can manage team
        if (!$event->isOwnedBy(auth()->user()) && !auth()->user()->isAdmin()) {
            abort(403, 'Only the event owner can manage the team.');
        }

        return view('organiser.events.team.index', compact('event'));
    }

    /**
     * Show form to add team member
     */
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Only event owner or admin can manage team
        if (!$event->isOwnedBy(auth()->user()) && !auth()->user()->isAdmin()) {
            abort(403, 'Only the event owner can manage the team.');
        }

        // Get available users (exclude already assigned team members)
        $assignedUserIds = $event->teamMembers()->pluck('user_id')->toArray();
        $assignedUserIds[] = $event->user_id; // Exclude owner

        $availableUsers = User::whereNotIn('id', $assignedUserIds)
            ->whereIn('role', ['organiser', 'user']) // Can assign organisers or regular users
            ->orderBy('name')
            ->get();

        $roles = [
            'clerk' => 'Clerk/Admin',
            'judge' => 'Judge',
            'scorekeeper' => 'Scorekeeper',
            'coordinator' => 'Event Coordinator',
            'custom' => 'Custom Role',
        ];

        $permissions = EventTeamMember::availablePermissions();

        return view('organiser.events.team.create', compact('event', 'availableUsers', 'roles', 'permissions'));
    }

    /**
     * Store new team member
     */
    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // Only event owner or admin can manage team
        if (!$event->isOwnedBy(auth()->user()) && !auth()->user()->isAdmin()) {
            abort(403, 'Only the event owner can manage the team.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:clerk,judge,scorekeeper,coordinator,custom',
            'custom_role_name' => 'required_if:role,custom|nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        // Check if already a team member
        if ($event->hasTeamMember(User::find($validated['user_id']))) {
            return back()->withErrors(['user_id' => 'This user is already a team member.']);
        }

        // Get default permissions if none selected
        if (empty($validated['permissions']) && $validated['role'] !== 'custom') {
            $validated['permissions'] = EventTeamMember::defaultPermissionsFor($validated['role']);
        }

        EventTeamMember::create([
            'event_id' => $eventId,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'custom_role_name' => $validated['custom_role_name'] ?? null,
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('organiser.events.team.index', $eventId)
            ->with('success', 'Team member added successfully!');
    }

    /**
     * Show form to edit team member
     */
    public function edit($eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);
        $teamMember = EventTeamMember::with('user')->findOrFail($teamMemberId);

        // Only event owner or admin can manage team
        if (!$event->isOwnedBy(auth()->user()) && !auth()->user()->isAdmin()) {
            abort(403, 'Only the event owner can manage the team.');
        }

        $roles = [
            'clerk' => 'Clerk/Admin',
            'judge' => 'Judge',
            'scorekeeper' => 'Scorekeeper',
            'coordinator' => 'Event Coordinator',
            'custom' => 'Custom Role',
        ];

        $permissions = EventTeamMember::availablePermissions();

        return view('organiser.events.team.edit', compact('event', 'teamMember', 'roles', 'permissions'));
    }

    /**
     * Update team member
     */
    public function update(Request $request, $eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);
        $teamMember = EventTeamMember::findOrFail($teamMemberId);

        // Only event owner or admin can manage team
        if (!$event->isOwnedBy(auth()->user()) && !auth()->user()->isAdmin()) {
            abort(403, 'Only the event owner can manage the team.');
        }

        $validated = $request->validate([
            'role' => 'required|in:clerk,judge,scorekeeper,coordinator,custom',
            'custom_role_name' => 'required_if:role,custom|nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $teamMember->update([
            'role' => $validated['role'],
            'custom_role_name' => $validated['custom_role_name'] ?? null,
            'permissions' => $validated['permissions'] ?? [],
        ]);

        return redirect()->route('organiser.events.team.index', $eventId)
            ->with('success', 'Team member updated successfully!');
    }

    /**
     * Remove team member
     */
    public function destroy($eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);
        $teamMember = EventTeamMember::findOrFail($teamMemberId);

        // Only event owner or admin can manage team
        if (!$event->isOwnedBy(auth()->user()) && !auth()->user()->isAdmin()) {
            abort(403, 'Only the event owner can manage the team.');
        }

        $teamMember->delete();

        return redirect()->route('organiser.events.team.index', $eventId)
            ->with('success', 'Team member removed successfully!');
    }
}