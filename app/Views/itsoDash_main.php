<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSO Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #176734;
            --primary-dark: #0f4a24;
            --accent-color: #d0c52a;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a7a3d 40%, var(--accent-color) 150%);
            padding: 20px 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section img {
            height: 50px;
            width: auto;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 20px;
            color: white;
        }

        .user-info {
            text-align: right;
        }

        .user-info h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .user-info p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .logout-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .logout-btn:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .dashboard-content {
            text-align: center;
            padding: 40px 20px;
        }

        .welcome-text {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }

        .sys-image {
            max-width: 400px;
            width: 100%;
            height: auto;
            margin: 0 auto 40px;
            display: block;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .action-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 0 1 calc(33.333% - 15px);
            min-width: 150px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .action-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            color: white;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .profile-section {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .user-info {
                text-align: center;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .sys-image {
                max-width: 300px;
            }
            
            .logout-btn {
                bottom: 20px;
                right: 20px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="<?= base_url('public/FEUTechBanner_White.png') ?>" alt="FEU Tech Logo">
            </div>
            
            <div class="profile-section">
                <div class="user-info">
                    <h4 id="dash_lastName"><?= esc(session()->get('lastname') ?? 'LAST NAME') ?></h4>
                    <p id="dash_firstName"><?= esc(session()->get('firstname') ?? 'First Name') ?></p>
                </div>
                <img src="<?= get_profile_image_url() ?>" alt="Profile Image" class="profile-img" id="profileImage">
            </div>
        </div>
    </div>
    
    <div class="dashboard-content">
        <h5 class="welcome-text"><b>Welcome to ITSO Dashboard</b></h5>
        <img src="<?= base_url('public/SYSMain.png') ?>"  alt="SYS Main" class="sys-image">
        
        <div class="action-buttons">
            <a href="<?= site_url('itso/items') ?>" class="action-btn">
                <i class="fas fa-box me-2"></i>Items
            </a>
            <a href="<?= site_url('itso/users') ?>" class="action-btn">
                <i class="fas fa-users me-2"></i>Users
            </a>
            <a href="<?= site_url('itso/records') ?>" class="action-btn">
                <i class="fas fa-file-alt me-2"></i>Records
            </a>
            <a href="<?= site_url('itso/reports') ?>" class="action-btn">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
            <a href="<?= site_url('about') ?>" class="action-btn">
                <i class="fas fa-info-circle me-2"></i>About
            </a>
        </div>
    </div>

    <!-- Logout Button at Bottom Right -->
    <a href="<?= site_url('auth/logout') ?>" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>Log out
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>