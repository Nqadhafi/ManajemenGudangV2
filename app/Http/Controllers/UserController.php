<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('karyawan')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,operator'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,operator'
        ]);

        $user->update([
            'email' => $request->email,
            'role' => $request->role
        ]);

        if($request->password) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}