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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    public function getRoleDisplayName(): string
    {
        if ($this->role === 'custom' && $this->custom_role_name) {
            return $this->custom_role_name;
        }

        return match ($this->role) {
            'clerk' => 'Clerk/Admin',
            'judge' => 'Judge',
            'scorekeeper' => 'Scorekeeper',
            'coordinator' => 'Event Coordinator',
            default => ucfirst($this->role),
        };
    }

    public static function availablePermissions(): array
    {
        return [
            'admin.participants' => 'View Participants',
            'admin.participants-details' => 'Manage Participants',
            'admin.event-grouping' => 'View Groups',
            'admin.groups' => 'Manage Groups',
            'admin.ranking' => 'View Scores',
            'admin.markah-form' => 'Manage/Edit Scores',
            // 'export_data' => 'Export Data',
            // 'manage_event_settings' => 'Manage Event Settings',
        ];
    }

    public static function defaultPermissionsFor(string $role): array
    {
        return match ($role) {
            'clerk' => [
                'admin.participants',
                'admin.participants-details',
                'admin.event-grouping',
                'admin.ranking',
            ],
            'judge' => [
                'admin.participants',
                'admin.event-grouping',
                'admin.ranking',
                'admin.markah-form',
            ],
            'scorekeeper' => [
                'admin.participants',
                'admin.event-grouping',
                'admin.ranking',
            ],
            'coordinator' => [
                'admin.participants',
                'admin.participants-details',
                'admin.event-grouping',
                'admin.groups',
                'admin.ranking',
            ],
            default => [],
        };
    }
}
