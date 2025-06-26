@extends('admin.layout')

{{-- Menambahkan section khusus untuk custom CSS --}}

<style>
    /*
    |--------------------------------------------------------------------------
    | Custom Dashboard Styles
    |--------------------------------------------------------------------------
    |
    | Gaya kustom untuk memodernisasi tampilan kartu statistik (small-box)
    |
    */

    /* Menghilangkan garis bawah dari link pada kartu */
    a.custom-card-link, a.custom-card-link:hover {
        text-decoration: none;
        color: white;
    }

    .small-box {
        border-radius: 15px; /* Membuat sudut lebih membulat */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden; /* Penting untuk menjaga sudut membulat */
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .small-box:hover {
        transform: translateY(-5px); /* Efek mengangkat saat di-hover */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .small-box .inner {
        padding: 20px;
    }

    .small-box h3 {
        font-size: 2.5rem; /* Ukuran angka lebih besar */
        font-weight: 700;
        margin-bottom: 5px;
    }

    .small-box p {
        font-size: 1.1rem; /* Ukuran teks deskripsi sedikit lebih besar */
    }

    .small-box .icon {
        top: 15px;
        right: 15px;
        font-size: 70px; /* Ukuran ikon lebih besar */
        opacity: 0.2; /* Ikon lebih transparan agar tidak terlalu mendominasi */
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .small-box:hover .icon {
        transform: scale(1.1); /* Ikon membesar saat di-hover */
        opacity: 0.3;
    }

    /* Definisi Gradien Warna Kustom */
    .bg-custom-siswa {
        background: linear-gradient(135deg, #17a2b8, #1f4e57); /* Info Gradient */
    }
    .bg-custom-materi {
        background: linear-gradient(135deg, #28a745, #1c5a2c); /* Success Gradient */
    }
    .bg-custom-quiz {
        background: linear-gradient(135deg, #007bff, #00448f); /* Primary Gradient */
    }

</style>


@section('content')
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><div class="col-sm-6">
            {{-- Breadcrumb yang lebih informatif --}}
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div></div></div></div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          {{-- Kartu Jumlah Siswa --}}
          <div class="col-lg-4 col-md-6 col-12">
            <a href="{{ route('dataSiswa') }}" class="custom-card-link">
              <div class="small-box bg-custom-siswa">
                <div class="inner">
                  <h3>{{ $jumlahSiswa }}</h3>
                  <p>Jumlah Siswa</p>
                </div>
                <div class="icon">
                  {{-- Ikon yang lebih relevan untuk siswa --}}
                  <i class="fas fa-user-graduate"></i>
                </div>
              </div>
            </a>
          </div>

          {{-- Kartu Jumlah Materi --}}
          <div class="col-lg-4 col-md-6 col-12">
            <a href="{{ route('kuis') }}" class="custom-card-link"> {{-- Asumsi route ini benar --}}
              <div class="small-box bg-custom-materi">
                <div class="inner">
                  <h3>{{ $jumlahMateri }}</h3>
                  <p>Jumlah Materi</p>
                </div>
                <div class="icon">
                  {{-- Ikon yang lebih relevan untuk materi/buku --}}
                  <i class="fas fa-book-open"></i>
                </div>
              </div>
            </a>
          </div>

          {{-- Kartu Jumlah Quiz --}}
          <div class="col-lg-4 col-md-6 col-12">
            <a href="{{ route('kuis') }}" class="custom-card-link"> {{-- Asumsi route ini benar --}}
              <div class="small-box bg-custom-quiz">
                <div class="inner">
                  <h3>{{ $jumlahQuiz }}</h3>
                  <p>Jumlah Quiz</p>
                </div>
                <div class="icon">
                  {{-- Ikon yang lebih relevan untuk kuis/pertanyaan --}}
                  <i class="fas fa-clipboard-list"></i>
                </div>
              </div>
            </a>
          </div>

        </div>
        <div class="row">
          {{-- Konten lainnya bisa ditambahkan di sini --}}
        </div>
        </div></section>
    </div>
@endsection
