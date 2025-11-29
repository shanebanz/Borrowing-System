<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .notification-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
        }
        .login-container {
            height: 100vh;
            background: radial-gradient(circle at center, #d0c52a 0%, #144e2c 90%);
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-login {
            background: #144e2c;
            border: none;
            padding: 10px;
        }
            .btn-login:hover {
                background: #0f3a1f;
            }
        .form-control:hover{
            background-color: #eff7f2;
        }
         
    </style>
</head>
<body>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show notification-alert" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show notification-alert" role="alert">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="login-container d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card login-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 class="card-title">SYS</h3>
                                <p class="text-muted">Login to your account</p>
                            </div>

                            <!--PIA ADDED -->
                            <form action="<?= site_url('/login') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= old('email') ?>">
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                                <div class="text-end mb-3">
                                    <a href="<?= site_url('forgot-password') ?>" class="text-decoration-none text-muted small">Forgot Password?</a>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-login text-white">Login</button>
                                </div>
                                <hr class="my-4">
                                <div class="text-center">
                                <p class="mb-0">
                                        Don't have an account yet? 
                                <a href="<?= site_url('register') ?>" class="text-decoration-none">Register</a>
                                </p>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 8 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.notification-alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 8000);
    </script>
</body>
</html>