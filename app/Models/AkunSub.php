<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunSub extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($akun_sub) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Sub '{$akun_sub->nama}' dibuat.",
            ]);
        });

        static::updated(function ($akun_sub) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Sub '{$akun_sub->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($akun_sub) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun Sub '{$akun_sub->nama}' dihapus.",
            ]);
        });
    }

    public function akun_induk()
    {
        return $this->belongsTo(AkunInduk::class);
    }
}
