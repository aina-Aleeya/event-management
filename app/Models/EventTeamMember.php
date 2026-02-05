<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'role_id',
        'invitation_token',
        'invited_at',
        'accepted_at',
        'status',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Get the role for this team member
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if team member has a specific permission (through role)
     */
    public function hasPermission($permission)
    {
        return $this->role ? $this->role->hasPermission($permission) : false;
    }

    /**
     * Get all permissions (from role)
     */
    public function getPermissions()
    {
        return $this->role ? $this->role->permissions : [];
    }

    /**
     * Get role name
     */
    public function getRoleName()
    {
        return $this->role ? $this->role->name : 'No Role';
    }

    /**
     * Check if invitation is still pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if invitation has been accepted
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if invitation has been declined
     */
    public function isDeclined()
    {
        return $this->status === 'declined';
    }

    /**
     * Check if invitation has expired (7 days)
     */
    public function isExpired()
    {
        if (!$this->invited_at) {
            return false;
        }
        
        return $this->invited_at->addDays(7)->isPast() && $this->status === 'pending';
    }

    /**
     * Scope to get only pending invitations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only accepted invitations
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope to get only declined invitations
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    /**
     * Get initials for display (works for both accepted and pending invitations)
     */
    public function getInitials()
    {
        if ($this->user) {
            return $this->user->initials();
        }
        
        // Generate initials from name for pending invitations
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get display name
     */
    public function getDisplayName()
    {
        return $this->user ? $this->user->name : $this->name;
    }

    /**
     * Get display email
     */
    public function getDisplayEmail()
    {
        return $this->user ? $this->user->email : $this->email;
    }

    /**
     * Get available permissions (static method)
     */
    public static function availablePermissions()
    {
        return [
            'manage_events' => 'Manage Events',
            'manage_team' => 'Manage Team Members',
            'view_reports' => 'View Reports',
            'edit_settings' => 'Edit Event Settings',
        ];
    }
}