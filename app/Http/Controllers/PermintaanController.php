<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\ProdukAkhir;
use Illuminate\Http\Request;
use App\Models\PermintaanDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('permintaan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk_akhirs = ProdukAkhir::all();
        return view('permintaan.create', compact('produk_akhirs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'berlaku_sampai' => 'required|date',
            'produk_akhirs' => 'required|array',
            'produk_akhirs.*' => 'required|exists:produk_akhirs,id',
            'jumlahs' => 'required|array',
            'jumlahs.*' => 'required|integer|min:1',
        ]);

        // Buat data permintaan
        $permintaan = Permintaan::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'berlaku_sampai' => $request->berlaku_sampai,
        ]);

        // Buat detail permintaan
        foreach ($request->produk_akhirs as $index => $produk_akhir_id) {
            PermintaanDetail::create([
                'permintaan_id' => $permintaan->id,
                'produk_akhir_id' => $produk_akhir_id,
                'jumlah' => $request->jumlahs[$index],
            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permintaan $permintaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        return view('permintaan.edit', compact('permintaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:permintaans,kode,' . $permintaan->id,
        ]);
        $permintaan->update($validated);
        return redirect()->route('permintaan.index')->with("success", "Permintaan berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->delete();
        return redirect()->back()->with("success", "Permintaan berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PermintaanDetail::with('permintaan', 'produk_akhir')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('permintaan_kode', function ($row) {
                return $row->permintaan->kode;
            })
            ->addColumn('produk_akhir_kode', function ($row) {
                return $row->produk_akhir->kode;
            })
            ->addColumn('produk_akhir_nama', function ($row) {
                return $row->produk_akhir->nama;
            })
            ->addColumn('satuan_besar', function ($row) {
                return $row->produk_akhir->satuan_besar->nama;
            })
            ->addColumn('user_nama', function ($row) {
                return $row->permintaan->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('permintaan.edit', $row->id);
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
