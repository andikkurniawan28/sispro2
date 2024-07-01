<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerintahProduksi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y');
        $urutan = self::whereDate('created_at', today())->count() + 1;
        $kode = "PPR-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($perintah_produksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Perintah Produksi '{$perintah_produksi->kode}' dibuat.",
            ]);
        });

        static::updated(function ($perintah_produksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Perintah Produksi '{$perintah_produksi->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($perintah_produksi) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Perintah Produksi '{$perintah_produksi->kode}' dihapus.",
            ]);
        });
    }
}
