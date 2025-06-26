<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\QuizModel;
use App\Models\UserModel1;
use Illuminate\Support\Facades\Hash;
use App\Rules\LoginCheck;
use App\Models\Kuis;
use Illuminate\Support\Facades\Session;

class BljrController extends Controller
{
    function dashboard()
    {
        $jumlahQuiz = Kuis::count();
        $jumlahSiswa = UserModel1::count();
        return view('admin.dashboard', compact('jumlahSiswa', 'jumlahQuiz'));
    }
    function login()
    {
        return view('admin.login');
    }

    function data()
    {
        $users = UserModel1::all();
        return view('user.tambahUser', compact('users'));
    }

    function daftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:5|string|max:255',
            'nisn' => 'required|digits:10',
            
        ]);

        $dataInsert = [
            'nama' => $request->nama,
            'nisn' => $request->nisn,
        ];

        UserModel1::insert($dataInsert);

        return redirect()->route('dataSiswa')->with('success', 'Pendaftaran Berhasil');
    }

    function editUser($id)
    {
        $users = UserModel1::where('id', $id)->first();
        $data = [
            'user' => $users
        ];
        return view('admin.edituser', $data);
    }

    function updateUser(Request $request, $id)
    {
        $nama = $request->input('nama');
        $nisn = $request->input('nisn');

        $dataUpdate = [
            'nama' => $nama,
            'nisn' => $nisn,
        ];

        UserModel1::where('id', $id)->update($dataUpdate);
        return redirect()->route('formhitung')->with('success', 'Data Berhasil Diubah');
    }

    function deleteUser($id)
    {
        $user = UserModel1::findOrFail($id);
        $user->delete();

        return redirect()->route('formhitung')->with('success', 'Data Berhasil Dihapus');
    }

    function listgempa()
    {
        $url = 'https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json';
        $json = file_get_contents($url);

        $data = json_decode($json, true);

        $gempaData = $data['Infogempa']['gempa'] ?? [];
        return view('admin.listgempa', compact('gempaData'));
    }

    function proseslogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', new LoginCheck($request)]
        ]);
        return redirect()->route('leaderboard');
    }

    function logout()
    {
        session::flush();
        return redirect()->route('loginadmin');
    }
}
