<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|numeric',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed' // password_confirmation wajib ada di form
        ]);

        // 1. Update Foto Jika Ada
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika bukan default
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 2. Update Data Diri
        $user->name = $request->name;
        $user->phone = $request->phone;

        // 3. Update Password Jika Diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            // Matikan status force change jika user ganti password sendiri
            $user->must_change_password = false;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
