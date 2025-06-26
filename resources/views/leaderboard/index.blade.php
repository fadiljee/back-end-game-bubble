@extends('admin.layout')

@section('title', 'Peringkat Siswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-tools">
                        {{-- Tombol aksi seperti Export bisa muncul di sini dari DataTables --}}
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($leaderboard) && $leaderboard->count() > 0)
                        <table id="leaderboard-table" class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 10%;" class="text-center">Ranking</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Total Nilai</th>
                                    <th class="text-center">Total Waktu (detik)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaderboard as $item)
                                    <tr>
                                        <td class="text-center align-middle">
                                            {{-- Memberi badge khusus untuk peringkat 1, 2, dan 3 --}}
                                            @if($loop->iteration == 1)
                                                <span class="badge" style="background-color: #FFD700; color: #333; font-size: 1rem; width: 40px; padding: 8px 0;">
                                                    <i class="fas fa-trophy"></i> 1
                                                </span>
                                            @elseif($loop->iteration == 2)
                                                <span class="badge" style="background-color: #C0C0C0; color: #fff; font-size: 0.9rem; width: 35px; padding: 6px 0;">
                                                    <i class="fas fa-medal"></i> 2
                                                </span>
                                            @elseif($loop->iteration == 3)
                                                <span class="badge" style="background-color: #CD7F32; color: #fff; font-size: 0.9rem; width: 35px; padding: 6px 0;">
                                                    <i class="fas fa-medal"></i> 3
                                                </span>
                                            @else
                                                <span class="badge bg-dark" style="font-size: 0.9rem;">{{ $loop->iteration }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->siswa->nama ?? 'S') }}&background=random" alt="Avatar" class="img-circle mr-3" width="40">
                                                <span class="font-weight-bold">{{ $item->siswa->nama ?? 'Data Siswa Tidak Ditemukan' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle" style="font-size: 1.2rem; font-weight: 600;">
                                            {{ $item->total_nilai }}
                                        </td>
                                        <td class="text-center align-middle text-muted">
                                            {{ $item->total_waktu }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        {{-- Tampilan jika data leaderboard kosong --}}
                        <div class="text-center py-5">
                            <i class="fas fa-trophy fa-4x text-muted mb-3"></i>
                            <h4 class="font-weight-bold">Leaderboard Masih Kosong</h4>
                            <p class="text-muted">Data akan muncul di sini setelah siswa mengerjakan kuis.</p>
                        </div>
                    @endif
                </div>
                </div>
            </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi DataTables untuk memberikan fitur-fitur canggih pada tabel
    $(function () {
      $("#leaderboard-table").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        // Mengurutkan default berdasarkan kolom Nilai (indeks ke-2), dari tertinggi ke terendah
        "order": [[ 2, "desc" ]],
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#leaderboard-table_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
