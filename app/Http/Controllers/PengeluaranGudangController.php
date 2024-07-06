<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Material;
use App\Models\PengeluaranGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\PengeluaranGudangItem;
use Illuminate\Support\Facades\Auth;

class PengeluaranGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('pengeluaran_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gudangs = Gudang::all();
        $materials = Material::all();
        $kode = PengeluaranGudang::kode_faktur();
        return view('pengeluaran_gudang.create', compact('materials', 'kode', 'gudangs'));
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

        // Buat data pengeluaran_gudang
        $pengeluaran_gudang = PengeluaranGudang::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'gudang_id' => $request->gudang_id,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $pengeluaran_gudang->delete(); // Hapus pengeluaran_gudang yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Material harus unik dalam satu pengeluaran_gudang.');
            }
            $existingMaterial[] = $material_id;

            PengeluaranGudangItem::create([
                'pengeluaran_gudang_id' => $pengeluaran_gudang->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('pengeluaran_gudang.index')->with('success', 'Pengeluaran Gudang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pengeluaran_gudang = PengeluaranGudang::findOrFail($id);
        $pengeluaran_gudang_detail = PengeluaranGudangItem::where('pengeluaran_gudang_id', $id)->get();
        return view('pengeluaran_gudang.show', compact('pengeluaran_gudang', 'pengeluaran_gudang_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = PengeluaranGudang::findOrFail($id);
        $pengeluaran_gudang_detail = PengeluaranGudangItem::where('pengeluaran_gudang_id', $id)->get();
        $materials = Material::all();
        $gudangs = Gudang::all();
        return view('pengeluaran_gudang.edit', compact('data', 'pengeluaran_gudang_detail', 'materials', 'gudangs'));
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

        $pengeluaran_gudang_items = PengeluaranGudangItem::where('pengeluaran_gudang_id', $id)->get();
        foreach($pengeluaran_gudang_items as $item)
        {
            PengeluaranGudangItem::findOrFail($item->id)->delete();
        }

        $pengeluaran_gudang = PengeluaranGudang::findOrFail($id);
        $pengeluaran_gudang->kode = $request->kode;
        $pengeluaran_gudang->gudang_id = $request->gudang_id;
        $pengeluaran_gudang->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Material harus unik dalam satu pengeluaran_gudang.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];

            PengeluaranGudangItem::create([
                "pengeluaran_gudang_id" => $id,
                "material_id" => $material_id,
                "jumlah_dalam_satuan_kecil" => $jumlah_kecil,
                "jumlah_dalam_satuan_besar" => $jumlah_besar,
            ]);
        }

        return redirect()->route('pengeluaran_gudang.index')->with('success', 'Pengeluaran Gudang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengeluaran_gudang = PengeluaranGudang::findOrFail($id);
        $pengeluaran_gudang_items = PengeluaranGudangItem::where('pengeluaran_gudang_id', $id)->get();
        foreach($pengeluaran_gudang_items as $item)
        {
            PengeluaranGudangItem::findOrFail($item->id)->delete();
        }
        $pengeluaran_gudang->delete();
        return redirect()->back()->with("success", "Pengeluaran Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PengeluaranGudang::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('pengeluaran_gudang.edit', $row->id);
                $showUrl = route('pengeluaran_gudang.show', $row->id);
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
