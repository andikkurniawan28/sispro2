<?php

namespace App\Http\Controllers;

use App\Models\JenisProdukAkhir;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisProdukAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_produk_akhir.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_produk_akhir.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_produk_akhirs",
        ]);
        JenisProdukAkhir::create($validated);
        return redirect()->back()->with("success", "Jenis Produk Akhir berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisProdukAkhir $jenis_produk_akhir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_produk_akhir = JenisProdukAkhir::findOrFail($id);
        return view('jenis_produk_akhir.edit', compact('jenis_produk_akhir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_produk_akhir = JenisProdukAkhir::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_produk_akhirs,nama,' . $jenis_produk_akhir->id,
        ]);
        $jenis_produk_akhir->update($validated);
        return redirect()->route('jenis_produk_akhir.index')->with("success", "Jenis Produk Akhir berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_produk_akhir = JenisProdukAkhir::findOrFail($id);
        $jenis_produk_akhir->delete();
        return redirect()->back()->with("success", "Jenis Produk Akhir berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisProdukAkhir::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_produk_akhir.edit', $row->id);
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
