<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

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
            color: white;
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

        /* Pagination Styles */
        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

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
                <a href="<?= site_url('studentDash_main') ?>" class="menu-item active">
                    <i class="fas fa-home"></i>
                    <span class="menu-text">Home</span>
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
                <h1><b>STUDENT DASHBOARD</b></h1>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="card-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5>Pending</h5>
                            <h3 class="text-primary"><?= esc($pendingCount ?? '0') ?></h3>
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
                            <div class="form-container">
                                <form action="<?= site_url('Student/process') ?>" method="post">
    <?= csrf_field() ?>

                                    <?= csrf_field() ?>
                                    
                                    <div class="row mb-3">
                                        <div class="col-9 item-name-col">
                                            <select class="form-select" name="item" id="item">
                                                <option value="">Select Item</option>

                                                <?php if (!empty($availableItems)): ?>
                                                <?php foreach ($availableItems as $item): ?>
                                                <option value="<?= $item['equipment_id']; ?>">
                                            <?= $item['name']; ?> (Available: <?= $item['available_quantity']; ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                <option value="">No available items</option>
                                    <?php endif; ?>
                                    </select>
                                        </div>
                                        <div class="col-3 quantity-col">
                                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Qty." min="1" required>
                                        </div>
                                    </div>
                                    <div class="button-row d-flex justify-content-between">
                                        <button type="submit" name="action" value="borrow" class="btn" id="borrow">Borrow</button>
                                        <button type="submit" name="action" value="return" class="btn" id="return">Return</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="table-container">
                        <h5>Active Account Status</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">BORROW ID</th>
                                    <th scope="col">ITEM NAME</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php if (isset($activeItems) && is_array($activeItems) && count($activeItems) > 0): ?>
        <?php foreach ($activeItems as $item): ?>
            <tr>
                <td><?= esc($item['borrow_id'] ?? 'N/A') ?></td>
                <td><?= esc($item['name'] ?? 'N/A') ?></td>
                <td><?= esc($item['quantity'] ?? '0') ?></td>
                <td>
                    <form action="<?= site_url('Student/returnEquipment') ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to return this equipment?');">
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
            <td colspan="4" class="text-center">No active items</td>
        </tr>
    <?php endif; ?>
</tbody>

                        </table>
                        
                        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                            <div class="pagination-container">
                                <?= $pager->links() ?>
                            </div>
                        <?php endif; ?>
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

            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const itemName = document.getElementById('itemName').value;
                const quantity = document.getElementById('quantity').value;
                
                if (!itemName || !quantity) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                    return;
                }
            });
        });

document.addEventListener("DOMContentLoaded", () => {
    loadItems(); // Auto-load dropdown on page load

    const itemDropdown = document.getElementById("itemName");
    const quantityInput = document.getElementById("quantity");
    const borrowBtn = document.getElementById("borrow");
    const returnBtn = document.getElementById("return");

    let currentAvailable = 0;
    let currentBorrowed = 0;

    // Load available items into dropdown
    function loadItems() {
        fetch("<?= site_url('dashboard/getAvailableItems') ?>")
            .then(res => res.json())
            .then(data => {
                itemDropdown.innerHTML = '<option value="" disabled selected>Select Item</option>';

                data.forEach(item => {
                    const disabled = item.available_quantity <= 0 ? "disabled" : "";
                    const label = `${item.name} (Available: ${item.available_quantity})`;

                    itemDropdown.innerHTML += `
                        <option value="${item.equipment_id}" ${disabled}>
                            ${label}
                        </option>
                    `;
                });
            });
    }

    // When an item is selected → load stock & borrowed qty
    itemDropdown.addEventListener("change", () => {
        const id = itemDropdown.value;

        // Load available qty
        fetch(`<?= site_url('dashboard/getItemStock') ?>/${id}`)
            .then(res => res.json())
            .then(data => {
                currentAvailable = data.available_quantity;
                quantityInput.placeholder = `Max: ${currentAvailable}`;
            });

        // Load how many the student currently borrowed
        fetch(`<?= site_url('dashboard/getUserBorrowedQty') ?>/${id}`)
            .then(res => res.json())
            .then(data => {
                currentBorrowed = data.borrowed;
            });
    });

    // SMART VALIDATION FOR BORROWING
    borrowBtn.addEventListener("click", e => {
        let qty = parseInt(quantityInput.value);

        if (qty > currentAvailable) {
            e.preventDefault();
            alert(`Not enough stock! Only ${currentAvailable} available.`);
        }
    });

    // SMART VALIDATION FOR RETURNING
    returnBtn.addEventListener("click", e => {
        let qty = parseInt(quantityInput.value);

        if (qty > currentBorrowed) {
            e.preventDefault();
            alert(`You cannot return more than you borrowed. Borrowed: ${currentBorrowed}`);
        }
    });

    // Live typing → prevent invalid numbers
    quantityInput.addEventListener("input", () => {
        if (itemDropdown.value === "") return;

        let qty = parseInt(quantityInput.value);

        if (qty > currentAvailable) {
            quantityInput.value = currentAvailable;
        }

        if (qty < 1) {
            quantityInput.value = 1;
        }
    });
});
    </script>
</body>
</html>