<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTeamMember extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'role',
        'custom_role_name',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Check if team member has specific permission
    public function hasPermission(string $permission): bool
    {
        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    // Get role display name
    public function getRoleDisplayName(): string
    {
        if ($this->role === 'custom' && $this->custom_role_name) {
            return $this->custom_role_name;
        }

        return match($this->role) {
            'clerk' => 'Clerk/Admin',
            'judge' => 'Judge',
            'scorekeeper' => 'Scorekeeper',
            'coordinator' => 'Event Coordinator',
            default => ucfirst($this->role),
        };
    }

    // Available permissions list
    public static function availablePermissions(): array
    {
        return [
            'view_participants' => 'View Participants',
            'manage_participants' => 'Manage Participants',
            'view_groups' => 'View Groups',
            'manage_groups' => 'Manage Groups',
            'view_scores' => 'View Scores',
            'manage_scores' => 'Manage/Edit Scores',
            'view_reports' => 'View Reports',
            'export_data' => 'Export Data',
            'manage_event_settings' => 'Manage Event Settings',
        ];
    }

    // Get default permissions for each role
    public static function defaultPermissionsFor(string $role): array
    {
        return match($role) {
            'clerk' => [
                'view_participants',
                'manage_participants',
                'view_groups',
                'view_scores',
                'view_reports',
                'export_data',
            ],
            'judge' => [
                'view_participants',
                'view_groups',
                'view_scores',
                'manage_scores',
            ],
            'scorekeeper' => [
                'view_participants',
                'view_groups',
                'view_scores',
                'manage_scores',
                'view_reports',
            ],
            'coordinator' => [
                'view_participants',
                'manage_participants',
                'view_groups',
                'manage_groups',
                'view_scores',
                'view_reports',
                'export_data',
                'manage_event_settings',
            ],
            default => [],
        };
    }
}