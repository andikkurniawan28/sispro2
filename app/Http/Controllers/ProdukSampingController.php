<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\ProdukSamping;
use Illuminate\Http\Request;
use App\Models\JenisProdukSamping;
use Yajra\DataTables\DataTables;

class ProdukSampingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('produk_samping.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_produk_sampings = JenisProdukSamping::all();
        $satuans = Satuan::all();
        return view('produk_samping.create', compact('jenis_produk_sampings', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:produk_sampings",
            "kode" => "required|unique:produk_sampings",
            "jenis_produk_samping_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        ProdukSamping::create($validated);
        return redirect()->back()->with("success", "Produk Samping berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukSamping $produk_samping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk_samping = ProdukSamping::findOrFail($id);
        $jenis_produk_sampings = JenisProdukSamping::all();
        $satuans = Satuan::all();
        return view('produk_samping.edit', compact('produk_samping', 'jenis_produk_sampings', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produk_samping = ProdukSamping::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:produk_sampings,kode,' . $produk_samping->id,
            'nama' => 'required|unique:produk_sampings,nama,' . $produk_samping->id,
            "jenis_produk_samping_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        $produk_samping->update($validated);
        return redirect()->route('produk_samping.index')->with("success", "Produk Samping berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk_samping = ProdukSamping::findOrFail($id);
        $produk_samping->delete();
        return redirect()->back()->with("success", "Produk Samping berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = ProdukSamping::with('jenis_produk_samping', 'satuan_besar', 'satuan_kecil')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('jenis_produk_samping_nama', function ($row) {
                return $row->jenis_produk_samping->nama;
            })
            ->addColumn('satuan_besar_nama', function ($row) {
                return $row->satuan_besar->nama;
            })
            ->addColumn('satuan_kecil_nama', function ($row) {
                return $row->satuan_kecil->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('produk_samping.edit', $row->id);
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
