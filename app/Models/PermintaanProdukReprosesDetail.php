<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanProdukReprosesDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanProdukReproses::class, 'permintaan_id');
    }

    public function produk_reproses()
    {
        return $this->belongsTo(ProdukReproses::class);
    }
}
