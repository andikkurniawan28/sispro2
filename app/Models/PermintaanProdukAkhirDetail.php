<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanProdukAkhirDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanProdukAkhir::class, 'permintaan_id');
    }

    public function produk_akhir()
    {
        return $this->belongsTo(ProdukAkhir::class);
    }
}
