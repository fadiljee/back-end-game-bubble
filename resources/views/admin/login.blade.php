<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Halaman Login | Tema Bubble</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    /* ============================================
      -- CUSTOM BUBBLE LOGIN UI STYLES --
      ============================================
    */
    :root {
      --primary-color-light: #87CEFA; /* Light Sky Blue */
      --primary-color-dark: #4682B4;  /* Steel Blue */
      --white-color: #FFFFFF;
      --text-color: #f0f8ff; /* Alice Blue */
      --glass-bg: rgba(255, 255, 255, 0.15);
      --glass-border: rgba(255, 255, 255, 0.25);
      --error-bg: rgba(220, 53, 69, 0.3);
      --error-border: rgba(220, 53, 69, 0.5);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, var(--primary-color-light), var(--primary-color-dark));
      overflow: hidden; /* Mencegah scroll karena gelembung */
      position: relative;
    }

    /* --- Animated Bubble Background --- */
    .background-wrapper {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .bubble {
      position: absolute;
      list-style: none;
      display: block;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      animation: float-up 25s linear infinite;
      bottom: -180px; /* Mulai dari bawah layar */
    }

    .bubble:nth-child(1) { left: 10%; width: 50px; height: 50px; animation-delay: 0s; animation-duration: 15s; }
    .bubble:nth-child(2) { left: 20%; width: 25px; height: 25px; animation-delay: 2s; animation-duration: 12s; }
    .bubble:nth-child(3) { left: 85%; width: 30px; height: 30px; animation-delay: 4s; animation-duration: 18s; }
    .bubble:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 22s; }
    .bubble:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 1s; }
    .bubble:nth-child(6) { left: 75%; width: 120px; height: 120px; animation-delay: 3s; }
    .bubble:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
    .bubble:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 40s; }
    .bubble:nth-child(9) { left: 25%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 30s; }
    .bubble:nth-child(10) { left: 90%; width: 160px; height: 160px; animation-delay: 0s; animation-duration: 13s; }

    @keyframes float-up {
      0% {
        transform: translateY(0) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(-120vh) rotate(720deg);
        opacity: 0;
      }
    }

    /* --- Login Box (Glass Bubble) --- */
    .login-box {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 420px;
      padding: 50px 40px;
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px); /* For Safari */
      border-radius: 35px;
      border: 1.5px solid var(--glass-border);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
      text-align: center;
      color: var(--white-color);
    }

    .login-logo {
      font-size: 2.8em;
      font-weight: 600;
      margin-bottom: 5px;
      line-height: 1.2;
    }

    .login-logo b {
      font-weight: 700;
      color: var(--white-color);
    }
    .login-logo a {
        color: var(--white-color);
        text-decoration: none;
    }

    .login-box-msg {
      color: var(--text-color);
      margin-bottom: 30px;
      font-size: 1.1em;
    }

    /* --- Form Elements --- */
    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .form-control {
      width: 100%;
      height: 50px;
      padding: 10px 20px 10px 50px; /* Space for icon */
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: 50px;
      color: var(--white-color);
      font-size: 1em;
      transition: all 0.3s ease;
    }

    .form-control::placeholder {
      color: var(--text-color);
      opacity: 0.8;
    }

    .form-control:focus {
      outline: none;
      background: transparent;
      border-color: var(--white-color);
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
    }

    .input-group-text {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--white-color);
      font-size: 1.2em;
    }

    .btn-primary {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 50px;
      background: var(--primary-color-dark);
      color: var(--white-color);
      font-size: 1.1em;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:hover, .btn-primary:focus {
      background: #5a9bd6;
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      outline: none;
    }

    /* --- Laravel Error Messages --- */
    .alert-danger {
        background-color: var(--error-bg);
        color: var(--white-color);
        padding: 12px;
        margin-bottom: 20px;
        border-radius: 15px;
        border: 1px solid var(--error-border);
        font-size: 0.9em;
        text-align: left;
    }
    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
    }

  </style>
</head>

<body>

  <div class="background-wrapper">
    <ul class="bubble">
      <li></li><li></li><li></li><li></li><li></li>
      <li></li><li></li><li></li><li></li><li></li>
    </ul>
  </div>

  <div class="login-box">
    <div class="login-logo">
      <a href="#">LOGIN</a>
    </div>

    <p class="login-box-msg">Silahkan login untuk melanjutkan</p>

    {{-- Form login akan menggunakan logika backend Laravel Anda --}}
    <form action="{{ route('loginproses') }}" method="post">
      @csrf

      {{-- Menampilkan pesan error validasi dari Laravel --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="input-group mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email" required value="{{ old('email') }}">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-envelope"></span>
          </div>
        </div>
      </div>

      <div class="input-group mb-3">
        <input type="password" class="form-control" name="password" placeholder="Password" required>
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-lock"></span>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-12">
          <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
      </div>
    </form>
  </div>

</body>
</html>
