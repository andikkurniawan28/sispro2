<?php

namespace App\Http\Controllers;

use App\Models\JenisPenyesuaianGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisPenyesuaianGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_penyesuaian_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_penyesuaian_gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_penyesuaian_gudangs",
            "saldo" => "required",
        ]);
        JenisPenyesuaianGudang::create($validated);
        return redirect()->back()->with("success", "Jenis Penyesuaian Gudang berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisPenyesuaianGudang $jenis_penyesuaian_gudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_penyesuaian_gudang = JenisPenyesuaianGudang::findOrFail($id);
        return view('jenis_penyesuaian_gudang.edit', compact('jenis_penyesuaian_gudang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_penyesuaian_gudang = JenisPenyesuaianGudang::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_penyesuaian_gudangs,nama,' . $jenis_penyesuaian_gudang->id,
            "saldo" => "required",
        ]);
        $jenis_penyesuaian_gudang->update($validated);
        return redirect()->route('jenis_penyesuaian_gudang.index')->with("success", "Jenis Penyesuaian Gudang berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_penyesuaian_gudang = JenisPenyesuaianGudang::findOrFail($id);
        $jenis_penyesuaian_gudang->delete();
        return redirect()->back()->with("success", "Jenis Penyesuaian Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisPenyesuaianGudang::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_penyesuaian_gudang.edit', $row->id);
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
