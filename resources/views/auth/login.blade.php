<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন - POS Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Hind Siliguri', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: #fff; border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden; width: 100%; max-width: 420px;
        }
        .login-header {
            background: linear-gradient(135deg, #FF6B35, #e55a28);
            padding: 35px 30px; text-align: center; color: #fff;
        }
        .login-header .logo { font-size: 3rem; margin-bottom: 10px; }
        .login-header h4 { margin: 0; font-weight: 700; font-size: 1.3rem; }
        .login-header p { margin: 5px 0 0; opacity: 0.9; font-size: 0.85rem; }
        .login-body { padding: 35px 30px; }
        .form-floating label { color: #888; }
        .form-control:focus { border-color: #FF6B35; box-shadow: 0 0 0 0.2rem rgba(255,107,53,0.15); }
        .btn-login {
            background: linear-gradient(135deg, #FF6B35, #e55a28);
            border: none; color: #fff; width: 100%;
            padding: 12px; border-radius: 10px;
            font-size: 1rem; font-weight: 600; letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,107,53,0.4); }
        .demo-info {
            background: #fff3ee; border-radius: 10px;
            padding: 12px 15px; margin-top: 20px; font-size: 0.8rem; color: #666;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div class="logo"><i class="fas fa-utensils"></i></div>
        <h4>POS Restaurant</h4>
        <p>Restaurant Management System</p>
    </div>
    <div class="login-body">
        @if($errors->any())
            <div class="alert alert-danger py-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" placeholder="email"
                       value="{{ old('email', 'admin@pos.com') }}" required>
                <label for="email"><i class="fas fa-envelope me-2"></i>ইমেইল</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="password" required>
                <label for="password"><i class="fas fa-lock me-2"></i>পাসওয়ার্ড</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted small" for="remember">মনে রাখুন</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>লগইন করুন
            </button>
        </form>

        <div class="demo-info">
            <strong>Demo:</strong> admin@pos.com / password
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
