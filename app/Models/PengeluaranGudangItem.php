<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranGudangItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pengeluaran_gudang()
    {
        return $this->belongsTo(PengeluaranGudang::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
