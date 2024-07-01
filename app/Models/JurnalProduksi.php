<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JurnalProduksi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasil()
    {
        return $this->hasMany(HasilProduksi::class);
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y'); // Format tanggal dd/mm/yy
        $urutan = self::whereDate('created_at', today())->count() + 1;
        $kode = "PRO-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($jurnal_produksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jurnal Produksi '{$jurnal_produksi->kode}' dibuat.",
            ]);
        });

        static::updated(function ($jurnal_produksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jurnal Produksi '{$jurnal_produksi->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($jurnal_produksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jurnal Produksi '{$jurnal_produksi->kode}' dihapus.",
            ]);
        });
    }
}
