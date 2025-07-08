<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
   public function edit()
{
    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('loginadmin');
    }

    $user = UserModel::find($userId);
    if (!$user) {
        abort(404, 'User tidak ditemukan');
    }

    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    $userId = session('user_id');
    if (!$userId) {
        return redirect()->route('loginadmin');
    }

    $user = UserModel::find($userId);
    if (!$user) {
        abort(404, 'User tidak ditemukan');
    }

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:data_user,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];

    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }

    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
}

}
