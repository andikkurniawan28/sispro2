<?php

namespace App\Http\Controllers;

use App\Models\PerintahProduksi;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\HasilProduksi;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PerintahProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('perintah_produksi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();
        $kode = PerintahProduksi::kode_faktur();
        return view('perintah_produksi.create', compact('materials', 'kode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data perintah_produksi
        $perintah_produksi = PerintahProduksi::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $perintah_produksi->delete(); // Hapus perintah_produksi yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Material harus unik dalam satu perintah_produksi.');
            }
            $existingMaterial[] = $material_id;

            HasilProduksi::create([
                'perintah_produksi_id' => $perintah_produksi->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('perintah_produksi.index')->with('success', 'Perintah Produksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $perintah_produksi = PerintahProduksi::findOrFail($id);
        $hasil_produksi = HasilProduksi::where('perintah_produksi_id', $id)->get();
        return view('perintah_produksi.show', compact('perintah_produksi', 'hasil_produksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = PerintahProduksi::findOrFail($id);
        $hasil_produksi = HasilProduksi::where('perintah_produksi_id', $id)->get();
        $materials = Material::all();
        return view('perintah_produksi.edit', compact('data', 'hasil_produksi', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $perintah_produksi = PerintahProduksi::findOrFail($id);
        $perintah_produksi->kode = $request->kode;
        $perintah_produksi->berlaku_sampai = $request->berlaku_sampai;
        $perintah_produksi->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Material harus unik dalam satu perintah_produksi.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];
            HasilProduksi::updateOrCreate(
                ['perintah_produksi_id' => $id, 'material_id' => $material_id],
                ['jumlah_dalam_satuan_kecil' => $jumlah_kecil, 'jumlah_dalam_satuan_besar' => $jumlah_besar]
            );
        }

        return redirect()->route('perintah_produksi.index')->with('success', 'Perintah Produksi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perintah_produksi = PerintahProduksi::findOrFail($id);
        $perintah_produksi->delete();
        return redirect()->back()->with("success", "Perintah Produksi berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PerintahProduksi::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            // ->addColumn('berlaku_sampai', function ($row) {
            //     return Carbon::parse($row->berlaku_sampai)->format('d-m-Y H:i:s');
            // })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('perintah_produksi.edit', $row->id);
                $showUrl = route('perintah_produksi.show', $row->id);
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
