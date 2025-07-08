@extends('admin.layout')

@section('title', 'Hasil Kuis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-tools">
                        {{-- PERBAIKAN 1: Gunakan !empty() untuk array --}}
                        @if(!empty($groupedResults))
                        <button id="btn-delete-all" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Hapus Semua Hasil
                        </button>
                        @endif
                    </div>
                </div>

                {{-- =================================== --}}
                {{-- == BAGIAN BARU: FORM FILTER TANGGAL == --}}
                {{-- =================================== --}}
                <div class="card-body border-bottom">
                    <h5 class="card-title font-weight-bold mb-3">Filter Data</h5>
                    <form method="GET" action="{{ route('hasilkuis.index') }}" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="start_date" class="mr-2">Dari Tanggal:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('hasilkuis.index') }}" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-sync-alt"></i> Reset</a>

                         @if(!empty($groupedResults))
            <a href="{{ route('hasilkuis.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success mb-2 ml-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        @endif
                    </form>
                </div>
                {{-- =================================== --}}
                {{-- == AKHIR FORM FILTER == --}}
                {{-- =================================== --}}

                <div class="card-body">
                    {{-- PERBAIKAN 2: Gunakan empty() untuk array --}}
                    @if(empty($groupedResults))
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
                            <h4 class="font-weight-bold">
                                @if($startDate && $endDate)
                                    Tidak Ada Hasil Kuis pada Rentang Tanggal yang Dipilih
                                @else
                                    Belum Ada Hasil Kuis
                                @endif
                            </h4>
                            <p class="text-muted">Data akan muncul di sini setelah siswa mengerjakan kuis.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="hasil-kuis-table" class="table table-bordered table-hover">
                                <thead class="text-center thead-light">
                                   <tr>
                                        <th rowspan="2" class="align-middle">Ranking</th>
                                        <th rowspan="2" class="align-middle">Nama Siswa</th>
                                        <th rowspan="2" class="align-middle">NISN</th>
                                        <th colspan="{{ $allQuestions->count() }}">Jawaban per Soal</th>
                                        <th rowspan="2" class="align-middle">Total Nilai</th>
                                        <th rowspan="2" class="align-middle">Total Waktu (detik)</th>
                                        <th rowspan="2" class="align-middle">Tanggal Pengerjaan</th>
                                        <th rowspan="2" class="align-middle">Aksi</th>
                                    </tr>
                                    <tr>
                                        @foreach ($allQuestions as $question)
                                            <th title="{{ $question->pertanyaan }}">{{ $loop->iteration }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedResults as $index => $result)
                                        <tr id="row-siswa-{{ $result['siswa_id'] }}">
                                            <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                            <td>{{ $result['siswa_nama'] }}</td>
                                            <td>{{ $result['siswa_nisn'] }}</td>

                                           @foreach ($allQuestions as $question)
    @php
        $jawabanList = $result['answers'][$question->id] ?? [];
    @endphp
    <td class="text-center">
        @if($jawabanList)
            @foreach($jawabanList as $ans)
                <span class="badge {{ $ans['benar'] ? 'bg-success' : 'bg-danger' }}"
                      title="Dijawab pada {{ \Carbon\Carbon::parse($ans['created_at'])->format('d-m H:i') }}">
                    {{ $ans['jawaban'] }}
                </span>
            @endforeach
        @else
            <span class="badge bg-secondary" title="Tidak Dijawab">-</span>
        @endif
    </td>
@endforeach


                                            <td class="text-center font-weight-bold bg-light">{{ $result['total_nilai'] }}</td>
                                            <td class="text-center text-muted">{{ $result['total_waktu'] }}</td>
                                            <td class="text-center">
                                                @if($result['tanggal_pengerjaan'])
                                                    {{ \Carbon\Carbon::parse($result['tanggal_pengerjaan'])->translatedFormat('d M Y, H:i') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $result['siswa_id'] }}" data-nama="{{ $result['siswa_nama'] }}" title="Hapus hasil kuis siswa ini">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $(function () {
        // Inisialisasi DataTables
        var table = $('#hasil-kuis-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#hasil-kuis-table_wrapper .col-md-6:eq(0)');

        // Setup CSRF Token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // AKSI HAPUS SATU DATA (EVENT DELEGATION)
        $('#hasil-kuis-table tbody').on('click', '.btn-delete', function () {
            var siswaId = $(this).data('id');
            var siswaNama = $(this).data('nama');
            var url = "{{ route('hasilkuis.destroy', ['siswa_id' => ':id']) }}".replace(':id', siswaId);

            Swal.fire({
                title: 'Anda Yakin?',
                text: `Hasil kuis milik "${siswaNama}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire(
                                'Dihapus!',
                                response.success,
                                'success'
                            );
                            // Hapus baris dari DataTable tanpa reload halaman
                            table.row('#row-siswa-' + siswaId).remove().draw(false);
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // AKSI HAPUS SEMUA DATA
        $('#btn-delete-all').on('click', function() {
            var url = "{{ route('hasilkuis.destroyAll') }}";

            Swal.fire({
                title: 'ANDA SANGAT YAKIN?',
                text: "Semua data hasil kuis akan dihapus dan tidak dapat dikembalikan!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Semuanya!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                response.success,
                                'success'
                            ).then(() => {
                                // Reload halaman untuk melihat tabel yang sudah kosong
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus semua data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
