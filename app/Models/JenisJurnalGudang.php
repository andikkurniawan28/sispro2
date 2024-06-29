<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisJurnalGudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_jurnal_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Jurnal Gudang '{$jenis_jurnal_gudang->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_jurnal_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Jurnal Gudang '{$jenis_jurnal_gudang->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_jurnal_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Jurnal Gudang '{$jenis_jurnal_gudang->nama}' dihapus.",
            ]);
        });
    }
}
