<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['event_id', 'name', 'capacity'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'group_peserta', 'group_id', 'peserta_id');
    }
}
