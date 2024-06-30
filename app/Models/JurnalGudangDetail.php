<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalGudangDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jurnal_gudang()
    {
        return $this->belongsTo(JurnalGudang::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
