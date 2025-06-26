<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\Materi;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\HasilKuis;
use Illuminate\Support\Facades\Log; // Sebaiknya tambahkan ini untuk logging error

class quizController extends Controller
{
    // Menampilkan daftar kuis
    public function index()
    {
        $kuis = Kuis::all();
        return view('quiz.index', compact('kuis'));
    }

    // Menampilkan form tambah kuis
    public function create()
    {
        $materis = Materi::all();
        return view('quiz.create', compact('materis'));
    }

    // Menyimpan kuis baru
    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban_a' => 'required|string|max:255',
            'jawaban_b' => 'required|string|max:255',
            'jawaban_c' => 'required|string|max:255',
            'jawaban_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'waktu_pengerjaan' => 'required|integer|min:10',
            'nilai' => 'required|integer|min:1', // validasi nilai
        ]);

        Kuis::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban_a' => $request->jawaban_a,
            'jawaban_b' => $request->jawaban_b,
            'jawaban_c' => $request->jawaban_c,
            'jawaban_d' => $request->jawaban_d,
            'jawaban_benar' => $request->jawaban_benar,
            'waktu_pengerjaan' => $request->waktu_pengerjaan,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('kuis')->with('success', 'Kuis berhasil ditambahkan');
    }

    // Menampilkan form edit kuis
    public function edit($id)
    {
        $kuis = Kuis::findOrFail($id);
        $materis = Materi::all();
        return view('quiz.edit', compact('kuis', 'materis'));
    }

    // Mengupdate kuis
    public function update(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban_a' => 'required|string|max:255',
            'jawaban_b' => 'required|string|max:255',
            'jawaban_c' => 'required|string|max:255',
            'jawaban_d' => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'waktu_pengerjaan' => 'required|integer|min:10',
            'nilai' => 'required|integer|min:1',
        ]);

        $kuis = Kuis::findOrFail($id);
        $kuis->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban_a' => $request->jawaban_a,
            'jawaban_b' => $request->jawaban_b,
            'jawaban_c' => $request->jawaban_c,
            'jawaban_d' => $request->jawaban_d,
            'jawaban_benar' => $request->jawaban_benar,
            'waktu_pengerjaan' => $request->waktu_pengerjaan,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('kuis')->with('success', 'Kuis berhasil diperbarui');
    }

    // Menghapus kuis
    public function destroy($id)
    {
        $kuis = Kuis::findOrFail($id);
        $kuis->delete();

        return redirect()->route('kuis')->with('success', 'Kuis berhasil dihapus');
    }

    // Menyimpan hasil kuis dari user dengan nilai poin
    public function storeHasilKuis(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|integer',
            'kuis_id' => 'required|integer',
            'jawaban_user' => 'required|string|in:A,B,C,D',
            'waktu' => 'required|integer',
        ]);

        $kuis = Kuis::findOrFail($request->kuis_id);
        $benar = $kuis->jawaban_benar === $request->jawaban_user;

        $hasil = new HasilKuis();
        $hasil->siswa_id = $request->siswa_id;
        $hasil->kuis_id = $request->kuis_id;
        $hasil->jawaban_user = $request->jawaban_user;
        $hasil->benar = $benar;
        $hasil->waktu = $request->waktu;
        $hasil->nilai = $benar ? $kuis->nilai : 0;  // simpan nilai poin soal jika benar
        $hasil->save();

        $siswa = Siswa::find($request->siswa_id);

        return response()->json([
            'status' => 'success',
            'siswa' => $siswa,
            'hasil' => $hasil,
        ]);
    }

    // Menampilkan leaderboard berdasarkan total nilai per siswa dan total waktu
    public function leaderboard()
    {
        // Ambil semua hasil kuis, relasi siswa, dan ambil nilai tiap jawaban
        $hasilKuis = HasilKuis::with('siswa')->get();

        // Group hasil kuis berdasarkan siswa_id
        $grouped = $hasilKuis->groupBy('siswa_id');

        // Hitung total nilai dan total waktu per siswa
        $leaderboard = $grouped->map(function ($items, $siswa_id) {
            $totalNilai = $items->sum('nilai');   // akumulasi nilai tiap jawaban benar
            $totalWaktu = $items->sum('waktu');   // total waktu semua soal

            // Ambil data siswa sekali saja dari relasi
            $siswa = $items->first()->siswa;

            return (object) [
                'siswa' => $siswa,
                'total_nilai' => $totalNilai,
                'total_waktu' => $totalWaktu,
            ];
        });

        // Urutkan berdasarkan total nilai descending dan waktu ascending (lebih cepat lebih bagus)
        $leaderboard = $leaderboard->sort(function ($a, $b) {
            if ($a->total_nilai === $b->total_nilai) {
                return $a->total_waktu <=> $b->total_waktu; // waktu kecil dulu
            }
            return $b->total_nilai <=> $a->total_nilai; // nilai besar dulu
        });

        return view('leaderboard.index', ['leaderboard' => $leaderboard]);
    }

    // Menampilkan semua hasil kuis (detail jawaban per soal)
    public function daftarHasilKuis()
    {
        // 1. Ambil semua soal untuk membuat header tabel secara dinamis
        $allQuestions = Kuis::orderBy('id', 'asc')->get();

        // 2. Ambil semua hasil kuis, eager load relasi siswa dan kuis
        $allResults = HasilKuis::with(['siswa', 'kuis'])->get();

        // 3. Kelompokkan hasil berdasarkan ID siswa
        $resultsBySiswa = $allResults->groupBy('siswa_id');

        // 4. Proses data untuk setiap siswa menjadi format yang kita inginkan
        $groupedResults = [];
        foreach ($resultsBySiswa as $siswaId => $results) {
            $siswa = $results->first()->siswa;

            // Jika data siswa tidak ada, lewati
            if (!$siswa) continue;

            $answers = [];
            $totalWaktu = 0;

            // Loop melalui jawaban siswa
            foreach ($results as $result) {
                // Simpan jawaban user untuk setiap soal
                $answers[$result->kuis_id] = [
                    'jawaban' => $result->jawaban_user,
                    'benar' => $result->benar,
                ];
                $totalWaktu += $result->waktu; // Akumulasi waktu
            }

            // Hitung total nilai berdasarkan jawaban yang benar
            $totalNilai = $results->where('benar', true)->sum(function($result) {
                return $result->kuis->nilai ?? 0;
            });

            // Simpan data yang sudah rapi
            $groupedResults[] = [
                'siswa_id' => $siswaId,
                'siswa_nama' => $siswa->nama,
                'siswa_nisn' => $siswa->nisn ?? 'N/A', // Ganti dengan field NISN Anda
                'answers' => $answers,
                'total_nilai' => $totalNilai,
                'total_waktu' => $totalWaktu,
            ];
        }

        // Urutkan hasil berdasarkan total nilai tertinggi, lalu waktu tercepat
        usort($groupedResults, function ($a, $b) {
            if ($a['total_nilai'] === $b['total_nilai']) {
                return $a['total_waktu'] <=> $b['total_waktu'];
            }
            return $b['total_nilai'] <=> $a['total_nilai'];
        });

        // 5. Kirim data yang sudah diproses ke view
        return view('hasilkuis.index', [
            'groupedResults' => $groupedResults,
            'allQuestions' => $allQuestions,
        ]);
    }

    public function nilaiAkhirSiswa($siswa_id)
    {
        // Hitung total nilai siswa
        $nilai = HasilKuis::where('siswa_id', $siswa_id)
            ->sum('nilai');

        return $nilai;
    }


    // ===================================================================
    // == METODE BARU UNTUK HAPUS HASIL KUIS ==
    // ===================================================================

    /**
     * Menghapus semua hasil kuis milik seorang siswa via AJAX.
     * Dipanggil oleh route: hasilkuis.destroy
     *
     * @param  int  $siswa_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyHasilKuis($siswa_id)
    {
        try {
            // Temukan semua record hasil kuis berdasarkan siswa_id dan hapus
            HasilKuis::where('siswa_id', $siswa_id)->delete();

            return response()->json(['success' => 'Hasil kuis siswa berhasil dihapus.']);

        } catch (\Exception $e) {
            Log::error('Gagal hapus hasil kuis siswa: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

    /**
     * Menghapus SEMUA hasil kuis dari tabel via AJAX.
     * Dipanggil oleh route: hasilkuis.destroyAll
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAllHasilKuis()
    {
        try {
            // Menghapus semua baris dari tabel HasilKuis
            HasilKuis::query()->delete();

            return response()->json(['success' => 'Semua data hasil kuis telah berhasil dihapus.']);

        } catch (\Exception $e) {
            Log::error('Gagal hapus semua hasil kuis: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus semua data.'], 500);
        }
    }
}
