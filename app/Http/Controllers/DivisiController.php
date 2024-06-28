<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('divisi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('divisi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:divisis",
        ]);
        Divisi::create($validated);
        return redirect()->back()->with("success", "Divisi berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Divisi $divisi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('divisi.edit', compact('divisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:divisis,nama,' . $divisi->id,
        ]);
        $divisi->update($validated);
        return redirect()->route('divisi.index')->with("success", "Divisi berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        if ($divisi->jabatan()->exists()) {
            return redirect()->back()->with("fail", "Data tidak dapat dihapus karena masih terkait dengan jabatan.");
        }
        $divisi->delete();
        return redirect()->back()->with("success", "Divisi berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Divisi::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('divisi.edit', $row->id);
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
