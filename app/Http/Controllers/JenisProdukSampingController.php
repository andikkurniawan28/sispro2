<?php

namespace App\Http\Controllers;

use App\Models\JenisProdukSamping;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisProdukSampingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_produk_samping.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_produk_samping.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_produk_sampings",
        ]);
        JenisProdukSamping::create($validated);
        return redirect()->back()->with("success", "Jenis Produk Samping berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisProdukSamping $jenis_produk_samping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_produk_samping = JenisProdukSamping::findOrFail($id);
        return view('jenis_produk_samping.edit', compact('jenis_produk_samping'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_produk_samping = JenisProdukSamping::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_produk_sampings,nama,' . $jenis_produk_samping->id,
        ]);
        $jenis_produk_samping->update($validated);
        return redirect()->route('jenis_produk_samping.index')->with("success", "Jenis Produk Samping berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_produk_samping = JenisProdukSamping::findOrFail($id);
        $jenis_produk_samping->delete();
        return redirect()->back()->with("success", "Jenis Produk Samping berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisProdukSamping::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_produk_samping.edit', $row->id);
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
