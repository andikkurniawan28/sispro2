<?php

namespace App\Http\Controllers;

use App\Models\PermintaanProdukReproses;
use App\Models\ProdukReproses;
use Illuminate\Http\Request;
use App\Models\PermintaanProdukReprosesDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermintaanProdukReprosesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('permintaan_produk_reproses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk_reproses = ProdukReproses::all();
        $kode = PermintaanProdukReproses::kode_faktur();
        return view('permintaan_produk_reproses.create', compact('produk_reproses', 'kode'));
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
            'produk_reproses' => 'required|array',
            'produk_reproses.*' => 'required|distinct|exists:produk_reproses,id', // distinct untuk memastikan produk_reproses tidak duplikat
            'jumlahs' => 'required|array',
            'jumlahs.*' => 'required|integer|min:1',
        ]);

        // Buat data permintaan_produk_reproses
        $permintaan_produk_reproses = PermintaanProdukReproses::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'berlaku_sampai' => $request->berlaku_sampai,
        ]);

        // Validasi produk reproses harus unik
        $existingProdukReproses = [];
        foreach ($request->produk_reproses as $index => $produk_reproses_id) {
            // Validasi jika produk reproses sudah pernah dimasukkan sebelumnya
            if (in_array($produk_reproses_id, $existingProdukReproses)) {
                $permintaan_produk_reproses->delete(); // Hapus permintaan_produk_reproses yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Produk Reproses harus unik dalam satu permintaan.');
            }
            $existingProdukReproses[] = $produk_reproses_id;

            PermintaanProdukReprosesDetail::create([
                'permintaan_id' => $permintaan_produk_reproses->id,
                'produk_reproses_id' => $produk_reproses_id,
                'jumlah' => $request->jumlahs[$index],
            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('permintaan_produk_reproses.index')->with('success', 'Permintaan Produk Reproses berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permintaan_produk_reproses = PermintaanProdukReproses::findOrFail($id);
        $permintaan_produk_reproses_detail = PermintaanProdukReprosesDetail::where('permintaan_id', $id)->get();
        return view('permintaan_produk_reproses.show', compact('permintaan_produk_reproses', 'permintaan_produk_reproses_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permintaan_produk_reproses = PermintaanProdukReproses::findOrFail($id);
        $permintaan_produk_reproses_detail = PermintaanProdukReprosesDetail::where('permintaan_id', $id)->get();
        $produk_reproses = ProdukReproses::all();
        return view('permintaan_produk_reproses.edit', compact('permintaan_produk_reproses', 'permintaan_produk_reproses_detail', 'produk_reproses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'berlaku_sampai' => 'required|date',
            'produk_reproses.*' => 'required|distinct|exists:produk_reproses,id', // distinct untuk memastikan produk_reproses tidak duplikat
            'jumlahs.*' => 'required|integer|min:1',
        ]);

        $permintaan_produk_reproses = PermintaanProdukReproses::findOrFail($id);
        $permintaan_produk_reproses->kode = $request->kode;
        $permintaan_produk_reproses->berlaku_sampai = $request->berlaku_sampai;
        $permintaan_produk_reproses->save();

        // Validasi produk reproses harus unik
        $existingProdukReproses = [];
        foreach ($request->produk_reproses as $index => $produk_reproses_id) {
            // Validasi jika produk reproses sudah pernah dimasukkan sebelumnya
            if (in_array($produk_reproses_id, $existingProdukReproses)) {
                return redirect()->back()->with('error', 'Produk Reproses harus unik dalam satu permintaan.');
            }
            $existingProdukReproses[] = $produk_reproses_id;

            $jumlah = $request->jumlahs[$index];
            PermintaanProdukReprosesDetail::updateOrCreate(
                ['permintaan_id' => $id, 'produk_reproses_id' => $produk_reproses_id],
                ['jumlah' => $jumlah]
            );
        }

        return redirect()->route('permintaan_produk_reproses.index')->with('success', 'Permintaan Produk Reproses berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permintaan_produk_reproses = PermintaanProdukReproses::findOrFail($id);
        $permintaan_produk_reproses->delete();
        return redirect()->back()->with("success", "Permintaan Produk Reproses berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PermintaanProdukReproses::with('user')->get();
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
                $editUrl = route('permintaan_produk_reproses.edit', $row->id);
                $showUrl = route('permintaan_produk_reproses.show', $row->id);
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
