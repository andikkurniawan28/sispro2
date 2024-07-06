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

    protected static function booted()
    {
        static::created(function ($pemasukan_gudang_item) {
            $nama_gudang = ucReplaceSpaceToUnderscore($pemasukan_gudang_item->pemasukan_gudang->gudang->nama);
            $saldo_terakhir = Material::whereId($pemasukan_gudang_item->material_id)->get()->last()->$nama_gudang;
            $saldo_baru = $saldo_terakhir + $pemasukan_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($pemasukan_gudang_item->material_id)->update([
                $nama_gudang => $saldo_baru,
            ]);
        });

        static::deleted(function ($pemasukan_gudang_item) {
            $nama_gudang = ucReplaceSpaceToUnderscore($pemasukan_gudang_item->pemasukan_gudang->gudang->nama);
            $saldo_terakhir = Material::whereId($pemasukan_gudang_item->material_id)->get()->last()->$nama_gudang;
            $saldo_baru = $saldo_terakhir - $pemasukan_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($pemasukan_gudang_item->material_id)->update([
                $nama_gudang => $saldo_baru,
            ]);
        });
    }
}
