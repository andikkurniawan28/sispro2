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

    public static function kode_faktur()
    {
        $tanggal = now()->format('d/m/y'); // Format tanggal dd/mm/yy
        $urutan = Permintaan::whereDate('created_at', today())->count() + 1;
        $kode = "PPA-{$tanggal}-" . str_pad($urutan, 6, '0', STR_PAD_LEFT);
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
}
