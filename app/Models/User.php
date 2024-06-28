<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function activity_log()
    {
        return $this->hasMany(ActivityLog::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "User '{$user->nama}' was created.",
            ]);
        });

        static::updated(function ($user) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "User '{$user->nama}' was updated.",
            ]);
        });

        static::deleted(function ($user) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "User '{$user->nama}' was deleted.",
            ]);
        });
    }
}
