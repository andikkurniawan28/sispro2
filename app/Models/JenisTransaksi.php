<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisTransaksi extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_transaksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Transaksi '{$jenis_transaksi->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_transaksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Transaksi '{$jenis_transaksi->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_transaksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Transaksi '{$jenis_transaksi->nama}' dihapus.",
            ]);
        });
    }
}
