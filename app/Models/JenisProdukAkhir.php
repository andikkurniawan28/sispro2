<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisProdukAkhir extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_produk_akhir) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Akhir '{$jenis_produk_akhir->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_produk_akhir) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Akhir '{$jenis_produk_akhir->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_produk_akhir) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Akhir '{$jenis_produk_akhir->nama}' dihapus.",
            ]);
        });
    }
}
