<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Material '{$jenis_material->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Material '{$jenis_material->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Material '{$jenis_material->nama}' dihapus.",
            ]);
        });
    }
}
