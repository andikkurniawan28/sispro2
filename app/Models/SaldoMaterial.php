<?php

namespace App\Models;

use App\Models\Gudang;
use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaldoMaterial extends Model
{
    use HasFactory;

    public static function data()
    {
        $field_yang_dicari = [];

        $field_yang_dicari[0] = 'kode';
        $field_yang_dicari[1] = 'nama';

        foreach(Gudang::all() as $gudang)
        {
            $field_yang_dicari[] = ucReplaceSpaceToUnderscore($gudang->nama);
        }

        $materials = Material::with('satuan_besar')->get();

        $data = $materials->map(function ($material) use ($field_yang_dicari) {
            $result = $material->only($field_yang_dicari);
            $result['satuan_besar'] = $material->satuan_besar->nama;
            return $result;
        });

        return $data;
    }
}
