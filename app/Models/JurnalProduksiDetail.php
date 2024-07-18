<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalProduksiDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jurnal_produksi()
    {
        return $this->belongsTo(JurnalProduksi::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
