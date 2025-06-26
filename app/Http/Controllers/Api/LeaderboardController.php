<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leaderboard;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI untuk mengambil data user

class LeaderboardController extends Controller
{
    /**
     * Menyimpan atau memperbarui skor terbaik pemain.
     */
    public function store(Request $request)
    {
        $request->validate([
            'time' => 'required|integer|min:1', // Validasi tetap bagus
        ]);

        // 1. Ambil user yang sedang login (terautentikasi)
        $user = Auth::Siswa();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $waktuBaru = $request->time;

        // 2. Cek apakah user sudah punya skor sebelumnya
        $skorLama = Leaderboard::where('siswa_id', $user->id)->first();

        // Jika user sudah punya skor, dan skor barunya TIDAK LEBIH BAIK (lebih lama),
        // maka jangan lakukan apa-apa.
        if ($skorLama && $waktuBaru >= $skorLama->time) {
            return response()->json(['message' => 'Skor tidak lebih baik dari rekor sebelumnya.'], 200);
        }

        // 3. Gunakan updateOrCreate untuk menyimpan skor terbaik.
        // - Jika siswa_id belum ada, buat data baru.
        // - Jika siswa_id sudah ada, update datanya dengan waktu yang baru (yang sudah pasti lebih baik).
        Leaderboard::updateOrCreate(
            ['siswa_id' => $user->id], // Kunci untuk mencari
            ['time' => $waktuBaru]      // Data untuk diisi atau di-update
        );

        return response()->json(['message' => 'Time recorded successfully'], 201);
    }

    /**
     * Mengambil daftar peringkat untuk ditampilkan di Flutter.
     */
    public function index()
    {
        // 4. Gunakan 'with('siswa')' untuk mengambil data siswa (nama, dll)
        // yang terhubung dengan setiap skor.
        // Ini membutuhkan relasi yang benar di Model Leaderboard.
        $leaderboards = Leaderboard::with('siswa')
            ->orderBy('time', 'asc') // Urutkan berdasarkan waktu tercepat
            ->take(100)               // Ambil 100 teratas
            ->get();

        return response()->json($leaderboards);
    }
}
