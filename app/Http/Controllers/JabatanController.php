<?php

namespace App\Http\Controllers;

use App\Models\Fitur;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('jabatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisis = Divisi::all();
        $fiturs = Fitur::all();
        return view('jabatan.create', compact('divisis', 'fiturs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "nama" => "required|unique:jabatans",
            "divisi_id" => "required",
        ]);

        if ($request->has('fitur_ids')) {
            Permission::where("jabatan_id", $jabatan->id)->delete();
            $fiturs = $request->input('fitur_ids');
            foreach ($fiturs as $fiturId) {
                if (Fitur::where('id', $fiturId)->exists()) {
                    Permission::create([
                        'fitur_id' => $fiturId,
                        'jabatan_id' => $jabatan->id,
                    ]);
                }
            }
        }

        Jabatan::create($validated);

        return redirect()->back()->with("success", "Jabatan berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $divisis = Divisi::all();
        $fiturs = Fitur::all();
        $permissions = Permission::where('jabatan_id', $id)->get();
        return view('jabatan.edit', compact('jabatan', 'divisis', 'fiturs', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|unique:jabatans,nama,' . $jabatan->id,
            "divisi_id" => "required",
        ]);

        if ($request->has('fitur_ids')) {
            Permission::where("jabatan_id", $id)->delete();
            $fiturs = $request->input('fitur_ids');
            foreach ($fiturs as $fiturId) {
                if (Fitur::where('id', $fiturId)->exists()) {
                    Permission::create([
                        'fitur_id' => $fiturId,
                        'jabatan_id' => $id,
                    ]);
                }
            }
        }

        $jabatan->update($validated);

        return redirect()->route('jabatan.index')->with("success", "Jabatan berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        if ($jabatan->user()->exists()) {
            return redirect()->back()->with("fail", "Data tidak dapat dihapus karena masih terkait dengan pengguna.");
        }
        $jabatan->delete();
        return redirect()->back()->with("success", "Jabatan berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = Jabatan::with('divisi')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('divisi_nama', function ($row) {
                return $row->divisi->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('jabatan.edit', $row->id);
                return '
                    <div class="btn-group" jabatan="group" aria-label="Action Buttons">
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
