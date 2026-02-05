<?php

namespace App\Mail;

use App\Models\EventTeamMember;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamMemberInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $teamMember;
    public $event;
    public $personalMessage;
    public $acceptUrl;
    public $roleName;

    /**
     * Create a new message instance.
     */
    public function __construct(EventTeamMember $teamMember, Event $event, $personalMessage = null)
    {
        $this->teamMember = $teamMember;
        $this->event = $event;
        $this->personalMessage = $personalMessage;
        $this->acceptUrl = route('team.invitation.accept', $teamMember->invitation_token);
        $this->roleName = $teamMember->role ? $teamMember->role->name : 'Team Member';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Team Invitation: ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}