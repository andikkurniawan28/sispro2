<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\ProdukReproses;
use Illuminate\Http\Request;
use App\Models\JenisProdukReproses;
use Yajra\DataTables\DataTables;

class ProdukReprosesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('produk_reproses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_produk_reproses = JenisProdukReproses::all();
        $satuans = Satuan::all();
        return view('produk_reproses.create', compact('jenis_produk_reproses', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:produk_reproses",
            "kode" => "required|unique:produk_reproses",
            "jenis_produk_reproses_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        ProdukReproses::create($validated);
        return redirect()->back()->with("success", "Produk Reproses berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukReproses $produk_reproses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk_reproses = ProdukReproses::findOrFail($id);
        $jenis_produk_reproses = JenisProdukReproses::all();
        $satuans = Satuan::all();
        return view('produk_reproses.edit', compact('produk_reproses', 'jenis_produk_reproses', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produk_reproses = ProdukReproses::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:produk_reproses,kode,' . $produk_reproses->id,
            'nama' => 'required|unique:produk_reproses,nama,' . $produk_reproses->id,
            "jenis_produk_reproses_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        $produk_reproses->update($validated);
        return redirect()->route('produk_reproses.index')->with("success", "Produk Reproses berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk_reproses = ProdukReproses::findOrFail($id);
        $produk_reproses->delete();
        return redirect()->back()->with("success", "Produk Reproses berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = ProdukReproses::with('jenis_produk_reproses', 'satuan_besar', 'satuan_kecil')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('jenis_produk_reproses_nama', function ($row) {
                return $row->jenis_produk_reproses->nama;
            })
            ->addColumn('satuan_besar_nama', function ($row) {
                return $row->satuan_besar->nama;
            })
            ->addColumn('satuan_kecil_nama', function ($row) {
                return $row->satuan_kecil->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('produk_reproses.edit', $row->id);
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
