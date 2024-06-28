<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divisi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }

    protected static function booted()
    {
        static::created(function ($divisi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Divisi '{$divisi->nama}' dibuat.",
            ]);
        });

        static::updated(function ($divisi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Divisi '{$divisi->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($divisi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Divisi '{$divisi->nama}' dihapus.",
            ]);
        });
    }
}
