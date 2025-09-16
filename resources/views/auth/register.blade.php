<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar - DipaNet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    .register-container {
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

  <!-- Form container -->
  <div class="scroll-wrapper">
    <div class="register-container">
      <!-- Logo -->
      <div class="text-center mb-3">
        <img src="{{ asset('images/logo.png.png') }}" alt="Logo" class="logo-img">
        <div class="logo-text">DipaNet</div>
        <p class="text-muted mt-1">Buat akun untuk mulai mengelola hotspot</p>
      </div>

      <!-- Form -->
      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-control" placeholder="Masukkan Nama Lengkap" required value="{{ old('name') }}">
          @error('name')
              <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required value="{{ old('email') }}">
          @error('email')
              <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
          @error('password')
              <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
          @error('password_confirmation')
              <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
          </button>
        </div>

        <div class="text-center">
          Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
