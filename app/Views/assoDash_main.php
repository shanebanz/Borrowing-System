<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associate Dashboard</title>
    
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
            background: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
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

        .content-area {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            background-color: #f2efeb;
        }

        .dashboard-header {
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            text-align: center;
            color:#0f4a24
        }

        .dashboard-header img{
            width: 300px;
            height: auto;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .form-container .btn {
            background-color: var(--primary-color);
            color: white;
            width: 48%;
            margin: 5px;
        }

        .form-container .btn:hover {
            background-color: var(--primary-dark);
        }

        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .table-container h5 {
            color: var(--primary-dark);
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .table th {
            background-color: var(--primary-color);
            color: white;s
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: visible;
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
            
            .profile-section {
                padding: 20px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="profile-section">
                <img src="<?= get_profile_image_url() ?>" alt="Profile Image" class="profile-img" id="profileImage">
                
                <div class="profile-name">
                    <h4 id="dash_lastName"><?= esc(session()->get('lastname') ?? 'LAST NAME') ?></h4>
                    <p id="dash_firstName"><?= esc(session()->get('firstname') ?? 'First Name') ?></p>
                </div>
            </div>
            
            <nav class="menu">
                <a href="<?= site_url('assoDash_main') ?>" class="menu-item active">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
                </a>
                <a href="<?= site_url('associate/reservation') ?>" class="menu-item">
                    <i class="fas fa-calendar-check"></i>
                    <span class="menu-text">Reservation</span>
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

        <main class="content-area">
            <div class="dashboard-header">
                <img src="<?= base_url('public/FEUTechBanner.png') ?>" alt="FEU Tech Banner">
                <h1><b>ASSOCIATES DASHBOARD</b></h1>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5>Borrowed</h5>
                            <h3 class="text-primary"><?= esc($borrowedCount ?? '0') ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="fas fa-undo"></i>
                            </div>
                            <h5>Returned</h5>
                            <h3 class="text-primary"><?= esc($returnedCount ?? '0') ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <?php if(session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <div class="form-container">
                                <h5 class="mb-3">Borrow Equipment</h5>
                                <form action="<?= site_url('associate/borrow') ?>" method="post">
                                    <?= csrf_field() ?>
                                    
                                    <div class="row mb-3">
                                        <div class="col-9 item-name-col">
                                            <select class="form-select" id="equipmentSelect" name="equipment_id" required>
                                                <option value="" selected disabled>Select Equipment</option>
                                                <?php if (!empty($availableEquipment)): ?>
                                                    <?php foreach ($availableEquipment as $equip): ?>
                                                        <option value="<?= esc($equip['equipment_id']) ?>" 
                                                                data-available="<?= esc($equip['available_quantity']) ?>"
                                                                data-accessories="<?= esc($equip['accessories'] ?? 'None') ?>">
                                                            <?= esc($equip['name']) ?> (Available: <?= esc($equip['available_quantity']) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <small class="text-muted" id="accessoriesInfo"></small>
                                        </div>
                                        <div class="col-3 quantity-col">
                                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Qty." min="1" max="1" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-hand-holding"></i> Borrow Equipment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="table-container">
                        <h5>Currently Borrowed Equipment</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Equipment</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Accessories</th>
                                    <th scope="col">Borrowed Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($borrowedItems)): ?>
                                    <?php foreach ($borrowedItems as $item): ?>
                                        <tr>
                                            <td><?= esc($item['name']) ?></td>
                                            <td><?= esc($item['category']) ?></td>
                                            <td><?= esc($item['quantity']) ?></td>
                                            <td><?= esc($item['accessories'] ?? 'None') ?></td>
                                            <td><?= date('M d, Y', strtotime($item['borrow_date'])) ?></td>
                                            <td>
                                                <form action="<?= site_url('associate/return') ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to return this equipment?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="transaction_id" value="<?= esc($item['borrow_id']) ?>">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No borrowed equipment</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            const equipmentSelect = document.getElementById('equipmentSelect');
            const quantityInput = document.getElementById('quantity');
            const accessoriesInfo = document.getElementById('accessoriesInfo');

            if (equipmentSelect) {
                equipmentSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const available = selectedOption.getAttribute('data-available');
                    const accessories = selectedOption.getAttribute('data-accessories');
                    
                    if (available) {
                        quantityInput.max = available;
                        quantityInput.value = 1;
                    }
                    
                    if (accessories && accessories !== 'None') {
                        accessoriesInfo.textContent = 'Includes: ' + accessories;
                        accessoriesInfo.style.color = '#176734';
                        accessoriesInfo.style.fontWeight = '500';
                    } else {
                        accessoriesInfo.textContent = '';
                    }
                });
            }

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>