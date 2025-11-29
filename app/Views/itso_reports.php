<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            background: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background: linear-gradient(to bottom, transparent 0%, rgba(208, 197, 42, 0.1) 20%, rgba(208, 197, 42, 0.2) 40%, rgba(208, 197, 42, 0.3) 100%);
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
            height: 100vh;
            overflow-y: auto;
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

        .report-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .report-card h4 {
            color: var(--primary-color);
            margin-bottom: 15px;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
        }

        .badge-active {
            background-color: #28a745;
        }

        .badge-inactive {
            background-color: #dc3545;
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

        @media print {
            .sidebar, .no-print {
                display: none !important;
            }
            .content-area {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="profile-section">
            <img src="<?= get_profile_image_url() ?>" alt="Profile" class="profile-img">
            <div class="profile-name">
                <h4>ITSO</h4>
                <p>Administrator</p>
            </div>
        </div>
        
        <nav class="menu">
            <a href="<?= site_url('itsoDash_main') ?>" class="menu-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= site_url('itso/items') ?>" class="menu-item">
                <i class="fas fa-box"></i>
                <span>Items</span>
            </a>
            <a href="<?= site_url('itso/users') ?>" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="<?= site_url('itso/records') ?>" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Records</span>
            </a>
            <a href="<?= site_url('itso/reports') ?>" class="menu-item active">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            <a href="<?= site_url('about') ?>" class="menu-item">
                <i class="fas fa-info-circle"></i>
                <span>About</span>
            </a>
            <a href="<?= site_url('auth/logout') ?>" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Log out</span>
            </a>
        </nav>
    </aside>

    <main class="content-area">
        <div class="dashboard-header">
            <img src="<?= base_url('public/FEUTechBanner.png') ?>" alt="FEU Tech Banner">
            <h1><b>REPORTS</b></h1>
        </div>

        <!-- Report Options -->
        <div class="row mb-4 no-print">
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-3x mb-3" style="color: var(--primary-color);"></i>
                        <h5>Active Equipment</h5>
                        <p class="text-muted">View all active equipment in inventory</p>
                        <a href="<?= site_url('itso/reports?type=active') ?>" class="btn btn-primary">Generate</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3" style="color: #ffc107;"></i>
                        <h5>Unusable Equipment</h5>
                        <p class="text-muted">View deactivated equipment</p>
                        <a href="<?= site_url('itso/reports?type=unusable') ?>" class="btn btn-primary">Generate</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-history fa-3x mb-3" style="color: #17a2b8;"></i>
                        <h5>Borrowing History</h5>
                        <p class="text-muted">View user borrowing records</p>
                        <a href="<?= site_url('itso/reports?type=history') ?>" class="btn btn-primary">Generate</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Equipment Report -->
        <div id="active-equipment-report" class="report-card" style="display: <?= isset($reportType) && $reportType === 'active' ? 'block' : 'none' ?>;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-boxes"></i> Active Equipment Report</h4>
                <div class="no-print">
                    <button class="btn btn-success btn-sm" onclick="exportToCSV('active-equipment')">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="btn btn-info btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="<?= site_url('itso/reports') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Close
                    </a>
                </div>
            </div>
            <p class="text-muted">Generated on: <?= date('F d, Y h:i A') ?></p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="activeEquipmentTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipment Name</th>
                            <th>Category</th>
                            <th>Total Quantity</th>
                            <th>Available</th>
                            <th>Borrowed</th>
                            <th>Accessories</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($activeEquipment) && count($activeEquipment) > 0): ?>
                            <?php foreach ($activeEquipment as $item): ?>
                                <tr>
                                    <td><?= esc($item['equipment_id']) ?></td>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td><?= esc($item['total_quantity']) ?></td>
                                    <td><?= esc($item['available_quantity']) ?></td>
                                    <td><?= $item['total_quantity'] - $item['available_quantity'] ?></td>
                                    <td><?= esc($item['accessories'] ?? 'None') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No active equipment found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <?php if (isset($pager) && $reportType === 'active'): ?>
            <div class="no-print" style="margin-top: 20px; display: flex; justify-content: center;">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Unusable Equipment Report -->
        <div id="unusable-equipment-report" class="report-card" style="display: <?= isset($reportType) && $reportType === 'unusable' ? 'block' : 'none' ?>;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-exclamation-triangle"></i> Unusable Equipment Report</h4>
                <div class="no-print">
                    <button class="btn btn-success btn-sm" onclick="exportToCSV('unusable-equipment')">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="btn btn-info btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="<?= site_url('itso/reports') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Close
                    </a>
                </div>
            </div>
            <p class="text-muted">Generated on: <?= date('F d, Y h:i A') ?></p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="unusableEquipmentTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipment Name</th>
                            <th>Category</th>
                            <th>Total Quantity</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($unusableEquipment) && count($unusableEquipment) > 0): ?>
                            <?php foreach ($unusableEquipment as $item): ?>
                                <tr>
                                    <td><?= esc($item['equipment_id']) ?></td>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td><?= esc($item['total_quantity']) ?></td>
                                    <td><?= esc($item['description']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No unusable equipment found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <?php if (isset($pager) && $reportType === 'unusable'): ?>
            <div class="no-print" style="margin-top: 20px; display: flex; justify-content: center;">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Borrowing History Report -->
        <div id="borrowing-history-report" class="report-card" style="display: <?= isset($reportType) && $reportType === 'history' ? 'block' : 'none' ?>;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-history"></i> Borrowing History Report</h4>
                <div class="no-print">
                    <button class="btn btn-success btn-sm" onclick="exportToCSV('borrowing-history')">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                    <button class="btn btn-info btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="<?= site_url('itso/reports') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Close
                    </a>
                </div>
            </div>
            <p class="text-muted">Generated on: <?= date('F d, Y h:i A') ?></p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="borrowingHistoryTable">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Equipment</th>
                            <th>Quantity</th>
                            <th>Borrow Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($borrowingHistory) && count($borrowingHistory) > 0): ?>
                            <?php foreach ($borrowingHistory as $record): ?>
                                <tr>
                                    <td><?= esc($record['borrow_id']) ?></td>
                                    <td><?= esc($record['user_firstname'] . ' ' . $record['user_lastname']) ?></td>
                                    <td><?= esc($record['user_email']) ?></td>
                                    <td><?= esc($record['equipment_name']) ?></td>
                                    <td><?= esc($record['quantity']) ?></td>
                                    <td><?= date('M d, Y h:i A', strtotime($record['borrow_date'])) ?></td>
                                    <td><?= $record['return_date'] ? date('M d, Y h:i A', strtotime($record['return_date'])) : 'Not returned' ?></td>
                                    <td>
                                        <?php if ($record['returned'] == 1): ?>
                                            <span class="badge bg-success">Returned</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Borrowed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No borrowing history found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <?php if (isset($pager) && $reportType === 'history'): ?>
            <div class="no-print" style="margin-top: 20px; display: flex; justify-content: center;">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showReport(reportType) {
            hideReports();
            document.getElementById(reportType + '-report').style.display = 'block';
            window.scrollTo({ top: document.getElementById(reportType + '-report').offsetTop - 20, behavior: 'smooth' });
        }

        function hideReports() {
            document.getElementById('active-equipment-report').style.display = 'none';
            document.getElementById('unusable-equipment-report').style.display = 'none';
            document.getElementById('borrowing-history-report').style.display = 'none';
        }

        function exportToCSV(reportType) {
            let tableId = '';
            let filename = '';
            
            switch(reportType) {
                case 'active-equipment':
                    tableId = 'activeEquipmentTable';
                    filename = 'active_equipment_report.csv';
                    break;
                case 'unusable-equipment':
                    tableId = 'unusableEquipmentTable';
                    filename = 'unusable_equipment_report.csv';
                    break;
                case 'borrowing-history':
                    tableId = 'borrowingHistoryTable';
                    filename = 'borrowing_history_report.csv';
                    break;
            }
            
            const table = document.getElementById(tableId);
            let csv = [];
            
            // Get headers
            const headers = [];
            table.querySelectorAll('thead th').forEach(th => {
                headers.push(th.textContent);
            });
            csv.push(headers.join(','));
            
            // Get rows
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach(td => {
                    let text = td.textContent.replace(/,/g, ';');
                    row.push('"' + text + '"');
                });
                csv.push(row.join(','));
            });
            
            // Download
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
