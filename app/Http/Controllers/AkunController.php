<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\AkunSub;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('akun.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akun_subs = AkunSub::all();
        return view('akun.create', compact('akun_subs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "kode" => "required|unique:akuns",
            "nama" => "required|unique:akuns",
            "akun_sub_id" => "required",
        ]);
        Akun::create($validated);
        return redirect()->back()->with("success", "Akun berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Akun $akun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $akun_subs = AkunSub::all();
        $akun = Akun::findOrFail($id);
        return view('akun.edit', compact('akun', 'akun_subs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $akun = Akun::findOrFail($id);
        $validated = $request->validate([
            'kode' => 'required|unique:akuns,kode,' . $akun->id,
            'nama' => 'required|unique:akuns,nama,' . $akun->id,
            "akun_sub_id" => "required",
        ]);
        $akun->update($validated);
        return redirect()->route('akun.index')->with("success", "Akun berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $akun = Akun::findOrFail($id);
        $akun->delete();
        return redirect()->back()->with("success", "Akun berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Akun::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('akun_sub_nama', function ($row) {
                return $row->akun_sub ? $row->akun_sub->nama : 'N/A';
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('akun.edit', $row->id);
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
