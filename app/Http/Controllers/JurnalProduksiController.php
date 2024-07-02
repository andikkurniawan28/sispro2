<?php

namespace App\Http\Controllers;

use App\Models\JurnalProduksi;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\HasilProduksi;
use App\Models\Permintaan;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JurnalProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jurnal_produksi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permintaans = Permintaan::yangTerbuka();
        $materials = Material::all();
        $kode = JurnalProduksi::kode_faktur();
        return view('jurnal_produksi.create', compact('permintaans', 'materials', 'kode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'permintaan_id' => 'required|exists:permintaans,id',
            'kode' => 'required|string|max:255',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data jurnal_produksi
        $jurnal_produksi = JurnalProduksi::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'permintaan_id' => $request->permintaan_id,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $jurnal_produksi->delete(); // Hapus jurnal_produksi yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Material harus unik dalam satu jurnal_produksi.');
            }
            $existingMaterial[] = $material_id;

            HasilProduksi::create([
                'jurnal_produksi_id' => $jurnal_produksi->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('jurnal_produksi.index')->with('success', 'Jurnal Produksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jurnal_produksi = JurnalProduksi::findOrFail($id);
        $hasil_produksi = HasilProduksi::where('jurnal_produksi_id', $id)->get();
        return view('jurnal_produksi.show', compact('jurnal_produksi', 'hasil_produksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permintaans = Permintaan::yangTerbuka();
        $data = JurnalProduksi::findOrFail($id);
        $hasil_produksi = HasilProduksi::where('jurnal_produksi_id', $id)->get();
        $materials = Material::all();
        return view('jurnal_produksi.edit', compact('permintaans', 'data', 'hasil_produksi', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'permintaan_id' => 'required||exists:permintaans,id',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $jurnal_produksi = JurnalProduksi::findOrFail($id);
        $jurnal_produksi->kode = $request->kode;
        $jurnal_produksi->permintaan_id = $request->permintaan_id;
        $jurnal_produksi->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Material harus unik dalam satu jurnal_produksi.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];
            HasilProduksi::updateOrCreate(
                ['jurnal_produksi_id' => $id, 'material_id' => $material_id],
                ['jumlah_dalam_satuan_kecil' => $jumlah_kecil, 'jumlah_dalam_satuan_besar' => $jumlah_besar]
            );
        }

        return redirect()->route('jurnal_produksi.index')->with('success', 'Jurnal Produksi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurnal_produksi = JurnalProduksi::findOrFail($id);
        $jurnal_produksi->delete();
        return redirect()->back()->with("success", "Jurnal Produksi berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JurnalProduksi::with('user', 'permintaan')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('permintaan_kode', function ($row) {
                return $row->permintaan->kode;
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                if ($row->permintaan->status == 1) {
                    $showUrl = route('jurnal_produksi.show', $row->id);
                    return '
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                            <span class="badge badge-secondary">
                                <i class="icon-lock"></i> Dikunci
                            </span>
                        </div>
                    ';
                } else {
                    $editUrl = route('jurnal_produksi.edit', $row->id);
                    $showUrl = route('jurnal_produksi.show', $row->id);
                    return '
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <button class="btn btn-dark btn-sm close-btn" data-id="' . $row->id . '">Tutup</button>
                            <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
                        </div>
                    ';
                }
            })
            ->rawColumns(['tindakan'])
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
            ->make(true);
    }
}
