<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanBahanBakuUntukProdukAkhir extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function produk_akhir()
    {
        return $this->belongsTo(ProdukAkhir::class);
    }

    public function bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
