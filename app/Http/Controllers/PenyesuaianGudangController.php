<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gudang;
use App\Models\Material;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\PenyesuaianGudang;
use Illuminate\Support\Facades\Auth;
use App\Models\PenyesuaianGudangItem;
use App\Models\JenisPenyesuaianGudang;

class PenyesuaianGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('penyesuaian_gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis_penyesuaian_gudangs = JenisPenyesuaianGudang::all();
        $gudangs = Gudang::all();
        $materials = Material::all();
        $kode = PenyesuaianGudang::kode_faktur();
        return view('penyesuaian_gudang.create', compact('jenis_penyesuaian_gudangs', 'materials', 'kode', 'gudangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'jenis_penyesuaian_gudang_id' => 'required|exists:jenis_penyesuaian_gudangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data penyesuaian_gudang
        $penyesuaian_gudang = PenyesuaianGudang::create([
            'jenis_penyesuaian_gudang_id' => $request->jenis_penyesuaian_gudang_id,
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'gudang_id' => $request->gudang_id,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $penyesuaian_gudang->delete(); // Hapus penyesuaian_gudang yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Material harus unik dalam satu penyesuaian_gudang.');
            }
            $existingMaterial[] = $material_id;

            PenyesuaianGudangItem::create([
                'penyesuaian_gudang_id' => $penyesuaian_gudang->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('penyesuaian_gudang.index')->with('success', 'Penyesuaian Gudang berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penyesuaian_gudang = PenyesuaianGudang::findOrFail($id);
        $penyesuaian_gudang_detail = PenyesuaianGudangItem::where('penyesuaian_gudang_id', $id)->get();
        return view('penyesuaian_gudang.show', compact('penyesuaian_gudang', 'penyesuaian_gudang_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = PenyesuaianGudang::findOrFail($id);
        $jenis_penyesuaian_gudangs = JenisPenyesuaianGudang::all();
        $penyesuaian_gudang_detail = PenyesuaianGudangItem::where('penyesuaian_gudang_id', $id)->get();
        $materials = Material::all();
        $gudangs = Gudang::all();
        return view('penyesuaian_gudang.edit', compact('data', 'jenis_penyesuaian_gudangs', 'penyesuaian_gudang_detail', 'materials', 'gudangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'jenis_penyesuaian_gudang_id' => 'required|exists:jenis_penyesuaian_gudangs,id',
            'gudang_id' => 'required|exists:gudangs,id',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $penyesuaian_gudang_items = PenyesuaianGudangItem::where('penyesuaian_gudang_id', $id)->get();
        foreach($penyesuaian_gudang_items as $item)
        {
            PenyesuaianGudangItem::findOrFail($item->id)->delete();
        }

        $penyesuaian_gudang = PenyesuaianGudang::findOrFail($id);
        $penyesuaian_gudang->kode = $request->kode;
        $penyesuaian_gudang->jenis_penyesuaian_gudang_id = $request->jenis_penyesuaian_gudang_id;
        $penyesuaian_gudang->gudang_id = $request->gudang_id;
        $penyesuaian_gudang->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Material harus unik dalam satu penyesuaian_gudang.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];

            PenyesuaianGudangItem::create([
                "penyesuaian_gudang_id" => $id,
                "material_id" => $material_id,
                "jumlah_dalam_satuan_kecil" => $jumlah_kecil,
                "jumlah_dalam_satuan_besar" => $jumlah_besar,
            ]);
        }

        return redirect()->route('penyesuaian_gudang.index')->with('success', 'Penyesuaian Gudang berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penyesuaian_gudang = PenyesuaianGudang::findOrFail($id);
        $penyesuaian_gudang_items = PenyesuaianGudangItem::where('penyesuaian_gudang_id', $id)->get();
        foreach($penyesuaian_gudang_items as $item)
        {
            PenyesuaianGudangItem::findOrFail($item->id)->delete();
        }
        $penyesuaian_gudang->delete();
        return redirect()->back()->with("success", "Penyesuaian Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = PenyesuaianGudang::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('penyesuaian_gudang.edit', $row->id);
                $showUrl = route('penyesuaian_gudang.show', $row->id);
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
