<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\EventTeamMember;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'organiser') {
            return redirect()->intended(route('organiser.dashboard'));
        }

        // If the user is a team member (e.g. Coordinator, Judge, etc.) with an
        // accepted invitation for at least one event, send them to that event's
        // dashboard instead of the generic public dashboard.
        $teamMember = EventTeamMember::accepted()
            ->where('user_id', $user->id)
            ->latest('accepted_at')
            ->first();

        if ($teamMember) {
            return redirect()->intended(
                route('admin.event.dashboard', $teamMember->event_id)
            );
        }

        // Fallback: normal public dashboard
        return redirect()->intended(route('dashboard'));
    }
}