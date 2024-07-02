<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\PermintaanDetail;
use App\Models\ActivityLog;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('permintaan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::with('satuan_besar')->get();
        $kode = Permintaan::kode_faktur();
        return view('permintaan.create', compact('materials', 'kode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode' => 'required|string|max:255',
            'berlaku_sampai' => 'required|date',
            'materials' => 'required|array',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        // Buat data permintaan
        $permintaan = Permintaan::create([
            'user_id' => Auth::id(),
            'kode' => $request->kode,
            'berlaku_sampai' => $request->berlaku_sampai,
        ]);

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                $permintaan->delete(); // Hapus permintaan yang sudah dibuat jika ada duplikasi
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu permintaan.');
            }
            $existingMaterial[] = $material_id;

            PermintaanDetail::create([
                'permintaan_id' => $permintaan->id,
                'material_id' => $material_id,
                'jumlah_dalam_satuan_kecil' => $request->jumlah_kecil[$index], // Sesuaikan dengan nama input di form Anda
                'jumlah_dalam_satuan_besar' => $request->jumlah_besar[$index], // Sesuaikan dengan nama input di form Anda

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan_detail = PermintaanDetail::where('permintaan_id', $id)->get();
        return view('permintaan.show', compact('permintaan', 'permintaan_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Permintaan::findOrFail($id);
        $permintaan_detail = PermintaanDetail::where('permintaan_id', $id)->get();
        $materials = Material::all();
        return view('permintaan.edit', compact('data', 'permintaan_detail', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'berlaku_sampai' => 'required|date',
            'materials.*' => 'required|distinct|exists:materials,id',
            'jumlah_kecil' => 'required|array',
            'jumlah_kecil.*' => 'required|numeric|min:0', // Mengganti "jumlahs" dengan "jumlah_kecil" sesuai dengan form Anda
            'jumlah_besar' => 'required|array',
            'jumlah_besar.*' => 'required|numeric|min:0', // Menambahkan validasi untuk "jumlah_besar"
        ]);

        $permintaan = Permintaan::findOrFail($id);
        $permintaan->kode = $request->kode;
        $permintaan->berlaku_sampai = $request->berlaku_sampai;
        $permintaan->save();

        // Validasi produk akhir harus unik
        $existingMaterial = [];
        foreach ($request->materials as $index => $material_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($material_id, $existingMaterial)) {
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu permintaan.');
            }
            $existingMaterial[] = $material_id;

            $jumlah_kecil = $request->jumlah_kecil[$index];
            $jumlah_besar = $request->jumlah_besar[$index];
            PermintaanDetail::updateOrCreate(
                ['permintaan_id' => $id, 'material_id' => $material_id],
                ['jumlah_dalam_satuan_kecil' => $jumlah_kecil, 'jumlah_dalam_satuan_besar' => $jumlah_besar]
            );
        }

        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->delete();
        return redirect()->back()->with("success", "Permintaan berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Permintaan::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('berlaku_sampai', function ($row) {
                return Carbon::parse($row->berlaku_sampai)->format('d-m-Y H:i:s');
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success">Ditutup</span>';
                } else {
                    return '<span class="badge badge-danger">Terbuka</span>';
                }
            })
            ->addColumn('tindakan', function ($row) {
                if ($row->status == 1) {
                    $showUrl = route('permintaan.show', $row->id);
                    return '
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                            <span class="badge badge-secondary">
                                <i class="icon-lock"></i> Dikunci
                            </span>
                        </div>
                    ';
                } else {
                    $editUrl = route('permintaan.edit', $row->id);
                    $showUrl = route('permintaan.show', $row->id);
                    return '
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <button class="btn btn-dark btn-sm close-btn" data-id="' . $row->id . '">Tutup</button>
                            <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="' . $showUrl . '" class="btn btn-info btn-sm">Detail</a>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Hapus</button>
                        </div>
                    ';
                }
            })
            ->rawColumns(['status', 'tindakan'])
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
            ->make(true);

    }

    public function api($id)
    {
        $data = PermintaanDetail::with('material')->where('permintaan_id', $id)->get();
        return response()->json($data);
    }

    public function tutup($id)
    {
        $data = Permintaan::findOrFail($id);
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => "Permintaan '{$data->kode}' ditutup.",
        ]);
        $data->update(['status' => 1]);
        return redirect()->route('permintaan.index')->with('success', 'Permintaan berhasil ditutup.');
    }
}
