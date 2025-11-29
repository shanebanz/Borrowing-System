<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #176734;
            --primary-dark: #0f4a24;
            --accent-color: #d0c52a;
            --light-bg: #f8f9fa;
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f2efeb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            color: #fff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1), 4px 0 8px rgba(0, 0, 0, 0.3);
        }

        .profile-section {
            padding: 30px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.2);
            margin: 0 auto 15px;
            display: block;
            background-color: #e9ecef;
        }

        .profile-name h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
        }

        .profile-name p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
        }

        .menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }

        .menu-item.active {
            background: rgba(15, 74, 36, 0.7);
            border-left-color: var(--accent-color);
        }

        .menu-item:hover {
            background: rgba(15, 74, 36, 0.5);
            text-decoration: none;
            color: white;
            transform: translateX(5px);
        }

        .content-area {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }

        .dashboard-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            text-align: center;
        }

        .dashboard-header h1 {
            color: var(--primary-dark);
            margin: 10px 0;
        }

        .dashboard-header img {
            max-width: 300px;
            height: auto;
        }

        .about-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .group-name {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
        }

        .group-name h2 {
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .group-name p {
            color: #666;
            font-size: 1.1rem;
        }

        .members-section {
            margin-top: 40px;
        }

        .members-section h3 {
            color: var(--primary-dark);
            margin-bottom: 30px;
            text-align: center;
            font-size: 2rem;
        }

        .member-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            border: 2px solid transparent;
        }

        .member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.15);
            border-color: var(--accent-color);
        }

        .member-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            object-fit: cover;
            border: 4px solid var(--primary-color);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--primary-color);
        }

        .member-name {
            color: var(--primary-dark);
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .member-role {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-style: italic;
        }

        .member-info {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .copyright-notice {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-top: 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .copyright-notice i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--accent-color);
        }

        .copyright-notice h4 {
            margin-bottom: 15px;
            font-weight: bold;
        }

        .copyright-notice p {
            margin: 0;
            opacity: 0.9;
            line-height: 1.8;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .profile-name,
            .sidebar .menu-text {
                display: none;
            }
            
            .sidebar .profile-img {
                width: 40px;
                height: 40px;
            }
            
            .sidebar .menu-item {
                justify-content: center;
                padding: 15px 10px;
            }
            
            .sidebar .menu-item i {
                margin-right: 0;
                font-size: 1.2rem;
            }
            
            .content-area {
                margin-left: 70px;
            }
            
            .about-container {
                padding: 20px;
            }

            .group-name h2 {
                font-size: 1.8rem;
            }

            .member-photo {
                width: 120px;
                height: 120px;
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="profile-section">
            <img src="<?= get_profile_image_url() ?>" alt="Profile" class="profile-img">
            <div class="profile-name">
                <h4><?= esc(session()->get('lastname') ?? 'LAST NAME') ?></h4>
                <p><?= esc(session()->get('firstname') ?? 'First Name') ?></p>
            </div>
        </div>
        
        <nav class="menu">
            <?php if (isset($user['role']) && $user['role'] === 'ITSO'): ?>
                <a href="<?= site_url('itsoDash_main') ?>" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
                </a>
                <a href="<?= site_url('itso/items') ?>" class="menu-item">
                    <i class="fas fa-box"></i>
                    <span class="menu-text">Items</span>
                </a>
                <a href="<?= site_url('itso/users') ?>" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Users</span>
                </a>
                <a href="<?= site_url('itso/records') ?>" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Records</span>
                </a>
                <a href="<?= site_url('itso/reports') ?>" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-text">Reports</span>
                </a>
            <?php elseif (isset($user['role']) && $user['role'] === 'Associate'): ?>
                <a href="<?= site_url('assoDash_main') ?>" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
                </a>
                <a href="<?= site_url('associate/reservation') ?>" class="menu-item">
                    <i class="fas fa-calendar-check"></i>
                    <span class="menu-text">Reservation</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('studentDash_main') ?>" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
                </a>
            <?php endif; ?>
            
            <a href="<?= site_url('about') ?>" class="menu-item active">
                <i class="fas fa-info-circle"></i>
                <span class="menu-text">About</span>
            </a>
            <a href="<?= site_url('auth/logout') ?>" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span class="menu-text">Log out</span>
            </a>
        </nav>
    </aside>

    <main class="content-area">
        <div class="dashboard-header">
            <img src="<?= base_url('public/FEUTechBanner.png') ?>" alt="FEU Tech Banner">
            <h1><b>ABOUT US</b></h1>
        </div>

        <div class="about-container">
            <div class="group-name">
                <h2>SYS - Equipment Management System</h2>
                <p>IT Services Office Equipment Tracking & Management</p>
            </div>

            <div class="members-section">
                <h3>Development Team</h3>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="member-card">
                            <div class="member-photo">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="member-name">Sophia Gabrielle Calalay</div>
                            <div class="member-role">Team Leader / Backend Developer</div>
                            <div class="member-info">
                                Responsible for system architecture, database design, and backend development.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="member-card">
                            <div class="member-photo">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="member-name">Yuki Emit</div>
                            <div class="member-role">Frontend Developer</div>
                            <div class="member-info">
                                Focused on user interface design, user experience, and frontend implementation.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="member-card">
                            <div class="member-photo">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="member-name">Norwood Shane Banzuela</div>
                            <div class="member-role">Full Stack Developer</div>
                            <div class="member-info">
                                Handled feature integration, testing, and system deployment.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="copyright-notice">
                <i class="fas fa-shield-alt"></i>
                <h4>Copyright & Disclaimer</h4>
                <p>
                    This project is developed for educational purposes only. All trademarks, logos, and brand names are the property of their respective owners. 
                    All company, product and service names used in this system are for identification purposes only. 
                    Use of these names, trademarks and brands does not imply endorsement.
                </p>
                <p style="margin-top: 15px; font-weight: bold;">
                    <i class="fas fa-exclamation-circle"></i> NO COPYRIGHT INFRINGEMENT INTENDED
                </p>
                <p style="margin-top: 10px; font-size: 0.9rem;">
                    &copy; <?= date('Y') ?> SYS Equipment Management System. All rights reserved.
                </p>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
