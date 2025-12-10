<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk | e-Jawatan CIAST</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef2f7;
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            width: 380px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 80px;
        }

        .title {
            font-size: 26px;
            font-weight: 700;
        }

        .subtitle {
            color: #6c757d;
            margin-bottom: 25px;
        }

        .btn-primary {
            font-size: 18px;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center" style="height:100vh;">
        <div class="login-card">

            <div class="text-center mb-4">
                <img src="/GAMBAR/LOGOCIAST.png" class="logo" alt="CIAST Logo">
                <div class="title mt-2">e-JAWATAN</div>
                <div class="subtitle">Log masuk ke sistem</div>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">No. MyKad</label>
                    <input type="text" name="mykad" value="{{ old('mykad') }}" class="form-control" required autofocus>
                    @error('mykad')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary w-100">Log Masuk</button>

                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}">Lupa kata laluan?</a>
                </div>

            </form>

        </div>
    </div>

</body>

</html>
