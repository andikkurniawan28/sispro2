<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiAntarGudangItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mutasi_antar_gudang()
    {
        return $this->belongsTo(MutasiAntarGudang::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    protected static function booted()
    {
        static::created(function ($mutasi_antar_gudang_item) {

            // Kurangi stock di Gudang Asal
            $nama_gudang_asal = ucReplaceSpaceToUnderscore($mutasi_antar_gudang_item->mutasi_antar_gudang->gudang_asal->nama);
            $saldo_terakhir_di_gudang_asal = Material::whereId($mutasi_antar_gudang_item->material_id)->get()->last()->$nama_gudang_asal;
            $saldo_baru_baru_di_gudang_asal = $saldo_terakhir_di_gudang_asal - $mutasi_antar_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($mutasi_antar_gudang_item->material_id)->update([
                $nama_gudang_asal => $saldo_baru_baru_di_gudang_asal,
            ]);

            // Tambah stock di Gudang Tujuan
            $nama_gudang_tujuan = ucReplaceSpaceToUnderscore($mutasi_antar_gudang_item->mutasi_antar_gudang->gudang_tujuan->nama);
            $saldo_terakhir_di_gudang_tujuan = Material::whereId($mutasi_antar_gudang_item->material_id)->get()->last()->$nama_gudang_tujuan;
            $saldo_baru_baru_di_gudang_tujuan = $saldo_terakhir_di_gudang_tujuan + $mutasi_antar_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($mutasi_antar_gudang_item->material_id)->update([
                $nama_gudang_tujuan => $saldo_baru_baru_di_gudang_tujuan,
            ]);

        });

        static::deleted(function ($mutasi_antar_gudang_item) {

            // Tambah stock di Gudang Asal
            $nama_gudang_asal = ucReplaceSpaceToUnderscore($mutasi_antar_gudang_item->mutasi_antar_gudang->gudang_asal->nama);
            $saldo_terakhir_di_gudang_asal = Material::whereId($mutasi_antar_gudang_item->material_id)->get()->last()->$nama_gudang_asal;
            $saldo_baru_baru_di_gudang_asal = $saldo_terakhir_di_gudang_asal + $mutasi_antar_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($mutasi_antar_gudang_item->material_id)->update([
                $nama_gudang_asal => $saldo_baru_baru_di_gudang_asal,
            ]);

            // Kurangi stock di Gudang Tujuan
            $nama_gudang_tujuan = ucReplaceSpaceToUnderscore($mutasi_antar_gudang_item->mutasi_antar_gudang->gudang_tujuan->nama);
            $saldo_terakhir_di_gudang_tujuan = Material::whereId($mutasi_antar_gudang_item->material_id)->get()->last()->$nama_gudang_tujuan;
            $saldo_baru_baru_di_gudang_tujuan = $saldo_terakhir_di_gudang_tujuan - $mutasi_antar_gudang_item->jumlah_dalam_satuan_besar;
            Material::whereId($mutasi_antar_gudang_item->material_id)->update([
                $nama_gudang_tujuan => $saldo_baru_baru_di_gudang_tujuan,
            ]);
        });
    }
}
