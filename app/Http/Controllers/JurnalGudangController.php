<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BahanBaku;
use App\Models\ProdukAkhir;
use App\Models\JurnalGudang;
use Illuminate\Http\Request;
use App\Models\ProdukSamping;
use App\Models\ProdukReproses;
use App\Models\Gudang;
use App\Models\JenisJurnalGudang;
use Yajra\DataTables\DataTables;
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
        $produk_akhirs = ProdukAkhir::with('satuan_besar', 'satuan_kecil')->get();
        $produk_reproses = ProdukReproses::with('satuan_besar', 'satuan_kecil')->get();
        $produk_sampings = ProdukSamping::with('satuan_besar', 'satuan_kecil')->get();
        $bahan_bakus = BahanBaku::with('satuan_besar', 'satuan_kecil')->get();
        $kode = JurnalGudang::kode_faktur();
        return view('jurnal_gudang.create',
            compact('produk_akhirs', 'produk_reproses', 'produk_sampings', 'bahan_bakus', 'kode',
                    'gudangs', 'jenis_jurnal_gudangs',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang_detail = JurnalGudangDetail::where('permintaan_id', $id)->get();
        return view('jurnal_gudang.show', compact('jurnal_gudang', 'jurnal_gudang_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang_detail = JurnalGudangDetail::where('permintaan_id', $id)->get();
        $produk_akhirs = ProdukAkhir::all();
        return view('jurnal_gudang.edit', compact('jurnal_gudang', 'jurnal_gudang_detail', 'produk_akhirs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'berlaku_sampai' => 'required|date',
            'produk_akhirs.*' => 'required|distinct|exists:produk_akhirs,id', // distinct untuk memastikan produk_akhir tidak duplikat
            'jumlahs.*' => 'required|integer|min:1',
        ]);

        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang->kode = $request->kode;
        $jurnal_gudang->berlaku_sampai = $request->berlaku_sampai;
        $jurnal_gudang->save();

        // Validasi produk akhir harus unik
        $existingProdukAkhir = [];
        foreach ($request->produk_akhirs as $index => $produk_akhir_id) {
            // Validasi jika produk akhir sudah pernah dimasukkan sebelumnya
            if (in_array($produk_akhir_id, $existingProdukAkhir)) {
                return redirect()->back()->with('error', 'Produk Akhir harus unik dalam satu permintaan.');
            }
            $existingProdukAkhir[] = $produk_akhir_id;

            $jumlah = $request->jumlahs[$index];
            JurnalGudangDetail::updateOrCreate(
                ['permintaan_id' => $id, 'produk_akhir_id' => $produk_akhir_id],
                ['jumlah' => $jumlah]
            );
        }

        return redirect()->route('jurnal_gudang.index')->with('success', 'Permintaan Produk Akhir berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurnal_gudang = JurnalGudang::findOrFail($id);
        $jurnal_gudang->delete();
        return redirect()->back()->with("success", "Permintaan Produk Akhir berhasil dihapus.");
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
