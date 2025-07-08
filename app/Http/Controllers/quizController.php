<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\Materi;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\HasilKuis;
use Carbon\Carbon;
use PDF; // Pastikan Anda sudah menginstal package laravel-dompdf
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
    // public function storeHasilKuis(Request $request)
    // {
    //     $request->validate([
    //         'siswa_id' => 'required|integer',
    //         'kuis_id' => 'required|integer',
    //         'jawaban_user' => 'required|string|in:A,B,C,D,E',
    //         'waktu' => 'required|integer',
    //         'attempt_id' => 'required|string',
    //     ]);

    //     $kuis = Kuis::findOrFail($request->kuis_id);
    //     $benar = $kuis->jawaban_benar === $request->jawaban_user;

    //     $hasil = new HasilKuis();
    //     $hasil->siswa_id = $request->siswa_id;
    //     $hasil->kuis_id = $request->kuis_id;
    //     $hasil->jawaban_user = $request->jawaban_user;
    //     $hasil->benar = $benar;
    //     $hasil->waktu = $request->waktu;
    //     $hasil->nilai = $benar ? $kuis->nilai : 0;  // simpan nilai poin soal jika benar
    //     $hasil->attempt_id = $request->attempt_id;
    //     $hasil->save();

    //     $siswa = Siswa::find($request->siswa_id);

    //     return response()->json([
    //         'status' => 'success',
    //         'siswa' => $siswa,
    //         'hasil' => $hasil,
    //     ]);
    // }

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
     public function daftarHasilKuis(Request $request)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $allQuestions = Kuis::orderBy('id', 'asc')->get();
    $query = HasilKuis::with(['siswa', 'kuis']);
    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [
            \Carbon\Carbon::parse($startDate)->startOfDay(),
            \Carbon\Carbon::parse($endDate)->endOfDay()
        ]);
    }
    $allResults = $query->get();

    $resultsBySiswa = $allResults->groupBy('siswa_id');
    $groupedResults = [];

    foreach ($resultsBySiswa as $siswaId => $results) {
        $siswa = $results->first()->siswa;
        if (!$siswa) continue;

        // --- Ambil SEMUA percobaan per soal untuk RIWAYAT (bukan cuma percobaan terakhir) ---
        $allAttempts = $results->groupBy('kuis_id')->map(function ($attempts) {
            return $attempts->sortBy('created_at')->values()->all(); // riwayat urut dari lama ke baru
        });

        $answers = [];
        $totalWaktu = 0;
        $tanggalPengerjaan = $results->max('created_at');
        $totalNilai = 0;

        foreach ($allAttempts as $kuis_id => $jawabanList) {
            $answers[$kuis_id] = [];
            foreach ($jawabanList as $ans) {
                $answers[$kuis_id][] = [
                    'jawaban'    => $ans->jawaban_user,
                    'benar'      => $ans->benar,
                    'created_at' => $ans->created_at,
                    'nilai'      => $ans->nilai,
                ];
                $totalWaktu += $ans->waktu;
                $totalNilai += $ans->nilai;
            }
        }

        $groupedResults[] = [
            'siswa_id' => $siswaId,
            'siswa_nama' => $siswa->nama,
            'siswa_nisn' => $siswa->nisn ?? 'N/A',
            'answers' => $answers, // <-- SEMUA RIWAYAT, array of array
            'total_nilai' => $totalNilai,
            'total_waktu' => $totalWaktu,
            'tanggal_pengerjaan' => $tanggalPengerjaan,
        ];
    }

    // Urutkan hasil (opsional, bisa menyesuaikan ranking, dst)
    usort($groupedResults, function ($a, $b) {
        if ($a['total_nilai'] === $b['total_nilai']) {
            return $a['total_waktu'] <=> $b['total_waktu'];
        }
        return $b['total_nilai'] <=> $a['total_nilai'];
    });

    return view('hasilkuis.index', [
        'groupedResults' => $groupedResults,
        'allQuestions' => $allQuestions,
        'startDate' => $startDate,
        'endDate' => $endDate,
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
   public function downloadPDF(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = HasilKuis::with(['siswa', 'kuis']);

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    $allResults = $query->get();

    $resultsBySiswa = $allResults->groupBy('siswa_id');

    $groupedResults = [];

    foreach ($resultsBySiswa as $siswaId => $results) {
        $siswa = $results->first()->siswa;
        if (!$siswa) continue;

        // Ambil waktu pengerjaan terbaru per siswa
        $latestCreatedAt = $results->max('created_at');
        $latestResults = $results->where('created_at', $latestCreatedAt);

        $totalNilai = $latestResults->sum('nilai');
        $totalWaktu = $latestResults->sum('waktu');

        $groupedResults[] = [
            'siswa_nama' => $siswa->nama,
            'siswa_nisn' => $siswa->nisn ?? 'N/A',
            'total_nilai' => $totalNilai,
            'total_waktu' => $totalWaktu,
        ];
    }

    // Urutkan hasil (nilai desc, waktu asc)
    usort($groupedResults, function ($a, $b) {
        if ($a['total_nilai'] === $b['total_nilai']) {
            return $a['total_waktu'] <=> $b['total_waktu'];
        }
        return $b['total_nilai'] <=> $a['total_nilai'];
    });

    $data = [
        'results' => $groupedResults,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
    ];

    $fileName = 'rekap-hasil-kuis-' . date('Y-m-d') . '.pdf';

    $pdf = PDF::loadView('hasilkuis.pdf_template', $data);

    return $pdf->download($fileName);
}


    public function historyHasilKuis(Request $request)
    {
         $request->validate([
        'start_date' => 'nullable|date',
        'end_date'   => 'nullable|date|after_or_equal:start_date',
    ]);

    $startDate = $request->input('start_date');
    $endDate   = $request->input('end_date');

    $query = HasilKuis::with('siswa')
        ->orderBy('created_at', 'asc');

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);
    }

    $allResults = $query->get();

    // Group hasil kuis per siswa dan per sesi berdasarkan waktu pengerjaan per menit
    $sessions = [];

    foreach ($allResults as $result) {
        $siswaId = $result->siswa_id;
        $sessionKey = $siswaId . '_' . $result->created_at->format('YmdHi'); // grup per menit

        if (!isset($sessions[$sessionKey])) {
            $sessions[$sessionKey] = [
                'siswa_id' => $siswaId,
                'nama' => $result->siswa->nama ?? 'N/A',
                'nisn' => $result->siswa->nisn ?? 'N/A',
                'total_nilai' => 0,
                'tanggal_pengerjaan' => $result->created_at,
                'hasil_ids' => [],
            ];
        }

        $sessions[$sessionKey]['total_nilai'] += $result->nilai;
        if ($result->created_at > $sessions[$sessionKey]['tanggal_pengerjaan']) {
            $sessions[$sessionKey]['tanggal_pengerjaan'] = $result->created_at;
        }
        $sessions[$sessionKey]['hasil_ids'][] = $result->id; // kumpulan id hasil untuk hapus nanti
    }

    // Reset array keys supaya numerik urut
    $groupedResults = array_values($sessions);

    return view('histori.index', [
        'groupedResults' => $groupedResults,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ]);
    }

    // Hapus satu histori hasil kuis berdasarkan id hasil_kuis
   public function destroyBatch(Request $request)
{
    $request->validate([
        'hasil_ids' => 'required|array',
        'hasil_ids.*' => 'integer|exists:hasil_kuis,id',
    ]);

    try {
        HasilKuis::whereIn('id', $request->hasil_ids)->delete();
        return response()->json(['success' => 'Sesi hasil kuis berhasil dihapus.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
    }
}

public function destroyAll()
{
    try {
        HasilKuis::query()->delete();
        return response()->json(['success' => 'Semua history hasil kuis berhasil dihapus.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Terjadi kesalahan saat menghapus data.'], 500);
    }
}

    // Download PDF histori hasil kuis (optional

   public function daftarHasilKuisTerbaru(Request $request)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $allQuestions = Kuis::orderBy('id', 'asc')->get();

    $query = HasilKuis::with(['siswa', 'kuis']);

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    $allResults = $query->get();

    // Group hasil kuis berdasarkan siswa_id
    $resultsBySiswa = $allResults->groupBy('siswa_id');

    $groupedResults = [];

    foreach ($resultsBySiswa as $siswaId => $results) {
        $siswa = $results->first()->siswa;
        if (!$siswa) continue;

        // Cari attempt_id terbaru (berdasarkan created_at maksimal)
        $latestAttemptId = $results->sortByDesc('created_at')->first()->attempt_id;

        // Ambil semua hasil untuk attempt_id terbaru saja
        $latestAttemptResults = $results->where('attempt_id', $latestAttemptId);

        $answers = [];
        $totalWaktu = 0;
        $totalNilai = 0;
        $tanggalPengerjaan = $latestAttemptResults->max('created_at');

        foreach ($latestAttemptResults as $ans) {
            $answers[$ans->kuis_id][] = [
                'jawaban' => $ans->jawaban_user,
                'benar' => $ans->benar,
                'created_at' => $ans->created_at,
                'nilai' => $ans->nilai,
            ];
            $totalWaktu += $ans->waktu;
            $totalNilai += $ans->nilai;
        }

        $groupedResults[] = [
            'siswa_id' => $siswaId,
            'siswa_nama' => $siswa->nama,
            'siswa_nisn' => $siswa->nisn ?? 'N/A',
            'answers' => $answers,
            'total_nilai' => $totalNilai,
            'total_waktu' => $totalWaktu,
            'tanggal_pengerjaan' => $tanggalPengerjaan,
        ];
    }

    // Urutkan hasil (nilai desc, waktu asc)
    usort($groupedResults, function ($a, $b) {
        if ($a['total_nilai'] === $b['total_nilai']) {
            return $a['total_waktu'] <=> $b['total_waktu'];
        }
        return $b['total_nilai'] <=> $a['total_nilai'];
    });

    return view('hasilkuis.index', [
        'groupedResults' => $groupedResults,
        'allQuestions' => $allQuestions,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ]);
}

public function downloadHistoryPDF(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate   = $request->input('end_date');

    $query = HasilKuis::with(['siswa', 'kuis'])->orderBy('created_at', 'desc');

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);
    }

    $hasilKuis = $query->get();

    // Proses grouping per sesi (per menit) seperti di daftarHasilKuisTerbaru
    $sessions = [];
    foreach ($hasilKuis as $result) {
        $siswaId = $result->siswa_id;
        $sessionKey = $siswaId . '_' . $result->created_at->format('YmdHi'); // grup per menit

        if (!isset($sessions[$sessionKey])) {
            $sessions[$sessionKey] = [
                'siswa_id' => $siswaId,
                'nama' => $result->siswa->nama ?? 'N/A',
                'nisn' => $result->siswa->nisn ?? 'N/A',
                'total_nilai' => 0,
                'tanggal_pengerjaan' => $result->created_at,
                'hasil_ids' => [],
            ];
        }

        $sessions[$sessionKey]['total_nilai'] += $result->nilai;
        if ($result->created_at > $sessions[$sessionKey]['tanggal_pengerjaan']) {
            $sessions[$sessionKey]['tanggal_pengerjaan'] = $result->created_at;
        }
        $sessions[$sessionKey]['hasil_ids'][] = $result->id;
    }

    $groupedResults = array_values($sessions);

    $data = [
        'groupedResults' => $groupedResults,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'tanggalCetak' => Carbon::now()->translatedFormat('d F Y'),
    ];

    $pdf = PDF::loadView('histori.pdf', $data);

    return $pdf->download('history-hasil-kuis-' . date('Y-m-d') . '.pdf');
}


}
