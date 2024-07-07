<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyesuaianGudangItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function penyesuaian_gudang()
    {
        return $this->belongsTo(PenyesuaianGudang::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    protected static function booted()
    {
        static::created(function ($penyesuaian_gudang_item) {
            $nama_gudang = ucReplaceSpaceToUnderscore($penyesuaian_gudang_item->penyesuaian_gudang->gudang->nama);
            $saldo_terakhir = Material::whereId($penyesuaian_gudang_item->material_id)->get()->last()->$nama_gudang;

            if($penyesuaian_gudang_item->penyesuaian_gudang->jenis_penyesuaian_gudang->saldo == "berkurang")
            {
                $saldo_baru = $saldo_terakhir - $penyesuaian_gudang_item->jumlah_dalam_satuan_besar;
            }
            elseif($penyesuaian_gudang_item->penyesuaian_gudang->jenis_penyesuaian_gudang->saldo == "bertambah")
            {
                $saldo_baru = $saldo_terakhir + $penyesuaian_gudang_item->jumlah_dalam_satuan_besar;
            }

            Material::whereId($penyesuaian_gudang_item->material_id)->update([
                $nama_gudang => $saldo_baru,
            ]);
        });

        static::deleted(function ($penyesuaian_gudang_item) {
            $nama_gudang = ucReplaceSpaceToUnderscore($penyesuaian_gudang_item->penyesuaian_gudang->gudang->nama);
            $saldo_terakhir = Material::whereId($penyesuaian_gudang_item->material_id)->get()->last()->$nama_gudang;

            if($penyesuaian_gudang_item->penyesuaian_gudang->jenis_penyesuaian_gudang->saldo == "berkurang")
            {
                $saldo_baru = $saldo_terakhir + $penyesuaian_gudang_item->jumlah_dalam_satuan_besar;
            }
            elseif($penyesuaian_gudang_item->penyesuaian_gudang->jenis_penyesuaian_gudang->saldo == "bertambah")
            {
                $saldo_baru = $saldo_terakhir - $penyesuaian_gudang_item->jumlah_dalam_satuan_besar;
            }

            Material::whereId($penyesuaian_gudang_item->material_id)->update([
                $nama_gudang => $saldo_baru,
            ]);
        });
    }
}
