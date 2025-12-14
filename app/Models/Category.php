<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['name'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'category_event', 'category_id', 'event_id');
    }

}
