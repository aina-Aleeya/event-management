<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'category',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'penyertaan', 'peserta_id', 'event_id')
            ->withPivot('kategori', 'unique_id')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}