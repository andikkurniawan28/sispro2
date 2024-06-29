<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukReproses extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jenis_produk_reproses()
    {
        return $this->belongsTo(JenisProdukReproses::class);
    }

    public function satuan_besar()
    {
        return $this->belongsTo(Satuan::class, 'satuan_besar_id');
    }

    public function satuan_kecil()
    {
        return $this->belongsTo(Satuan::class, 'satuan_kecil_id');
    }

    public function kebutuhan_bahan_baku_untuk_produk_reproses()
    {
        return $this->hasMany(KebutuhanBahanBakuUntukProdukReproses::class);
    }

    protected static function booted()
    {
        static::created(function ($produk_reproses) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Produk Reproses '{$produk_reproses->nama}' dibuat.",
            ]);
        });

        static::updated(function ($produk_reproses) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Produk Reproses '{$produk_reproses->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($produk_reproses) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Produk Reproses '{$produk_reproses->nama}' dihapus.",
            ]);
        });
    }
}
