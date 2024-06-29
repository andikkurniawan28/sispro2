<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalGudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jenis_jurnal_gudang()
    {
        return $this->belongsTo(JenisJurnalGudang::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y'); // Format tanggal dd/mm/yy
        $urutan = PermintaanProdukAkhir::whereDate('created_at', today())->count() + 1;
        $kode = "JRG-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }
}
