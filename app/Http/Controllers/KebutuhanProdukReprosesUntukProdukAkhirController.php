<?php

namespace App\Http\Controllers;

use App\Models\ProdukReproses;
use App\Models\ProdukAkhir;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\KebutuhanProdukReprosesUntukProdukAkhir;

class KebutuhanProdukReprosesUntukProdukAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('kebutuhan_produk_reproses_untuk_produk_akhir.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk_akhirs = ProdukAkhir::all();
        $produk_reprosess = ProdukReproses::all();
        return view('kebutuhan_produk_reproses_untuk_produk_akhir.create', compact('produk_akhirs', 'produk_reprosess'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_akhir_id' => 'required|exists:produk_akhirs,id',
            'produk_reprosess' => 'required|array',
            'produk_reprosess.*' => 'required|exists:produk_reproses,id',
            'jumlah_dalam_satuan_kecil' => 'required|array',
            'jumlah_dalam_satuan_kecil.*' => 'required|numeric|min:0',
            'jumlah_dalam_satuan_besar' => 'required|array',
            'jumlah_dalam_satuan_besar.*' => 'required|numeric|min:0',
        ]);

        $produk_akhir_id = $request->input('produk_akhir_id');
        $produkReprosess = $request->input('produk_reprosess');
        $jumlah_dalam_satuan_kecil = $request->input('jumlah_dalam_satuan_kecil');
        $jumlah_dalam_satuan_besar = $request->input('jumlah_dalam_satuan_besar');

        // Validate uniqueness of produk_reprosess
        $uniqueProdukReprosess = array_unique($produkReprosess);
        if (count($produkReprosess) !== count($uniqueProdukReprosess)) {
            return redirect()->back()->withInput()->withErrors(['produk_reprosess' => 'Produk Reproses harus unik.']);
        }

        foreach ($produkReprosess as $index => $produkReprosesId) {
            KebutuhanProdukReprosesUntukProdukAkhir::create([
                'produk_akhir_id' => $produk_akhir_id,
                'produk_reproses_id' => $produkReprosesId,
                'jumlah_dalam_satuan_kecil' => $jumlah_dalam_satuan_kecil[$index],
                'jumlah_dalam_satuan_besar' => $jumlah_dalam_satuan_besar[$index],
            ]);
        }
        return redirect()->back()->with("success", "Kebutuhan Produk Reproses Untuk Produk Akhir berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produk_akhir = ProdukAkhir::findOrFail($id);
        $kebutuhan_produk_reproses_untuk_produk_akhir = KebutuhanProdukReprosesUntukProdukAkhir::where('produk_akhir_id', $id)->get();
        return view('kebutuhan_produk_reproses_untuk_produk_akhir.show', compact('kebutuhan_produk_reproses_untuk_produk_akhir', 'produk_akhir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produk_akhirs = ProdukAkhir::all();
        $produk_reprosess = ProdukReproses::all();
        $kebutuhan_produk_reproses_untuk_produk_akhir = KebutuhanProdukReprosesUntukProdukAkhir::where('produk_akhir_id', $id)->get();
        return view('kebutuhan_produk_reproses_untuk_produk_akhir.edit', compact('kebutuhan_produk_reproses_untuk_produk_akhir', 'produk_akhirs', 'produk_reprosess', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari formulir
        $request->validate([
            'produk_akhir_id' => 'required|exists:produk_akhirs,id',
            'produk_reprosess' => 'required|array',
            'produk_reprosess.*' => 'required|exists:produk_reproses,id',
            'jumlah_dalam_satuan_kecil' => 'required|array',
            'jumlah_dalam_satuan_kecil.*' => 'required|numeric|min:0',
            'jumlah_dalam_satuan_besar' => 'required|array',
            'jumlah_dalam_satuan_besar.*' => 'required|numeric|min:0',
        ]);

        $produkReprosess = $request->input('produk_reprosess');
        $jumlah_dalam_satuan_kecil = $request->input('jumlah_dalam_satuan_kecil');
        $jumlah_dalam_satuan_besar = $request->input('jumlah_dalam_satuan_besar');

        // Validasi keunikan produk_reprosess
        if (count($produkReprosess) !== count(array_unique($produkReprosess))) {
            return redirect()->back()->withInput()->withErrors(['produk_reprosess' => 'Produk Reproses harus unik.']);
        }

        // Hapus data yang ada
        KebutuhanProdukReprosesUntukProdukAkhir::where('produk_akhir_id', $id)->delete();

        // Tambahkan kembali bahan baku baru berdasarkan input formulir
        foreach ($produkReprosess as $index => $produk_reproses_id) {
            $jumlah_kecil = $request->jumlah_dalam_satuan_kecil[$index];
            $jumlah_besar = $request->jumlah_dalam_satuan_besar[$index];
            KebutuhanProdukReprosesUntukProdukAkhir::create([
                'produk_akhir_id' => $id,
                'produk_reproses_id' => $produk_reproses_id,
                'jumlah_dalam_satuan_kecil' => $jumlah_kecil,
                'jumlah_dalam_satuan_besar' => $jumlah_besar,
            ]);
        }

        // Simpan perubahan
        return redirect()->route('kprupa.index')->with('success', 'Kebutuhan Produk Reproses Untuk Produk Akhir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        KebutuhanProdukReprosesUntukProdukAkhir::where('produk_akhir_id', $id)->delete();
        return redirect()->back()->with("success", "Kebutuhan Produk Reproses Untuk Produk Akhir berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = ProdukAkhir::with('kebutuhan_produk_reproses_untuk_produk_akhir.produk_reproses')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kebutuhan_produk_reproses', function ($row) {
                $produkReprosesList = $row->kebutuhan_produk_reproses_untuk_produk_akhir->map(function ($kebutuhan) {
                    $namaProdukReproses = $kebutuhan->produk_reproses->nama;
                    $jumlahSatuanBesar = $kebutuhan->jumlah_dalam_satuan_besar;
                    $satuanBesarNama = $kebutuhan->produk_reproses->satuan_besar->nama;
                    $jumlahSatuanKecil = $kebutuhan->jumlah_dalam_satuan_kecil;
                    $satuanKecilNama = $kebutuhan->produk_reproses->satuan_kecil->nama;

                    return "<li>{$namaProdukReproses}: {$jumlahSatuanBesar} {$satuanBesarNama} / {$jumlahSatuanKecil} {$satuanKecilNama}</li>";
                })->implode('');

                return "<ul>{$produkReprosesList}</ul>";
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('kprupa.edit', $row->id);
                $showUrl = route('kprupa.show', $row->id);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
                    </div>
                ';
            })
            ->rawColumns(['tindakan', 'kebutuhan_produk_reproses'])
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
            ->make(true);
    }
}
