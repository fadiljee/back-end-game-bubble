@extends('admin.layout')

@section('title', 'Manajemen Materi')

@push('styles')
{{-- Style tambahan untuk Lightbox (popup gambar) --}}
<link rel="stylesheet" href="{{ asset('template/plugins/ekko-lightbox/ekko-lightbox.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-tools">
                        <a href="{{ route('tambahMateri') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah Materi</a>
                    </div>
                </div>
                <div class="card-body">
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
                            <ul class="mb-0 pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <table id="materi-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                {{-- PERUBAHAN: Mengganti 'Gambar' menjadi 'Media' --}}
                                <th style="width: 15%;">Media</th>
                                <th>Judul</th>
                                <th>Isi Konten (Ringkasan)</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materis as $materi)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-center align-middle">
                                        {{-- PERUBAHAN: Logika untuk menampilkan Video atau Gambar --}}
                                        @if ($materi->link_yt)
                                            <a href="{{ $materi->link_yt }}" target="_blank" class="text-danger" title="Lihat Video YouTube: {{ $materi->judul }}">
                                                <i class="fab fa-youtube fa-3x"></i>
                                                <p class="text-xs mb-0 font-weight-bold">Video</p>
                                            </a>
                                        @elseif ($materi->gambar)
                                            <a href="{{ asset('storage/' . $materi->gambar) }}" data-toggle="lightbox" data-title="{{ $materi->judul }}">
                                                <img src="{{ asset('storage/' . $materi->gambar) }}" alt="Gambar Materi" class="img-fluid rounded" style="max-height: 75px; object-fit: cover;">
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada media</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold align-middle">{{ $materi->judul }}</td>
                                    <td class="align-middle">
                                        {{ \Str::limit(strip_tags($materi->konten), 80) }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{-- PERUBAHAN: Menghapus onsubmit dari form --}}
                                        <form action="{{ route('materidelete', $materi->id) }}" method="POST" class="form-delete d-inline">
                                            <a href="{{ route('materiedit', $materi->id) }}" class="btn btn-warning btn-xs" title="Edit Materi">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            {{-- PERUBAHAN: Menjadikan tombol sebagai submit biasa, dikontrol oleh JS --}}
                                            <button type="submit" class="btn btn-danger btn-xs" title="Hapus Materi">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                                        <p>Belum ada data materi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library tambahan untuk Lightbox (popup gambar) --}}
<script src="{{ asset('template/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<script>
    $(function () {
        // Inisialisasi DataTables
        $("#materi-table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"],
            "columnDefs": [
                { "orderable": false, "targets": [1, 4] } // Menonaktifkan sorting untuk kolom media dan aksi
            ]
        }).buttons().container().appendTo('#materi-table_wrapper .col-md-6:eq(0)');

        // Inisialisasi Lightbox untuk popup gambar
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });

        // === PERUBAHAN: Logika Hapus dengan SweetAlert2 ===
        // Pastikan SweetAlert2 sudah dimuat di layout utama Anda (admin.layout.blade.php)
        $('.form-delete').on('submit', function(e) {
            e.preventDefault(); // Mencegah form untuk submit secara default
            var form = this;
            var judulMateri = $(this).closest('tr').find('td:eq(2)').text(); // Ambil judul dari kolom ke-3

            Swal.fire({
                title: 'Anda Yakin?',
                text: `Materi "${judulMateri}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika dikonfirmasi, lanjutkan submit form
                }
            });
        });
    });
</script>
@endpush
