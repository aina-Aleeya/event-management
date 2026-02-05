<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_system',
        'created_by',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    /**
     * Get team members with this role
     */
    public function teamMembers()
    {
        return $this->hasMany(EventTeamMember::class);
    }

    /**
     * Get the creator of this role
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Add permission to role
     */
    public function addPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
        return $this;
    }

    /**
     * Remove permission from role
     */
    public function removePermission($permission)
    {
        $permissions = array_diff($this->permissions ?? [], [$permission]);
        $this->update(['permissions' => array_values($permissions)]);
        return $this;
    }

    /**
     * Set permissions (replace all)
     */
    public function setPermissions(array $permissions)
    {
        $this->update(['permissions' => $permissions]);
        return $this;
    }

    /**
     * Scope for system roles
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope for custom roles
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Scope for custom roles created by specific user
     */
    public function scopeCustomByUser($query, $userId)
    {
        return $query->where('is_system', false)->where('created_by', $userId);
    }

    /**
     * Check if role can be deleted
     */
    public function isDeletable()
    {
        // System roles cannot be deleted
        if ($this->is_system) {
            return false;
        }

        // Custom roles can be deleted if no team members are assigned
        return $this->teamMembers()->count() === 0;
    }
}