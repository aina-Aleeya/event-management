<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
    protected $fillable = [
        'event_id',
        'status',
        'rejection_reason'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
