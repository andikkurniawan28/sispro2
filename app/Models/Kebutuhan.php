<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kebutuhan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Material::class, 'produk_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Material::class, 'bahan_id');
    }

    protected static function booted()
    {
        static::created(function ($kebutuhan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Kebutuhan '{$kebutuhan->produk->nama}' dibuat.",
            ]);
        });

        static::updated(function ($kebutuhan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Kebutuhan '{$kebutuhan->produk->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($kebutuhan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Kebutuhan '{$kebutuhan->produk->nama}' dihapus.",
            ]);
        });
    }
}
