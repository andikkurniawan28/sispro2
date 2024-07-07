<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Material;
use App\Models\PemasukanGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\PemasukanGudangItem;
use Illuminate\Support\Facades\Auth;

class PemasukanGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('pemasukan_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gudangs = Gudang::all();
        $materials = Material::all();
        $kode = PemasukanGudang::kode_faktur();
        return view('pemasukan_gudang.create', compact('materials', 'kode', 'gudangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'gudang_id' => 'required|exists:gudangs,id',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data pemasukan_gudang
        $pemasukan_gudang = PemasukanGudang::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'gudang_id' => $request->gudang_id,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $pemasukan_gudang->delete(); // Hapus pemasukan_gudang yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Material harus unik dalam satu pemasukan_gudang.');
            }
            $existingMaterial[] = $material_id;

            PemasukanGudangItem::create([
                'pemasukan_gudang_id' => $pemasukan_gudang->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('pemasukan_gudang.index')->with('success', 'Pemasukan Gudang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pemasukan_gudang = PemasukanGudang::findOrFail($id);
        $pemasukan_gudang_detail = PemasukanGudangItem::where('pemasukan_gudang_id', $id)->get();
        return view('pemasukan_gudang.show', compact('pemasukan_gudang', 'pemasukan_gudang_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = PemasukanGudang::findOrFail($id);
        $pemasukan_gudang_detail = PemasukanGudangItem::where('pemasukan_gudang_id', $id)->get();
        $materials = Material::all();
        $gudangs = Gudang::all();
        return view('pemasukan_gudang.edit', compact('data', 'pemasukan_gudang_detail', 'materials', 'gudangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'gudang_id' => 'required',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $pemasukan_gudang_items = PemasukanGudangItem::where('pemasukan_gudang_id', $id)->get();
        foreach($pemasukan_gudang_items as $item)
        {
            PemasukanGudangItem::findOrFail($item->id)->delete();
        }

        $pemasukan_gudang = PemasukanGudang::findOrFail($id);
        $pemasukan_gudang->kode = $request->kode;
        $pemasukan_gudang->gudang_id = $request->gudang_id;
        $pemasukan_gudang->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Material harus unik dalam satu pemasukan_gudang.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];

            PemasukanGudangItem::create([
                "pemasukan_gudang_id" => $id,
                "material_id" => $material_id,
                "jumlah_dalam_satuan_kecil" => $jumlah_kecil,
                "jumlah_dalam_satuan_besar" => $jumlah_besar,
            ]);
        }

        return redirect()->route('pemasukan_gudang.index')->with('success', 'Pemasukan Gudang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pemasukan_gudang = PemasukanGudang::findOrFail($id);
        $pemasukan_gudang_items = PemasukanGudangItem::where('pemasukan_gudang_id', $id)->get();
        foreach($pemasukan_gudang_items as $item)
        {
            PemasukanGudangItem::findOrFail($item->id)->delete();
        }
        $pemasukan_gudang->delete();
        return redirect()->back()->with("success", "Pemasukan Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PemasukanGudang::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('pemasukan_gudang.edit', $row->id);
                $showUrl = route('pemasukan_gudang.show', $row->id);
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
