<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

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

    public function generateQrCode()
    {
        try {
            $url = route('score.form', ['token' => $this->token]);
            
            $renderer = new ImageRenderer(
                new RendererStyle(300, 1),
                new SvgImageBackEnd()
            );
            
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($url);
            
            $this->qr_code = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            $this->save();
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR code for group ' . $this->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code;
    }
}