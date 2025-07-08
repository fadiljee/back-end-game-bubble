<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\HasilKuis;
use Illuminate\Support\Facades\Validator;
use App\Models\Kuis;
use Illuminate\Support\Facades\Auth;
use App\Models\Leaderboard;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Login menggunakan NISN tanpa password, sekaligus buat token
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa) {
            return response()->json(['message' => 'NISN tidak ditemukan'], 404);
        }

        // Buat token dengan Sanctum
        $token = $siswa->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data_siswa' => $siswa,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Semua route di bawah wajib menggunakan middleware 'auth:sanctum'
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login');
    }

    // === PERUBAHAN: Menampilkan semua materi dengan link YouTube ===
    public function index()
    {
        $materis = Materi::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'konten' => $item->konten,
                'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                'link_yt' => $item->link_yt, // Memastikan link_yt selalu ada di respons
            ];
        });
        return response()->json($materis);
    }

    // === PERUBAHAN: Menampilkan materi by ID dengan link YouTube ===
    public function show($id)
    {
        $materi = Materi::find($id);
        if (!$materi) {
            return response()->json(['message' => 'Materi tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $materi->id,
            'judul' => $materi->judul,
            'konten' => $materi->konten,
            'gambar_url' => $materi->gambar ? asset('storage/' . $materi->gambar) : null,
            'link_yt' => $materi->link_yt, // Memastikan link_yt selalu ada di respons
        ]);
    }

    // === PERUBAHAN: Mencari materi by judul dengan link YouTube ===
    public function searchByTitle(Request $request)
    {
        $judul = $request->query('judul');
        if (!$judul || strlen($judul) < 3) {
            return response()->json(['message' => 'Parameter judul minimal 3 karakter'], 422);
        }

        $materis = Materi::where('judul', 'like', '%' . $judul . '%')->get();

        if ($materis->isEmpty()) {
            return response()->json(['message' => 'Materi tidak ditemukan'], 404);
        }

        // Transformasi untuk memastikan struktur respons konsisten
        $formattedMateris = $materis->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'konten' => $item->konten,
                'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                'link_yt' => $item->link_yt,
            ];
        });

        return response()->json($formattedMateris);
    }

    // Ambil semua kuis dengan waktu pengerjaan
    public function kuis()
    {
        $kuis = Kuis::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'pertanyaan' => $item->pertanyaan,
                'jawaban_a' => $item->jawaban_a,
                'jawaban_b' => $item->jawaban_b,
                'jawaban_c' => $item->jawaban_c,
                'jawaban_d' => $item->jawaban_d,
                'jawaban_benar' => $item->jawaban_benar,
                'materi_id' => $item->materi_id,
                'waktu_pengerjaan' => $item->waktu_pengerjaan,
            ];
        });

        return response()->json(['kuis' => $kuis]);
    }

    // Ambil kuis by materi_id dengan waktu pengerjaan
    public function kuisShow($id)
    {
        $kuis = Kuis::where('materi_id', $id)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'pertanyaan' => $item->pertanyaan,
                'jawaban_a' => $item->jawaban_a,
                'jawaban_b' => $item->jawaban_b,
                'jawaban_c' => $item->jawaban_c,
                'jawaban_d' => $item->jawaban_d,
                'jawaban_benar' => $item->jawaban_benar,
                'materi_id' => $item->materi_id,
                'waktu_pengerjaan' => $item->waktu_pengerjaan,
            ];
        });

        if ($kuis->isEmpty()) {
            return response()->json(['message' => 'Kuis tidak ditemukan'], 404);
        }

        return response()->json(['kuis' => $kuis]);
    }

    // Simpan hasil kuis dari user dengan waktu pengerjaan
    public function storeHasilKuis(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|integer',
            'kuis_id' => 'required|integer',
            'jawaban_user' => 'required|string|in:A,B,C,D,E', // E untuk waktu habis
            'waktu' => 'required|integer|min:0',
            'attempt_id' => 'required|string',
        ]);

        $kuis = Kuis::findOrFail($request->kuis_id);
        $siswa = Siswa::findOrFail($request->siswa_id);

        $benar = $kuis->jawaban_benar === $request->jawaban_user;
        $nilai = $benar ? $kuis->nilai : 0;

        // Gunakan updateOrCreate untuk efisiensi
        $hasil = HasilKuis::create([
        'siswa_id'     => $request->siswa_id,
        'attempt_id' => $request->attempt_id,
        'kuis_id'      => $request->kuis_id,
        'jawaban_user' => $request->jawaban_user,
        'waktu'        => $request->waktu,
        'benar'        => $benar,
        'nilai'        => $nilai
    ]);

        return response()->json([
            'status' => 'success',
            'hasil' => $hasil,
            'siswa' => $siswa,
        ]);
    }

   // Menyimpan skor terbaik ke leaderboard
   public function store(Request $request)
   {
       $request->validate([
           'time' => 'required|integer|min:1',
       ]);

       $user = Auth::user();
       if (!$user) {
           return response()->json(['message' => 'Unauthorized'], 401);
       }

       $waktuBaru = $request->time;
       $skorLama = Leaderboard::where('siswa_id', $user->id)->first();

       if ($skorLama && $waktuBaru >= $skorLama->time) {
           return response()->json(['message' => 'Skor tidak lebih baik dari rekor sebelumnya.'], 200);
       }

       Leaderboard::updateOrCreate(
           ['siswa_id' => $user->id],
           ['time' => $waktuBaru]
       );

       return response()->json(['message' => 'Time recorded successfully'], 201);
   }

   // Mengambil daftar peringkat leaderboard
   public function lead()
   {
       $leaderboards = Leaderboard::with('siswa')
           ->orderBy('time', 'asc')
           ->take(100)
           ->get();

       return response()->json($leaderboards);
   }
}
