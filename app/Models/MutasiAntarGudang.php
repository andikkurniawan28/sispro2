<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MutasiAntarGudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->hasMany(MutasiAntarGudangItem::class);
    }

    public function gudang_asal()
    {
        return $this->belongsTo(Gudang::class, 'gudang_asal_id');
    }

    public function gudang_tujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y');
        $urutan = self::whereDate('created_at', today())->count() + 1;
        $kode = "MAG-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($mutasi_antar_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Mutasi Antar Gudang '{$mutasi_antar_gudang->kode}' dibuat.",
            ]);
        });

        static::updated(function ($mutasi_antar_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Mutasi Antar Gudang '{$mutasi_antar_gudang->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($mutasi_antar_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Mutasi Antar Gudang '{$mutasi_antar_gudang->kode}' dihapus.",
            ]);
        });
    }
}
