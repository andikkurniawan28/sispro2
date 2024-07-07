<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenyesuaianGudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_penyesuaian_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Penyesuaian Gudang '{$jenis_penyesuaian_gudang->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_penyesuaian_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Penyesuaian Gudang '{$jenis_penyesuaian_gudang->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_penyesuaian_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Penyesuaian Gudang '{$jenis_penyesuaian_gudang->nama}' dihapus.",
            ]);
        });
    }
}
