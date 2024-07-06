<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanGudangItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pemasukan_gudang()
    {
        return $this->belongsTo(PemasukanGudang::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
