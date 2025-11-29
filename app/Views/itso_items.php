<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management - ITSO</title>

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
            background: #f2efeb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            padding: 0;
        }

        .container{
            display: flex;
            min-height: 100vh;
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
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 4px solid var(--accent-color);
        }

        .menu-item i {
            width: 30px;
            font-size: 1.1rem;
        }

        .menu-item:hover {
            transform: translateX(5px);
        }

        .content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            height: 100vh;
            overflow-y: auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top-bar h1 {
            color: var(--primary-dark);
            font-weight: 600;
            margin: 0;
        }

        .user-table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table thead {
            background: var(--primary-color);
            color: white;
        }

        .user-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .user-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .user-table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-edit, .btn-deactivate, .btn-activate {
            padding: 6px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #ffc107;
            color: #000;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-deactivate {
            background: #dc3545;
            color: white;
        }

        .btn-deactivate:hover {
            background: #c82333;
        }

        .btn-activate {
            background: #28a745;
            color: white;
        }

        .btn-activate:hover {
            background: #218838;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h2 {
            color: var(--primary-dark);
            margin: 0;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.95rem;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-cancel, .btn-save {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .btn-save {
            background: var(--primary-color);
            color: white;
        }

        .btn-save:hover {
            background: var(--primary-dark);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .user-table {
                font-size: 0.85rem;
            }

            .user-table th, .user-table td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <!-- SIDEBAR -->
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
            <a href="<?= site_url('itso/items') ?>" class="menu-item active">
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

    <!-- CONTENT -->
    <main class="content">

        <div class="top-bar">
            <h1>Equipment Management</h1>
            <button class="add-btn" onclick="openAddModal()" title="Add Equipment" style="width:45px;height:45px;border-radius:50%;font-size:25px;cursor:pointer;border:none;background:#176734;color:white;">+</button>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="user-table-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Accessories</th>
                        <th>Available / Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($equipment)): ?>
                        <?php foreach($equipment as $item): ?>
                            <tr>
                                <td><?= esc($item['equipment_id']) ?></td>
                                <td><?= esc($item['name']) ?></td>
                                <td><?= esc($item['category']) ?></td>
                                <td><?= !empty($item['accessories']) ? esc($item['accessories']) : 'None' ?></td>
                                <td><?= $item['available_quantity'] ?> / <?= $item['total_quantity'] ?></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="btn-edit" onclick="openEditModal(<?= esc($item['equipment_id']) ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="<?= site_url('itso/equipment/deactivate') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to deactivate this equipment?')">
                                            <input type="hidden" name="equipment_id" value="<?= esc($item['equipment_id']) ?>">
                                            <button type="submit" class="btn-deactivate">
                                                <i class="fas fa-ban"></i> Deactivate
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">No equipment found. Click + to add equipment.</td>
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

<!-- Add Equipment Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Equipment</h2>
        </div>
        <form action="<?= site_url('itso/equipment/add') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="add_name">Equipment Name *</label>
                    <input type="text" id="add_name" name="name" required placeholder="e.g., MacBook Pro">
                </div>
                <div class="form-group">
                    <label for="add_category">Category *</label>
                    <select id="add_category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Laptops">Laptops</option>
                        <option value="DLP Projectors">DLP Projectors</option>
                        <option value="Cables">Cables (HDMI/VGA)</option>
                        <option value="Remote Controls">Remote Controls</option>
                        <option value="Keyboards & Mouse">Keyboards & Mouse</option>
                        <option value="Drawing Tablets">Drawing Tablets (Wacom)</option>
                        <option value="Speakers">Speakers</option>
                        <option value="Webcams">Webcams</option>
                        <option value="Extension Cords">Extension Cords</option>
                        <option value="Tools">Tools (Cable Crimping/Testers)</option>
                        <option value="Keys">Lab Room Keys</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="add_description">Description</label>
                    <textarea id="add_description" name="description" placeholder="Equipment description"></textarea>
                </div>
                <div class="form-group">
                    <label for="add_quantity">Total Quantity *</label>
                    <input type="number" id="add_quantity" name="total_quantity" required min="1" value="1">
                </div>
                <div class="form-group">
                    <label for="add_accessories">Accessories (comma-separated)</label>
                    <input type="text" id="add_accessories" name="accessories" placeholder="e.g., Charger, VGA Cable, Remote">
                </div>
                <div class="form-group">
                    <label for="add_equipment_image">Equipment Image (Optional)</label>
                    <input type="file" id="add_equipment_image" name="equipment_image" accept="image/*" style="padding: 5px;">
                    <small style="display: block; margin-top: 5px; color: #666;">Max 2MB. Allowed: JPG, PNG, GIF</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn-save">Add Equipment</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Equipment Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Equipment</h2>
        </div>
        <form action="<?= site_url('itso/equipment/update') ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="edit_equipment_id" name="equipment_id">
                
                <div class="form-group">
                    <label for="edit_name">Equipment Name *</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="edit_category">Category *</label>
                    <select id="edit_category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Laptops">Laptops</option>
                        <option value="DLP Projectors">DLP Projectors</option>
                        <option value="Cables">Cables (HDMI/VGA)</option>
                        <option value="Remote Controls">Remote Controls</option>
                        <option value="Keyboards & Mouse">Keyboards & Mouse</option>
                        <option value="Drawing Tablets">Drawing Tablets (Wacom)</option>
                        <option value="Speakers">Speakers</option>
                        <option value="Webcams">Webcams</option>
                        <option value="Extension Cords">Extension Cords</option>
                        <option value="Tools">Tools (Cable Crimping/Testers)</option>
                        <option value="Keys">Lab Room Keys</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_total_quantity">Total Quantity *</label>
                    <input type="number" id="edit_total_quantity" name="total_quantity" required min="1">
                </div>

                <div class="form-group">
                    <label for="edit_available_quantity">Available Quantity *</label>
                    <input type="number" id="edit_available_quantity" name="available_quantity" required min="0">
                </div>

                <div class="form-group">
                    <label for="edit_accessories">Accessories (comma-separated)</label>
                    <input type="text" id="edit_accessories" name="accessories">
                </div>

                <div class="form-group">
                    <label for="edit_equipment_image">Equipment Image (Optional)</label>
                    <input type="file" id="edit_equipment_image" name="equipment_image" accept="image/*" style="padding: 5px;">
                    <small style="display: block; margin-top: 5px; color: #666;">Max 2MB. Allowed: JPG, PNG, GIF. Leave empty to keep current image.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.add('show');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.remove('show');
    }

    function openEditModal(equipmentId) {
        fetch(`<?= site_url('itso/equipment/get/') ?>${equipmentId}`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('edit_equipment_id').value = data.equipment.equipment_id;
                    document.getElementById('edit_name').value = data.equipment.name;
                    document.getElementById('edit_category').value = data.equipment.category;
                    document.getElementById('edit_description').value = data.equipment.description || '';
                    document.getElementById('edit_total_quantity').value = data.equipment.total_quantity;
                    document.getElementById('edit_available_quantity').value = data.equipment.available_quantity;
                    document.getElementById('edit_accessories').value = data.equipment.accessories || '';
                    document.getElementById('editModal').classList.add('show');
                } else {
                    alert('Error loading equipment data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading equipment data');
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        
        if (event.target === addModal) {
            closeAddModal();
        }
        if (event.target === editModal) {
            closeEditModal();
        }
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
</body>
</html>
