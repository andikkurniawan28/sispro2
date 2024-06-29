<?php

namespace App\Http\Controllers;

use App\Models\JenisMaterial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_material.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_material.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_materials",
        ]);
        JenisMaterial::create($validated);
        return redirect()->back()->with("success", "Jenis Material berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisMaterial $jenis_material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_material = JenisMaterial::findOrFail($id);
        return view('jenis_material.edit', compact('jenis_material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_material = JenisMaterial::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_materials,nama,' . $jenis_material->id,
        ]);
        $jenis_material->update($validated);
        return redirect()->route('jenis_material.index')->with("success", "Jenis Material berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_material = JenisMaterial::findOrFail($id);
        $jenis_material->delete();
        return redirect()->back()->with("success", "Jenis Material berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisMaterial::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_material.edit', $row->id);
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
