<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Group extends Model
{
    protected $fillable = ['event_id', 'name', 'capacity', 'token', 'qr_code'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->token)) {
                $group->token = Str::random(32);
            }
        });
        
        static::created(function ($group) {
            $group->generateQrCode();
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pesertas()
    {
        return $this->belongsToMany(Peserta::class, 'group_peserta', 'group_id', 'peserta_id')
            ->withPivot('event_id')
            ->withTimestamps();
    }

    /**
     * Generate and save QR code as image file
     */
    public function generateQrCode()
    {
        try {
            $url = url('/markah/' . $this->token);
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($url);
            
            // Fetch QR code image
            $imageData = @file_get_contents($qrApiUrl);
            
            if ($imageData !== false) {
                // Create directory if not exists
                $directory = 'qr_codes';
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                
                // Save image file
                $filename = 'qr_group_' . $this->id . '_' . $this->token . '.png';
                $path = $directory . '/' . $filename;
                
                Storage::disk('public')->put($path, $imageData);
                
                // Save path to database
                $this->qr_code = $path;
                $this->save();
                
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR code for group ' . $this->id . ': ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Get full path to QR code image for PDF
     */
    public function getQrCodePathAttribute()
    {
        if (!empty($this->qr_code)) {
            return storage_path('app/public/' . $this->qr_code);
        }
        return null;
    }

    /**
     * Get public URL for QR code (for web display)
     */
    public function getQrCodeUrlAttribute()
    {
        if (!empty($this->qr_code)) {
            return Storage::url($this->qr_code);
        }
        return null;
    }

    /**
     * Delete QR code file when group is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($group) {
            if (!empty($group->qr_code)) {
                Storage::disk('public')->delete($group->qr_code);
            }
        });
    }
}