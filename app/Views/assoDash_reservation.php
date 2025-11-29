<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>

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

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 0 auto;
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

        .form-container .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .form-container .btn-cancel:hover {
            background-color: #5a6268;
        }

        .form-label {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 8px;
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

            .form-container {
                padding: 20px;
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
                <a href="<?= site_url('assoDash_main') ?>" class="menu-item">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
                </a>
                <a href="<?= site_url('associate/reservation') ?>" class="menu-item active">
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
                <h1><b>RESERVATION</b></h1>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-center" style="background: linear-gradient(135deg, #176734 0%, #0f4a24 100%); color: white;">
                        <div class="card-body">
                            <h5 class="card-title">Pending Reservations</h5>
                            <h2><?= $pendingCount ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center" style="background: linear-gradient(135deg, #d0c52a 0%, #b8ae24 100%); color: #0f4a24;">
                        <div class="card-body">
                            <h5 class="card-title">Confirmed Reservations</h5>
                            <h2><?= $confirmedCount ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-container mb-4">
                <h4 class="mb-3" style="color: var(--primary-dark);">Create New Reservation</h4>
                <form action="<?= site_url('associate/reservation/create') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Select Equipment</label>
                        <select class="form-select" id="equipmentSelect" name="equipment_id" required>
                            <option value="" selected disabled>Choose equipment...</option>
                            <?php if (isset($availableEquipment) && is_array($availableEquipment)): ?>
                                <?php foreach ($availableEquipment as $item): ?>
                                    <option value="<?= esc($item['equipment_id']) ?>" 
                                            data-available="<?= esc($item['total_quantity']) ?>"
                                            data-accessories="<?= esc($item['accessories'] ?? 'None') ?>">
                                        <?= esc($item['name']) ?> - <?= esc($item['category']) ?> (Available: <?= esc($item['total_quantity']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted" id="accessoriesInfo"></small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   placeholder="Enter quantity" min="1" max="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pickup Date & Time</label>
                            <input type="datetime-local" class="form-control" id="pickupDate" name="pickup_date" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any special requirements or notes..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Reservations must be made at least <strong>1 day in advance</strong>.
                    </div>
                    
                    <div class="button-row d-flex justify-content-between">
                        <button type="button" class="btn btn-cancel" onclick="this.form.reset();">Clear</button>
                        <button type="submit" class="btn">Create Reservation</button>
                    </div>
                </form>
            </div>

            <!-- Upcoming Reservations -->
            <div class="card">
                <div class="card-header" style="background-color: var(--primary-color); color: white;">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Upcoming Reservations</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($upcomingReservations) && count($upcomingReservations) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background-color: var(--light-bg);">
                                    <tr>
                                        <th>Equipment</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>Pickup Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingReservations as $reservation): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($reservation['equipment_name']) ?></strong>
                                                <?php if (!empty($reservation['accessories'])): ?>
                                                    <br><small class="text-muted">Includes: <?= esc($reservation['accessories']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($reservation['category']) ?></td>
                                            <td><?= esc($reservation['quantity']) ?></td>
                                            <td><?= date('M d, Y h:i A', strtotime($reservation['pickup_date'])) ?></td>
                                            <td>
                                                <?php if ($reservation['status'] == 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php elseif ($reservation['status'] == 'confirmed'): ?>
                                                    <span class="badge bg-success">Confirmed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rescheduleModal<?= $reservation['reservation_id'] ?>">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button>
                                                    <form action="<?= site_url('associate/reservation/cancel') ?>" method="post" style="display: inline;" 
                                                          onsubmit="return confirm('Cancel this reservation?');">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id'] ?>">
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Reschedule Modal -->
                                        <div class="modal fade" id="rescheduleModal<?= $reservation['reservation_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: var(--primary-color); color: white;">
                                                        <h5 class="modal-title">Reschedule Reservation</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="<?= site_url('associate/reservation/reschedule') ?>" method="post">
                                                        <?= csrf_field() ?>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id'] ?>">
                                                            <p><strong>Equipment:</strong> <?= esc($reservation['equipment_name']) ?></p>
                                                            <p><strong>Current Pickup:</strong> <?= date('M d, Y h:i A', strtotime($reservation['pickup_date'])) ?></p>
                                                            <hr>
                                                            <div class="mb-3">
                                                                <label class="form-label">New Pickup Date & Time</label>
                                                                <input type="datetime-local" class="form-control" name="new_pickup_date" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn" style="background-color: var(--primary-color); color: white;">Reschedule</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>No upcoming reservations</p>
                        </div>
                    <?php endif; ?>
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
            const pickupDate = document.getElementById('pickupDate');

            // Set minimum pickup date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(8, 0, 0, 0); // Default to 8 AM
            const minDate = tomorrow.toISOString().slice(0, 16);
            pickupDate.setAttribute('min', minDate);
            pickupDate.value = minDate;

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