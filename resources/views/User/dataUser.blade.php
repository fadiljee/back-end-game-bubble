@extends('admin.layout')

@section('title', 'Manajemen Data Siswa')

@section('content')
{{-- Menghapus div.content-wrapper dan section.content yang tidak perlu karena sudah ada di layout --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                    <div class="card-tools">
                        {{-- Tombol Tambah dan Form Pencarian dipindah ke sini agar lebih rapi --}}
                        <a href="{{ route('tambahSiswa')}}" class="btn btn-success btn-sm"><i class="fas fa-user-plus"></i> Tambah Siswa</a>
                        {{-- Fitur pencarian bisa ditambahkan di sini jika perlu --}}
                    </div>
                </div>
                <div class="card-body">
                    {{-- Notifikasi/Alert yang lebih baik dan konsisten --}}
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

                    {{-- ID tabel diubah untuk inisialisasi DataTables yang spesifik --}}
                    <table id="siswa-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Menggunakan @forelse untuk menangani kasus data kosong dengan lebih elegan --}}
                            @forelse ($users as $user)
                                <tr>
                                    {{-- Menggunakan $loop->iteration untuk penomoran otomatis --}}
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->nisn }}</td>
                                    <td class="text-center">
                                        {{-- Tombol aksi menggunakan ikon agar lebih ringkas dan modern --}}
                                        <form action="{{ route('userdelete', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">

                                            {{-- Tombol Detail/View bisa ditambahkan di sini --}}
                                            {{-- <a href="#" class="btn btn-info btn-xs" title="Lihat Detail"><i class="fas fa-eye"></i></a> --}}

                                            <a href="{{ route('useredit', $user->id) }}" class="btn btn-warning btn-xs" title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                        <p>Belum ada data siswa.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Menambahkan card-footer untuk paginasi jika diperlukan --}}
                {{-- @if ($users->hasPages())
                    <div class="card-footer clearfix">
                        <div class="float-right">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif --}}
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Inisialisasi DataTables untuk memberikan fitur-fitur canggih
    $(function () {
      $("#siswa-table").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#siswa-table_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
