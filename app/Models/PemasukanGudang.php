<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PemasukanGudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function item()
    {
        return $this->hasMany(PemasukanGudangItem::class);
    }

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y');
        $urutan = self::whereDate('created_at', today())->count() + 1;
        $kode = "PMG-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
        return $kode;
    }

    protected static function booted()
    {
        static::created(function ($pemasukan_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Pemasukan Gudang '{$pemasukan_gudang->kode}' dibuat.",
            ]);
        });

        static::updated(function ($pemasukan_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Pemasukan Gudang '{$pemasukan_gudang->kode}' dirubah.",
            ]);
        });

        static::deleted(function ($pemasukan_gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Pemasukan Gudang '{$pemasukan_gudang->kode}' dihapus.",
            ]);
        });
    }
}
