@extends('admin.layout')

@section('title', 'Edit Materi')

@section('content')
<div class="container-fluid">
    <form action="{{ route('updatemateri', $materi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Kolom utama untuk Judul dan Konten --}}
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Konten Materi</h3>
                    </div>
                    <div class="card-body">

                        {{-- Notifikasi Error Profesional --}}
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

                        <div class="form-group">
                            <label for="judul">Judul Materi</label>
                            <input type="text" class="form-control form-control-lg" id="judul" name="judul" value="{{ old('judul', $materi->judul) }}" placeholder="Masukkan Judul Materi" required>
                        </div>

                        <div class="form-group">
                            <label for="konten">Isi Konten</label>
                            <textarea class="form-control" id="konten" name="konten" rows="18" placeholder="Tulis isi materi di sini..." required>{{ old('konten', $materi->konten) }}</textarea>
                            <small class="form-text text-muted">Anda bisa menggunakan baris baru untuk membuat paragraf.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom samping untuk Media dan Tombol Aksi --}}
            <div class="col-md-4">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Media Utama</h3>
                    </div>
                    <div class="card-body">
                        {{-- Input untuk Link YouTube --}}
                        <div class="form-group">
                            <label for="link_yt">Link Video YouTube (Opsional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                </div>
                                <input type="url" class="form-control" id="link_yt" name="link_yt" value="{{ old('link_yt', $materi->link_yt) }}" placeholder="Contoh: https://youtube.com/watch?v=...">
                            </div>
                            <small class="form-text text-muted">Akan diprioritaskan di atas gambar.</small>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Gambar (Digunakan jika tidak ada video)</label><br>
                            @if ($materi->gambar)
                                <a href="{{ asset('storage/' . $materi->gambar) }}" target="_blank" title="Lihat gambar penuh">
                                    <img src="{{ asset('storage/' . $materi->gambar) }}" alt="Gambar Materi" class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
                                </a>
                            @else
                                <p class="text-muted">Tidak ada gambar.</p>
                            @endif

                            <label for="gambar" class="mt-2">Ubah Gambar (Opsional)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                <label class="custom-file-label" for="gambar">Pilih file baru...</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('materi') }}" class="btn btn-secondary btn-block mb-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Update Materi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk menampilkan nama file di input file bootstrap
    $(function () {
        bsCustomFileInput.init();
    });
</script>
@endpush
