<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisProdukReproses extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($jenis_produk_reproses) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Reproses '{$jenis_produk_reproses->nama}' dibuat.",
            ]);
        });

        static::updated(function ($jenis_produk_reproses) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Reproses '{$jenis_produk_reproses->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($jenis_produk_reproses) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jenis Produk Reproses '{$jenis_produk_reproses->nama}' dihapus.",
            ]);
        });
    }
}
