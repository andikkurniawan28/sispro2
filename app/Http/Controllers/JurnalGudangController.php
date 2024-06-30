<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Material;
use App\Models\JurnalGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\JenisJurnalGudang;
use App\Models\JurnalGudangDetail;
use Illuminate\Support\Facades\Auth;

class JurnalGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jurnal_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gudangs = Gudang::all();
        $jenis_jurnal_gudangs = JenisJurnalGudang::all();
        $materials = Material::all();
        $kode = JurnalGudang::kode_faktur();
        return view('jurnal_gudang.create', compact('materials', 'kode', 'gudangs', 'jenis_jurnal_gudangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'jenis_jurnal_gudang_id' => 'required',
            'gudang_id' => 'required',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data jurnal_gudang
        $jurnal_gudang = JurnalGudang::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'jenis_jurnal_gudang_id' => $request->jenis_jurnal_gudang_id,
            'gudang_id' => $request->gudang_id,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $jurnal_gudang->delete(); // Hapus jurnal_gudang yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu jurnal_gudang.');
            }
            $existingMaterial[] = $material_id;

            JurnalGudangDetail::create([
                'jurnal_gudang_id' => $jurnal_gudang->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('jurnal_gudang.index')->with('success', 'Jurnal Gudang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang_detail = JurnalGudangDetail::where('jurnal_gudang_id', $id)->get();
        return view('jurnal_gudang.show', compact('jurnal_gudang', 'jurnal_gudang_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = JurnalGudang::findOrFail($id);
        $jurnal_gudang_detail = JurnalGudangDetail::where('jurnal_gudang_id', $id)->get();
        $materials = Material::all();
        $gudangs = Gudang::all();
        $jenis_jurnal_gudangs = JenisJurnalGudang::all();
        return view('jurnal_gudang.edit', compact('data', 'jurnal_gudang_detail', 'materials', 'gudangs', 'jenis_jurnal_gudangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            // 'jenis_jurnal_gudang_id' => 'required',
            'gudang_id' => 'required',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang->kode = $request->kode;
        // $jurnal_gudang->jenis_jurnal_gudang_id = $request->jenis_jurnal_gudang_id;
        $jurnal_gudang->gudang_id = $request->gudang_id;
        $jurnal_gudang->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu jurnal_gudang.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];
            JurnalGudangDetail::updateOrCreate(
                ['jurnal_gudang_id' => $id, 'material_id' => $material_id],
                ['jumlah_dalam_satuan_kecil' => $jumlah_kecil, 'jumlah_dalam_satuan_besar' => $jumlah_besar]
            );
        }

        return redirect()->route('jurnal_gudang.index')->with('success', 'Jurnal Gudang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang->delete();
        return redirect()->back()->with("success", "JurnalGudang Produk Akhir berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JurnalGudang::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jurnal_gudang.edit', $row->id);
                $showUrl = route('jurnal_gudang.show', $row->id);
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
