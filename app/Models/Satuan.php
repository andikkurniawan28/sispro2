<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($satuan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Satuan '{$satuan->nama}' dibuat.",
            ]);
        });

        static::updated(function ($satuan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Satuan '{$satuan->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($satuan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Satuan '{$satuan->nama}' dihapus.",
            ]);
        });
    }
}
