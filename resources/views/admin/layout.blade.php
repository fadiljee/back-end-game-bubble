<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modern Dashboard</title>

  {{-- CSRF Token untuk AJAX --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{asset('template/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('template/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">

  {{-- CSS untuk DataTables (sudah benar) --}}
  <link rel="stylesheet" href="{{asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  {{-- CSS Tambahan untuk Tombol DataTables --}}
  <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


  <style>
    /* Menggunakan Font Poppins di seluruh body */
    body, .brand-text, .nav-link p, .user-panel .info, .form-control, .btn, .card-title, .table, h1, h2, h3, h4, h5, h6 {
      font-family: 'Poppins', sans-serif !important;
    }

    /* Membuat preloader lebih halus */
    .animation__pulse {
      animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
      0% { transform: scale(0.95); opacity: 0.8; }
      50% { transform: scale(1.05); opacity: 1; }
      100% { transform: scale(0.95); opacity: 0.8; }
    }

    /* Navbar dengan shadow halus untuk efek melayang */
    .main-header {
      border-bottom: 0 !important;
      box-shadow: 0 2px 8px rgba(0,0,0,.05) !important;
    }

    /* Sidebar dengan warna dan hover yang lebih menarik */
    .sidebar-dark-indigo .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-indigo .nav-sidebar>.nav-item>.nav-link.active {
      background-color: #5D3FD3; /* Warna ungu yang lebih vibrant */
      box-shadow: 0 2px 10px rgba(93, 63, 211, 0.5);
      border-radius: 0.5rem;
    }
    .nav-sidebar .nav-link {
        transition: all 0.2s ease-in-out;
    }
    .nav-sidebar .nav-item:hover > .nav-link {
        background-color: rgba(255, 255, 255, 0.08);
    }

    /* Sudut lebih rounded untuk card, button, dan input */
    .card, .btn, .form-control, .input-group-text, .brand-link, .info-box {
      border-radius: 0.5rem; /* Lebih rounded */
    }
    .card {
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
        border: none;
    }

    /* Tombol dengan efek hover */
    .btn {
        transition: all 0.3s ease;
    }
    .btn-primary {
        background-color: #5D3FD3;
        border-color: #5D3FD3;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

/* CSS KHUSUS UNTUK LEADERBOARD PODIUM */
.leaderboard-podium {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  gap: 1rem;
  margin-bottom: 2rem;
}

.podium-card {
  width: 100%;
  max-width: 280px;
  text-align: center;
  border-width: 3px;
  border-style: solid;
  padding: 1.5rem 1rem;
  border-radius: 0.75rem;
  position: relative;
  transition: all 0.3s ease;
}

.podium-card:hover {
    transform: translateY(-10px);
}

.podium-card .rank-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.podium-card .rank-number {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.podium-card .student-name {
  font-weight: 600;
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.podium-card .score {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

/* Peringkat 1 (Emas) - Menonjol di tengah */
.rank-1 {
  order: 2;
  border-color: #FFD700; /* Gold */
  background-color: #fffbeb;
  transform: translateY(-20px);
}
.rank-1 .rank-icon { color: #FFD700; }
.rank-1:hover {
    transform: translateY(-30px);
}

/* Peringkat 2 (Perak) */
.rank-2 {
  order: 1;
  border-color: #C0C0C0; /* Silver */
  background-color: #f8f9fa;
}
.rank-2 .rank-icon { color: #C0C0C0; }

/* Peringkat 3 (Perunggu) */
.rank-3 {
  order: 3;
  border-color: #CD7F32; /* Bronze */
  background-color: #fff5e8;
}
.rank-3 .rank-icon { color: #CD7F32; }

/* Styling untuk avatar */
.podium-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 1rem auto;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
  </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
  <div class="wrapper">

    {{-- Preloader, Navbar, dan Sidebar (tidak ada perubahan) --}}
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__pulse" src="{{asset('template/dist/img/AdminLTELogo.png')}}" alt="AppLogo" height="80" width="80">
    </div>

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Admin Menu</span>
                <div class="dropdown-divider"></div>

                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-indigo elevation-4">
      <a href="{{ route('leaderboard') }}" class="brand-link">

     <center><span class="brand-text  font-weight-bold"><h4>BUBBLE TEACHER</h4></span></center>

      </a>

      <div class="sidebar">


        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">NAVIGASI UTAMA</li>

            <li class="nav-item">
              <a href="{{route('leaderboard')}}" class="nav-link {{ Request::routeIs('leaderboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-trophy"></i>
                <p>Leaderboard</p>
              </a>
            </li>

            <li class="nav-header">MANAJEMEN KONTEN</li>

            <li class="nav-item">
                <a href="{{route('dataSiswa')}}" class="nav-link {{ Request::routeIs('dataSiswa') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-users"></i>
                  <p>Data Siswa</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('materi')}}" class="nav-link {{ Request::routeIs('materi') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-book-open"></i>
                  <p>Materi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('kuis')}}" class="nav-link {{ Request::routeIs('kuis') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-pencil-alt"></i>
                  <p>Manajemen Kuis</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('hasilkuis.index')}}" class="nav-link {{ Request::routeIs('hasilkuis.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-poll"></i>
                  <p>
                     Hasil Kuis

                  </p>
                </a>
            </li>
          </ul>
        </nav>
        </div>
      </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
        </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2024-{{ date('Y') }} <a href="#">Bubble Game</a></strong>
    </footer>

    <aside class="control-sidebar control-sidebar-dark">
      </aside>
    </div>

{{-- ======================================================= --}}
{{-- == BAGIAN SKRIP YANG DIPERBARUI == --}}
{{-- ======================================================= --}}
  <script src="{{asset('template/plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
  <script src="{{asset('template/dist/js/adminlte.js')}}"></script>

  {{-- DataTables & Plugins --}}
  <script src="{{asset('template/plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

  {{-- SCRIPT BARU: DataTables Buttons --}}
  <script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('template/plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('template/plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('template/plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

  {{-- SCRIPT BARU: SweetAlert2 (INI YANG PALING PENTING) --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- @stack('scripts') harus selalu di paling akhir --}}
  @stack('scripts')
</body>

</html>
