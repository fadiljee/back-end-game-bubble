@extends('admin.layout')

@section('title', 'Tambah Materi Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                {{-- Menggunakan struktur form yang lebih rapi --}}
                <form action="{{ route('prosestambah') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        {{-- Menampilkan notifikasi error yang lebih baik --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h5 class="font-weight-bold">Gagal Menyimpan!</h5>
                                <ul class="mb-0 pl-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="judul">Judul Materi</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul materi" required>
                        </div>

                        <div class="form-group">
                            <label for="konten">Konten Materi</label>
                            <textarea class="form-control" id="konten" name="konten" rows="5" placeholder="Masukkan isi konten materi" required>{{ old('konten') }}</textarea>
                        </div>

                        <hr>
                        <p class="text-muted">Pilih salah satu media di bawah ini (opsional):</p>

                        {{-- Input untuk Link YouTube --}}
                        <div class="form-group">
                            <label for="link_yt">Link Video YouTube</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                </div>
                                <input type="url" class="form-control" id="link_yt" name="link_yt" value="{{ old('link_yt') }}" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx">
                            </div>
                            <small class="form-text text-muted">Jika diisi, video ini akan ditampilkan sebagai media utama.</small>
                        </div>

                        {{-- Input untuk Gambar --}}
                        <div class="form-group">
                            <label for="gambar">Atau Unggah Gambar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                <label class="custom-file-label" for="gambar">Pilih file gambar...</label>
                            </div>
                             <small class="form-text text-muted">Akan digunakan jika tidak ada link YouTube yang dimasukkan.</small>
                        </div>

                    </div>
                    <div class="card-footer">
                        <a href="{{ route('materi') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fas fa-save mr-1"></i> Simpan Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk menampilkan nama file di input file bootstrap
    $(function () {
        bsCustomFileInput.init();
    });
</script>
{{-- Pastikan Anda memuat bs-custom-file-input.min.js di layout utama jika belum ada --}}
{{-- Contoh: <script src="{{ asset('template/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script> --}}
@endpush
