@extends('admin.layout')

@section('title', 'History Hasil Kuis Per Sesi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-tools">
                        @if(!empty($groupedResults))
                        <button id="btn-delete-all" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Hapus Semua History
                        </button>
                        @endif
                    </div>
                </div>

                <div class="card-body border-bottom">
                    <h5 class="card-title font-weight-bold mb-3">Filter Data</h5>
                    <form method="GET" action="{{ route('hasilkuis.history') }}" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="start_date" class="mr-2">Dari Tanggal:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('hasilkuis.history') }}" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-sync-alt"></i> Reset</a>
                        <a href="{{ route('hasilkuis.history.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
   target="_blank"
   class="btn btn-success mb-2 ml-2">
    <i class="fas fa-file-pdf"></i> Export PDF
</a>

                    </form>
                </div>

                <div class="card-body">
                    @if(empty($groupedResults))
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-4x text-muted mb-3"></i>
                            <h4 class="font-weight-bold">
                                @if($startDate && $endDate)
                                    Tidak Ada Data History Kuis pada Rentang Tanggal yang Dipilih
                                @else
                                    Belum Ada History Hasil Kuis
                                @endif
                            </h4>
                            <p class="text-muted">Data akan muncul setelah siswa mengerjakan kuis.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="history-sesi-table" class="table table-bordered table-hover text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Siswa</th>
                                        <th>NISN</th>
                                        <th>Total Nilai</th>
                                        <th>Tanggal Pengerjaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupedResults as $index => $session)
                                    <tr id="row-session-{{ $session['siswa_id'] }}-{{ $index }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $session['nama'] }}</td>
                                        <td>{{ $session['nisn'] }}</td>
                                        <td>{{ $session['total_nilai'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session['tanggal_pengerjaan'])->format('d M Y, H:i') }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-hasil-ids="{{ implode(',', $session['hasil_ids']) }}"
                                                data-nama="{{ $session['nama'] }}">
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
    var table = $('#history-sesi-table').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: false,
        buttons: ["copy", "csv", "excel", "pdf", "print"]
    });
    table.buttons().container().appendTo('#history-sesi-table_wrapper .col-md-6:eq(0)');

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    // Hapus semua hasil dari satu sesi (per tombol delete per baris)
    $('#history-sesi-table tbody').on('click', '.btn-delete', function () {
        var hasilIds = $(this).data('hasil-ids').toString().split(',');
        var nama = $(this).data('nama');

        Swal.fire({
            title: 'Anda Yakin?',
            text: `Semua hasil kuis milik "${nama}" di sesi ini akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('hasilkuis.history.destroyBatch') }}",
                    method: "POST",
                    data: {hasil_ids: hasilIds},
                    success: function(response) {
                        Swal.fire('Dihapus!', response.success, 'success');
                        // Hapus baris dari DataTables
                        table
                            .row($(this).parents('tr'))
                            .remove()
                            .draw(false);
                    }.bind(this),
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    });

    // Hapus semua history (semua sesi)
    $('#btn-delete-all').on('click', function() {
        Swal.fire({
            title: 'Anda Yakin?',
            text: "Semua history hasil kuis akan dihapus permanen!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('hasilkuis.history.destroyAll') }}",
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('Berhasil!', response.success, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus semua data.', 'error');
                    }
                });
            }
        });
    });
});

</script>
@endpush
