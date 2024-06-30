<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Kebutuhan;

class KebutuhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('kebutuhan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Material::whereHas('fungsi_material', function ($query) { $query->where('nama', 'Produk'); })->get();
        $bahans = Material::whereHas('fungsi_material', function ($query) { $query->where('nama', '!=', 'Produk'); })->get();
        return view('kebutuhan.create', compact('produks', 'bahans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:materials,id',
            'bahans' => 'required|array',
            'bahans.*' => 'required|exists:materials,id',
            'jumlah_dalam_satuan_kecil' => 'required|array',
            'jumlah_dalam_satuan_kecil.*' => 'required|numeric|min:0',
            'jumlah_dalam_satuan_besar' => 'required|array',
            'jumlah_dalam_satuan_besar.*' => 'required|numeric|min:0',
        ]);

        $produk_id = $request->input('produk_id');
        $bahans = $request->input('bahans');
        $jumlah_dalam_satuan_kecil = $request->input('jumlah_dalam_satuan_kecil');
        $jumlah_dalam_satuan_besar = $request->input('jumlah_dalam_satuan_besar');

        // Validate uniqueness of bahans within the request
        $uniqueBahans = array_unique($bahans);
        if (count($bahans) !== count($uniqueBahans)) {
            return redirect()->back()->withInput()->withErrors(['bahans' => 'Bahan harus unik dalam satu produk.']);
        }

        // Validate that no duplicate bahan_id exists in the database for the given produk_id
        foreach ($bahans as $bahanId) {
            $existingKebutuhan = Kebutuhan::where('produk_id', $produk_id)
                ->where('bahan_id', $bahanId)
                ->exists();

            if ($existingKebutuhan) {
                return redirect()->back()->withInput()->withErrors(['bahans' => 'Bahan dengan ID ' . $bahanId . ' sudah ada untuk produk ini.']);
            }
        }

        foreach ($bahans as $index => $bahanId) {
            Kebutuhan::create([
                'produk_id' => $produk_id,
                'bahan_id' => $bahanId,
                'jumlah_dalam_satuan_kecil' => $jumlah_dalam_satuan_kecil[$index],
                'jumlah_dalam_satuan_besar' => $jumlah_dalam_satuan_besar[$index],
            ]);
        }

        return redirect()->back()->with("success", "Kebutuhan berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produk = Material::findOrFail($id);
        $kebutuhan = Kebutuhan::where('produk_id', $id)->get();
        return view('kebutuhan.show', compact('kebutuhan', 'produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produks = Material::whereHas('fungsi_material', function ($query) { $query->where('nama', 'Produk'); })->get();
        $bahans = Material::whereHas('fungsi_material', function ($query) { $query->where('nama', '!=', 'Produk'); })->get();
        $kebutuhans = Kebutuhan::where('produk_id', $id)->get();
        return view('kebutuhan.edit', compact('kebutuhans', 'produks', 'bahans', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dari formulir
        $request->validate([
            'produk_id' => 'required|exists:materials,id',
            'bahans' => 'required|array',
            'bahans.*' => 'required|exists:materials,id',
            'jumlah_dalam_satuan_kecil' => 'required|array',
            'jumlah_dalam_satuan_kecil.*' => 'required|numeric|min:0',
            'jumlah_dalam_satuan_besar' => 'required|array',
            'jumlah_dalam_satuan_besar.*' => 'required|numeric|min:0',
        ]);

        $bahans = $request->input('bahans');
        $jumlah_dalam_satuan_kecil = $request->input('jumlah_dalam_satuan_kecil');
        $jumlah_dalam_satuan_besar = $request->input('jumlah_dalam_satuan_besar');

        // Validasi keunikan bahans
        if (count($bahans) !== count(array_unique($bahans))) {
            return redirect()->back()->withInput()->withErrors(['bahans' => 'Bahan baku harus unik.']);
        }

        // Hapus data yang ada
        Kebutuhan::where('produk_id', $id)->delete();

        // Tambahkan kembali bahan baku baru berdasarkan input formulir
        foreach ($bahans as $index => $bahan_id) {
            $jumlah_kecil = $request->jumlah_dalam_satuan_kecil[$index];
            $jumlah_besar = $request->jumlah_dalam_satuan_besar[$index];
            Kebutuhan::create([
                'produk_id' => $id,
                'bahan_id' => $bahan_id,
                'jumlah_dalam_satuan_kecil' => $jumlah_kecil,
                'jumlah_dalam_satuan_besar' => $jumlah_besar,
            ]);
        }

        // Simpan perubahan
        return redirect()->route('kebutuhan.index')->with('success', 'Kebutuhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Kebutuhan::where('produk_id', $id)->delete();
        return redirect()->back()->with("success", "Kebutuhan berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Material::with([
            'kebutuhan',
            'kebutuhan.bahan',
            'kebutuhan.bahan.satuan_kecil',
            'kebutuhan.bahan.satuan_besar'
        ])
        ->whereHas('fungsi_material', function ($query) {
            $query->where('nama', 'Produk');
        })
        ->get();

        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('produk', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->nama));
                })
                ->addColumn('kebutuhan_bahan', function ($row) {
                    $bahanList = $row->kebutuhan->map(function ($kebutuhan) {
                        $kodeBahan = $kebutuhan->bahan->kode;
                        $namaBahan = $kebutuhan->bahan->nama;
                        $jumlahSatuanBesar = $kebutuhan->jumlah_dalam_satuan_besar;
                        $satuanBesarNama = $kebutuhan->bahan->satuan_besar->nama;
                        $jumlahSatuanKecil = $kebutuhan->jumlah_dalam_satuan_kecil;
                        $satuanKecilNama = $kebutuhan->bahan->satuan_kecil->nama;
                        return "<li>{$kodeBahan} | {$namaBahan}: {$jumlahSatuanBesar} {$satuanBesarNama} / {$jumlahSatuanKecil} {$satuanKecilNama}</li>";
                    })->implode('');
                    return "<ul>{$bahanList}</ul>";
                })
                ->addColumn('tindakan', function ($row) {
                    $editUrl = route('kebutuhan.edit', $row->id);
                    $showUrl = route('kebutuhan.show', $row->id);
                    return '
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
                        </div>
                    ';
                })
                ->rawColumns(['tindakan', 'kebutuhan_bahan'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
    }
}
