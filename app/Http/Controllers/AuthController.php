<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        $setup = Setup::get()->last();
        return view('auth.login', compact('setup'));
    }

    public function loginProcess(Request $request){
        $attempt = Auth::attempt([
            'username'  => $request->username,
            'password'  => $request->password,
            'is_active'  => 1,
        ]);
        if ($attempt){
            $request->session()->regenerate();
            ActivityLog::insert(["user_id" => Auth::id(), "description" => "Login."]);
            return redirect()->intended();
        }
        else
        {
            return redirect('login')->with('fail', 'Username / password salah.');
        }
    }

    public function logout(Request $request){
        ActivityLog::insert(["user_id" => Auth::id(), "description" => "Logout."]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

}
