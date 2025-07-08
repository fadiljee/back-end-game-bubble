<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kuis;
use Illuminate\Support\Facades\Hash;
use App\Models\Materi;
use App\Models\UserModel;
use App\Rules\LoginCheck;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    function login()
    {
        return view('admin.login');
    }

    function dashboard()
    {
        $users = Siswa::all();
        return view('User.dataUser', compact('users'));
    }

    function tampilData()
    {
        // return view('User.dataUser');
        $users = Siswa::all();
        return view('User.dataUser', compact('users'));
    }
    function tambahUser()
    {
        return view('User.tambahUser');
    }

    function editData()
    {
        return view('User.editUser');
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

        Siswa::insert($dataInsert);

        return redirect()->route('dataSiswa')->with('success', 'Pendaftaran Berhasil');
    }

    function editUser($id)
    {
        $users = Siswa::where('id', $id)->first();
        $data = [
            'user' => $users
        ];
        return view('User.edituser', $data);
    }

    function updateUser(Request $request, $id)
    {
        $nama = $request->input('nama');
        $nisn = $request->input('nisn');

        $dataUpdate = [
            'nama' => $nama,
            'nisn' => $nisn,
        ];

        Siswa::where('id', $id)->update($dataUpdate);
        return redirect()->route('dataSiswa')->with('success', 'Data Berhasil Diubah');
    }

    function deleteUser($id)
    {
        $user = Siswa::findOrFail($id);
        $user->delete();

        return redirect()->route('dataSiswa')->with('success', 'Data Berhasil Dihapus');
    }


   public function proseslogin(Request $request)
{
    // Validasi input login dulu (email, password)

    // Cek user berdasarkan email
    $user = UserModel::where('email', $request->email)->first();

    // Jika user ada dan password cocok
    if ($user && Hash::check($request->password, $user->password)) {
        // Simpan session loginStatus dan user_id
        session([
            'loginStatus' => true,
            'user_id' => $user->id,
        ]);
        return redirect()->route('dashboardadmin');
    }

    // Kalau gagal login
    return redirect()->back()->withErrors(['email' => 'Email atau password salah']);
}


    function logout()
    {
        session::flush();
        return redirect()->route('loginadmin');
    }

}
