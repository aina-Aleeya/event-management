<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            ->withPivot('unique_id','status_bayaran','categorizable_type','categorizable_id','created_at')
            ->withTimestamps();
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_event','event_id', 'category_id');
    }

    public function customCategories()
    {
        return $this->hasMany(CustomCategory::class);
    }
    
    public function penyertaan()
    {
        return $this->hasMany(Penyertaan::class, 'peserta_id');
    }

}