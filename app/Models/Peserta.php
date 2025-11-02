<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    //
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

    public function events(){
        return $this->belongsToMany(Event::class, 'penyertaan','peserta_id','event_id')
                    ->withTimestamps();
    }

}