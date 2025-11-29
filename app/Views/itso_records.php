<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Records</title>

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
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            background: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            padding: 0;
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

        .sidebar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30%;
            background: linear-gradient(to bottom, 
                transparent 0%, 
                rgba(208, 197, 42, 0.1) 20%, 
                rgba(208, 197, 42, 0.2) 40%, 
                rgba(208, 197, 42, 0.3) 100%);
            pointer-events: none;
            z-index: -1;
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

        .profile-name {
            color: white;
        }

        .profile-name h4 {
            font-weight: 600;
            margin-bottom: 0;
            font-size: 1.5rem;
        }

        .profile-name p {
            opacity: 0.9;
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

        .content {
            flex: 1;
            padding: 30px;
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            height: 100vh;
            overflow-y: auto;
        }

        .record-header {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a7a3d 40%, var(--accent-color) 150%);
            color: white;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .record-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr) 40px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 5px;
        }

        .dropdown-arrow {
            text-align: center;
            transition: transform 0.3s;
            font-size: 20px;
        }

        /* COLLAPSE SECTION */
        .collapse {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .collapse.open {
            max-height: 300px;
        }

        .dropdown-arrow.rotate {
            transform: rotate(180deg);
        }

        /* table */
        .record-table {
            background: white;
            border-radius: 10px;
            padding: 20px;
        }

        .table-header, .table-body {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a {
            display: block;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .active a {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
    </style>
</head>

<body>
<div class="container">

    <aside class="sidebar">
        <div class="profile-section">
            <img src="<?= get_profile_image_url() ?>" alt="Profile Image" class="profile-img" id="profileImage">
                
            <div class="profile-name">
                <h4 id="dash_lastName"><?= esc(session()->get('lastname') ?? 'LAST NAME') ?></h4>
                <p id="dash_firstName"><?= esc(session()->get('firstname') ?? 'First Name') ?></p>
            </div>
        </div>
            
        <nav class="menu">
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
            <a href="<?= site_url('itso/records') ?>" class="menu-item active">
                <i class="fas fa-file-alt"></i>
                <span class="menu-text">Records</span>
            </a>
            <a href="<?= site_url('itso/reports') ?>" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-text">Reports</span>
            </a>
            <a href="<?= site_url('about') ?>" class="menu-item">
                <i class="fas fa-info-circle"></i>
                <span class="menu-text">About</span>
            </a>
            <a href="<?= site_url('auth/logout') ?>" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span class="menu-text">Log out</span>
            </a>
        </nav>
    </aside>

    <main class="content">
        <h1>Active Borrowing Records</h1>

        <div class="record-table" style="margin-top: 30px;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
                <thead>
                    <tr style="background: var(--primary-color); color: white;">
                        <th style="padding: 15px; text-align: left;">Transaction ID</th>
                        <th style="padding: 15px; text-align: left;">User</th>
                        <th style="padding: 15px; text-align: left;">Email</th>
                        <th style="padding: 15px; text-align: left;">Equipment</th>
                        <th style="padding: 15px; text-align: left;">Category</th>
                        <th style="padding: 15px; text-align: left;">Quantity</th>
                        <th style="padding: 15px; text-align: left;">Borrow Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($activeTransactions) && count($activeTransactions) > 0): ?>
                        <?php foreach ($activeTransactions as $record): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 12px;"><?= esc($record['borrow_id']) ?></td>
                                <td style="padding: 12px;"><?= esc($record['firstname'] . ' ' . $record['lastname']) ?></td>
                                <td style="padding: 12px;"><?= esc($record['email']) ?></td>
                                <td style="padding: 12px;"><?= esc($record['equipment_name']) ?></td>
                                <td style="padding: 12px;"><?= esc($record['category']) ?></td>
                                <td style="padding: 12px;"><?= esc($record['quantity']) ?></td>
                                <td style="padding: 12px;"><?= date('M d, Y h:i A', strtotime($record['borrow_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #666;">No active borrowing records</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager)): ?>
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            <?= $pager->links() ?>
        </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
