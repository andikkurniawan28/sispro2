<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permintaan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(PermintaanDetail::class);
    }

    public function jurnal_produksi()
    {
        return $this->hasMany(JurnalProduksi::class);
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y');
        $urutan = Permintaan::whereDate('created_at', today())->count() + 1;
        $kode = "REQ-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($permintaan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan '{$permintaan->kode}' dibuat.",
            ]);
        });

        static::updated(function ($permintaan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan '{$permintaan->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($permintaan) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Permintaan '{$permintaan->kode}' dihapus.",
            ]);
        });
    }

    public static function yangTerbuka()
    {
        return self::with('detail')
            ->where('status', 0)
            ->where('berlaku_sampai', '>', date('Y-m-d H:i:s'))
            ->get();
    }
}
