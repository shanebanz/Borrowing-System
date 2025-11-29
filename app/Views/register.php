<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    
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
        .regist-container {
            height: 100vh;
            background: radial-gradient(circle at center, #d0c52a 0%, #144e2c 90%);
        }
        .regist-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-regist {
            background: #144e2c;
            border: none;
            padding: 10px;
        }
        .btn-regist:hover {
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

    <div class="regist-container d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card regist-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 class="card-title">SYS</h3>
                                <p class="text-muted">Register to your new account</p>
                            </div>

                            <form action="<?= site_url('register/submit') ?>" method="post" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>

                                <div class="mb-3">
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="" disabled selected>Select Role</option>
                                        <option value="Student">Student</option>
                                        <option value="ITSO">IT Personnel</option>
                                        <option value="Associate">Associate</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" class="form-control" id="confPassword" name="confPassword" placeholder="Confirm Password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="profile_image" class="form-label" style="font-size: 0.9rem; color: #666;">Profile Picture (Optional)</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/jpeg,image/jpg,image/png,image/gif">
                                    <small class="text-muted">Max 2MB. Allowed: JPG, PNG, GIF</small>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-regist text-white">Register</button>
                                </div>

                                <hr class="my-4">

                                <div class="text-center">
                                    <p class="mb-0">
                                        Already have an account? 
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
