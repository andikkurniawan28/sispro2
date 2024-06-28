<?php

namespace App\Http\Controllers;

use App\Models\PermintaanProdukAkhir;
use App\Models\ProdukAkhir;
use Illuminate\Http\Request;
use App\Models\PermintaanProdukAkhirDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermintaanProdukAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('permintaan_produk_akhir.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk_akhirs = ProdukAkhir::all();
        $kode = PermintaanProdukAkhir::kode_faktur();
        return view('permintaan_produk_akhir.create', compact('produk_akhirs', 'kode'));
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
            'produk_akhirs.*' => 'required|distinct|exists:produk_akhirs,id', // distinct untuk memastikan produk_akhir tidak duplikat
            'jumlahs' => 'required|array',
            'jumlahs.*' => 'required|integer|min:1',
        ]);

        // Buat data permintaan_produk_akhir
        $permintaan_produk_akhir = PermintaanProdukAkhir::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'berlaku_sampai' => $request->berlaku_sampai,
        ]);

        // Validasi produk akhir harus unik
        $existingProdukAkhir = [];
        foreach ($request->produk_akhirs as $index => $produk_akhir_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($produk_akhir_id, $existingProdukAkhir)) {
                $permintaan_produk_akhir->delete(); // Hapus permintaan_produk_akhir yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu permintaan.');
            }
            $existingProdukAkhir[] = $produk_akhir_id;

            PermintaanProdukAkhirDetail::create([
                'permintaan_id' => $permintaan_produk_akhir->id,
                'produk_akhir_id' => $produk_akhir_id,
                'jumlah' => $request->jumlahs[$index],
            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('permintaan_produk_akhir.index')->with('success', 'Permintaan Produk Akhir berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permintaan_produk_akhir = PermintaanProdukAkhir::findOrFail($id);
        $permintaan_produk_akhir_detail = PermintaanProdukAkhirDetail::where('permintaan_id', $id)->get();
        return view('permintaan_produk_akhir.show', compact('permintaan_produk_akhir', 'permintaan_produk_akhir_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permintaan_produk_akhir = PermintaanProdukAkhir::findOrFail($id);
        $permintaan_produk_akhir_detail = PermintaanProdukAkhirDetail::where('permintaan_id', $id)->get();
        $produk_akhirs = ProdukAkhir::all();
        return view('permintaan_produk_akhir.edit', compact('permintaan_produk_akhir', 'permintaan_produk_akhir_detail', 'produk_akhirs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'berlaku_sampai' => 'required|date',
            'produk_akhirs.*' => 'required|distinct|exists:produk_akhirs,id', // distinct untuk memastikan produk_akhir tidak duplikat
            'jumlahs.*' => 'required|integer|min:1',
        ]);

        $permintaan_produk_akhir = PermintaanProdukAkhir::findOrFail($id);
        $permintaan_produk_akhir->kode = $request->kode;
        $permintaan_produk_akhir->berlaku_sampai = $request->berlaku_sampai;
        $permintaan_produk_akhir->save();

        // Validasi produk akhir harus unik
        $existingProdukAkhir = [];
        foreach ($request->produk_akhirs as $index => $produk_akhir_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($produk_akhir_id, $existingProdukAkhir)) {
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu permintaan.');
            }
            $existingProdukAkhir[] = $produk_akhir_id;

            $jumlah = $request->jumlahs[$index];
            PermintaanProdukAkhirDetail::updateOrCreate(
                ['permintaan_id' => $id, 'produk_akhir_id' => $produk_akhir_id],
                ['jumlah' => $jumlah]
            );
        }

        return redirect()->route('permintaan_produk_akhir.index')->with('success', 'Permintaan Produk Akhir berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permintaan_produk_akhir = PermintaanProdukAkhir::findOrFail($id);
        $permintaan_produk_akhir->delete();
        return redirect()->back()->with("success", "Permintaan Produk Akhir berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PermintaanProdukAkhir::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('berlaku_sampai', function ($row) {
                return Carbon::parse($row->berlaku_sampai)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('permintaan_produk_akhir.edit', $row->id);
                $showUrl = route('permintaan_produk_akhir.show', $row->id);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
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
