<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'posters',
        'event_type',
        'venue',
        'city',
        'contact_email',
        'contact_phone',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'registration_deadline',
        'entry_fee',
        'max_participants',
        'organizer_name',
        'qr_code',
        'event_link',
        'ads_start_date',
        'ads_end_date'
    ];

    protected $casts = [
        'posters' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'ads_start_date' => 'date',
        'ads_end_date' => 'date',
        'entry_fee' => 'decimal:2',
        'max_participants' => 'integer',
        'registration_deadline' => 'date',
    ];

    /**
     * Relationship: event belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'penyertaan', 'event_id', 'peserta_id')
            ->using(\App\Models\Penyertaan::class)
            ->withPivot('unique_id', 'status_bayaran', 'categorizable_type', 'categorizable_id', 'created_at')
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_event', 'event_id', 'category_id');
    }

    public function customCategories()
    {
        return $this->hasMany(CustomCategory::class);
    }

    // Event owner (organiser who created the event)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Team members assigned to this event
    public function teamMembers(): HasMany
    {
        return $this->hasMany(EventTeamMember::class);
    }

    // Check if a user is the owner
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    // Check if a user is a team member
    public function hasTeamMember(User $user): bool
    {
        return $this->teamMembers()->where('user_id', $user->id)->exists();
    }

    // Check if user can access this event (owner, team member, or admin)
    public function canBeAccessedBy(User $user): bool
    {
        // Admin can access everything
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can access
        if ($this->isOwnedBy($user)) {
            return true;
        }

        // Team member can access
        return $this->hasTeamMember($user);
    }

    // Get user's role in this event
    public function getUserRole(User $user): ?EventTeamMember
    {
        if ($this->isOwnedBy($user)) {
            return null; // Owner has full access
        }

        return $this->teamMembers()->where('user_id', $user->id)->first();
    }

    // Check if user has specific permission
    public function userHasPermission(User $user, string $permission): bool
    {
        // Admin and owner have all permissions
        if ($user->isAdmin() || $this->isOwnedBy($user)) {
            return true;
        }

        // Check team member permission
        $teamMember = $this->getUserRole($user);
        return $teamMember && $teamMember->hasPermission($permission);
    }

    public function getCertificateTemplatePath($category)
    {
        Log::info("Looking for template for category: " . $category);

        $customCategory = $this->customCategories()->where('name', $category)->first();
        if ($customCategory) {
            Log::info("Custom category found: " . $customCategory->name);
            Log::info("Template path: " . ($customCategory->certificate_template ?? 'NULL'));

            if ($customCategory->certificate_template && Storage::exists($customCategory->certificate_template)) {
                return $customCategory->certificate_template;
            }
        }

        $defaultCategory = $this->categories()->where('name', $category)->first();
        if ($defaultCategory) {
            Log::info("Default category found: " . $defaultCategory->name);
            Log::info("Template path: " . ($defaultCategory->certificate_template ?? 'NULL'));

            if ($defaultCategory->certificate_template && Storage::exists($defaultCategory->certificate_template)) {
                return $defaultCategory->certificate_template;
            }
        }

        Log::warning("No template found for category: " . $category);
        return null;
    }


}
