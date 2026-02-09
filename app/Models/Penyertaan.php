<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Penyertaan extends Pivot
{
    protected $table = 'penyertaan';

protected $fillable = [
    'event_id',
    'peserta_id',
    'unique_id',
    'status_bayaran',
    'group_token',
    'pendaftar_id',
    'categorizable_id',      
    'categorizable_type',
    'payment_receipt',      
    'payment_date',         
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

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    
    // Polymorphic relationship - MUCH CLEARER!
    public function categorizable()
    {
        return $this->morphTo();
    }

    // Easy accessor to get category name
    public function getCategoryNameAttribute()
    {
        return $this->categorizable ? $this->categorizable->name : 'N/A';
    }

    // Easy checker methods
    public function isDefaultCategory()
    {
        return $this->categorizable_type === 'App\Models\Category';
    }

    public function isCustomCategory()
    {
        return $this->categorizable_type === 'App\Models\CustomCategory';
    }

    
    public function getJumlahBayaranAttribute()
    {
        $bilPeserta = self::where('event_id', $this->event_id)
            ->where('categorizable_id', $this->categorizable_id)
            ->where('categorizable_type', $this->categorizable_type)
            ->count();

        $hargaSeorang = $this->event->entry_fee ?? 0;

        return $hargaSeorang * $bilPeserta;
    }

}