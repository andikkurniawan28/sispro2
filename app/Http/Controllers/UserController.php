<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('user.create', compact('jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "jabatan_id" => "required",
            "nama" => "required|unique:users",
            "username" => "required|unique:users",
            "password" => "required|min:8",
        ]);
        $validated['password'] = bcrypt($request->password);
        User::create($validated);
        return redirect()->back()->with("success", "User berhasil disimpan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $jabatans = Jabatan::all();
        return view('user.edit', compact('user', 'jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            "jabatan_id" => "required",
            "nama" => "required|unique:users,nama,' . $user->id",
            'username' => 'required|unique:users,username,' . $user->id,
            "is_active" => "required",
        ]);
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }
        $user->update($validated);
        return redirect()->back()->with("success", "User has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->activity_log()->exists()) {
            return redirect()->back()->with("fail", "Data tidak dapat dihapus karena masih terkait dengan log aktifitas.");
        }
        $user->delete();
        return redirect()->back()->with("success", "User berhasil dihapus.");
    }

    public static function dataTable()
    {
        $data = User::with('jabatan')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('jabatan_nama', function ($row) {
                return $row->jabatan->nama;
            })
            ->addColumn('tindakan', function ($row) {
                $editUrl = route('user.edit', $row->id);
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
