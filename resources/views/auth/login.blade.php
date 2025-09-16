<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - DipaNet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: auto;
      font-family: 'Segoe UI', sans-serif;
    }

    .background-fixed {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: linear-gradient(to bottom right, #0F172A, #1E293B);
      z-index: -1;
    }

    .scroll-wrapper {
      position: relative;
      height: 100vh;
      width: 100vw;
      overflow-y: auto;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }

    .login-container {
      width: 100%;
      max-width: 400px;
      background: white;
      border-radius: 1rem;
      padding: 1.5rem 2rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .logo-img {
      width: 75px;
      height: 75px;
      object-fit: cover;
      border-radius: 50%;
      margin: 0 auto 0.8rem auto;
      display: block;
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: #7B61FF;
      text-align: center;
      margin-bottom: 0.3rem;
    }

    .text-muted {
      color: #6c757d;
      font-size: 0.9rem;
      text-align: center;
    }

    .form-label {
      font-weight: 600;
      display: block;
      margin-bottom: 0.3rem;
      font-size: 0.9rem;
    }

    .form-control {
      border-radius: 0.5rem;
      padding: 0.5rem 0.75rem;
      width: 100%;
      border: 1px solid #ccc;
      font-size: 0.9rem;
    }

    .input-group {
      display: flex;
      align-items: center;
      margin-bottom: 0.8rem;
    }

    .input-group-text {
      background-color: #f1f1f1;
      border: 1px solid #ced4da;
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem 0 0 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
    }

    .form-text a {
      color: #6D74FF;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .form-text a:hover {
      text-decoration: underline;
    }

    .btn-primary {
      background: linear-gradient(to right, #7B61FF, #7B50D0);
      border: none;
      border-radius: 999px;
      font-weight: bold;
      padding: 0.6rem;
      color: white;
      width: 100%;
      font-size: 0.95rem;
      transition: 0.3s ease;
    }

    .btn-primary:hover {
      opacity: 0.9;
    }

    .text-center {
      text-align: center;
      font-size: 0.9rem;
    }

    .d-grid {
      display: grid;
    }

    .mb-3 {
      margin-bottom: 0.8rem;
    }

    .mb-4 {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <!-- Background -->
  <div class="background-fixed"></div>

  <!-- Login form wrapper -->
  <div class="scroll-wrapper">
    <div class="login-container">

      <!-- Logo -->
      <div class="text-center mb-3">
        <img src="{{ asset('images/logo.png.png') }}" alt="Logo" class="logo-img">
        <div class="logo-text">DipaNet</div>
        <p class="text-muted mt-1">Masuk ke akun Anda untuk melanjutkan mengelola hotspot</p>
      </div>
   <!-- ✅ Pesan status -->
    @if (session('status'))
      <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem;">
        {{ session('status') }}
      </div>
    @endif
      <!-- Form Login -->
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Nomor Telepon -->
        <div class="mb-3">
          <label class="form-label">Nomor Telepon</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
            <input type="text" name="email" class="form-control" placeholder="Masukkan Nomor Telepon" required autofocus>
          </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
          </div>
        </div>

        <!-- Lupa Password -->
        <div class="mb-3 text-center">
          <a href="{{ route('password.request') }}" class="form-text">Lupa Password?</a>
        </div>

        <!-- Tombol Login -->
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk Sekarang
          </button>
        </div>

        <!-- Link Daftar -->
        <div class="text-center">
          Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>

      </form>
    </div>
  </div>

</body>
</html>
