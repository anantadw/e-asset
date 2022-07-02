<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        return view('admin.account-admin', [
            'link' => 'AccountsAdmin',
            'admins' => User::where('is_admin', true)->get()
        ]);
    }

    public function users()
    {
        return view('admin.account-user', [
            'link' => 'AccountsUser',
            'users' => User::where('is_admin', false)->get()
        ]);
    }

    public function create($role)
    {
        return view('admin.account-create', [
            'link' => 'Accounts' . ucfirst($role),
            'role' => $role
        ]);
    }

    public function store($role, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]*$/',
            'unique_code' => 'bail|required|digits:9|unique:users,unique_code',
            'username' => 'bail|required|alpha_dash|same:password',
            'password' => 'bail|required|same:username',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->unique_code = $request->unique_code;
            if ($role === 'admin') {
                $user->is_admin = true;
            } else {
                $user->is_admin = false;
            }
            $user->is_active = true;

            if ($user->save()) {
                return response()->json([
                    'status' => true,
                    'redirect' => route('admin-accounts-' . $role)
                ]);
            }
        }
    }

    public function update(Request $request)
    {
        $admin = User::find($request->id);

        if ($admin->is_active === 1) {
            $admin->is_active = false;
        } else {
            $admin->is_active = true;
        }

        if ($admin->save()) {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        if ($user->delete()) {
            return response()->json([
                'status' => true,
                'deleted' => 'Pengguna'
            ]);
        }
    }
}
