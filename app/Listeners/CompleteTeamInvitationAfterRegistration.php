<?php

namespace App\Listeners;

use App\Models\EventTeamMember;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Request;

class CompleteTeamInvitationAfterRegistration
{
    /**
     * If the user registered with an invitation_token, link them to the team member
     * record, mark the invitation as accepted, and set redirect to the event dashboard.
     */
    public function handle(Registered $event): void
    {
        $token = Request::input('invitation_token');
        if (!$token) {
            return;
        }

        $teamMember = EventTeamMember::where('invitation_token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$teamMember || $teamMember->email !== $event->user->email) {
            return;
        }

        $teamMember->update([
            'user_id' => $event->user->id,
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        session(['url.intended' => route('admin.event.dashboard', $teamMember->event_id)]);
    }
}
