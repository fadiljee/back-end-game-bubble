@extends('admin.layout')

@section('title', 'Edit Kuis')

@section('content')
{{-- Menghapus .content-wrapper karena sudah ada di layout utama --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('updatekuis', $kuis->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Metode yang benar untuk update --}}

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@yield('title'): {{ Str::limit($kuis->pertanyaan, 30) }}</h3>
                    </div>
                    <div class="card-body">

                        {{-- Notifikasi Error yang Profesional --}}
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-triangle"></i> Gagal Validasi!</strong>
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
                            <label for="pertanyaan">Pertanyaan</label>
                            <textarea class="form-control" name="pertanyaan" rows="3" placeholder="Masukkan pertanyaan kuis..." required>{{ old('pertanyaan', $kuis->pertanyaan) }}</textarea>
                        </div>

                        <hr>
                        <p class="font-weight-bold">Pilihan Jawaban</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jawaban_a">Jawaban A</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><b>A</b></span>
                                        </div>
                                        <input type="text" class="form-control" name="jawaban_a" value="{{ old('jawaban_a', $kuis->jawaban_a) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jawaban_b">Jawaban B</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><b>B</b></span>
                                        </div>
                                        <input type="text" class="form-control" name="jawaban_b" value="{{ old('jawaban_b', $kuis->jawaban_b) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jawaban_c">Jawaban C</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><b>C</b></span>
                                        </div>
                                        <input type="text" class="form-control" name="jawaban_c" value="{{ old('jawaban_c', $kuis->jawaban_c) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jawaban_d">Jawaban D</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><b>D</b></span>
                                        </div>
                                        <input type="text" class="form-control" name="jawaban_d" value="{{ old('jawaban_d', $kuis->jawaban_d) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <p class="font-weight-bold">Konfigurasi Soal</p>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jawaban_benar">Kunci Jawaban</label>
                                    <select class="form-control select2" name="jawaban_benar" required>
                                        {{-- old('jawaban_benar', $kuis->jawaban_benar) akan memilih nilai lama jika ada error validasi, jika tidak, akan memilih nilai dari database --}}
                                        <option value="A" @if(old('jawaban_benar', $kuis->jawaban_benar) == 'A') selected @endif>Jawaban A</option>
                                        <option value="B" @if(old('jawaban_benar', $kuis->jawaban_benar) == 'B') selected @endif>Jawaban B</option>
                                        <option value="C" @if(old('jawaban_benar', $kuis->jawaban_benar) == 'C') selected @endif>Jawaban C</option>
                                        <option value="D" @if(old('jawaban_benar', $kuis->jawaban_benar) == 'D') selected @endif>Jawaban D</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nilai">Nilai Soal (Poin)</label>
                                    <input type="number" class="form-control" name="nilai" value="{{ old('nilai', $kuis->nilai) }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="waktu_pengerjaan">Waktu Pengerjaan (detik)</label>
                                    <input type="number" class="form-control" name="waktu_pengerjaan" value="{{ old('waktu_pengerjaan', $kuis->waktu_pengerjaan) }}" min="10" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Kuis
                        </button>
                        <a href="{{ route('kuis') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>
@endsection
