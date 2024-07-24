<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($akun) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun '{$akun->nama}' dibuat.",
            ]);
        });

        static::updated(function ($akun) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun '{$akun->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($akun) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Akun '{$akun->nama}' dihapus.",
            ]);
        });
    }

    public function akun_sub()
    {
        return $this->belongsTo(AkunSub::class);
    }
}
