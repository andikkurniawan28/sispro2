<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function produk()
    {
        $materials = Material::whereIn('fungsi_material_id', [3,4,5])->get();
        return $materials;
    }

    public function fungsi_material()
    {
        return $this->belongsTo(FungsiMaterial::class);
    }

    public function jenis_material()
    {
        return $this->belongsTo(JenisMaterial::class);
    }

    public function satuan_besar()
    {
        return $this->belongsTo(Satuan::class, 'satuan_besar_id');
    }

    public function satuan_kecil()
    {
        return $this->belongsTo(Satuan::class, 'satuan_kecil_id');
    }

    public function kebutuhan()
    {
        return $this->hasMany(Kebutuhan::class, 'produk_id');
    }

    protected static function booted()
    {
        static::created(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->nama}' dibuat.",
            ]);
        });

        static::updated(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->nama}' dihapus.",
            ]);
        });
    }
}
