<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunInduk extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($akun_induk) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Induk '{$akun_induk->nama}' dibuat.",
            ]);
        });

        static::updated(function ($akun_induk) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Induk '{$akun_induk->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($akun_induk) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Induk '{$akun_induk->nama}' dihapus.",
            ]);
        });
    }

    public function akun_dasar()
    {
        return $this->belongsTo(AkunDasar::class);
    }
}
