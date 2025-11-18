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
        'status_bayaran',
        'group_token',
        'pendaftar_id',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pendaftar()
    {
        return $this->belongsTo(User::class, 'pendaftar_id');
    }

    public function getKategoriNamaAttribute()
    {
        $kategori = strtoupper($this->kategori);

        $type = str_starts_with($kategori, 'I') ? 'Individual' : (str_starts_with($kategori, 'G') ? 'Group' : '-');

        $kodUmurJantina = substr($kategori, -2);
        
        $mapping = [
            'AM' => 'Adult Male',
            'AF' => 'Adult Female',
            'KB' => 'Kid Boy',
            'KG' => 'Kid Girl',
            'EL' => 'Elderly',
        ];

        $kategoriUmur = $mapping[$kodUmurJantina] ?? $kodUmurJantina;

        return  "{$type} - {$kategoriUmur}";
    }
    public function getJumlahBayaranAttribute()
{
    $bilPeserta = self::where('event_id', $this->event_id)
        ->where('kategori', $this->kategori)
        ->count();

    $hargaSeorang = $this->event->entry_fee ?? 0;

    return $hargaSeorang * $bilPeserta;
}

}