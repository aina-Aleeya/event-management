<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peserta extends Model
{
    protected $fillable = [
        'nama_penuh',
        'nama_panggilan',
        'kelas',
        'gambar',
        'email',
        'jantina',
        'ic',
        'tarikh_lahir',
        'ip_address',
        'user_agent',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'penyertaan', 'peserta_id', 'event_id')
            ->using(\App\Models\Penyertaan::class)
            ->withPivot('unique_id', 'status_bayaran', 'categorizable_type', 'categorizable_id', 'created_at')
            ->withTimestamps();
    }

    public function penyertaans(): HasMany
    {
        return $this->hasMany(Penyertaan::class, 'peserta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_peserta', 'peserta_id', 'group_id')
            ->withPivot('event_id')
            ->withTimestamps();
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'peserta_id');
    }
}