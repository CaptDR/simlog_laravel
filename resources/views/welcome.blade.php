<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIMLOG BPBD</title>
  <!-- Bootstrap 5 CSS via CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --theme-black: #111111;
      --theme-black-surface: #1e1e1e;
      --theme-orange: #ff6a00;
      --theme-orange-hover: #e65c00;
      --theme-orange-light: #fff3ec;
      --theme-bg: #f8f9fa;
      --theme-border: #e9ecef;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: var(--theme-bg);
      color: var(--theme-black);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      margin: 0;
    }

    .main-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .login-card {
      width: 100%;
      max-width: 440px;
      background: #ffffff;
      border: 1px solid rgba(0, 0, 0, 0.06);
      border-radius: 1.25rem;
      box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 20px 25px -5px rgba(0, 0, 0, 0.03);
      padding: 2.5rem 2.25rem;
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--theme-black) 0%, var(--theme-orange) 100%);
    }

    /* Logo Badges */
    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .logo-placeholder {
      width: 72px;
      height: 72px;
      border-radius: 1rem;
      background-color: #fafafa;
      border: 1px solid #eeeeee;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 0.5rem;
      transition: all 0.2s ease;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .logo-placeholder:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(255, 106, 0, 0.12);
      border-color: var(--theme-orange-light);
    }

    .logo-placeholder svg {
      width: 32px;
      height: 32px;
      margin-bottom: 4px;
    }

    .logo-placeholder span {
      font-size: 0.65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      text-align: center;
      line-height: 1.1;
      color: #333333;
    }

    .title-app {
      font-size: 1.75rem;
      font-weight: 800;
      color: var(--theme-black);
      letter-spacing: -0.5px;
      margin-bottom: 0.35rem;
      text-align: center;
    }

    .subtitle-app {
      font-size: 0.85rem;
      color: #6c757d;
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 500;
    }

    /* Form styling */
    .form-label {
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--theme-black);
      margin-bottom: 0.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .input-group-custom {
      position: relative;
    }

    .form-control-custom {
      border: 1.5px solid #e2e8f0;
      border-radius: 0.75rem;
      padding: 0.75rem 1rem 0.75rem 2.6rem;
      font-size: 0.95rem;
      color: #1a1a1a;
      transition: all 0.2s ease;
      background-color: #fafbfc;
    }

    .form-control-custom:focus {
      background-color: #ffffff;
      border-color: var(--theme-orange);
      box-shadow: 0 0 0 4px rgba(255, 106, 0, 0.15);
      outline: none;
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1.05rem;
      pointer-events: none;
      transition: color 0.2s ease;
      z-index: 5;
    }

    .form-control-custom:focus ~ .input-icon {
      color: var(--theme-orange);
    }

    .toggle-password {
      position: absolute;
      right: 0.85rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #94a3b8;
      cursor: pointer;
      padding: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.2s ease;
      z-index: 5;
    }

    .toggle-password:hover {
      color: var(--theme-black);
    }

    .btn-orange {
      background-color: var(--theme-orange);
      color: #ffffff;
      border: none;
      font-weight: 700;
      font-size: 1rem;
      letter-spacing: 0.3px;
      padding: 0.85rem 1.5rem;
      border-radius: 0.75rem;
      width: 100%;
      transition: all 0.25s ease;
      box-shadow: 0 4px 14px rgba(255, 106, 0, 0.35);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-orange:hover, .btn-orange:focus {
      background-color: var(--theme-orange-hover);
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(255, 106, 0, 0.45);
    }

    .btn-orange:active {
      transform: translateY(0);
      box-shadow: 0 2px 8px rgba(255, 106, 0, 0.3);
    }

    /* Page Footer */
    .page-footer {
      padding: 1.5rem 1rem 2rem;
      text-align: center;
      color: #8c959f;
      font-size: 0.825rem;
      font-weight: 500;
    }

    .badge-subsystem {
      display: inline-block;
      background: var(--theme-black);
      color: #ffffff;
      font-size: 0.65rem;
      font-weight: 700;
      letter-spacing: 0.75px;
      padding: 0.25rem 0.65rem;
      border-radius: 9999px;
      text-transform: uppercase;
      margin-bottom: 0.75rem;
    }
  </style>
</head>
<body>

  <div class="main-wrapper">
    <div class="login-card">
      
      <!-- Subsystem badge -->
      <div class="text-center">
        <span class="badge-subsystem">Sistem Informasi Logistik</span>
      </div>

      <!-- Header inside card: Logo Pemkot & Logo BPBD side-by-side -->
      <div class="logo-container">
        <!-- Logo Pemkot Placeholder -->
        <div class="logo-placeholder" title="Logo Pemkot">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #111111;">
            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
            <polyline points="2 17 12 22 22 17"></polyline>
            <polyline points="2 12 12 17 22 12"></polyline>
          </svg>
          <span>Logo Pemkot</span>
        </div>

        <!-- Logo BPBD Placeholder (Orange Accent) -->
        <div class="logo-placeholder" title="Logo BPBD" style="border-color: #ffd9bf;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #ff6a00;">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            <polygon points="12 8 9 14 12 13 11 17 15 11 12 12 12 8" fill="#ff6a00" stroke="none"></polygon>
          </svg>
          <span style="color: #ff6a00;">Logo BPBD</span>
        </div>
      </div>

      <!-- Title & Subtitle -->
      <h1 class="title-app">SIMLOG BPBD</h1>
      <p class="subtitle-app">Sistem Informasi Manajemen Logistik Bencana</p>

      <!-- Form Elements -->
      <form action="{{ url('/login') }}" method="POST" autocomplete="off">
        @csrf
        
        <!-- Username input -->
        <div class="mb-3">
          <label for="username" class="form-label">
            <span>Username</span>
          </label>
          <div class="input-group-custom">
            <input 
              type="text" 
              class="form-control form-control-custom w-100" 
              id="username" 
              name="username" 
              placeholder="Masukkan username" 
              required
              autofocus
            >
            <i class="bi bi-person input-icon"></i>
          </div>
        </div>

        <!-- Password input -->
        <div class="mb-4">
          <label for="password" class="form-label">
            <span>Password</span>
          </label>
          <div class="input-group-custom">
            <input 
              type="password" 
              class="form-control form-control-custom w-100" 
              id="password" 
              name="password" 
              placeholder="Masukkan kata sandi" 
              required
            >
            <i class="bi bi-lock input-icon"></i>
            <button type="button" class="toggle-password" id="btnTogglePassword" aria-label="Lihat kata sandi">
              <i class="bi bi-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-orange">
          <span>Masuk</span>
          <i class="bi bi-arrow-right"></i>
        </button>

      </form>

    </div>
  </div>

  <!-- Footer outside card at bottom center -->
  <footer class="page-footer">
    <p class="mb-0">
      &copy; 2026 BPBD Kota Cirebon - Dikembangkan oleh Dimas Dwi Rianto
    </p>
  </footer>

  <!-- Bootstrap 5 JS Bundle via CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
  <!-- Toggle password visibility script -->
  <script>
    const btnToggle = document.getElementById('btnTogglePassword');
    const inputPassword = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (btnToggle && inputPassword && toggleIcon) {
      btnToggle.addEventListener('click', function () {
        const isPassword = inputPassword.getAttribute('type') === 'password';
        inputPassword.setAttribute('type', isPassword ? 'text' : 'password');
        toggleIcon.classList.toggle('bi-eye', !isPassword);
        toggleIcon.classList.toggle('bi-eye-slash', isPassword);
      });
    }
  </script>
</body>
</html>
