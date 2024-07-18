<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Material;
use App\Models\Permintaan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\JurnalProduksi;
use Yajra\DataTables\DataTables;
use App\Models\JurnalProduksiDetail;
use Illuminate\Support\Facades\Auth;

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
        $materials = Material::produk();
        $kode = JurnalProduksi::kode_faktur();
        $permintaans = Permintaan::yangTerbuka();
        return view('jurnal_produksi.create', compact('materials', 'kode', 'permintaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'permintaan_id' => 'required||exists:permintaans,id',
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
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu jurnal_produksi.');
            }
            $existingMaterial[] = $material_id;

            JurnalProduksiDetail::create([
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
        $jurnal_produksi_detail = JurnalProduksiDetail::where('jurnal_produksi_id', $id)->get();
        return view('jurnal_produksi.show', compact('jurnal_produksi', 'jurnal_produksi_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = JurnalProduksi::findOrFail($id);
        $jurnal_produksi_detail = JurnalProduksiDetail::where('jurnal_produksi_id', $id)->get();
        $materials = Material::produk();
        $permintaans = Permintaan::yangTerbuka();
        return view('jurnal_produksi.edit', compact('data', 'jurnal_produksi_detail', 'materials', 'permintaans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'permintaan_id' => 'required|exists:permintaans,id',
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

        JurnalProduksiDetail::where("jurnal_produksi_id", $id)->delete();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu jurnal_produksi.');
            }
            $existingMaterial[] = $material_id;
            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];
            JurnalProduksiDetail::create([
                "jurnal_produksi_id" => $id,
                "material_id" => $material_id,
                "jumlah_dalam_satuan_kecil" => $jumlah_kecil,
                "jumlah_dalam_satuan_besar" => $jumlah_besar,
            ]);
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
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('permintaan_kode', function ($row) {
                return $row->permintaan->kode;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jurnal_produksi.edit', $row->id);
                $showUrl = route('jurnal_produksi.show', $row->id);
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

    // public function api($id)
    // {
    //     $data = JurnalProduksiDetail::with('material')->where('jurnal_produksi_id', $id)->get();
    //     return response()->json($data);
    // }

    // public function tutup($id)
    // {
    //     $data = JurnalProduksi::findOrFail($id);
    //     ActivityLog::create([
    //         'user_id' => Auth::id(),
    //         'description' => "Jurnal Produksi '{$data->kode}' ditutup.",
    //     ]);
    //     $data->update(['status' => 1]);
    //     return redirect()->route('jurnal_produksi.index')->with('success', 'Jurnal Produksi berhasil ditutup.');
    // }
}
