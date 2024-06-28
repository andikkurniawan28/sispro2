<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    protected static function booted()
    {
        static::created(function ($jabatan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jabatan '{$jabatan->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jabatan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jabatan '{$jabatan->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jabatan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jabatan '{$jabatan->nama}' dihapus.",
            ]);
        });
    }
}
