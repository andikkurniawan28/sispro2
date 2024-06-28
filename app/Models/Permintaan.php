<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permintaan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($permintaan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan '{$permintaan->kode}' dibuat.",
            ]);
        });

        static::updated(function ($permintaan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan '{$permintaan->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($permintaan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan '{$permintaan->kode}' dihapus.",
            ]);
        });
    }
}
