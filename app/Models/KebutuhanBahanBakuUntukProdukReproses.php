<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanBahanBakuUntukProdukReproses extends Model
{
    use HasFactory;

    protected $table = "kbbuprs";

    protected $guarded = [];

    public function produk_reproses()
    {
        return $this->belongsTo(ProdukReproses::class);
    }

    public function bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class);
    }
}
