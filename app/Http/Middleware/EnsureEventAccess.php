<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\EventTeamMember;

class EnsureEventAccess
{
    /**
     * Allow access only if user is admin, organiser, event owner, or accepted team member for this event.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $event = $request->route('event') ?? $request->route('eventId');
        if (!$event) {
            abort(403, 'Event not found.');
        }
        // Resolve event by ID if route passed an ID
        if (is_numeric($event)) {
            $event = \App\Models\Event::find($event);
        }
        if (!$event) {
            abort(404, 'Event not found.');
        }

        // Admin and organiser can access any event
        if (in_array($user->role, ['admin', 'organiser'])) {
            return $next($request);
        }

        // Event owner
        if ($event->user_id === $user->id) {
            return $next($request);
        }

        // Accepted team member for this event
        $teamMember = EventTeamMember::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->first();

        if ($teamMember) {
            return $next($request);
        }

        abort(403, 'You do not have access to this event.');
    }
}
