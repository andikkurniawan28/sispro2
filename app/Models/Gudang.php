<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Gudang '{$gudang->nama}' dibuat.",
            ]);
        });

        static::updated(function ($gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Gudang '{$gudang->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Gudang '{$gudang->nama}' dihapus.",
            ]);
        });
    }
}
