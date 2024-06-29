<?php

namespace App\Http\Controllers;

use App\Models\JenisJurnalGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisJurnalGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_jurnal_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_jurnal_gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_jurnal_gudangs",
            "saldo" => "required",
        ]);
        JenisJurnalGudang::create($validated);
        return redirect()->back()->with("success", "Jenis Jurnal Gudang berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisJurnalGudang $jenis_jurnal_gudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_jurnal_gudang = JenisJurnalGudang::findOrFail($id);
        return view('jenis_jurnal_gudang.edit', compact('jenis_jurnal_gudang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_jurnal_gudang = JenisJurnalGudang::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_jurnal_gudangs,nama,' . $jenis_jurnal_gudang->id,
            "saldo" => "required",
        ]);
        $jenis_jurnal_gudang->update($validated);
        return redirect()->route('jenis_jurnal_gudang.index')->with("success", "Jenis Jurnal Gudang berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_jurnal_gudang = JenisJurnalGudang::findOrFail($id);
        $jenis_jurnal_gudang->delete();
        return redirect()->back()->with("success", "Jenis Jurnal Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisJurnalGudang::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_jurnal_gudang.edit', $row->id);
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
