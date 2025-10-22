<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'poster',
        'location',
        'qr_code',
        'link',
        'start_date',
        'end_date',
        'ads_start',
        'ads_end',
    ];
}