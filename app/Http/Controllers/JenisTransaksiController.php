<?php

namespace App\Http\Controllers;

use App\Models\JenisTransaksi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jenis_transaksi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_transaksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jenis_transaksis",
            "saldo" => "required",
        ]);
        JenisTransaksi::create($validated);
        return redirect()->back()->with("success", "Jenis Transaksi berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisTransaksi $jenis_transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jenis_transaksi = JenisTransaksi::findOrFail($id);
        return view('jenis_transaksi.edit', compact('jenis_transaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jenis_transaksi = JenisTransaksi::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jenis_transaksis,nama,' . $jenis_transaksi->id,
            "saldo" => "required",
        ]);
        $jenis_transaksi->update($validated);
        return redirect()->route('jenis_transaksi.index')->with("success", "Jenis Transaksi berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_transaksi = JenisTransaksi::findOrFail($id);
        $jenis_transaksi->delete();
        return redirect()->back()->with("success", "Jenis Transaksi berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = JenisTransaksi::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jenis_transaksi.edit', $row->id);
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
