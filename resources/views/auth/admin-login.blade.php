<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - KueKeringUMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); overflow: hidden; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-cog fa-3x text-primary"></i>
                            <h3 class="mt-3 fw-bold">Admin Login</h3>
                            <p class="text-muted">Masuk ke dashboard admin</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="/admin/login" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email Admin</label>
                                <input type="email" name="email" class="form-control" required placeholder="admin@email.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="/" class="text-decoration-none">← Kembali ke Website</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>