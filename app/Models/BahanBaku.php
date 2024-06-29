<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jenis_bahan_baku()
    {
        return $this->belongsTo(JenisBahanBaku::class);
    }

    public function satuan_besar()
    {
        return $this->belongsTo(Satuan::class, 'satuan_besar_id');
    }

    public function satuan_kecil()
    {
        return $this->belongsTo(Satuan::class, 'satuan_kecil_id');
    }

    public function kebutuhan_bahan_baku_untuk_produk_akhir()
    {
        return $this->hasMany(KebutuhanBahanBakuUntukProdukAkhir::class);
    }

    protected static function booted()
    {
        static::created(function ($bahan_baku) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Bahan Baku '{$bahan_baku->nama}' dibuat.",
            ]);
        });

        static::updated(function ($bahan_baku) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Bahan Baku '{$bahan_baku->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($bahan_baku) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Bahan Baku '{$bahan_baku->nama}' dihapus.",
            ]);
        });
    }
}
