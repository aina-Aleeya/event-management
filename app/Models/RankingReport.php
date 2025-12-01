<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'penyertaan_id',
        'ranking',
    ];

    public function penyertaan()
    {
        return $this->belongsTo(Penyertaan::class, 'penyertaan_id');
    }
}
