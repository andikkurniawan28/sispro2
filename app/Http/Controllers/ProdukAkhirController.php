<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\ProdukAkhir;
use Illuminate\Http\Request;
use App\Models\JenisProdukAkhir;
use Yajra\DataTables\DataTables;

class ProdukAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('produk_akhir.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_produk_akhirs = JenisProdukAkhir::all();
        $satuans = Satuan::all();
        return view('produk_akhir.create', compact('jenis_produk_akhirs', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:produk_akhirs",
            "kode" => "required|unique:produk_akhirs",
            "jenis_produk_akhir_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        ProdukAkhir::create($validated);
        return redirect()->back()->with("success", "Produk Akhir berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukAkhir $produk_akhir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk_akhir = ProdukAkhir::findOrFail($id);
        $jenis_produk_akhirs = JenisProdukAkhir::all();
        $satuans = Satuan::all();
        return view('produk_akhir.edit', compact('produk_akhir', 'jenis_produk_akhirs', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produk_akhir = ProdukAkhir::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:produk_akhirs,kode,' . $produk_akhir->id,
            'nama' => 'required|unique:produk_akhirs,nama,' . $produk_akhir->id,
            "jenis_produk_akhir_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        $produk_akhir->update($validated);
        return redirect()->route('produk_akhir.index')->with("success", "Produk Akhir berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk_akhir = ProdukAkhir::findOrFail($id);
        $produk_akhir->delete();
        return redirect()->back()->with("success", "Produk Akhir berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = ProdukAkhir::with('jenis_produk_akhir', 'satuan_besar', 'satuan_kecil')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('jenis_produk_akhir_nama', function ($row) {
                return $row->jenis_produk_akhir->nama;
            })
            ->addColumn('satuan_besar_nama', function ($row) {
                return $row->satuan_besar->nama;
            })
            ->addColumn('satuan_kecil_nama', function ($row) {
                return $row->satuan_kecil->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('produk_akhir.edit', $row->id);
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
