<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::firstWhere('username', $request->username);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_active === 1) {
                    session([
                        'is_login' => true,
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_role' => $user->is_admin,
                    ]);
                    if (Hash::check($user->username, $user->password)) {
                        session(['password_alert' => true]);
                    }
                    if ($user->is_admin === 1) {
                        return redirect()->route('admin-index');
                    } else {
                        return redirect()->route('user-index');
                    }
                } else {
                    session()->flash('flash-fail', 'Akun tidak aktif!');
                    return redirect('/');
                }
            } else {
                session()->flash('flash-fail', 'Kata sandi salah!');
                return redirect('/');
            }
        } else {
            session()->flash('flash-fail', 'Pengguna tidak terdaftar!');
            return redirect('/');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}
