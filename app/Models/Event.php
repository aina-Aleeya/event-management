<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

 
protected $fillable = [
    'user_id', 'title', 'description', 'poster', 'event_type', 'venue', 'address', 
    'city', 'contact_email', 'contact_phone', 'start_date', 'end_date', 'start_time', 
    'end_time', 'registration_deadline', 'categories', 'entry_fee', 'max_participants', 
    'organizer_name', 'qr_code', 'event_link', 'ads_start_date', 'ads_end_date'
];
   
    protected $casts = [
        'categories' => 'array', 
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
            ->withPivot('kategori', 'unique_id')
            ->withTimestamps();
    }


    /**
     * Optional: Accessor untuk full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->venue, $this->address, $this->city]);
        return implode(', ', $parts);
    }
}
