<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function produk_akhir()
    {
        return $this->belongsTo(ProdukAkhir::class);
    }
}
