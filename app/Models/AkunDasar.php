<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkunDasar extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($akun_dasar) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Dasar '{$akun_dasar->nama}' dibuat.",
            ]);
        });

        static::updated(function ($akun_dasar) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Dasar '{$akun_dasar->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($akun_dasar) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Dasar '{$akun_dasar->nama}' dihapus.",
            ]);
        });
    }
}
