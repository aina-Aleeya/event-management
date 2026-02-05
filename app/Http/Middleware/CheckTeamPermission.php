<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\EventTeamMember;

class CheckTeamPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin and Organiser bypass - allow all permissions
        if (in_array($user->role, ['admin', 'organiser'])) {
            return $next($request);
        }

        // Get the event from the route parameters
        $event = $request->route('event');
        
        if (!$event) {
            abort(403, 'Event not found.');
        }

        // Check if user is a team member for this specific event
        $teamMember = EventTeamMember::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->first();

        if (!$teamMember) {
            abort(403, 'You are not a team member of this event.');
        }

        // Check if team member has the required permission through their role
        if (!$teamMember->hasPermission($permission)) {
            abort(403, 'You do not have the required permission: ' . $permission);
        }

        return $next($request);
    }
}