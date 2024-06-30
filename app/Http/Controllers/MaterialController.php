<?php

namespace App\Http\Controllers;

use App\Models\FungsiMaterial;
use App\Models\Satuan;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\JenisMaterial;
use Yajra\DataTables\DataTables;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('material.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fungsi_materials = FungsiMaterial::all();
        $jenis_materials = JenisMaterial::all();
        $satuans = Satuan::all();
        return view('material.create', compact('fungsi_materials', 'jenis_materials', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:materials",
            "kode" => "required|unique:materials",
            "barcode" => "nullable|unique:materials",
            "fungsi_material_id" => "required",
            "jenis_material_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        Material::create($validated);
        return redirect()->back()->with("success", "Material berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('material.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $fungsi_materials = FungsiMaterial::all();
        $jenis_materials = JenisMaterial::all();
        $satuans = Satuan::all();
        return view('material.edit', compact('material', 'fungsi_materials', 'jenis_materials', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:materials,kode,' . $material->id,
            'nama' => 'required|unique:materials,nama,' . $material->id,
            'barcode' => 'nullable|unique:materials,barcode,' . $material->id,
            "fungsi_material_id" => "required",
            "jenis_material_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        $material->update($validated);
        return redirect()->route('material.index')->with("success", "Material berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();
        return redirect()->back()->with("success", "Material berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Material::with('fungsi_material', 'jenis_material')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('fungsi_material_nama', function ($row) {
                return $row->fungsi_material ? $row->fungsi_material->nama : 'N/A';
            })
            ->addColumn('jenis_material_nama', function ($row) {
                return $row->jenis_material ? $row->jenis_material->nama : 'N/A';
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('material.edit', $row->id);
                $showUrl = route('material.show', $row->id);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
                    </div>
                ';
            })
            ->rawColumns(['tindakan'])
            ->make(true);
    }

}
