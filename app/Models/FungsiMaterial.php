<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FungsiMaterial extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($fungsi_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Fungsi Material '{$fungsi_material->nama}' dibuat.",
            ]);
        });

        static::updated(function ($fungsi_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Fungsi Material '{$fungsi_material->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($fungsi_material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Fungsi Material '{$fungsi_material->nama}' dihapus.",
            ]);
        });
    }
}
