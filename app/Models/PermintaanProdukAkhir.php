<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PermintaanProdukAkhir extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(PermintaanProdukAkhirDetail::class);
    }

    public static function kode_faktur()
    {
        // Mendapatkan tanggal saat ini
        $tanggal = now()->format('d/m/y'); // Format tanggal dd/mm/yy

        // Mendapatkan urutan permintaan pada tanggal tersebut
        $urutan = PermintaanProdukAkhir::whereDate('created_at', today())->count() + 1;

        // Format kode faktur sesuai dengan yang diminta
        $kode = "PPA-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);

        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($permintaan_produk_akhir) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan Produk Akhir '{$permintaan_produk_akhir->kode}' dibuat.",
            ]);
        });

        static::updated(function ($permintaan_produk_akhir) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan Produk Akhir '{$permintaan_produk_akhir->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($permintaan_produk_akhir) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan Produk Akhir '{$permintaan_produk_akhir->kode}' dihapus.",
            ]);
        });
    }
}
