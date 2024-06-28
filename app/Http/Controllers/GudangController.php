<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:gudangs",
        ]);
        Gudang::create($validated);
        return redirect()->back()->with("success", "Gudang berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Gudang $gudang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('gudang.edit', compact('gudang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $gudang = Gudang::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:gudangs,nama,' . $gudang->id,
        ]);
        self::updateColumn($gudang, $request);
        $gudang->update($validated);
        return redirect()->route('gudang.index')->with("success", "Gudang berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gudang = Gudang::findOrFail($id);
        $gudang->delete();
        return redirect()->back()->with("success", "Gudang berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Gudang::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('gudang.edit', $row->id);
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

    public static function updateColumn($gudang, $request)
    {
        $old_column_name = str_replace(' ', '_', $gudang->nama);
        $new_column_name = str_replace(' ', '_', $request->nama);
        $queries = [
            "ALTER TABLE bahan_bakus CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL",
            "ALTER TABLE produk_reproses CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL",
            "ALTER TABLE produk_sampings CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL",
            "ALTER TABLE produk_akhirs CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL",
        ];
        foreach ($queries as $query) {
            DB::statement($query);
        }
    }
}
