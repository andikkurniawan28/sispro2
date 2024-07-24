<?php

namespace App\Http\Controllers;

use App\Models\AkunDasar;
use App\Models\AkunInduk;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class AkunIndukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('akun_induk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akun_dasars = AkunDasar::all();
        return view('akun_induk.create', compact('akun_dasars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "kode" => "required|unique:akun_induks",
            "nama" => "required|unique:akun_induks",
            "akun_dasar_id" => "required",
        ]);
        AkunInduk::create($validated);
        return redirect()->back()->with("success", "Akun Induk berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(AkunInduk $akun_induk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $akun_dasars = AkunDasar::all();
        $akun_induk = AkunInduk::findOrFail($id);
        return view('akun_induk.edit', compact('akun_induk', 'akun_dasars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $akun_induk = AkunInduk::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:akun_induks,kode,' . $akun_induk->id,
            'nama' => 'required|unique:akun_induks,nama,' . $akun_induk->id,
            "akun_dasar_id" => "required",
        ]);
        $akun_induk->update($validated);
        return redirect()->route('akun_induk.index')->with("success", "Akun Induk berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $akun_induk = AkunInduk::findOrFail($id);
        $akun_induk->delete();
        return redirect()->back()->with("success", "Akun Induk berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = AkunInduk::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('akun_dasar_nama', function ($row) {
                return $row->akun_dasar ? $row->akun_dasar->nama : 'N/A';
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('akun_induk.edit', $row->id);
                return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
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
