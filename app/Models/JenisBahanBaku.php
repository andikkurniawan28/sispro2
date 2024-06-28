<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisBahanBaku extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_bahan_baku) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Bahan Baku '{$jenis_bahan_baku->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_bahan_baku) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Bahan Baku '{$jenis_bahan_baku->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_bahan_baku) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Bahan Baku '{$jenis_bahan_baku->nama}' dihapus.",
            ]);
        });
    }
}
