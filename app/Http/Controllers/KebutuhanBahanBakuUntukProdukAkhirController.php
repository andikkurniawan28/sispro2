<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\ProdukAkhir;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\KebutuhanBahanBakuUntukProdukAkhir;

class KebutuhanBahanBakuUntukProdukAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('kebutuhan_bahan_baku_untuk_produk_akhir.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk_akhirs = ProdukAkhir::all();
        $bahan_bakus = BahanBaku::all();
        return view('kebutuhan_bahan_baku_untuk_produk_akhir.create', compact('produk_akhirs', 'bahan_bakus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_akhir_id' => 'required|exists:produk_akhirs,id',
            'bahan_bakus' => 'required|array',
            'bahan_bakus.*' => 'required|exists:bahan_bakus,id',
            'jumlah_dalam_satuan_kecil' => 'required|array',
            'jumlah_dalam_satuan_kecil.*' => 'required|numeric|min:0',
            'jumlah_dalam_satuan_besar' => 'required|array',
            'jumlah_dalam_satuan_besar.*' => 'required|numeric|min:0',
        ]);

        $produk_akhir_id = $request->input('produk_akhir_id');
        $bahanBakus = $request->input('bahan_bakus');
        $jumlah_dalam_satuan_kecil = $request->input('jumlah_dalam_satuan_kecil');
        $jumlah_dalam_satuan_besar = $request->input('jumlah_dalam_satuan_besar');

        // Validate uniqueness of bahan_bakus
        $uniqueBahanBakus = array_unique($bahanBakus);
        if (count($bahanBakus) !== count($uniqueBahanBakus)) {
            return redirect()->back()->withInput()->withErrors(['bahan_bakus' => 'Bahan baku harus unik.']);
        }

        foreach ($bahanBakus as $index => $bahanBakuId) {
            KebutuhanBahanBakuUntukProdukAkhir::create([
                'produk_akhir_id' => $produk_akhir_id,
                'bahan_baku_id' => $bahanBakuId,
                'jumlah_dalam_satuan_kecil' => $jumlah_dalam_satuan_kecil[$index],
                'jumlah_dalam_satuan_besar' => $jumlah_dalam_satuan_besar[$index],
            ]);
        }
        return redirect()->back()->with("success", "Kebutuhan Bahan Baku Untuk Produk Akhir berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produk_akhir = ProdukAkhir::findOrFail($id);
        $kebutuhan_bahan_baku_untuk_produk_akhir = KebutuhanBahanBakuUntukProdukAkhir::where('produk_akhir_id', $id)->get();
        return view('kebutuhan_bahan_baku_untuk_produk_akhir.show', compact('kebutuhan_bahan_baku_untuk_produk_akhir', 'produk_akhir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk_akhirs = ProdukAkhir::all();
        $bahan_bakus = BahanBaku::all();
        $kebutuhan_bahan_baku_untuk_produk_akhir = KebutuhanBahanBakuUntukProdukAkhir::where('produk_akhir_id', $id)->get();
        return view('kebutuhan_bahan_baku_untuk_produk_akhir.edit', compact('kebutuhan_bahan_baku_untuk_produk_akhir', 'produk_akhirs', 'bahan_bakus', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari formulir
        $request->validate([
            'produk_akhir_id' => 'required|exists:produk_akhirs,id',
            'bahan_bakus' => 'required|array',
            'bahan_bakus.*' => 'required|exists:bahan_bakus,id',
            'jumlah_dalam_satuan_kecil' => 'required|array',
            'jumlah_dalam_satuan_kecil.*' => 'required|numeric|min:0',
            'jumlah_dalam_satuan_besar' => 'required|array',
            'jumlah_dalam_satuan_besar.*' => 'required|numeric|min:0',
        ]);

        $bahanBakus = $request->input('bahan_bakus');
        $jumlah_dalam_satuan_kecil = $request->input('jumlah_dalam_satuan_kecil');
        $jumlah_dalam_satuan_besar = $request->input('jumlah_dalam_satuan_besar');

        // Validasi keunikan bahan_bakus
        if (count($bahanBakus) !== count(array_unique($bahanBakus))) {
            return redirect()->back()->withInput()->withErrors(['bahan_bakus' => 'Bahan baku harus unik.']);
        }

        // Hapus data yang ada
        KebutuhanBahanBakuUntukProdukAkhir::where('produk_akhir_id', $id)->delete();

        // Tambahkan kembali bahan baku baru berdasarkan input formulir
        foreach ($bahanBakus as $index => $bahan_baku_id) {
            $jumlah_kecil = $request->jumlah_dalam_satuan_kecil[$index];
            $jumlah_besar = $request->jumlah_dalam_satuan_besar[$index];
            KebutuhanBahanBakuUntukProdukAkhir::create([
                'produk_akhir_id' => $id,
                'bahan_baku_id' => $bahan_baku_id,
                'jumlah_dalam_satuan_kecil' => $jumlah_kecil,
                'jumlah_dalam_satuan_besar' => $jumlah_besar,
            ]);
        }

        // Simpan perubahan
        return redirect()->route('kbbupa.index')->with('success', 'Kebutuhan Bahan Baku Untuk Produk Akhir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        KebutuhanBahanBakuUntukProdukAkhir::where('produk_akhir_id', $id)->delete();
        return redirect()->back()->with("success", "Kebutuhan Bahan Baku Untuk Produk Akhir berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = ProdukAkhir::with('kebutuhan_bahan_baku_untuk_produk_akhir.bahan_baku')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kebutuhan_bahan_baku', function ($row) {
                $bahanBakuList = $row->kebutuhan_bahan_baku_untuk_produk_akhir->map(function ($kebutuhan) {
                    $namaBahanBaku = $kebutuhan->bahan_baku->nama;
                    $jumlahSatuanBesar = $kebutuhan->jumlah_dalam_satuan_besar;
                    $satuanBesarNama = $kebutuhan->bahan_baku->satuan_besar->nama;
                    $jumlahSatuanKecil = $kebutuhan->jumlah_dalam_satuan_kecil;
                    $satuanKecilNama = $kebutuhan->bahan_baku->satuan_kecil->nama;

                    return "<li>{$namaBahanBaku}: {$jumlahSatuanBesar} {$satuanBesarNama} / {$jumlahSatuanKecil} {$satuanKecilNama}</li>";
                })->implode('');

                return "<ul>{$bahanBakuList}</ul>";
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('kbbupa.edit', $row->id);
                $showUrl = route('kbbupa.show', $row->id);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
                    </div>
                ';
            })
            ->rawColumns(['tindakan', 'kebutuhan_bahan_baku'])
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
            ->make(true);
    }
}
