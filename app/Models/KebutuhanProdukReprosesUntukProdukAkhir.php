<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanProdukReprosesUntukProdukAkhir extends Model
{
    use HasFactory;

    protected $table = "kprupas";

    protected $guarded = [];

    public function produk_akhir()
    {
        return $this->belongsTo(ProdukAkhir::class);
    }

    public function produk_reproses()
    {
        return $this->belongsTo(ProdukReproses::class);
    }
}
