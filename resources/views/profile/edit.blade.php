@extends('admin.layout')

@section('title', 'Edit Profile')

<style>
    /* Impor Font (jika belum ada di layout utama) */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Keyframes untuk animasi */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Mengganti font dasar dan background body */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .profile-card {
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: none;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .profile-card .card-header {
        background: #ffffff;
        color: #212529;
        border-bottom: 1px solid #e9ecef;
        padding: 2rem;
        text-align: left;
    }

    .profile-card .card-title {
        font-weight: 600;
        font-size: 1.6rem;
        color: #343a40;
    }

    .profile-card .card-title .user-name {
        font-weight: 400;
        color: #007bff;
    }

    .form-group {
        position: relative;
    }

    .form-group label {
        font-weight: 500;
        color: #555;
        margin-bottom: 0.5rem;
    }

    .form-group .form-control {
        border-radius: 8px;
        padding-left: 2.75rem;
        height: 50px; /* Tinggi input yang konsisten */
        border: 1px solid #ced4da;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-group .form-icon {
        position: absolute;
        left: 1rem;
        top: 68%; /* Sesuaikan posisi vertikal ikon */
        transform: translateY(-50%);
        color: #adb5bd;
        transition: color 0.2s;
    }

    .form-group .form-control:focus ~ .form-icon {
        color: #007bff;
    }

    .password-section {
        border-top: 1px solid #e9ecef;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
    }

    .password-section-title {
        font-weight: 600;
        color: #495057;
    }

    .card-footer {
        background-color: #ffffff;
        border-top: 1px solid #e9ecef;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: flex-end; /* Pindahkan tombol ke kanan */
        align-items: center;
    }

    .btn {
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(0, 123, 255, 0.25);
    }

    .btn-secondary {
        background-color: #e9ecef;
        color: #212529;
        margin-right: 1rem; /* Beri jarak antara tombol */
    }

    .btn-secondary:hover {
        background-color: #d8dde2;
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.08);
    }
</style>


@section('content')
{{-- Perhatikan perubahan pada baris <div class="row"> dan <div class="col-.."> --}}
<div class="container-fluid">
  <div class="row">
    <div class="col-12"> {{-- Ini akan membuatnya full-width --}}
      <div class="card profile-card">
        {{-- <div class="card-header">
          <h3 class="card-title">Edit Profile</span></h3>
        </div> --}}
        <form action="{{ route('profile.update') }}" method="POST">
          @csrf
          @method('PUT')

          <div class="card-body p-4">

            {{-- Pesan Notifikasi (Success & Error) tetap di sini --}}
            @if(session('success'))
              <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <div>
                  {{ session('success') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger" role="alert">
                <div class="d-flex">
                    <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                    <div>
                        <h6 class="alert-heading"><strong>Gagal!</strong> Mohon periksa kembali isian Anda.</h6>
                        <ul class="mb-0 pl-3">
                          @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            @endif

            {{-- Bagian Form --}}
            <div class="form-group mb-4">
              <label for="name">Nama Lengkap</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
              <i class="fas fa-user form-icon"></i>
            </div>

            <div class="form-group mb-4">
              <label for="email">Alamat Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
              <i class="fas fa-envelope form-icon"></i>
            </div>

            <div class="password-section">
              <p class="password-section-title">Ubah Password (Opsional)</p>
              <small class="form-text text-muted mb-3 d-block">Kosongkan jika tidak ingin mengubah password.</small>

              <div class="form-group mb-4">
                <label for="password">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                <i class="fas fa-key form-icon"></i>
              </div>

              <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                <i class="fas fa-key form-icon"></i>
              </div>
            </div>

          </div>

          <div class="card-footer">
            <a href="{{ route('dashboardadmin') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-2"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
