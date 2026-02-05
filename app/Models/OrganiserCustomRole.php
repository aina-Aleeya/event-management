<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganiserCustomRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_name',
    ];

    /**
     * Get the user that owns the custom role.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}