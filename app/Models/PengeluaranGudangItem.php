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

    protected static function booted()
    {
        static::created(function ($pengeluaran_gudang_item) {
            $nama_gudang = ucReplaceSpaceToUnderscore($pengeluaran_gudang_item->pengeluaran_gudang->gudang->nama);
            $saldo_terakhir = Material::whereId($pengeluaran_gudang_item->material_id)->get()->last()->$nama_gudang;
            $saldo_baru = $saldo_terakhir - $pengeluaran_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($pengeluaran_gudang_item->material_id)->update([
                $nama_gudang => $saldo_baru,
            ]);
        });

        static::deleted(function ($pengeluaran_gudang_item) {
            $nama_gudang = ucReplaceSpaceToUnderscore($pengeluaran_gudang_item->pengeluaran_gudang->gudang->nama);
            $saldo_terakhir = Material::whereId($pengeluaran_gudang_item->material_id)->get()->last()->$nama_gudang;
            $saldo_baru = $saldo_terakhir + $pengeluaran_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($pengeluaran_gudang_item->material_id)->update([
                $nama_gudang => $saldo_baru,
            ]);
        });
    }
}
