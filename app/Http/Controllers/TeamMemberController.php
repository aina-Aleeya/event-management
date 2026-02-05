<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTeamMember;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TeamMemberInvitation;
use Illuminate\Support\Facades\Log;

class TeamMemberController extends Controller
{
    /**
     * Display team members list
     */
    public function index($eventId)
    {
        $event = Event::with(['teamMembers.role', 'teamMembers.user'])->findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        return view('organiser.events.team.index', compact('event'));
    }

    /**
     * Show the form for creating a new team member
     */
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        // Get system roles
        $systemRoles = Role::system()->orderBy('name')->get();

        // Get custom roles created by this user
        $customRoles = Role::customByUser(Auth::id())->orderBy('name')->get();

        // Get all available permissions from config
        $allPermissions = collect(config('team_permissions.all_permissions'))
            ->mapWithKeys(function ($details, $key) {
                return [$key => $details['label']];
            })
            ->toArray();

        // Get permission details for grouping in view
        $permissionGroups = collect(config('team_permissions.all_permissions'))
            ->groupBy('group');

        return view('organiser.events.team.create', compact(
            'event',
            'systemRoles',
            'customRoles',
            'allPermissions',
            'permissionGroups'
        ));
    }

    /**
     * Store a newly created team member invitation
     */
    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role_id' => 'required_without:custom_role_name|nullable|exists:roles,id',
            'custom_role_name' => 'required_without:role_id|nullable|string|max:255',
            'custom_role_description' => 'nullable|string|max:500',
            'custom_role_permissions' => 'required_with:custom_role_name|array',
            'custom_role_permissions.*' => 'string',
            'message' => 'nullable|string|max:1000'
        ]);

        // Handle custom role creation
        if ($request->filled('custom_role_name')) {
            $role = Role::create([
                'name' => trim($request->custom_role_name),
                'slug' => Str::slug($request->custom_role_name),
                'description' => $request->custom_role_description,
                'permissions' => $request->custom_role_permissions ?? [],
                'is_system' => false,
                'created_by' => Auth::id(),
            ]);
            $roleId = $role->id;
        } else {
            $roleId = $request->role_id;
        }

        // Generate invitation token
        $invitationToken = Str::random(60);

        // Check if user already exists
        $user = User::where('email', $request->email)->first();

        // Check if this email is already invited to this event
        $existingInvitation = EventTeamMember::where('event_id', $event->id)
            ->where('email', $request->email)
            ->first();

        if ($existingInvitation) {
            return back()->withErrors([
                'email' => 'This email has already been invited to this event.'
            ])->withInput();
        }

        // Create team member invitation
        $teamMember = EventTeamMember::create([
            'event_id' => $event->id,
            'user_id' => $user ? $user->id : null,
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $roleId,
            'invitation_token' => $invitationToken,
            'invited_at' => now(),
            'status' => 'pending',
        ]);

        // Send invitation email
        try {
            Mail::to($request->email)->send(
                new TeamMemberInvitation($teamMember, $event, $request->message)
            );

            $successMessage = 'Invitation sent successfully to ' . $request->email . '!';
        } catch (\Exception $e) {
            Log::error('Failed to send team invitation email: ' . $e->getMessage());
            $successMessage = 'Team member added, but invitation email failed to send. Please check your email configuration.';
        }

        return redirect()
            ->route('organiser.events.team.index', $event->id)
            ->with('success', $successMessage);
    }

    /**
     * Accept team invitation
     */
    public function acceptInvitation($token)
    {
        $teamMember = EventTeamMember::where('invitation_token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        // Check if invitation has expired
        if ($teamMember->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired. Please contact the event organizer.');
        }

        // If user doesn't exist, redirect to registration with pre-filled data
        if (!$teamMember->user_id) {
            return redirect()->route('register', [
                'email' => $teamMember->email,
                'name' => $teamMember->name,
                'invitation_token' => $token
            ]);
        }

        // If user exists, accept the invitation
        $teamMember->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()
            ->route('organiser.event.dashboard', $teamMember->event_id)
            ->with('success', 'You have successfully joined the team!');
    }

    /**
     * Update team member role
     */
    public function updateRole(Request $request, $eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        $teamMember = EventTeamMember::where('event_id', $event->id)
            ->where('id', $teamMemberId)
            ->firstOrFail();

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $teamMember->update([
            'role_id' => $request->role_id
        ]);

        return redirect()
            ->route('organiser.events.team.index', $event->id)
            ->with('success', 'Team member role updated successfully.');
    }

    /**
     * Remove team member
     */
    public function destroy($eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        $teamMember = EventTeamMember::where('event_id', $event->id)
            ->where('id', $teamMemberId)
            ->firstOrFail();

        $teamMember->delete();

        return redirect()
            ->route('organiser.events.team.index', $event->id)
            ->with('success', 'Team member removed successfully.');
    }

    /**
     * Show the form for editing team member
     */
    public function edit($eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        $teamMember = EventTeamMember::with('role')
            ->where('event_id', $event->id)
            ->where('id', $teamMemberId)
            ->firstOrFail();

        // Get system roles
        $systemRoles = Role::system()->orderBy('name')->get();

        // Get custom roles created by this user
        $customRoles = Role::customByUser(Auth::id())->orderBy('name')->get();

        return view('organiser.events.team.edit', compact(
            'event',
            'teamMember',
            'systemRoles',
            'customRoles'
        ));
    }

    /**
     * Update team member
     */
    public function update(Request $request, $eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        $teamMember = EventTeamMember::where('event_id', $event->id)
            ->where('id', $teamMemberId)
            ->firstOrFail();

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $teamMember->update([
            'role_id' => $request->role_id
        ]);

        return redirect()
            ->route('organiser.events.team.index', $event->id)
            ->with('success', 'Team member role updated successfully to ' . $teamMember->role->name . '!');
    }

    /**
     * Resend invitation
     */
    public function resendInvitation($eventId, $teamMemberId)
    {
        $event = Event::findOrFail($eventId);

        // Check if user is event owner
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Only the event organizer can manage team members');
        }

        $teamMember = EventTeamMember::where('event_id', $event->id)
            ->where('id', $teamMemberId)
            ->where('status', 'pending')
            ->firstOrFail();

        // Generate new token
        $teamMember->update([
            'invitation_token' => Str::random(60),
            'invited_at' => now(),
        ]);

        // Resend email
        try {
            Mail::to($teamMember->email)->send(
                new TeamMemberInvitation($teamMember, $event, null)
            );

            return redirect()
                ->route('organiser.events.team.index', $event->id)
                ->with('success', 'Invitation resent successfully to ' . $teamMember->email);
        } catch (\Exception $e) {
            Log::error('Failed to resend invitation: ' . $e->getMessage());

            return redirect()
                ->route('organiser.events.team.index', $event->id)
                ->with('error', 'Failed to resend invitation. Please try again.');
        }
    }
}
