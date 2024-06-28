<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisProdukSamping extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_produk_samping) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Samping '{$jenis_produk_samping->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_produk_samping) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Samping '{$jenis_produk_samping->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_produk_samping) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Samping '{$jenis_produk_samping->nama}' dihapus.",
            ]);
        });
    }
}
