<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'event_id',
        'group_id', 
        'peserta_id',
        'round1',
        'round2',
        'round3',
        'average',
        'remarks'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    // Auto calculate average
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($score) {
            $rounds = collect([$score->round1, $score->round2, $score->round3])
                ->filter(fn($value) => !is_null($value) && $value !== '');

            $score->average = $rounds->count() > 0 ? round($rounds->avg(), 2) : null;
        });
    }
}