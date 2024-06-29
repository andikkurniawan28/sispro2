<?php

namespace App\Http\Controllers;

use App\Models\FungsiMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FungsiMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('fungsi_material.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fungsi_material.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:fungsi_materials",
        ]);
        FungsiMaterial::create($validated);
        return redirect()->back()->with("success", "Fungsi Material berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(FungsiMaterial $fungsi_material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $fungsi_material = FungsiMaterial::findOrFail($id);
        return view('fungsi_material.edit', compact('fungsi_material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fungsi_material = FungsiMaterial::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:fungsi_materials,nama,' . $fungsi_material->id,
        ]);
        $fungsi_material->update($validated);
        return redirect()->route('fungsi_material.index')->with("success", "Fungsi Material berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fungsi_material = FungsiMaterial::findOrFail($id);
        $fungsi_material->delete();
        return redirect()->back()->with("success", "Fungsi Material berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = FungsiMaterial::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('fungsi_material.edit', $row->id);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
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
