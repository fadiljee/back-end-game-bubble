@extends('admin.layout')

@section('title', 'Tambah Data Siswa Baru')

@section('content')
{{-- Menghapus .content-wrapper dan .section yang tidak perlu --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <form action="{{ route('prosesregister') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        {{-- Notifikasi Error yang lebih profesional dan konsisten --}}
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle"></i> Gagal!</strong> Mohon periksa kembali isian Anda.
                                <ul class="mb-0 pl-4">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Menggunakan layout 2 kolom untuk kerapian --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap Siswa</label>
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" value="{{ old('nama') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nisn">NISN</label>
                                    <input type="number" class="form-control" id="nisn" name="nisn" placeholder="Masukkan NISN" value="{{ old('nisn') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                                    <small class="form-text text-muted">Password akan digunakan siswa untuk login.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ketik Ulang Password" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Siswa
                        </button>
                        <a href="{{ route('dataSiswa') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
            </div>
    </div>
</div>
@endsection
