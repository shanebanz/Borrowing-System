<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    
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
        .reset-container {
            height: 100vh;
            background: radial-gradient(circle at center, #d0c52a 0%, #144e2c 90%);
        }
        .reset-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-reset {
            background: #144e2c;
            border: none;
            padding: 10px;
        }
        .btn-reset:hover {
            background: #0f3a1f;
        }
        .form-control:hover {
            background-color: #eff7f2;
        }
    </style>
</head>
<body>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show notification-alert" role="alert">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="reset-container d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card reset-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 class="card-title">Reset Password</h3>
                                <p class="text-muted">Enter your new password</p>
                            </div>

                            <form action="<?= site_url('reset-password/update') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="token" value="<?= esc($token) ?>">
                                <div class="mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" id="confPassword" name="confPassword" placeholder="Confirm Password" required>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-reset text-white">Reset Password</button>
                                </div>
                                <hr class="my-4">
                                <div class="text-center">
                                    <p class="mb-0">
                                        Remember your password? 
                                        <a href="<?= site_url('/') ?>" class="text-decoration-none">Login</a>
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
