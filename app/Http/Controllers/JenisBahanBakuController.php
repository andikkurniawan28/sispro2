<?php

namespace App\Http\Controllers;

use App\Models\JenisBahanBaku;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisBahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_bahan_baku.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_bahan_baku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_bahan_bakus",
        ]);
        JenisBahanBaku::create($validated);
        return redirect()->back()->with("success", "Jenis Bahan Baku berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisBahanBaku $jenis_bahan_baku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_bahan_baku = JenisBahanBaku::findOrFail($id);
        return view('jenis_bahan_baku.edit', compact('jenis_bahan_baku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_bahan_baku = JenisBahanBaku::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_bahan_bakus,nama,' . $jenis_bahan_baku->id,
        ]);
        $jenis_bahan_baku->update($validated);
        return redirect()->route('jenis_bahan_baku.index')->with("success", "Jenis Bahan Baku berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_bahan_baku = JenisBahanBaku::findOrFail($id);
        $jenis_bahan_baku->delete();
        return redirect()->back()->with("success", "Jenis Bahan Baku berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisBahanBaku::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_bahan_baku.edit', $row->id);
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