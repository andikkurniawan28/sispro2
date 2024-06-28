<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function init()
    {
        $setup = self::get()->last();
        // $setup->permission = Permission::where("role_id", Auth()->user()->role_id)->with('feature')->get();
        return $setup;
    }
}
