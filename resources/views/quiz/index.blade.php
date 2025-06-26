@extends('admin.layout')

@section('title', 'Manajemen Kuis')

@section('content')
{{-- Hapus div.content-wrapper dari sini karena sudah ada di layout utama --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-tools">
                        <a href="{{ route('tambahkuis') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kuis
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Notifikasi/Alert yang lebih baik --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Tabel yang sudah dioptimalkan --}}
                    {{-- Kita akan menyembunyikan kolom jawaban A-D di tampilan utama untuk menghemat ruang --}}
                    <table id="kuis-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Pertanyaan</th>
                                <th class="text-center">Jawaban Benar</th>
                                <th class="text-center">Waktu (detik)</th>
                                <th class="text-center">Nilai Soal</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>

                                {{-- Kolom ini akan disembunyikan dan hanya muncul saat dibutuhkan --}}
                                <th class="d-none">Jawaban A</th>
                                <th class="d-none">Jawaban B</th>
                                <th class="d-none">Jawaban C</th>
                                <th class="d-none">Jawaban D</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kuis as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $item->pertanyaan }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success" style="font-size: 0.9rem;">{{ $item->jawaban_benar }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item->waktu_pengerjaan }} detik</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-purple">{{ $item->nilai }} Poin</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('kuisdelete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?');">
                                        <a href="{{ route('kuisedit', $item->id) }}" class="btn btn-warning btn-xs" title="Edit Kuis">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Hapus Kuis">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                                {{-- Data untuk kolom tersembunyi --}}
                                <td class="d-none">{{ $item->jawaban_a }}</td>
                                <td class="d-none">{{ $item->jawaban_b }}</td>
                                <td class="d-none">{{ $item->jawaban_c }}</td>
                                <td class="d-none">{{ $item->jawaban_d }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi DataTables dengan fitur responsif
    $(function () {
        $('#kuis-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            // Mengatur kolom mana yang selalu terlihat dan mana yang bisa disembunyikan
            "columnDefs": [
                { "responsivePriority": 1, "targets": 1 }, // Pertanyaan
                { "responsivePriority": 2, "targets": 5 }, // Aksi
                { "responsivePriority": 3, "targets": 2 }, // Jawaban Benar
                // Kolom Jawaban A-D akan otomatis disembunyikan jika tidak cukup ruang
            ]
        }).buttons().container().appendTo('#kuis-table_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
