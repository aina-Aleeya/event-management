<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Penyertaan extends Pivot
{
    protected $table = 'penyertaan';

    protected $fillable = [
        'event_id',
        'peserta_id',
        'kategori',
        'unique_id',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getKategoriNamaAttribute()
    {
        $mapping = [
            'AM' => 'Adult-Male',
            'AF' => 'Adult-Female',
            'KB' => 'Kid-Boy',
            'KG' => 'Kid-Girl',
            'El' => 'Elderly',
        ];

        return $mapping[$this->kategori] ?? $this->kategori;
    }
}