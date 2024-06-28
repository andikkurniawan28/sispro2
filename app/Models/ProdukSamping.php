<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukSamping extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jenis_produk_samping()
    {
        return $this->belongsTo(JenisProdukSamping::class);
    }

    public function satuan_besar()
    {
        return $this->belongsTo(Satuan::class, 'satuan_besar_id');
    }

    public function satuan_kecil()
    {
        return $this->belongsTo(Satuan::class, 'satuan_kecil_id');
    }

    protected static function booted()
    {
        static::created(function ($produk_samping) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Produk Samping '{$produk_samping->nama}' dibuat.",
            ]);
        });

        static::updated(function ($produk_samping) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Produk Samping '{$produk_samping->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($produk_samping) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Produk Samping '{$produk_samping->nama}' dihapus.",
            ]);
        });
    }
}
