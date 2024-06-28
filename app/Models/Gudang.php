<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gudang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Gudang '{$gudang->nama}' dibuat.",
            ]);
            $column_name = str_replace(' ', '_', $gudang->nama);
            $queries = [
                "ALTER TABLE bahan_bakus ADD COLUMN `{$column_name}` FLOAT NULL",
                "ALTER TABLE produk_reproses ADD COLUMN `{$column_name}` FLOAT NULL",
                "ALTER TABLE produk_sampings ADD COLUMN `{$column_name}` FLOAT NULL",
                "ALTER TABLE produk_akhirs ADD COLUMN `{$column_name}` FLOAT NULL",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });

        static::updated(function ($gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Gudang '{$gudang->nama}' dirubah.",
            ]);
        });

        static::deleted(function ($gudang) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Gudang '{$gudang->nama}' dihapus.",
            ]);
            $column_name = str_replace(' ', '_', $gudang->nama);
            $queries = [
                "ALTER TABLE bahan_bakus DROP COLUMN `{$column_name}`",
                "ALTER TABLE produk_reproses DROP COLUMN `{$column_name}`",
                "ALTER TABLE produk_sampings DROP COLUMN `{$column_name}`",
                "ALTER TABLE produk_akhirs DROP COLUMN `{$column_name}`",
            ];
            foreach ($queries as $query) {
                DB::statement($query);
            }
        });
    }
}
