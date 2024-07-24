<?php

namespace App\Http\Controllers;

use App\Models\AkunDasar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class AkunDasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('akun_dasar.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('akun_dasar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "kode" => "required|unique:akun_dasars",
            "nama" => "required|unique:akun_dasars",
            "laporan" => "required",
            "kelompok" => "required",
        ]);
        AkunDasar::create($validated);
        return redirect()->back()->with("success", "Akun Dasar berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(AkunDasar $akun_dasar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $akun_dasar = AkunDasar::findOrFail($id);
        return view('akun_dasar.edit', compact('akun_dasar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $akun_dasar = AkunDasar::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:akun_dasars,kode,' . $akun_dasar->id,
            'nama' => 'required|unique:akun_dasars,nama,' . $akun_dasar->id,
            "laporan" => "required",
            "kelompok" => "required",
        ]);
        $akun_dasar->update($validated);
        return redirect()->route('akun_dasar.index')->with("success", "Akun Dasar berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $akun_dasar = AkunDasar::findOrFail($id);
        $akun_dasar->delete();
        return redirect()->back()->with("success", "Akun Dasar berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = AkunDasar::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('akun_dasar.edit', $row->id);
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
