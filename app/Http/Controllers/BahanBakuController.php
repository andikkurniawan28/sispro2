<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use App\Models\JenisBahanBaku;
use Yajra\DataTables\DataTables;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('bahan_baku.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_bahan_bakus = JenisBahanBaku::all();
        $satuans = Satuan::all();
        return view('bahan_baku.create', compact('jenis_bahan_bakus', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:bahan_bakus",
            "kode" => "required|unique:bahan_bakus",
            "jenis_bahan_baku_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        BahanBaku::create($validated);
        return redirect()->back()->with("success", "Bahan Baku berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanBaku $bahan_baku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bahan_baku = BahanBaku::findOrFail($id);
        $jenis_bahan_bakus = JenisBahanBaku::all();
        $satuans = Satuan::all();
        return view('bahan_baku.edit', compact('bahan_baku', 'jenis_bahan_bakus', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bahan_baku = BahanBaku::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:bahan_bakus,kode,' . $bahan_baku->id,
            'nama' => 'required|unique:bahan_bakus,nama,' . $bahan_baku->id,
            "jenis_bahan_baku_id" => "required",
            "satuan_besar_id" => "required",
            "satuan_kecil_id" => "required",
            "sejumlah" => "required",
        ]);
        $bahan_baku->update($validated);
        return redirect()->route('bahan_baku.index')->with("success", "Bahan Baku berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahan_baku = BahanBaku::findOrFail($id);
        $bahan_baku->delete();
        return redirect()->back()->with("success", "Bahan Baku berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = BahanBaku::with('jenis_bahan_baku', 'satuan_besar', 'satuan_kecil')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('jenis_bahan_baku_nama', function ($row) {
                return $row->jenis_bahan_baku->nama;
            })
            ->addColumn('satuan_besar_nama', function ($row) {
                return $row->satuan_besar->nama;
            })
            ->addColumn('satuan_kecil_nama', function ($row) {
                return $row->satuan_kecil->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('bahan_baku.edit', $row->id);
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
