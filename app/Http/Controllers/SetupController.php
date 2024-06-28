<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("setup.index");
    }

    public function process(Request $request)
    {
        $id = Setup::get()->last()->id;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setup = Setup::findOrFail($id);

        if ($request->hasFile('company_logo')) {
            $image_name = time() . '.' . $request->company_logo->extension();
            $request->company_logo->move(public_path('setups'), $image_name);
            $validated["company_logo"] = 'setups/' . $image_name;
            if ($setup->company_logo && file_exists(public_path($setup->company_logo))) {
                @unlink(public_path($setup->company_logo));
            }
        }

        $setup->update($validated);

        ActivityLog::insert(["user_id" => Auth()->user()->id, "description" => "Setup dirubah."]);

        return redirect()->back()->with("success", "Setup berhasil diproses.");
    }
}
