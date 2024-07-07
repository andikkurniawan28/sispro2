<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Material;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\SaldoMaterial;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class SaldoMaterialController extends Controller
{
    public function index(Request $request)
    {
        $gudangs = Gudang::all();
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('saldo_material.index', compact('gudangs'));
    }

    public static function dataTable()
    {
        $data = SaldoMaterial::data();
        return Datatables::of($data)
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
        ->make(true);
    }
}
