<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function init()
    {
        $setup = self::get()->last();
        if(Auth()->check()) {
            $setup->permission = Permission::where("jabatan_id", Auth()->user()->jabatan_id)->with('fitur')->get();
        }
        return $setup;
    }
}
