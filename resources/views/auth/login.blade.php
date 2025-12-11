
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Jawatan Portal - Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0052A3;
            --secondary-color: #E74C3C;
            --light-bg: #F8F9FA;
            --border-color: #E0E0E0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0052A3 0%, #003D7A 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .login-card {
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: white;
        }

        .login-left {
            background: linear-gradient(135deg, #0052A3 0%, #003D7A 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 500px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-logo {
            font-size: 48px;
            margin-bottom: 20px;
            display: inline-block;
            background: #FFFFFF;
            padding: 20px 25px;
            border-radius: 10px;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .login-features {
            list-style: none;
            margin-bottom: 40px;
        }

        .login-features li {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login-features li i {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .login-footer {
            font-size: 12px;
            opacity: 0.8;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 20px;
        }

        .login-right {
            padding: 60px 40px;
            background: #F8F9FA;
        }

        .login-form-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .login-form-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 40px;
        }

        .form-floating > .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            height: 50px;
            font-size: 14px;
        }

        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 82, 163, 0.15);
        }

        .form-floating > label {
            color: #666;
            font-size: 13px;
            padding: 1rem 0.75rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #0052A3 0%, #003D7A 100%);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 0;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 20px;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #003D7A 0%, #002A57 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 82, 163, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .error-message {
            color: var(--secondary-color);
            font-size: 13px;
            margin-top: 8px;
            display: flex;
            align-items: center;
        }

        .error-message i {
            margin-right: 6px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-top: -15px;
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 3px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            font-size: 13px;
            color: #666;
            cursor: pointer;
            margin-left: 8px;
            margin-bottom: 0;
        }

        .input-group-text {
            border: 1px solid var(--border-color);
            background: white;
            color: #666;
        }

        .show-password {
            cursor: pointer;
            color: #666;
            transition: color 0.2s;
        }

        .show-password:hover {
            color: var(--primary-color);
        }

        .alert-error {
            background-color: #FEE;
            border: 1px solid #ECC;
            color: var(--secondary-color);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 25px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error i {
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .login-left {
                padding: 40px 30px;
                min-height: auto;
                display: none;
            }

            .login-right {
                padding: 40px 25px;
            }

            .login-form-title {
                font-size: 22px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }

        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-login.loading {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login.loading .loading-spinner {
            display: inline-block;
        }

        .divider {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: var(--border-color);
        }

        .divider span {
            background: #F8F9FA;
            padding: 0 10px;
            position: relative;
            z-index: 1;
        }

        .support-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .support-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .support-link a:hover {
            color: var(--secondary-color);
        }

        .form-control::placeholder {
            color: #999;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeIn 0.4s ease-out;
        }

        .badge-new {
            background: var(--secondary-color);
            color: white;
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="row g-0 login-card">
            <!-- Left Side Panel -->
            <div class="col-lg-5 login-left d-none d-lg-flex flex-column">
                <div>
                    <div class="login-header">
                        <div class="login-logo">
                            <img src="{{ asset('GAMBAR/LOGOCIAST.png') }}" alt="Logo" style="width:80px;">
                        </div>
                        <h1>e-Jawatan</h1>
                        <p>Sistem Pengurusan Perjawatan</p>
                    </div>

                    <ul class="login-features">
                        <li>
                            <i class="fas fa-check"></i>
                            Pengurusan data pekerja yang mudah dan cepat
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Rekod kesihatan dan latihan terintegrasi
                        </li>
                        <li>

                            <i class="fas fa-check"></i>
                            Laporan dan analisis perjawatan yang komprehensif
                        </li>
                    </ul>
                </div>

                <div class="login-footer">
                    <p style="margin: 0;">
                        <strong>e-Jawatan Portal</strong> <br>
                        Versi 2.0 | Portal Pengurusan Perjawatan Terintegrasi <br>
                        ©️ 2024 JPK. Semua hak terpelihara.
                    </p>
                </div>
            </div>

            <!-- Right Side Form -->
            <div class="col-lg-7 login-right">
                <div>
                    <h2 class="login-form-title">
                        <i class="fas fa-sign-in-alt" style="color: var(--primary-color); margin-right: 10px;"></i>
                        Log Masuk
                    </h2>
                    <p class="login-form-subtitle">Masukkan No. MyKad dan kata laluan anda untuk mengakses portal</p>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>Ralat Log Masuk</strong>
                                <p style="margin: 4px 0 0 0;">{{ $errors->first('mykad') ?? $errors->first() }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif --}}

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <!-- MyKad Input -->
                        <div class="form-group">
                            <div class="form-floating">
                                <input type="text"
                                       class="form-control @error('mykad') is-invalid @enderror"
                                       id="mykad"
                                       name="mykad"
                                       placeholder="No. MyKad"
                                       value="{{ old('mykad') }}"
                                       autocomplete="off"
                                       required
                                       autofocus>
                                <label for="mykad">
                                    <i class="fas fa-id-badge" style="margin-right: 8px;"></i>
                                    No. MyKad
                                </label>
                            </div>
                            @error('mykad')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="form-group">
                            <div class="form-floating input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Kata Laluan"
                                       required
                                       autocomplete="current-password">
                                <label for="password">
                                    <i class="fas fa-lock" style="margin-right: 8px;"></i>
                                    Kata Laluan
                                </label>
                                <span class="input-group-text border-start-0">
                                    <i class="fas fa-eye show-password" id="togglePassword"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-times-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="remember-me">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya untuk akses seterusnya
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-login" id="submitBtn">
                            <span class="loading-spinner"></span>
                            <span>Log Masuk</span>
                        </button>

                        <!-- Support Link -->
                        <div class="support-link">
                            Lupa Kata Laluan? <br>
                            <a href="#" onclick="alert('Hubungi bahagian IT untuk sokongan'); return false;">
                                <i class="fas fa-question-circle"></i> Tukar Kata Laluan
                            </a>
                        </div>
                    </form>

                    <div class="divider">
                        <span>Portal Rasmi</span>
                    </div>

                    <p style="text-align: center; font-size: 12px; color: #999; margin: 0;">
                        Sistem e-Jawatan adalah portal pengurusan perjawatan yang selamat dan tersertifikasi. <br>
                        Pastikan anda menggunakan komputer yang dipercayai.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const mykad = document.getElementById('mykad').value.trim();
            const password = document.getElementById('password').value.trim();

            // Basic validation
            if (!mykad || !password) {
                e.preventDefault();
                alert('Sila masukkan No. MyKad dan Kata Laluan');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<span class="loading-spinner"></span><span>Sedang memproses...</span>';
        });

        // Format MyKad input (optional - adds dashes)
        document.getElementById('mykad').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 12) {
                e.target.value = value;
            }
        });

        // Auto-focus first field on page load
        window.addEventListener('load', function() {
            document.getElementById('mykad').focus();
        });
    </script>
</body>
</html>
