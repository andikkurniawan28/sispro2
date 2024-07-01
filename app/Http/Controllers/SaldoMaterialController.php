<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\SaldoMaterial;
use Yajra\DataTables\DataTables;

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

    public function penyesuaian($id)
    {
        $gudangs = Gudang::all();
        $material = Material::findOrFail($id);
        return view('saldo_material.penyesuaian', compact('gudangs', 'material', 'id'));
    }

    public function proses(Request $request)
    {
        $id = $request->id;
        Material::whereId($id)->update($request->except(['_token', '_method']));
        return redirect()->route('saldo_material.index')->with('success', 'Saldo Material berhasil disesuaikan');
    }

    public static function dataTable()
    {
        $data = SaldoMaterial::data();
        return Datatables::of($data)
            ->addColumn('tindakan', function ($row) {
                $penyesuaian = route('saldo_material.penyesuaian', $row['id']);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $penyesuaian . '" class="btn btn-secondary btn-sm">Penyesuaian</a>
                    </div>
                ';
                })
            ->rawColumns(['tindakan'])
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
        ->make(true);
    }
}
