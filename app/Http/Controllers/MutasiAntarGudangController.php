<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Material;
use App\Models\MutasiAntarGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MutasiAntarGudangItem;
use Illuminate\Support\Facades\Auth;

class MutasiAntarGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('mutasi_antar_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gudangs = Gudang::all();
        $materials = Material::all();
        $kode = MutasiAntarGudang::kode_faktur();
        return view('mutasi_antar_gudang.create', compact('materials', 'kode', 'gudangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'gudang_asal_id' => 'required|exists:gudangs,id',
            'gudang_tujuan_id' => 'required|exists:gudangs,id',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data mutasi_antar_gudang
        $mutasi_antar_gudang = MutasiAntarGudang::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'gudang_asal_id' => $request->gudang_asal_id,
            'gudang_tujuan_id' => $request->gudang_tujuan_id,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $mutasi_antar_gudang->delete(); // Hapus mutasi_antar_gudang yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Material harus unik dalam satu mutasi_antar_gudang.');
            }
            $existingMaterial[] = $material_id;

            MutasiAntarGudangItem::create([
                'mutasi_antar_gudang_id' => $mutasi_antar_gudang->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('mutasi_antar_gudang.index')->with('success', 'Mutasi Antar Gudang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mutasi_antar_gudang = MutasiAntarGudang::findOrFail($id);
        $mutasi_antar_gudang_detail = MutasiAntarGudangItem::where('mutasi_antar_gudang_id', $id)->get();
        return view('mutasi_antar_gudang.show', compact('mutasi_antar_gudang', 'mutasi_antar_gudang_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = MutasiAntarGudang::findOrFail($id);
        $mutasi_antar_gudang_detail = MutasiAntarGudangItem::where('mutasi_antar_gudang_id', $id)->get();
        $materials = Material::all();
        $gudangs = Gudang::all();
        return view('mutasi_antar_gudang.edit', compact('data', 'mutasi_antar_gudang_detail', 'materials', 'gudangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'gudang_asal_id' => 'required|exists:gudangs,id',
            'gudang_tujuan_id' => 'required|exists:gudangs,id',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $mutasi_antar_gudang_items = MutasiAntarGudangItem::where('mutasi_antar_gudang_id', $id)->get();
        foreach($mutasi_antar_gudang_items as $item)
        {
            MutasiAntarGudangItem::findOrFail($item->id)->delete();
        }

        $mutasi_antar_gudang = MutasiAntarGudang::findOrFail($id);
        $mutasi_antar_gudang->kode = $request->kode;
        $mutasi_antar_gudang->gudang_asal_id = $request->gudang_asal_id;
        $mutasi_antar_gudang->gudang_tujuan_id = $request->gudang_tujuan_id;
        $mutasi_antar_gudang->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Material harus unik dalam satu mutasi_antar_gudang.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];

            MutasiAntarGudangItem::create([
                "mutasi_antar_gudang_id" => $id,
                "material_id" => $material_id,
                "jumlah_dalam_satuan_kecil" => $jumlah_kecil,
                "jumlah_dalam_satuan_besar" => $jumlah_besar,
            ]);
        }

        return redirect()->route('mutasi_antar_gudang.index')->with('success', 'Mutasi Antar Gudang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mutasi_antar_gudang = MutasiAntarGudang::findOrFail($id);
        $mutasi_antar_gudang_items = MutasiAntarGudangItem::where('mutasi_antar_gudang_id', $id)->get();
        foreach($mutasi_antar_gudang_items as $item)
        {
            MutasiAntarGudangItem::findOrFail($item->id)->delete();
        }
        $mutasi_antar_gudang->delete();
        return redirect()->back()->with("success", "Mutasi Antar Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = MutasiAntarGudang::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('mutasi_antar_gudang.edit', $row->id);
                $showUrl = route('mutasi_antar_gudang.show', $row->id);
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
