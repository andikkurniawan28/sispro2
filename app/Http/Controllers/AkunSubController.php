<?php

namespace App\Http\Controllers;

use App\Models\AkunSub;
use App\Models\AkunInduk;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class AkunSubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('akun_sub.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akun_induks = AkunInduk::all();
        return view('akun_sub.create', compact('akun_induks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "kode" => "required|unique:akun_subs",
            "nama" => "required|unique:akun_subs",
            "akun_induk_id" => "required",
        ]);
        AkunSub::create($validated);
        return redirect()->back()->with("success", "Akun Sub berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(AkunSub $akun_sub)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $akun_induks = AkunInduk::all();
        $akun_sub = AkunSub::findOrFail($id);
        return view('akun_sub.edit', compact('akun_sub', 'akun_induks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $akun_sub = AkunSub::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:akun_subs,kode,' . $akun_sub->id,
            'nama' => 'required|unique:akun_subs,nama,' . $akun_sub->id,
            "akun_induk_id" => "required",
        ]);
        $akun_sub->update($validated);
        return redirect()->route('akun_sub.index')->with("success", "Akun Sub berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $akun_sub = AkunSub::findOrFail($id);
        $akun_sub->delete();
        return redirect()->back()->with("success", "Akun Sub berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = AkunSub::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('akun_induk_nama', function ($row) {
                return $row->akun_induk ? $row->akun_induk->nama : 'N/A';
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('akun_sub.edit', $row->id);
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
