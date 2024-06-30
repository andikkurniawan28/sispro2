<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function detail()
    {
        return $this->hasMany(JurnalGudangDetail::class);
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y'); // Format tanggal dd/mm/yy
        $urutan = self::whereDate('created_at', today())->count() + 1;
        $kode = "-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($jurnal_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jurnal Gudang '{$jurnal_gudang->kode}' dibuat.",
            ]);
        });

        static::updated(function ($jurnal_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jurnal Gudang '{$jurnal_gudang->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($jurnal_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Jurnal Gudang '{$jurnal_gudang->kode}' dihapus.",
            ]);
        });
    }
}
