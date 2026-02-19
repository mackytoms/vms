<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk V-Pass Admin - <?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #f39c12;
            --primary-dark: #e67e22;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --info-color: #3498db;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: #f5f6fa; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 250px; background: var(--sidebar-bg); transition: all 0.3s ease; z-index: 1000; overflow-y: auto; }
        .sidebar.collapsed { width: 70px; }
        .sidebar-header { background: linear-gradient(135deg, #f39c12, #1e9338); padding: 20px; text-align: center; color: white; }
        .sidebar-header h3 { margin: 0; font-size: 1.5em; font-weight: 600; }
        .sidebar.collapsed .sidebar-header h3 { display: none; }
        .sidebar.collapsed .user-filter-badge { display: none; }
        .sidebar-logo { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 10px; }
        .sidebar-logo img { width: 40px; height: 40px; background: white; border-radius: 10px; padding: 5px; object-fit: contain; }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-item { padding: 15px 20px; color: #ecf0f1; display: flex; align-items: center; gap: 15px; cursor: pointer; transition: all 0.3s ease; position: relative; }
        .sidebar-item:hover { background: var(--sidebar-hover); padding-left: 25px; }
        .sidebar-item.active { background: var(--sidebar-hover); border-left: 4px solid var(--primary-color); }
        .sidebar-item i { font-size: 1.3em; width: 30px; text-align: center; }
        .sidebar-item.logout {color: #2c3e50 !important;}
        .sidebar-item.logout:hover {color: #e0e0e0 !important; background-color: #dc3545 !important;}
        .sidebar.collapsed .sidebar-item span { display: none; }
        .sidebar-badge { background: var(--danger-color); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75em; margin-left: auto; }
        .main-content { margin-left: 250px; transition: all 0.3s ease; min-height: 100vh; }
        .main-content.expanded { margin-left: 70px; }
        .topbar { background: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .topbar-left { display: flex; align-items: center; gap: 20px; }
        .menu-toggle { font-size: 1.5em; cursor: pointer; color: var(--sidebar-bg); }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .dashboard-content { padding: 30px; }
        .page-title { font-size: 2em; color: var(--sidebar-bg); margin-bottom: 10px; }
        .page-subtitle { color: #7f8c8d; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: relative; overflow: hidden; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .stat-card-icon { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 3em; opacity: 0.1; }
        .stat-value { font-size: 2.5em; font-weight: 600; margin-bottom: 5px; }
        .stat-label { color: #7f8c8d; font-size: 0.95em; }
        .quick-stats { display: flex; gap: 20px; padding: 15px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 12px; color: white; margin-bottom: 20px; }
        .quick-stat-item { text-align: center; flex: 1; }
        .quick-stat-value { font-size: 1.8em; font-weight: 600; }
        .quick-stat-label { font-size: 0.9em; opacity: 0.9; }
        .table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .table td, .table tr, .table th { overflow: hidden; text-overflow: ellipsis; word-wrap: break-word; white-space: normal; }
        .visitor-photo { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #f8f9fa; display: inline-flex; align-items: center; justify-content: center; }
        .visitor-photo img { width: 100%; height: 100%; object-fit: cover; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 500; }
        .status-badge.checked-in { background: rgba(39, 174, 96, 0.1); color: var(--success-color); }
        .status-badge.checked-out { background: rgba(52, 152, 219, 0.1); color: var(--info-color); }
        .badge-number { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 2px 8px; border-radius: 6px; font-size: 0.85em; font-weight: 600; }
        .action-btn { padding: 5px 10px; border: none; background: none; cursor: pointer; font-size: 1.1em; transition: all 0.3s ease; }
        .action-btn:hover { transform: scale(1.2); }
        .action-btn.view { color: var(--info-color); }
        .action-btn.edit { color: var(--primary-color); }
        .action-btn.delete { color: var(--danger-color); }
        .company-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 500; display: inline-block; }
        .company-badge.toms-world { background: rgba(243, 156, 18, 0.15); color: #f39c12; border: 1px solid #f39c12; }
        .company-badge.pan-asia { background: rgba(30, 147, 56, 0.15); color: #1e9338; border: 1px solid #1e9338; }
        .info-grid { background: #f8f9fa; padding: 15px; border-radius: 8px; }
        .info-grid .row { padding: 5px 0; border-bottom: 1px solid #e0e0e0; }
        .info-grid .row:last-child { border-bottom: none; }
        .user-filter-badge { background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 0.85em; margin-top: 10px; display: inline-block; }
        .notes-text { font-size: 0.85em; color: #6c757d; font-style: italic; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .notes-text:hover { overflow: visible; white-space: normal; word-wrap: break-word; }
        .modal-header.tw-admin { background: linear-gradient(135deg, #f39c12, #e67e22) !important; color: white; }
        .modal-header.pa-admin { background: linear-gradient(135deg, #1e9338, #0e7a28) !important; color: white; }
        .modal-header.super-admin { background: linear-gradient(135deg, #f39c12, #1e9338) !important; color: white; }
        .modal-header.history-modal.tw-admin { background: linear-gradient(135deg, #f39c12, #e67e22) !important; }
        .modal-header.history-modal.pa-admin { background: linear-gradient(135deg, #1e9338, #0e7a28) !important; }
        .modal-header.history-modal.super-admin { background: linear-gradient(135deg, #3498db, #2980b9) !important; }
        .modal-header.employee-modal.tw-admin, .modal-header.employee-modal.pa-admin { background: linear-gradient(135deg, #9b59b6, #8e44ad) !important; }
        .modal-header.department-modal.tw-admin, .modal-header.department-modal.pa-admin { background: linear-gradient(135deg, #e67e22, #d35400) !important; }
        .text-purple { color: #800080 !important; }
        .badge.bg-purple { background-color: #800080 !important; color: white; }
        .badge { padding: 4px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 500; }
        .emergency-alert-popup { border: 3px solid #e74c3c !important; box-shadow: 0 0 30px rgba(231, 76, 60, 0.5) !important; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }

        /* Add this to your existing styles */
        .modal-lg {
            max-width: 900px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        hr.my-3 {
            border-top: 2px solid #e0e0e0;
        }

        /* ============================================
        ADD THESE STYLES TO YOUR EXISTING <style> SECTION
        ============================================ */

        /* Filter Container Styles */
        .filter-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filter-container .form-label {
            font-size: 0.85em;
            color: #495057;
            margin-bottom: 5px;
        }

        .filter-container .form-label i {
            margin-right: 5px;
            color: var(--primary-color);
        }

        .filter-container .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .filter-container .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.15);
        }

        .filter-container .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-container .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
        }

        .filter-container .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
        }

        .filter-container .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .filter-container .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Filter Badge Counter */
        .filter-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: white;
            font-size: 0.75em;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }

        /* Active Filter Indicator */
        .filter-active {
            position: relative;
        }

        .filter-active::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background: var(--danger-color);
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .filter-container .row {
                gap: 10px;
            }
            
            .filter-container .col-md-2,
            .filter-container .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .filter-container .btn {
                margin-top: 5px;
            }
        }

        /* Animation for filter results */
        @keyframes filterFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #employeeTableBody tr,
        #departmentTableBody tr,
        #purposeTableBody tr {
            animation: filterFadeIn 0.3s ease;
        }

        /* Filter Dropdown Hover Effects */
        .filter-container .form-select option:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Quick Filter Pills (Optional Enhancement) */
        .quick-filter-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #dee2e6;
        }

        .quick-filter-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 0.8em;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-filter-pill:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .quick-filter-pill.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .quick-filter-pill i {
            margin-right: 5px;
        }

        /* Icon Selector Styles */
        .icon-select-wrapper {
            position: relative;
        }

        .icon-select-wrapper .form-select {
            padding-left: 40px;
        }

        .icon-select-preview {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2em;
            pointer-events: none;
            z-index: 5;
        }

        .icon-option {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Custom dropdown for icons */
        .icon-dropdown {
            max-height: 300px;
            overflow-y: auto;
        }

        .icon-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .icon-dropdown-item:hover {
            background: #f0f0f0;
        }

        .icon-dropdown-item i {
            font-size: 1.3em;
            width: 25px;
            text-align: center;
        }

        .icon-dropdown-item.selected {
            background: #e3f2fd;
            font-weight: 500;
        }

        /* Report Cards */
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }

        .report-card.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #fff9e6, #fff);
        }

        .report-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.8em;
            color: white;
        }

        .report-card h5 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        /* Chart Container */
        .chart-container {
            min-height: 350px;
        }

        /* Report Summary Cards */
        .report-summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
        }

        .report-summary-card.primary { border-left-color: #3498db; }
        .report-summary-card.success { border-left-color: #27ae60; }
        .report-summary-card.warning { border-left-color: #f39c12; }
        .report-summary-card.danger { border-left-color: #e74c3c; }
        .report-summary-card.info { border-left-color: #00bcd4; }

        .report-summary-value {
            font-size: 2em;
            font-weight: 700;
            color: #2c3e50;
        }

        .report-summary-label {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Report Table Styling */
        #reportDataTable thead {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
        }

        #reportDataTable thead th {
            border: none;
            padding: 12px 15px;
            font-weight: 500;
        }

        #reportDataTable tfoot {
            background: #f8f9fa;
            font-weight: 600;
        }

        #reportDataTable tbody tr:hover {
            background: #f5f6fa;
        }

        /* Print Styles */
        @media print {
            .sidebar, .topbar, .filter-container, .btn, #reportTypeCards {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .table-container {
                box-shadow: none !important;
            }
            
            #reportResults {
                display: block !important;
            }
        }

        /* Animation for report cards */
        @keyframes reportCardPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .report-card.generating {
            animation: reportCardPulse 1s infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .report-card {
                padding: 15px;
            }
            
            .report-card-icon {
                width: 45px;
                height: 45px;
                font-size: 1.4em;
            }
            
            .chart-container {
                margin-bottom: 20px;
            }
        }

        /* Bulk checkout button styles */
        #bulkCheckoutBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        #bulkCheckoutBtn:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(240, 173, 78, 0.4);
        }

        /* Checkbox styling */
        .visit-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        #selectAllVisits {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Row hover effect when checkbox is checked */
        tr:has(.visit-checkbox:checked) {
            background-color: rgba(13, 110, 253, 0.05) !important;
        }

        /* Selected count badge animation */
        #selectedCount {
            transition: all 0.3s ease;
        }

        #bulkCheckoutBtn:not(:disabled) #selectedCount {
            animation: pulse-badge 1.5s infinite;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        
        /* Bulk checkout button styles */
        #bulkCheckoutBtn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        #bulkCheckoutBtn:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(240, 173, 78, 0.4);
        }

        /* Checkbox styling */
        .visit-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        #selectAllVisits {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Row hover effect when checkbox is checked */
        tr:has(.visit-checkbox:checked) {
            background-color: rgba(13, 110, 253, 0.05) !important;
        }

        /* Selected count badge animation */
        #selectedCount {
            transition: all 0.3s ease;
        }

        #bulkCheckoutBtn:not(:disabled) #selectedCount {
            animation: pulse-badge 1.5s infinite;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }


        /* Auto-Logout Modal Styles */
        #sessionWarningModal .modal-content {
            border-radius: 16px;
        }

        #sessionWarningModal .modal-header {
            border-radius: 16px 16px 0 0;
            padding: 20px 24px 20px;
        }

        #sessionWarningIcon {
            animation: pulseIcon 1.5s ease-in-out infinite;
        }

        @keyframes pulseIcon {
            0%, 100% { transform: scale(1); background: rgba(255,255,255,0.2); }
            50%       { transform: scale(1.1); background: rgba(255,255,255,0.35); }
        }

        #countdownArc {
            filter: drop-shadow(0 0 4px rgba(231,76,60,0.4));
        }

        /* Urgency pulse when <= 10 seconds */
        .countdown-urgent #countdownSeconds {
            animation: urgentPulse 0.6s ease-in-out infinite;
        }

        @keyframes urgentPulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }

        #sessionExpiredModal .modal-content {
            border-radius: 16px;
        }
    </style>
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <?php if ($companyFilter !== 'Pan Asia'): ?>
                <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" alt="TOMS WORLD" onerror="this.style.display='none'">
                <?php endif; ?>
                <?php if ($companyFilter !== 'Toms World'): ?>
                <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" alt="PAN-ASIA" onerror="this.style.display='none'">
                <?php endif; ?>
            </div>
            <h3>KIOSK V-PASS</h3>
            <?php if ($companyFilter): ?>
            <div class="user-filter-badge">
                <i class="bi bi-building"></i> <?php echo $companyFilter === 'Toms World' ? "Tom's World" : "Pan-Asia"; ?> Admin
            </div>
            <?php else: ?>
            <div class="user-filter-badge">
                <i class="bi bi-shield-check"></i> Super Admin
            </div>
            <?php endif; ?>
        </div>
        <div class="sidebar-menu">
            <div class="sidebar-item active" onclick="showSection('dashboard')">
                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
            </div>
            <div class="sidebar-item" onclick="showSection('active-visits')">
                <i class="bi bi-person-check"></i><span>Active Visits</span>
                <span class="sidebar-badge" id="activeVisitCount"><?php echo $dashboardStats['currently_in']; ?></span>
            </div>
            <div class="sidebar-item" onclick="showSection('visitors')">
                <i class="bi bi-people"></i><span>All Visitors</span>
            </div>
            <div class="sidebar-item" onclick="showSection('employees')">
                <i class="bi bi-person-badge"></i><span>Employees</span>
            </div>
            <div class="sidebar-item" onclick="showSection('departments')">
                <i class="bi bi-building"></i><span>Departments</span>
            </div>
            <div class="sidebar-item" onclick="showSection('purposes')">
                <i class="bi bi-flag"></i><span>Purposes</span>
            </div>
            <div class="sidebar-item" onclick="showSection('reports')">
                <i class="bi bi-file-earmark-bar-graph"></i><span>Reports</span>
            </div>
            <div class="sidebar-item" onclick="showSection('settings')">
                <i class="bi bi-gear"></i><span>Settings</span>
            </div>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        <div class="topbar">
            <div class="topbar-left">
                <i class="bi bi-list menu-toggle" onclick="toggleSidebar()"></i>
            </div>
            <div class="topbar-right">
            <a href="<?= base_url('auth/logout') ?>" class="sidebar-item logout" onclick="return confirmLogout(event)">
                <i class="bi bi-box-arrow-left"></i><span>Logout</span>
            </a>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div class="dashboard-content" id="dashboardSection">
            <h1 class="page-title">Visitor Management Dashboard</h1>
            <p class="page-subtitle"><?php echo $welcomeMessage; ?></p>
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="todayTotal"><?php echo $dashboardStats['today_total']; ?></div>
                    <div class="quick-stat-label">Today's Visitors</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="currentlyIn"><?php echo $dashboardStats['currently_in']; ?></div>
                    <div class="quick-stat-label">Currently In Building</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="currentlyOut"><?php echo $dashboardStats['today_total'] - $dashboardStats['currently_in']; ?></div>
                    <div class="quick-stat-label">Out of the Building</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="avgDuration"><?php echo $dashboardStats['avg_duration']; ?></div>
                    <div class="quick-stat-label">Avg. Visit Duration</div>
                </div>
            </div>
            <div class="table-container mb-4">
                <div class="table-header">
                    <h3 class="chart-title">Recent Check-ins</h3>
                    <button class="btn btn-outline-secondary btn-sm" onclick="refreshDashboard()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
                <table class="table table-hover" id="recentActivityTable">
                    <thead>
                        <tr>
                            <th>Badge #</th><th>Visitor</th><th>Company / Branch</th><th>Host</th><th>Purpose</th><th>Notes</th><th>Visiting</th><th>Check-In</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentActivityTableBody">
                        <?php 
                        $purposes_query = $this->db->query("SELECT purpose_code, purpose_name, color_class FROM purposes");
                        $purposes_style_map = array();
                        foreach ($purposes_query->result_array() as $p) {
                            $color_map = array(
                                'text-primary' => 'bg-primary',
                                'text-success' => 'bg-success',
                                'text-warning' => 'bg-warning',
                                'text-danger' => 'bg-danger',
                                'text-info' => 'bg-info',
                                'text-secondary' => 'bg-secondary',
                                'text-dark' => 'bg-dark',
                                'text-purple' => 'bg-purple'
                            );
                            $badge_class = isset($color_map[$p['color_class']]) ? $color_map[$p['color_class']] : 'bg-secondary';
                            $purposes_style_map[$p['purpose_code']] = array(
                                'name' => $p['purpose_name'],
                                'class' => $badge_class
                            );
                        }
                        
                        foreach($recentActivity as $activity): 
                            $companyBadge = '';
                            if ($activity['company_visited'] == 'Toms World') {
                                $companyBadge = '<span class="company-badge toms-world"><i class="bi bi-building"></i> Tom\'s World</span>';
                            } elseif ($activity['company_visited'] == 'Pan Asia') {
                                $companyBadge = '<span class="company-badge pan-asia"><i class="bi bi-building"></i> Pan-Asia</span>';
                            } else {
                                $companyBadge = '<span class="badge bg-secondary">' . ($activity['company_visited'] ?? 'N/A') . '</span>';
                            }
                            
                            $purpose_code = $activity['purpose'];
                            $purpose_html = '<span class="badge bg-secondary">N/A</span>';
                            if (isset($purposes_style_map[$purpose_code])) {
                                $purpose_html = '<span class="badge ' . $purposes_style_map[$purpose_code]['class'] . '">' 
                                            . $purposes_style_map[$purpose_code]['name'] . '</span>';
                            } elseif ($purpose_code) {
                                $purpose_html = '<span class="badge bg-secondary">' . $purpose_code . '</span>';
                            }
                        ?>
                        <tr>
                            <td><span class="badge-number"><?php echo $activity['badge_number']; ?></span></td>
                            <td><?php echo $activity['first_name'] . ' ' . $activity['last_name']; ?></td>
                            <td><?php echo $activity['company']; ?></td>
                            <td><?php echo $activity['host_name']; ?></td>
                            <td><?php echo $purpose_html; ?></td>
                            <td><span class="notes-text" title="<?php echo htmlspecialchars($activity['additional_notes'] ?? ''); ?>"><?php echo $activity['additional_notes'] ? htmlspecialchars($activity['additional_notes']) : '-'; ?></span></td>
                            <td><?php echo $companyBadge; ?></td>
                            <td><?php echo date('H:i:s', strtotime($activity['check_in_time'])); ?></td>
                            <td><?php echo $activity['check_out_time'] ? '<span class="status-badge checked-out">Checked Out</span>' : '<span class="status-badge checked-in">Checked In</span>'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Active Visits Section -->
        <div class="dashboard-content" id="active-visitsSection" style="display: none;">
            <h1 class="page-title">Active Visits</h1>
            <p class="page-subtitle">Visitors currently in the building</p>
            <div class="table-container">
            
                <div class="table-header">
                    <h3 class="chart-title">Currently Checked In</h3>
                    <button class="btn btn-outline-secondary btn-sm" onclick="loadActiveVisits()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>

                <!-- Add these buttons in your Active Visits card header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <!-- <i class="bi bi-people-fill me-2"></i>Active Visits
                        <span class="badge bg-primary ms-2" id="activeVisitCount">0</span> -->
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-warning btn-sm" id="bulkCheckoutBtn" 
                                onclick="bulkCheckoutSelected()" disabled>
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Checkout Selected (<span id="selectedCount">0</span>)
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="checkoutAllActive()">
                            <i class="bi bi-door-open me-1"></i>
                            Checkout All
                        </button>
                    </div>
                </div>

                <!-- <table class="table table-hover" id="activeVisitsTable">
                    <thead>
                        <tr>
                            <th>Badge #</th>
                            <th>Visitor</th>
                            <th>Company</th>
                            <th>Host</th>
                            <th>Department</th>
                            <th>Purpose</th>
                            <th>Notes</th>
                            <th>Visiting</th>
                            <th>Check-In</th>
                            <th>Valid Until</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeVisitsTableBody"></tbody>
                </table> -->

                <table class="table table-hover" id="activeVisitsTable">
                    <thead>
                        <tr>
                            <th>Visitor</th>
                            <th>Company / Branch</th>
                            <th>Host</th>
                            <th>Department</th>
                            <th>Check-in Time</th>
                            <th>Duration</th>
                            <th style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="selectAllVisits" 
                                    onchange="toggleSelectAll(this)">
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeVisitsBody">
                        <!-- Rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Visitors Section -->
        <div class="dashboard-content" id="visitorsSection" style="display: none;">
            <h1 class="page-title">Visitor Management</h1>
            <p class="page-subtitle">Complete visitor records</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Visitors</h3>
                </div>
                <table class="table table-hover" id="allVisitorsTable">
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Company / Branch</th><th>Type</th><th>Total Visits</th><th>Last Visit</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allVisitorsTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Employees Section -->
        <!-- <div class="dashboard-content" id="employeesSection" style="display: none;">
            <h1 class="page-title">Employee Directory</h1>
            <p class="page-subtitle">Manage employee records and host assignments</p>
            <div class="table-container">
                <div class="table-header">
                    <h4>Employee List</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="bi bi-plus-circle"></i> Add Employee
                    </button>
                </div>
                <table class="table table-hover" id="employeeTable">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Total Visits Hosted</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody"></tbody>
                </table>
            </div>
        </div> -->

        <!-- ============================================== -->
        <!-- EMPLOYEES SECTION - Replace your existing one -->
        <!-- ============================================== -->
        <div class="dashboard-content" id="employeesSection" style="display: none;">
            <h1 class="page-title">Employee Directory</h1>
            <p class="page-subtitle">Manage employee records and host assignments</p>
            <div class="table-container">
                <div class="table-header">
                    <h4>Employee List</h4>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="bi bi-plus-circle"></i> Add Employee
                    </button>
                </div>
                
                <!-- Employee Filters -->
                <div class="filter-container mb-3 p-3 bg-light rounded">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-building"></i> Department</label>
                            <select class="form-select" id="employeeDeptFilter">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold"><i class="bi bi-toggle-on"></i> Status</label>
                            <select class="form-select" id="employeeStatusFilter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-briefcase"></i> Company</label>
                            <select class="form-select" id="employeeCompanyFilter">
                                <option value="">All Companies</option>
                                <option value="Toms World">Tom's World</option>
                                <option value="Pan Asia">Pan-Asia</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" onclick="clearEmployeeFilters()">
                                <i class="bi bi-x-circle"></i> Clear
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="applyEmployeeFilters()">
                                <i class="bi bi-funnel"></i> Apply
                            </button>
                        </div>
                    </div>
                </div>
                
                <table class="table table-hover" id="employeeTable">
                    <thead>
                        <tr>
                            <th>Host ID</th>
                            <th></th>
                            <th>Name</th>
                            <th>Email & Phone Number</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Total Visits Hosted</th>
                            <th>Company Located</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody"></tbody>
                </table>
            </div>
        </div>


        <!-- Departments Section -->
        <!-- <div class="dashboard-content" id="departmentsSection" style="display: none;">
            <h1 class="page-title">Department Management</h1>
            <p class="page-subtitle">Manage organizational departments</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Departments</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="bi bi-plus-circle"></i> Add Department
                    </button>
                </div>
                <table class="table table-hover" id="departmentTable">
                    <thead>
                        <tr>
                            <th>Department Code</th>
                            <th>Department Name</th>
                            <th>Total Employees</th>
                            <th>Total Visits</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody"></tbody>
                </table>
            </div>
        </div> -->

        <!-- Departments Section - UPDATE THIS IN YOUR MAIN VIEW -->
        <!-- <div class="dashboard-content" id="departmentsSection" style="display: none;">
            <h1 class="page-title">Department Management</h1>
            <p class="page-subtitle">Manage organizational departments</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Departments</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="bi bi-plus-circle"></i> Add Department
                    </button>
                </div>
                <table class="table table-hover" id="departmentTable">
                    <thead>
                        <tr>
                            <th>Department Code</th>
                            <th>Department Name</th>
                            <th>Total Employees</th>
                            <th>Total Visits</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody"></tbody>
                </table>
            </div>
        </div> -->

        <!-- Departments Section -->
        <!-- <div class="dashboard-content" id="departmentsSection" style="display: none;">
            <h1 class="page-title">Department Management</h1>
            <p class="page-subtitle">Manage organizational departments</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Departments</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="bi bi-plus-circle"></i> Add Department
                    </button>
                </div>
                <table class="table table-hover" id="departmentTable">
                    <thead>
                        <tr>
                            <th>Department Code</th>
                            <th>Department Name</th>
                            <th>Total Employees</th>
                            <th>Total Visits</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody"></tbody>
                </table>
            </div>
        </div> -->

        <!-- ============================================== -->
        <!-- DEPARTMENTS SECTION - Replace your existing one -->
        <!-- ============================================== -->
        <div class="dashboard-content" id="departmentsSection" style="display: none;">
            <h1 class="page-title">Department Management</h1>
            <p class="page-subtitle">Manage organizational departments</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Departments</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="bi bi-plus-circle"></i> Add Department
                    </button>
                </div>
                
                <!-- Department Filters -->
                <div class="filter-container mb-3 p-3 bg-light rounded">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-toggle-on"></i> Status</label>
                            <select class="form-select" id="departmentStatusFilter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-people"></i> Has Employees</label>
                            <select class="form-select" id="departmentEmployeeFilter">
                                <option value="">All</option>
                                <option value="yes">With Employees</option>
                                <option value="no">No Employees</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary w-100" onclick="clearDepartmentFilters()">
                                <i class="bi bi-x-circle"></i> Clear Filters
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="applyDepartmentFilters()">
                                <i class="bi bi-funnel"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
                
                <table class="table table-hover" id="departmentTable">
                    <thead>
                        <tr>
                            <th>Department Code</th>
                            <th>Department Name</th>
                            <th>Total Employees</th>
                            <th>Total Visits</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Purposes Section -->
        <!-- <div class="dashboard-content" id="purposesSection" style="display: none;">
            <h1 class="page-title">Purpose Management</h1>
            <p class="page-subtitle">Manage visit purpose types</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Visit Purposes</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPurposeModal">
                        <i class="bi bi-plus-circle"></i> Add Purpose
                    </button>
                </div>
                <table class="table table-hover" id="purposeTable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Purpose Code</th>
                            <th>Purpose Name</th>
                            <th>Icon</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="purposeTableBody"></tbody>
                </table>
            </div>
        </div> -->

        <!-- ============================================== -->
        <!-- PURPOSES SECTION - Replace your existing one -->
        <!-- ============================================== -->
        <div class="dashboard-content" id="purposesSection" style="display: none;">
            <h1 class="page-title">Purpose Management</h1>
            <p class="page-subtitle">Manage visit purpose types</p>
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Visit Purposes</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPurposeModal">
                        <i class="bi bi-plus-circle"></i> Add Purpose
                    </button>
                </div>
                
                <!-- Purpose Filters -->
                <div class="filter-container mb-3 p-3 bg-light rounded">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-bold"><i class="bi bi-toggle-on"></i> Status</label>
                            <select class="form-select" id="purposeStatusFilter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-briefcase"></i> Company</label>
                            <select class="form-select" id="purposeCompanyFilter">
                                <option value="">All Companies</option>
                                <option value="Toms World">Tom's World</option>
                                <option value="Pan Asia">Pan-Asia</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-palette"></i> Color</label>
                            <select class="form-select" id="purposeColorFilter">
                                <option value="">All Colors</option>
                                <option value="text-primary">Primary (Blue)</option>
                                <option value="text-success">Success (Green)</option>
                                <option value="text-warning">Warning (Orange)</option>
                                <option value="text-danger">Danger (Red)</option>
                                <option value="text-info">Info (Cyan)</option>
                                <option value="text-secondary">Secondary (Gray)</option>
                                <option value="text-dark">Dark</option>
                                <option value="text-purple">Purple</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" onclick="clearPurposeFilters()">
                                <i class="bi bi-x-circle"></i> Clear
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="applyPurposeFilters()">
                                <i class="bi bi-funnel"></i> Apply
                            </button>
                        </div>
                    </div>
                </div>
                
                <table class="table table-hover" id="purposeTable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Purpose Code</th>
                            <th>Purpose Name</th>
                            <th>Icon</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th>Company</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="purposeTableBody"></tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-content" id="reportsSection" style="display: none;">
            <h1 class="page-title">Reports & Analytics</h1>
            <p class="page-subtitle">Generate comprehensive reports and insights</p>
            
            <!-- Report Type Selection Cards -->
            <div class="row mb-4" id="reportTypeCards">
                <!-- Department Report -->
                <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('department')">
                        <div class="report-card-icon bg-primary">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5>Department Report</h5>
                        <p class="text-muted small">Visitor statistics by department</p>
                    </div>
                </div>
                
                <!-- Employee Visits Report -->
                <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('employee_visits')">
                        <div class="report-card-icon bg-success">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5>Employee Visits</h5>
                        <p class="text-muted small">Visit stats for each host</p>
                    </div>
                </div>
                
                <!-- Visitors Report -->
                <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('visitor_visits')">
                        <div class="report-card-icon bg-info">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5>Visitors Report</h5>
                        <p class="text-muted small">Complete visitor activity</p>
                    </div>
                </div>
                
                <!-- Purposes Report -->
                <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('purposes')">
                        <div class="report-card-icon bg-warning">
                            <i class="bi bi-flag"></i>
                        </div>
                        <h5>Purposes Report</h5>
                        <p class="text-muted small">Breakdown by purpose type</p>
                    </div>
                </div>
                
                <!-- Daily Report -->
                <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('daily')">
                        <div class="report-card-icon bg-danger">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                        <h5>Daily Report</h5>
                        <p class="text-muted small">Day-by-day statistics</p>
                    </div>
                </div>
                
                <!-- Weekly Report -->
                <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('weekly')">
                        <div class="report-card-icon bg-secondary">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <h5>Weekly Report</h5>
                        <p class="text-muted small">Week-by-week trends</p>
                    </div>
                </div>
                
                <!-- Monthly Report -->
                <!-- <div class="col-md-3 mb-3 disabled">
                    <div class="report-card" onclick="selectReportType('monthly')">
                        <div class="report-card-icon bg-dark">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                        <h5>Monthly Report</h5>
                        <p class="text-muted small">Monthly analytics</p>
                    </div>
                </div> -->
                
                <!-- Annual Report -->
                <!-- <div class="col-md-3 mb-3">
                    <div class="report-card" onclick="selectReportType('annual')">
                        <div class="report-card-icon bg-purple">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <h5>Annual Report</h5>
                        <p class="text-muted small">Yearly overview</p>
                    </div>
                </div> -->
            </div>
            
            <!-- Report Generator Panel (Hidden by default) -->
            <div class="table-container" id="reportGeneratorPanel" style="display: none;">
                <div class="table-header">
                    <div>
                        <h4 id="selectedReportTitle">
                            <i class="bi bi-file-earmark-bar-graph"></i> Generate Report
                        </h4>
                        <small class="text-muted" id="selectedReportDescription"></small>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" onclick="hideReportPanel()">
                        <i class="bi bi-arrow-left"></i> Back to Reports
                    </button>
                </div>
                
                <!-- Report Filters -->
                <div class="filter-container mb-4 p-3 bg-light rounded">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-calendar-event"></i> Date From</label>
                            <input type="date" class="form-control" id="reportDateFrom">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold"><i class="bi bi-calendar-event"></i> Date To</label>
                            <input type="date" class="form-control" id="reportDateTo">
                        </div>
                        <div class="col-md-3" id="reportDepartmentFilter" style="display: none;">
                            <label class="form-label fw-bold"><i class="bi bi-building"></i> Department</label>
                            <select class="form-select" id="reportDepartmentSelect">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="reportVisitorTypeFilter" style="display: none;">
                            <label class="form-label fw-bold"><i class="bi bi-person"></i> Visitor Type</label>
                            <select class="form-select" id="reportVisitorTypeSelect">
                                <option value="">All Types</option>
                                <option value="new">New</option>
                                <option value="returning">Returning</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="generateReport()">
                                <i class="bi bi-play-fill"></i> Generate Report
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Date Filters -->
                    <div class="mt-3 pt-3 border-top">
                        <label class="form-label fw-bold"><i class="bi bi-lightning"></i> Quick Filters:</label>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('today')">Today</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('yesterday')">Yesterday</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('week')">This Week</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('month')">This Month</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('quarter')">This Quarter</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('year')">This Year</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('last30')">Last 30 Days</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateFilter('last90')">Last 90 Days</button>
                        </div>
                    </div>
                </div>
                
                <!-- Report Results -->
                <div id="reportResults" style="display: none;">
                    <!-- Summary Cards -->
                    <div class="row mb-4" id="reportSummaryCards">
                        <!-- Dynamic summary cards will be inserted here -->
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="chart-container p-3 bg-white rounded shadow-sm">
                                <canvas id="reportChart" height="300"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="chart-container p-3 bg-white rounded shadow-sm">
                                <canvas id="reportPieChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Export Buttons -->
                    <div class="mb-3 d-flex gap-2">
                        <button class="btn btn-success" onclick="exportReportToExcel()">
                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                        </button>
                        <!-- <button class="btn btn-danger" onclick="exportReportToPDF()">
                            <i class="bi bi-file-earmark-pdf"></i> Export to PDF
                        </button>
                        <button class="btn btn-secondary" onclick="printReport()">
                            <i class="bi bi-printer"></i> Print
                        </button> -->
                    </div>
                    
                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="reportDataTable">
                            <thead id="reportTableHead">
                                <!-- Dynamic headers -->
                            </thead>
                            <tbody id="reportTableBody">
                                <!-- Dynamic data -->
                            </tbody>
                            <tfoot id="reportTableFoot">
                                <!-- Dynamic totals -->
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div id="reportLoading" style="display: none;" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Generating report...</p>
                </div>
                
                <!-- No Data State -->
                <div id="reportNoData" style="display: none;" class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4em; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">No Data Found</h5>
                    <p class="text-muted">Try adjusting your filters or date range</p>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="dashboard-content" id="settingsSection" style="display: none;">
            <h1 class="page-title">System Settings</h1>
            <p class="page-subtitle">Configure visitor management system</p>
            
            <!-- General Settings Card -->
            <div class="table-container mb-4">
                <h4><i class="bi bi-gear-fill"></i> General Settings</h4>
                <form id="generalSettingsForm">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" value="<?php echo $pageTitle; ?>" disabled>
                        <small class="text-muted">Company name is managed by system administrator</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Visit Duration (Hours)</label>
                        <input type="number" class="form-control" value="8" min="1" max="24" disabled>
                        <small class="text-muted">Visit duration settings are managed by system administrator</small>
                    </div>
                </form>
            </div>
            
            <!-- IT Support Card -->
            <div class="table-container mb-4">
                <h4><i class="bi bi-headset"></i> IT Support & Notifications</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                            <div>
                                <h6 class="mb-1">Need Technical Assistance?</h6>
                                <p class="mb-0 small">Contact the IT Department for system issues, feature requests, or technical support.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-primary btn-lg" onclick="notifyITDepartment()">
                            <i class="bi bi-envelope-fill me-2"></i>
                            Notify IT Department
                        </button>
                    </div>
                </div>
                
                <!-- Quick Contact Info -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-clock-fill text-warning fs-4"></i>
                            <h6 class="mt-2 mb-1">Support Hours</h6>
                            <p class="mb-0 text-muted small">Mon-Fri: 8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-envelope-fill text-success fs-4"></i>
                            <h6 class="mt-2 mb-1">Email Support</h6>
                            <p class="mb-0 text-muted small">itheldesk@tomsworld.com.ph</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-telephone-fill text-primary fs-4"></i>
                            <h6 class="mt-2 mb-1">Phone Support</h6>
                            <p class="mb-0 text-muted small">Local: 6211</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Information Card -->
            <div class="table-container">
                <h4><i class="bi bi-info-square-fill"></i> System Information</h4>
                <div class="info-grid">
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold"><i class="bi bi-code-square text-primary"></i> System Version:</div>
                        <div class="col-sm-8">Kiosk V-Pass v1.5.9</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold"><i class="bi bi-calendar-check text-primary"></i> Last Updated:</div>
                        <div class="col-sm-8"><?php echo date('F d, Y'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold"><i class="bi bi-server text-primary"></i> Server Status:</div>
                        <div class="col-sm-8"><span class="badge bg-success">Online</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 fw-bold"><i class="bi bi-shield-check text-primary"></i> Security:</div>
                        <div class="col-sm-8"><span class="badge bg-success">Secure</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (View Visitor, Add Employee, Add Department, Add Purpose, etc.) -->
    <!-- View Visitor Modal -->
    <div class="modal fade" id="viewVisitorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-person-badge"></i> Visitor Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modalVisitorPhoto" src="" alt="Visitor Photo" class="img-fluid rounded-circle border border-3 border-primary mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                            <div class="badge bg-primary text-white p-2">
                                <i class="bi bi-card-text"></i> Badge: <span id="modalBadgeNumber"></span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3 text-primary" id="modalVisitorName"></h4>
                            <div class="info-grid">
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-envelope"></i> Email:</div><div class="col-sm-8" id="modalEmail"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-telephone"></i> Phone:</div><div class="col-sm-8" id="modalPhone"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-building"></i> Company:</div><div class="col-sm-8" id="modalCompany"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-person-check"></i> Host:</div><div class="col-sm-8" id="modalHost"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-flag"></i> Purpose:</div><div class="col-sm-8"><span class="badge bg-info" id="modalPurpose"></span></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-geo-alt"></i> Visiting:</div><div class="col-sm-8" id="modalCompanyVisited"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-clock"></i> Check-In:</div><div class="col-sm-8" id="modalCheckIn"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-hourglass-split"></i> Valid Until:</div><div class="col-sm-8" id="modalValidUntil"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-card-checklist"></i> Status:</div><div class="col-sm-8" id="modalStatus"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="checkOutVisitorBtn" onclick="performCheckout()">
                        <i class="bi bi-box-arrow-right"></i> Check Out
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View All Visitor Modal -->
    <div class="modal fade" id="viewAllVisitorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-person-vcard"></i> Visitor Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="allVisitorPhoto" src="" alt="Visitor Photo" class="rounded-circle shadow mb-3" style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #667eea;">
                            <div class="badge bg-primary p-2 mb-2" id="allVisitorType">
                                <i class="bi bi-person-badge"></i> Type: <span id="visitorTypeText"></span>
                            </div>
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Visit Statistics</h6>
                                <div><i class="bi bi-bar-chart"></i> <strong>Total Visits:</strong> <span id="allVisitorTotalVisits">0</span></div>
                                <div><i class="bi bi-calendar-check"></i> <strong>Last Visit:</strong> <span id="allVisitorLastVisit">N/A</span></div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-3 text-primary border-bottom pb-2" id="allVisitorFullName"></h4>
                            <div class="info-section mb-3">
                                <h6 class="text-muted mb-2">Contact Information</h6>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-envelope-fill text-primary"></i> Email:</div><div class="col-sm-8"><a href="#" id="allVisitorEmail" class="text-decoration-none"></a></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-telephone-fill text-primary"></i> Phone:</div><div class="col-sm-8"><a href="#" id="allVisitorPhone" class="text-decoration-none"></a></div></div>
                            </div>
                            <div class="info-section mb-3">
                                <h6 class="text-muted mb-2">Organization</h6>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-building text-primary"></i> Company:</div><div class="col-sm-8" id="allVisitorCompany"></div></div>
                            </div>
                            <div class="info-section mb-3">
                                <h6 class="text-muted mb-2">Additional Details</h6>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-calendar-plus text-primary"></i> First Registered:</div><div class="col-sm-8" id="allVisitorCreated"></div></div>
                                <div class="row mb-2"><div class="col-sm-4 fw-bold"><i class="bi bi-clock-history text-primary"></i> Last Updated:</div><div class="col-sm-8" id="allVisitorUpdated"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Add New Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addEmployeeForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-telephone"></i> Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" placeholder="+63 XXX-XXX-XXXX">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-briefcase"></i> Position</label>
                                <input type="text" class="form-control" name="position" placeholder="e.g., Manager">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" name="department_code" id="employeeDepartmentSelect" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company located</label>
                            <select class="form-select" name="company_owned_by" id="addCompanyOwnedBy">
                                <option value="Both">Both Companies</option>
                                <option value="Toms World">Tom's World Only</option>
                                <option value="Pan Asia">Pan-Asia Only</option>
                            </select>
                            <small class="text-muted">Determines which company can see this employee</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="isActiveCheck" checked>
                            <label class="form-check-label" for="isActiveCheck">Active Employee</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="modal fade" id="addEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Add New Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addEmployeeForm">
                    <!- Modify Add Employee Modal body ->
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" name="department_code" id="employeeDepartmentSelect" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Ownership</label>
                            <select class="form-select" name="company_owned_by" id="addCompanyOwnedBy">
                                <option value="Both">Both Companies</option>
                                <option value="Toms World">Tom's World Only</option>
                                <option value="Pan Asia">Pan-Asia Only</option>
                            </select>
                            <small class="text-muted">Determines which company can see this employee</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="isActiveCheck" checked>
                            <label class="form-check-label" for="isActiveCheck">Active Employee</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Edit Employee Modal - UPDATED WITH POSITION, PHONE & PROFILE PIC -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editEmployeeForm">
                    <input type="hidden" name="employee_id" id="editEmployeeId">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Profile Picture Column -->
                            <div class="col-md-4 text-center mb-3">
                                <div class="mb-3">
                                    <img id="editEmployeeProfilePic" 
                                    src="<?= base_url('assets/images/icons/default-avatar.png') ?>" 
                                    alt="Employee Photo" 
                                    class="rounded-circle border border-3 border-primary shadow"
                                    style="width: 150px; height: 150px; object-fit: cover;"
                                    onerror="this.src='<?= base_url('assets/images/icons/default-avatar.png') ?>'">
                                </div>
                                <div class="mb-2">
                                    <span class="badge bg-secondary" id="editEmployeeIdBadge"></span>
                                </div>
                                <div id="editEmployeeStatusBadge"></div>
                            </div>
                            
                            <!-- Form Fields Column -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Host ID</label>
                                        <input type="text" class="form-control" id="editEmployeeIdDisplay" readonly>
                                        <small class="text-muted">Cannot be changed</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="editEmployeeName" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" id="editEmployeeEmail" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-telephone"></i> Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" id="editEmployeePhone" 
                                            placeholder="+63 XXX-XXX-XXXX">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Department <span class="text-danger">*</span></label>
                                        <select class="form-select" name="department_code" id="editEmployeeDepartment" required>
                                            <option value="">Select Department</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-briefcase"></i> Position</label>
                                        <input type="text" class="form-control" name="position" id="editEmployeePosition" 
                                            placeholder="e.g., Manager, Officer, Staff">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Company Ownership</label>
                                        <select class="form-select" name="company_owned_by" id="editCompanyOwnedBy">
                                            <option value="Both">Both Companies</option>
                                            <option value="Toms World">Tom's World Only</option>
                                            <option value="Pan Asia">Pan-Asia Only</option>
                                        </select>
                                        <small class="text-muted">Determines which company can see this employee</small>
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="is_active" id="editIsActiveCheck">
                                            <label class="form-check-label" for="editIsActiveCheck">Active Employee</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editEmployeeForm">
                    <input type="hidden" name="employee_id" id="editEmployeeId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="editEmployeeIdDisplay" readonly>
                            <small class="text-muted">Employee ID cannot be changed</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="editEmployeeName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="editEmployeeEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" name="department_code" id="editEmployeeDepartment" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Ownership</label>
                            <select class="form-select" name="company_owned_by" id="editCompanyOwnedBy">
                                <option value="Both">Both Companies</option>
                                <option value="Toms World">Tom's World Only</option>
                                <option value="Pan Asia">Pan-Asia Only</option>
                            </select>
                            <small class="text-muted">Determines which company can see this employee</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="editIsActiveCheck">
                            <label class="form-check-label" for="editIsActiveCheck">Active Employee</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Replace the Add Department Modal with this: -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-building-add"></i> Add New Department</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addDepartmentForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="department_code" placeholder="e.g., IT, HR, SALES" required maxlength="20">
                                <small class="text-muted">Unique identifier for the department</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="e.g., Information Technology" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Brief description of the department"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="departmentActiveCheck" checked>
                            <label class="form-check-label" for="departmentActiveCheck">Active Department</label>
                        </div>
                        
                        <hr class="my-3">
                        <h6 class="mb-3"><i class="bi bi-translate"></i> Translations (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇬🇧 English</label>
                                <input type="text" class="form-control" name="name_en" placeholder="English translation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇹🇼 Traditional Chinese</label>
                                <input type="text" class="form-control" name="name_zh_tw" placeholder="繁體中文">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇨🇳 Simplified Chinese</label>
                                <input type="text" class="form-control" name="name_zh_cn" placeholder="简体中文">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇵🇭 Filipino</label>
                                <input type="text" class="form-control" name="name_fil" placeholder="Filipino translation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇯🇵 Japanese</label>
                                <input type="text" class="form-control" name="name_ja" placeholder="日本語">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Replace the Edit Department Modal with this: -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Department</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDepartmentForm">
                    <input type="hidden" name="department_code" id="editDepartmentCode">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department Code</label>
                                <input type="text" class="form-control" id="editDepartmentCodeDisplay" readonly>
                                <small class="text-muted">Department code cannot be changed</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="editDepartmentName" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editDepartmentDescription" rows="2"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="editDepartmentActiveCheck">
                            <label class="form-check-label" for="editDepartmentActiveCheck">Active Department</label>
                        </div>
                        
                        <hr class="my-3">
                        <h6 class="mb-3"><i class="bi bi-translate"></i> Translations (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇬🇧 English</label>
                                <input type="text" class="form-control" name="name_en" id="editDepartmentNameEn">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇹🇼 Traditional Chinese</label>
                                <input type="text" class="form-control" name="name_zh_tw" id="editDepartmentNameZhTw">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇨🇳 Simplified Chinese</label>
                                <input type="text" class="form-control" name="name_zh_cn" id="editDepartmentNameZhCn">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇵🇭 Filipino</label>
                                <input type="text" class="form-control" name="name_fil" id="editDepartmentNameFil">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇯🇵 Japanese</label>
                                <input type="text" class="form-control" name="name_ja" id="editDepartmentNameJa">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Purpose Modal -->
    <!-- <div class="modal fade" id="addPurposeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-flag-fill"></i> Add New Purpose</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addPurposeForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Purpose Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="purpose_code" placeholder="e.g., meeting, interview" required maxlength="20">
                            <small class="text-muted">Unique identifier (lowercase, no spaces)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purpose Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="purpose_name" placeholder="e.g., Meeting, Interview" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" name="icon_class" placeholder="e.g., bi-people, bi-briefcase" value="bi-circle">
                            <small class="text-muted">Bootstrap Icons class (e.g., bi-people)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color Class</label>
                            <select class="form-select" name="color_class">
                                <option value="text-primary">Primary (Blue)</option>
                                <option value="text-success">Success (Green)</option>
                                <option value="text-warning">Warning (Orange)</option>
                                <option value="text-danger">Danger (Red)</option>
                                <option value="text-info">Info (Cyan)</option>
                                <option value="text-secondary">Secondary (Gray)</option>
                                <option value="text-dark">Dark</option>
                                <option value="text-purple">Purple</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="purposeActiveCheck" checked>
                            <label class="form-check-label" for="purposeActiveCheck">Active Purpose</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Add Purpose Modal - UPDATED WITH TRANSLATIONS -->
    <!-- <div class="modal fade" id="addPurposeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-flag-fill"></i> Add New Purpose</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addPurposeForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="purpose_code" placeholder="e.g., meeting, interview" required maxlength="20">
                                <small class="text-muted">Unique identifier (lowercase, no spaces)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="purpose_name" placeholder="e.g., Meeting, Interview" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Icon Class</label>
                                <input type="text" class="form-control" name="icon_class" placeholder="e.g., bi-people, bi-briefcase" value="bi-circle">
                                <small class="text-muted">Bootstrap Icons class</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color Class</label>
                                <select class="form-select" name="color_class">
                                    <option value="text-primary">Primary (Blue)</option>
                                    <option value="text-success">Success (Green)</option>
                                    <option value="text-warning">Warning (Orange)</option>
                                    <option value="text-danger">Danger (Red)</option>
                                    <option value="text-info">Info (Cyan)</option>
                                    <option value="text-secondary">Secondary (Gray)</option>
                                    <option value="text-dark">Dark</option>
                                    <option value="text-purple">Purple</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Ownership</label>
                                <select class="form-select" name="company_owned_by">
                                    <option value="Both">Both Companies</option>
                                    <option value="Toms World">Tom's World Only</option>
                                    <option value="Pan Asia">Pan-Asia Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="purposeActiveCheck" checked>
                                    <label class="form-check-label" for="purposeActiveCheck">Active Purpose</label>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        <h6 class="mb-3"><i class="bi bi-translate"></i> Translations (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇬🇧 English</label>
                                <input type="text" class="form-control" name="name_en" placeholder="English translation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇹🇼 Traditional Chinese</label>
                                <input type="text" class="form-control" name="name_zh_tw" placeholder="繁體中文">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇨🇳 Simplified Chinese</label>
                                <input type="text" class="form-control" name="name_zh_cn" placeholder="简体中文">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇵🇭 Filipino</label>
                                <input type="text" class="form-control" name="name_fil" placeholder="Filipino translation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇯🇵 Japanese</label>
                                <input type="text" class="form-control" name="name_ja" placeholder="日本語">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Add Purpose Modal - WITH ICON DROPDOWN -->
    <div class="modal fade" id="addPurposeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-flag-fill"></i> Add New Purpose</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addPurposeForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="purpose_code" placeholder="e.g., meeting, interview" required maxlength="20">
                                <small class="text-muted">Unique identifier (lowercase, no spaces)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="purpose_name" placeholder="e.g., Meeting, Interview" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-icons"></i> Icon</label>
                                <div class="icon-select-wrapper">
                                    <i class="icon-select-preview bi bi-circle" id="addIconPreview"></i>
                                    <select class="form-select" name="icon_class" id="addIconSelect" onchange="updateIconPreview('add')">
                                        <!-- Options will be populated by JavaScript -->
                                    </select>
                                </div>
                                <small class="text-muted">Select an icon for this purpose</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color Class</label>
                                <select class="form-select" name="color_class" id="addColorSelect" onchange="updateColorPreview('add')">
                                    <option value="text-primary">Primary (Blue)</option>
                                    <option value="text-success">Success (Green)</option>
                                    <option value="text-warning">Warning (Orange)</option>
                                    <option value="text-danger">Danger (Red)</option>
                                    <option value="text-info">Info (Cyan)</option>
                                    <option value="text-secondary">Secondary (Gray)</option>
                                    <option value="text-dark">Dark</option>
                                    <option value="text-purple">Purple</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Icon Preview -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">Preview</label>
                                <div class="p-3 bg-light rounded text-center">
                                    <i id="addPurposeIconPreviewLarge" class="bi bi-circle text-primary" style="font-size: 3em;"></i>
                                    <p class="mt-2 mb-0" id="addPurposePreviewText">Purpose Preview</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Ownership</label>
                                <select class="form-select" name="company_owned_by">
                                    <option value="Both">Both Companies</option>
                                    <option value="Toms World">Tom's World Only</option>
                                    <option value="Pan Asia">Pan-Asia Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="purposeActiveCheck" checked>
                                    <label class="form-check-label" for="purposeActiveCheck">Active Purpose</label>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        <h6 class="mb-3"><i class="bi bi-translate"></i> Translations (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇬🇧 English</label>
                                <input type="text" class="form-control" name="name_en" placeholder="English translation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇹🇼 Traditional Chinese</label>
                                <input type="text" class="form-control" name="name_zh_tw" placeholder="繁體中文">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇨🇳 Simplified Chinese</label>
                                <input type="text" class="form-control" name="name_zh_cn" placeholder="简体中文">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇵🇭 Filipino</label>
                                <input type="text" class="form-control" name="name_fil" placeholder="Filipino translation">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇯🇵 Japanese</label>
                                <input type="text" class="form-control" name="name_ja" placeholder="日本語">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Purpose Modal -->
    <!-- <div class="modal fade" id="editPurposeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Purpose</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPurposeForm">
                    <input type="hidden" name="purpose_id" id="editPurposeId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Purpose Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="purpose_code" id="editPurposeCode" readonly>
                            <small class="text-muted">Purpose code cannot be changed</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purpose Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="purpose_name" id="editPurposeName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon Class</label>
                            <input type="text" class="form-control" name="icon_class" id="editIconClass" placeholder="e.g., bi-people, bi-briefcase">
                            <small class="text-muted">Bootstrap Icons class (e.g., bi-people)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color Class</label>
                            <select class="form-select" name="color_class" id="editColorClass">
                                <option value="text-primary">Primary (Blue)</option>
                                <option value="text-success">Success (Green)</option>
                                <option value="text-warning">Warning (Orange)</option>
                                <option value="text-danger">Danger (Red)</option>
                                <option value="text-info">Info (Cyan)</option>
                                <option value="text-secondary">Secondary (Gray)</option>
                                <option value="text-dark">Dark</option>
                                <option value="text-purple">Purple</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company Ownership</label>
                            <select class="form-select" name="company_owned_by" id="editCompanyOwnedBy">
                                <option value="Both">Both Companies</option>
                                <option value="Toms World">Tom's World Only</option>
                                <option value="Pan Asia">Pan-Asia Only</option>
                            </select>
                            <small class="text-muted">Determines which company can see this purpose</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="editPurposeActiveCheck">
                            <label class="form-check-label" for="editPurposeActiveCheck">Active Purpose</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Edit Purpose Modal - UPDATED WITH TRANSLATIONS -->
    <!-- <div class="modal fade" id="editPurposeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Purpose</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPurposeForm">
                    <input type="hidden" name="purpose_id" id="editPurposeId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Code</label>
                                <input type="text" class="form-control" name="purpose_code" id="editPurposeCode" readonly>
                                <small class="text-muted">Purpose code cannot be changed</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="purpose_name" id="editPurposeName" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Icon Class</label>
                                <input type="text" class="form-control" name="icon_class" id="editIconClass">
                                <small class="text-muted">Bootstrap Icons class</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color Class</label>
                                <select class="form-select" name="color_class" id="editColorClass">
                                    <option value="text-primary">Primary (Blue)</option>
                                    <option value="text-success">Success (Green)</option>
                                    <option value="text-warning">Warning (Orange)</option>
                                    <option value="text-danger">Danger (Red)</option>
                                    <option value="text-info">Info (Cyan)</option>
                                    <option value="text-secondary">Secondary (Gray)</option>
                                    <option value="text-dark">Dark</option>
                                    <option value="text-purple">Purple</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Ownership</label>
                                <select class="form-select" name="company_owned_by" id="editPurposeCompanyOwnedBy">
                                    <option value="Both">Both Companies</option>
                                    <option value="Toms World">Tom's World Only</option>
                                    <option value="Pan Asia">Pan-Asia Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="editPurposeActiveCheck">
                                    <label class="form-check-label" for="editPurposeActiveCheck">Active Purpose</label>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        <h6 class="mb-3"><i class="bi bi-translate"></i> Translations (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇬🇧 English</label>
                                <input type="text" class="form-control" name="name_en" id="editPurposeNameEn">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇹🇼 Traditional Chinese</label>
                                <input type="text" class="form-control" name="name_zh_tw" id="editPurposeNameZhTw">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇨🇳 Simplified Chinese</label>
                                <input type="text" class="form-control" name="name_zh_cn" id="editPurposeNameZhCn">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇵🇭 Filipino</label>
                                <input type="text" class="form-control" name="name_fil" id="editPurposeNameFil">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇯🇵 Japanese</label>
                                <input type="text" class="form-control" name="name_ja" id="editPurposeNameJa">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Edit Purpose Modal - WITH ICON DROPDOWN -->
    <div class="modal fade" id="editPurposeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Purpose</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPurposeForm">
                    <input type="hidden" name="purpose_id" id="editPurposeId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Code</label>
                                <input type="text" class="form-control" name="purpose_code" id="editPurposeCode" readonly>
                                <small class="text-muted">Purpose code cannot be changed</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="purpose_name" id="editPurposeName" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="bi bi-icons"></i> Icon</label>
                                <div class="icon-select-wrapper">
                                    <i class="icon-select-preview bi bi-circle" id="editIconPreview"></i>
                                    <select class="form-select" name="icon_class" id="editIconSelect" onchange="updateIconPreview('edit')">
                                        <!-- Options will be populated by JavaScript -->
                                    </select>
                                </div>
                                <small class="text-muted">Select an icon for this purpose</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color Class</label>
                                <select class="form-select" name="color_class" id="editColorSelect" onchange="updateColorPreview('edit')">
                                    <option value="text-primary">Primary (Blue)</option>
                                    <option value="text-success">Success (Green)</option>
                                    <option value="text-warning">Warning (Orange)</option>
                                    <option value="text-danger">Danger (Red)</option>
                                    <option value="text-info">Info (Cyan)</option>
                                    <option value="text-secondary">Secondary (Gray)</option>
                                    <option value="text-dark">Dark</option>
                                    <option value="text-purple">Purple</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Icon Preview -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">Preview</label>
                                <div class="p-3 bg-light rounded text-center">
                                    <i id="editPurposeIconPreviewLarge" class="bi bi-circle text-primary" style="font-size: 3em;"></i>
                                    <p class="mt-2 mb-0" id="editPurposePreviewText">Purpose Preview</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Ownership</label>
                                <select class="form-select" name="company_owned_by" id="editPurposeCompanyOwnedBy">
                                    <option value="Both">Both Companies</option>
                                    <option value="Toms World">Tom's World Only</option>
                                    <option value="Pan Asia">Pan-Asia Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="editPurposeActiveCheck">
                                    <label class="form-check-label" for="editPurposeActiveCheck">Active Purpose</label>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        <h6 class="mb-3"><i class="bi bi-translate"></i> Translations (Optional)</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇬🇧 English</label>
                                <input type="text" class="form-control" name="name_en" id="editPurposeNameEn">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇹🇼 Traditional Chinese</label>
                                <input type="text" class="form-control" name="name_zh_tw" id="editPurposeNameZhTw">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇨🇳 Simplified Chinese</label>
                                <input type="text" class="form-control" name="name_zh_cn" id="editPurposeNameZhCn">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇵🇭 Filipino</label>
                                <input type="text" class="form-control" name="name_fil" id="editPurposeNameFil">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🇯🇵 Japanese</label>
                                <input type="text" class="form-control" name="name_ja" id="editPurposeNameJa">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Visitor History Modal -->
    <div class="modal fade" id="visitorHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header history-modal <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-clock-history"></i> Visitor History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded">
                        <h5 id="visitorHistoryName" class="text-primary mb-0"></h5>
                        <small class="text-muted">Complete visit history</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="visitorHistoryTable">
                            <thead>
                                <tr>
                                    <th>Badge #</th>
                                    <th>Host</th>
                                    <th>Department</th>
                                    <th>Purpose</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="visitorHistoryTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Visit History Modal -->
    <div class="modal fade" id="employeeHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header employee-modal <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-person-lines-fill"></i> Employee Visit History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded">
                        <h5 id="employeeHistoryName" class="text-primary mb-0"></h5>
                        <small class="text-muted">All visits hosted by this employee</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="employeeHistoryTable">
                            <thead>
                                <tr>
                                    <th>Badge #</th>
                                    <th>Visitor Name</th>
                                    <th>Company</th>
                                    <th>Purpose</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="employeeHistoryTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Employees Modal -->
    <div class="modal fade" id="departmentEmployeesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header department-modal <?php echo $modalHeaderClass; ?>">
                    <h5 class="modal-title"><i class="bi bi-people-fill"></i> Department Employees</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded">
                        <h5 id="departmentEmployeesName" class="text-primary mb-0"></h5>
                        <small class="text-muted">All employees in this department</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="departmentEmployeesTable">
                            <thead>
                                <tr>
                                    <th>Host ID</th>
                                    <th>Name</th>
                                    <th>Email & Phone Number</th>
                                    <th>Status</th>
                                    <th>Total Visits Hosted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="departmentEmployeesTableBody"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ============================================
        AUTO-LOGOUT WARNING MODAL
    ============================================ -->
    <div class="modal fade" id="sessionWarningModal" tabindex="-1" 
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg overflow-hidden">
                
                <!-- Animated header -->
                <div class="modal-header border-0 pb-0"
                    style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white;">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <div id="sessionWarningIcon" 
                            style="width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,0.2);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-shield-exclamation" style="font-size:1.6em;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold">Session Expiring Soon</h5>
                            <small style="opacity:0.85;">Your session is about to expire due to inactivity</small>
                        </div>
                    </div>
                </div>

                <div class="modal-body pt-4 pb-2 px-4">

                    <!-- Countdown Ring -->
                    <div class="text-center mb-4">
                        <div style="position:relative;display:inline-block;width:130px;height:130px;">
                            <svg width="130" height="130" style="transform:rotate(-90deg);">
                                <!-- background track -->
                                <circle cx="65" cy="65" r="56"
                                        fill="none" stroke="#f0f0f0" stroke-width="8"/>
                                <!-- countdown arc -->
                                <circle id="countdownArc"
                                        cx="65" cy="65" r="56"
                                        fill="none" stroke="#e74c3c" stroke-width="8"
                                        stroke-linecap="round"
                                        stroke-dasharray="351.86"
                                        stroke-dashoffset="0"
                                        style="transition:stroke-dashoffset 1s linear, stroke 0.5s ease;"/>
                            </svg>
                            <!-- center text -->
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                                        text-align:center;line-height:1.1;">
                                <span id="countdownSeconds"
                                    style="font-size:2.4em;font-weight:700;color:#e74c3c;"></span>
                                <div style="font-size:0.7em;color:#7f8c8d;font-weight:500;">seconds</div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="mb-3">
                        <div class="progress" style="height:6px;border-radius:10px;background:#f0f0f0;">
                            <div id="countdownProgressBar"
                                class="progress-bar bg-danger"
                                style="width:100%;border-radius:10px;transition:width 1s linear;"></div>
                        </div>
                    </div>

                    <p class="text-center text-muted mb-1" style="font-size:0.92em;">
                        <i class="bi bi-info-circle me-1"></i>
                        You will be automatically logged out in <strong id="countdownSecondsText"></strong> seconds.
                    </p>
                    <p class="text-center text-muted mb-0" style="font-size:0.85em;">
                        Click <strong>"Stay Logged In"</strong> to continue your session.
                    </p>

                </div>

                <div class="modal-footer border-0 pt-2 px-4 pb-4 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-fill"
                            onclick="performSessionLogout()">
                        <i class="bi bi-box-arrow-left me-1"></i> Logout Now
                    </button>
                    <button type="button" class="btn btn-success flex-fill fw-bold"
                            id="stayLoggedInBtn" onclick="extendSession()">
                        <i class="bi bi-shield-check me-1"></i> Stay Logged In
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Auto-Logout Complete Modal (shown after timeout) -->
    <div class="modal fade" id="sessionExpiredModal" tabindex="-1"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg text-center">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div style="width:70px;height:70px;border-radius:50%;background:#fee;
                                    display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="bi bi-lock-fill text-danger" style="font-size:2em;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-danger mb-1">Session Expired</h5>
                    <p class="text-muted small mb-3">
                        Your session has expired.<br>Redirecting to login page...
                    </p>
                    <div class="spinner-border spinner-border-sm text-danger" role="status">
                        <span class="visually-hidden">Redirecting...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        const companyFilter = <?php echo json_encode($companyFilter ?? '' ); ?>;
        const filterParam = companyFilter ? `&company_filter=${encodeURIComponent(companyFilter)}` : '&company_filter=null';
        const ajaxUrl = '<?= base_url("admin/ajax_handler") ?>';

        // Fallback variables - add at TOP of your script section
        var baseUrl = "<?= base_url(); ?>";
        
        let currentVisitId = null;
        let currentVisitorData = null;
        let dataTableInstances = {};
        let purposesMap = {};
        let lastAlertId = 0;

        // ============================================
        // ADD THESE VARIABLES AT THE TOP WITH YOUR OTHER VARIABLES
        // ============================================
        let allEmployeesData = [];
        let allDepartmentsData = [];
        let allPurposesData = [];        


        // Bootstrap Icons List for Purpose Selection
        const bootstrapIconsList = [
            // General Purpose Icons
            { value: 'bi-circle', label: 'Circle (Default)', category: 'General' },
            { value: 'bi-check-circle', label: 'Check Circle', category: 'General' },
            { value: 'bi-x-circle', label: 'X Circle', category: 'General' },
            { value: 'bi-info-circle', label: 'Info Circle', category: 'General' },
            { value: 'bi-question-circle', label: 'Question Circle', category: 'General' },
            { value: 'bi-exclamation-circle', label: 'Exclamation Circle', category: 'General' },
            
            // People & Business
            { value: 'bi-people', label: 'People / Group', category: 'People' },
            { value: 'bi-people-fill', label: 'People Filled', category: 'People' },
            { value: 'bi-person', label: 'Person', category: 'People' },
            { value: 'bi-person-fill', label: 'Person Filled', category: 'People' },
            { value: 'bi-person-check', label: 'Person Check', category: 'People' },
            { value: 'bi-person-badge', label: 'Person Badge', category: 'People' },
            { value: 'bi-person-workspace', label: 'Person Workspace', category: 'People' },
            { value: 'bi-person-video', label: 'Person Video', category: 'People' },
            { value: 'bi-person-lines-fill', label: 'Person Lines', category: 'People' },
            
            // Business & Work
            { value: 'bi-briefcase', label: 'Briefcase', category: 'Business' },
            { value: 'bi-briefcase-fill', label: 'Briefcase Filled', category: 'Business' },
            { value: 'bi-building', label: 'Building', category: 'Business' },
            { value: 'bi-building-fill', label: 'Building Filled', category: 'Business' },
            { value: 'bi-shop', label: 'Shop', category: 'Business' },
            { value: 'bi-shop-window', label: 'Shop Window', category: 'Business' },
            { value: 'bi-cart', label: 'Cart', category: 'Business' },
            { value: 'bi-cart-fill', label: 'Cart Filled', category: 'Business' },
            { value: 'bi-cash', label: 'Cash', category: 'Business' },
            { value: 'bi-cash-stack', label: 'Cash Stack', category: 'Business' },
            { value: 'bi-credit-card', label: 'Credit Card', category: 'Business' },
            { value: 'bi-wallet', label: 'Wallet', category: 'Business' },
            { value: 'bi-bank', label: 'Bank', category: 'Business' },
            
            // Communication
            { value: 'bi-chat', label: 'Chat', category: 'Communication' },
            { value: 'bi-chat-dots', label: 'Chat Dots', category: 'Communication' },
            { value: 'bi-chat-left-text', label: 'Chat Text', category: 'Communication' },
            { value: 'bi-telephone', label: 'Telephone', category: 'Communication' },
            { value: 'bi-telephone-fill', label: 'Telephone Filled', category: 'Communication' },
            { value: 'bi-envelope', label: 'Envelope', category: 'Communication' },
            { value: 'bi-envelope-fill', label: 'Envelope Filled', category: 'Communication' },
            { value: 'bi-megaphone', label: 'Megaphone', category: 'Communication' },
            { value: 'bi-broadcast', label: 'Broadcast', category: 'Communication' },
            
            // Meeting & Events
            { value: 'bi-calendar', label: 'Calendar', category: 'Events' },
            { value: 'bi-calendar-check', label: 'Calendar Check', category: 'Events' },
            { value: 'bi-calendar-event', label: 'Calendar Event', category: 'Events' },
            { value: 'bi-calendar-date', label: 'Calendar Date', category: 'Events' },
            { value: 'bi-clock', label: 'Clock', category: 'Events' },
            { value: 'bi-clock-fill', label: 'Clock Filled', category: 'Events' },
            { value: 'bi-alarm', label: 'Alarm', category: 'Events' },
            { value: 'bi-hourglass', label: 'Hourglass', category: 'Events' },
            { value: 'bi-hourglass-split', label: 'Hourglass Split', category: 'Events' },
            
            // Documents & Files
            { value: 'bi-file-text', label: 'File Text', category: 'Documents' },
            { value: 'bi-file-earmark', label: 'File Earmark', category: 'Documents' },
            { value: 'bi-file-earmark-text', label: 'File Earmark Text', category: 'Documents' },
            { value: 'bi-clipboard', label: 'Clipboard', category: 'Documents' },
            { value: 'bi-clipboard-check', label: 'Clipboard Check', category: 'Documents' },
            { value: 'bi-clipboard-data', label: 'Clipboard Data', category: 'Documents' },
            { value: 'bi-journal', label: 'Journal', category: 'Documents' },
            { value: 'bi-book', label: 'Book', category: 'Documents' },
            { value: 'bi-folder', label: 'Folder', category: 'Documents' },
            { value: 'bi-folder-fill', label: 'Folder Filled', category: 'Documents' },
            
            // Technical & Tools
            { value: 'bi-gear', label: 'Gear / Settings', category: 'Technical' },
            { value: 'bi-gear-fill', label: 'Gear Filled', category: 'Technical' },
            { value: 'bi-tools', label: 'Tools', category: 'Technical' },
            { value: 'bi-wrench', label: 'Wrench', category: 'Technical' },
            { value: 'bi-hammer', label: 'Hammer', category: 'Technical' },
            { value: 'bi-screwdriver', label: 'Screwdriver', category: 'Technical' },
            { value: 'bi-pc-display', label: 'PC Display', category: 'Technical' },
            { value: 'bi-laptop', label: 'Laptop', category: 'Technical' },
            { value: 'bi-printer', label: 'Printer', category: 'Technical' },
            { value: 'bi-cpu', label: 'CPU', category: 'Technical' },
            { value: 'bi-server', label: 'Server', category: 'Technical' },
            
            // Delivery & Logistics
            { value: 'bi-truck', label: 'Truck / Delivery', category: 'Delivery' },
            { value: 'bi-box', label: 'Box / Package', category: 'Delivery' },
            { value: 'bi-box-seam', label: 'Box Seam', category: 'Delivery' },
            { value: 'bi-boxes', label: 'Boxes', category: 'Delivery' },
            { value: 'bi-archive', label: 'Archive', category: 'Delivery' },
            { value: 'bi-send', label: 'Send', category: 'Delivery' },
            
            // Security & Safety
            { value: 'bi-shield', label: 'Shield', category: 'Security' },
            { value: 'bi-shield-check', label: 'Shield Check', category: 'Security' },
            { value: 'bi-shield-lock', label: 'Shield Lock', category: 'Security' },
            { value: 'bi-lock', label: 'Lock', category: 'Security' },
            { value: 'bi-unlock', label: 'Unlock', category: 'Security' },
            { value: 'bi-key', label: 'Key', category: 'Security' },
            { value: 'bi-eye', label: 'Eye', category: 'Security' },
            { value: 'bi-eye-slash', label: 'Eye Slash', category: 'Security' },
            
            // Education & Training
            { value: 'bi-mortarboard', label: 'Graduation Cap', category: 'Education' },
            { value: 'bi-award', label: 'Award', category: 'Education' },
            { value: 'bi-trophy', label: 'Trophy', category: 'Education' },
            { value: 'bi-lightbulb', label: 'Lightbulb / Idea', category: 'Education' },
            { value: 'bi-lightbulb-fill', label: 'Lightbulb Filled', category: 'Education' },
            { value: 'bi-puzzle', label: 'Puzzle', category: 'Education' },
            
            // Medical & Health
            { value: 'bi-heart', label: 'Heart', category: 'Medical' },
            { value: 'bi-heart-fill', label: 'Heart Filled', category: 'Medical' },
            { value: 'bi-heart-pulse', label: 'Heart Pulse', category: 'Medical' },
            { value: 'bi-hospital', label: 'Hospital', category: 'Medical' },
            { value: 'bi-bandaid', label: 'Bandaid', category: 'Medical' },
            { value: 'bi-capsule', label: 'Capsule', category: 'Medical' },
            { value: 'bi-thermometer', label: 'Thermometer', category: 'Medical' },
            
            // Food & Dining
            { value: 'bi-cup-hot', label: 'Coffee / Hot Cup', category: 'Food' },
            { value: 'bi-cup-straw', label: 'Cup with Straw', category: 'Food' },
            { value: 'bi-egg-fried', label: 'Food / Meal', category: 'Food' },
            
            // Flags & Status
            { value: 'bi-flag', label: 'Flag', category: 'Flags' },
            { value: 'bi-flag-fill', label: 'Flag Filled', category: 'Flags' },
            { value: 'bi-bookmark', label: 'Bookmark', category: 'Flags' },
            { value: 'bi-bookmark-fill', label: 'Bookmark Filled', category: 'Flags' },
            { value: 'bi-star', label: 'Star', category: 'Flags' },
            { value: 'bi-star-fill', label: 'Star Filled', category: 'Flags' },
            { value: 'bi-pin', label: 'Pin', category: 'Flags' },
            { value: 'bi-pin-fill', label: 'Pin Filled', category: 'Flags' },
            
            // Actions
            { value: 'bi-check', label: 'Check', category: 'Actions' },
            { value: 'bi-check2', label: 'Check 2', category: 'Actions' },
            { value: 'bi-check-lg', label: 'Check Large', category: 'Actions' },
            { value: 'bi-plus', label: 'Plus', category: 'Actions' },
            { value: 'bi-plus-circle', label: 'Plus Circle', category: 'Actions' },
            { value: 'bi-dash', label: 'Dash', category: 'Actions' },
            { value: 'bi-x', label: 'X', category: 'Actions' },
            { value: 'bi-arrow-right', label: 'Arrow Right', category: 'Actions' },
            { value: 'bi-arrow-left', label: 'Arrow Left', category: 'Actions' },
            { value: 'bi-box-arrow-in-right', label: 'Box Arrow In', category: 'Actions' },
            { value: 'bi-box-arrow-right', label: 'Box Arrow Out', category: 'Actions' },
            
            // Misc
            { value: 'bi-house', label: 'House / Home', category: 'Misc' },
            { value: 'bi-house-fill', label: 'House Filled', category: 'Misc' },
            { value: 'bi-geo-alt', label: 'Location Pin', category: 'Misc' },
            { value: 'bi-geo-alt-fill', label: 'Location Pin Filled', category: 'Misc' },
            { value: 'bi-map', label: 'Map', category: 'Misc' },
            { value: 'bi-compass', label: 'Compass', category: 'Misc' },
            { value: 'bi-bell', label: 'Bell', category: 'Misc' },
            { value: 'bi-bell-fill', label: 'Bell Filled', category: 'Misc' },
            { value: 'bi-camera', label: 'Camera', category: 'Misc' },
            { value: 'bi-camera-video', label: 'Video Camera', category: 'Misc' },
            { value: 'bi-image', label: 'Image', category: 'Misc' },
            { value: 'bi-mic', label: 'Microphone', category: 'Misc' },
            { value: 'bi-music-note', label: 'Music Note', category: 'Misc' },
            { value: 'bi-film', label: 'Film', category: 'Misc' },
            { value: 'bi-gift', label: 'Gift', category: 'Misc' },
            { value: 'bi-balloon', label: 'Balloon', category: 'Misc' },
            { value: 'bi-emoji-smile', label: 'Smile', category: 'Misc' },
            { value: 'bi-hand-thumbs-up', label: 'Thumbs Up', category: 'Misc' },
            { value: 'bi-hand-thumbs-down', label: 'Thumbs Down', category: 'Misc' },
            { value: 'bi-handshake', label: 'Handshake', category: 'Misc' },
        ];


        // Initialize Icon Dropdowns
        function initIconDropdowns() {
            populateIconSelect('addIconSelect');
            populateIconSelect('editIconSelect');
        }

        // ============================================
        // HELPER FUNCTION FOR FILTER RESULT TOAST
        // (Add this BEFORE the filter functions so it's available)
        // ============================================
        function showFilterResultToast(module, filtered, total) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Showing ${filtered} of ${total} ${module.toLowerCase()}s`,
                showConfirmButton: false,
                timer: 2000
            });
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        }

        function showSection(section) {
            document.querySelectorAll('.dashboard-content').forEach(c => c.style.display = 'none');
            document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
            
            const sectionMap = {
                'dashboard': 'dashboardSection',
                'active-visits': 'active-visitsSection',
                'visitors': 'visitorsSection',
                'employees': 'employeesSection',
                'departments': 'departmentsSection',
                'purposes': 'purposesSection',
                
                'reports': 'reportsSection',  
                'settings': 'settingsSection'
            };
            
            if (sectionMap[section]) {
                document.getElementById(sectionMap[section]).style.display = 'block';
                event.target.closest('.sidebar-item').classList.add('active');
                
                switch(section) {
                    case 'active-visits': loadActiveVisits(); break;
                    case 'visitors': loadAllVisitors(); break;
                    case 'employees': loadEmployees(); break;
                    case 'departments': loadDepartments(); break;
                    
                    case 'reports': initReportsSection(); break;
                    case 'purposes': loadPurposes(); break;
                }
            }
        }

        function initDataTable(tableId, data, columns) {
            if (dataTableInstances[tableId]) {
                dataTableInstances[tableId].destroy();
            }
            
            const tbody = document.getElementById(tableId + 'Body');
            tbody.innerHTML = '';
            
            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = columns(row);
                tbody.appendChild(tr);
            });
            
            dataTableInstances[tableId] = $('#' + tableId).DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                order: [],
                language: {
                    emptyTable: "No data available",
                    zeroRecords: "No matching records found"
                }
            });
        }

        function loadPurposesMap() {
            fetch(ajaxUrl + '?action=get_all_purposes')
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        data.purposes.forEach(p => {
                            purposesMap[p.purpose_code] = {
                                name: p.purpose_name,
                                color_class: p.color_class,
                                icon_class: p.icon_class
                            };
                        });
                    }
                })
                .catch(e => console.error('Error loading purposes map:', e));
        }

        function getPurposeBadgeHTML(purposeCode) {
            if (!purposeCode) return '<span class="badge bg-secondary">N/A</span>';
            
            if (purposesMap[purposeCode]) {
                const purpose = purposesMap[purposeCode];
                const colorMap = {
                    'text-primary': 'bg-primary',
                    'text-success': 'bg-success',
                    'text-warning': 'bg-warning',
                    'text-danger': 'bg-danger',
                    'text-info': 'bg-info',
                    'text-secondary': 'bg-secondary',
                    'text-dark': 'bg-dark',
                    'text-purple': 'bg-purple'
                };
                
                const badgeClass = colorMap[purpose.color_class] || 'bg-secondary';
                return `<span class="badge ${badgeClass}">${purpose.name}</span>`;
            }
            
            return `<span class="badge bg-secondary">${purposeCode}</span>`;
        }

        function loadActiveVisits() {
            fetch(ajaxUrl + '?action=active_visits' + filterParam)
                .then(r => r.json())
                .then(data => {
                    initDataTable('activeVisitsTable', data, (v) => `
                        <td><span class="badge-number">${v.badge_number}</span></td>
                        <td><strong>${v.first_name} ${v.last_name}</strong></td>
                        <td>${v.company}</td>
                        <td>${v.host_name}</td>
                        <td>${v.department_name}</td>
                        <td>${getPurposeBadgeHTML(v.purpose)}</td>
                        <td><span class="notes-text" title="${v.additional_notes || ''}">${v.additional_notes || '-'}</span></td>
                        <td>${getCompanyBadgeHTML(v.company_visited)}</td>
                        <td>${new Date(v.check_in_time).toLocaleString()}</td>
                        <td>${new Date(v.valid_until).toLocaleString()}</td>
                        <td>
                            <button class="action-btn view" onclick="viewVisitDetails(${v.visit_id})" title="View"><i class="bi bi-eye"></i></button>
                            <button class="action-btn delete" onclick="checkOutVisitor(${v.visit_id})" title="Check Out"><i class="bi bi-box-arrow-right"></i></button>
                        </td>
                    `);
                    document.getElementById('activeVisitCount').textContent = data.length;
                })
                .catch(e => console.error('Error loading active visits:', e));
        }

        // function loadActiveVisits() {
        //     fetch(ajaxUrl + '?action=active_visits' + filterParam)
        //         .then(r => r.json())
        //         .then(data => {
        //             const now = new Date();
                    
        //             initDataTable('activeVisitsTable', data, (v) => {
        //                 const validUntil = new Date(v.valid_until);
        //                 const isExpired = now > validUntil;
        //                 const rowClass = isExpired ? 'style="background-color: #ffebee;"' : '';
                        
        //                 return `
        //                     <td ${rowClass}><span class="badge-number">${v.badge_number}</span></td>
        //                     <td ${rowClass}><strong>${v.first_name} ${v.last_name}</strong>${isExpired ? ' <span class="badge bg-danger ms-2"><i class="bi bi-clock-history"></i> EXPIRED</span>' : ''}</td>
        //                     <td ${rowClass}>${v.company}</td>
        //                     <td ${rowClass}>${v.host_name}</td>
        //                     <td ${rowClass}>${v.department_name}</td>
        //                     <td ${rowClass}>${getPurposeBadgeHTML(v.purpose)}</td>
        //                     <td ${rowClass}><span class="notes-text" title="${v.additional_notes || ''}">${v.additional_notes || '-'}</span></td>
        //                     <td ${rowClass}>${getCompanyBadgeHTML(v.company_visited)}</td>
        //                     <td ${rowClass}>${new Date(v.check_in_time).toLocaleString()}</td>
        //                     <td ${rowClass}>${isExpired ? '<strong class="text-danger">' + validUntil.toLocaleString() + ' <br><small>(Expired - will auto-checkout)</small></strong>' : validUntil.toLocaleString()}</td>
        //                     <td ${rowClass}>
        //                         <button class="action-btn view" onclick="viewVisitDetails(${v.visit_id})" title="View"><i class="bi bi-eye"></i></button>
        //                         <button class="action-btn delete" onclick="checkOutVisitor(${v.visit_id})" title="Check Out"><i class="bi bi-box-arrow-right"></i></button>
        //                     </td>
        //                 `;
        //             });
        //             document.getElementById('activeVisitCount').textContent = data.length;
        //         })
        //         .catch(e => console.error('Error loading active visits:', e));
        // }

        function loadAllVisitors() {
            fetch(ajaxUrl + '?action=all_visitors' + filterParam)
                .then(r => r.json())
                .then(data => {
                    initDataTable('allVisitorsTable', data, (v) => `
                        <td>${v.visitor_id}</td>
                        <td><strong>${v.first_name} ${v.last_name}</strong></td>
                        <td>${v.email || 'N/A'}</td>
                        <td>${v.phone || 'N/A'}</td>
                        <td>${v.company || 'N/A'}</td>
                        <td><span class="badge bg-info">${v.visitor_type || 'new'}</span></td>
                        <td>${v.total_visits || 0}</td>
                        <td>${v.last_visit ? new Date(v.last_visit).toLocaleDateString('en-GB') : 'N/A'}</td>
                        <td>
                            <button class="action-btn view" onclick="viewVisitor(${v.visitor_id})" title="View Details"><i class="bi bi-eye"></i></button>
                            <button class="action-btn" style="color: #3498db;" onclick="viewVisitorHistory(${v.visitor_id}, '${v.first_name} ${v.last_name}')" title="View History"><i class="bi bi-clock-history"></i></button>
                        </td>
                    `);
                })
                .catch(e => console.error('Error loading visitors:', e));
        }

        // function loadEmployees() {
        //     loadDepartmentsForSelect();
        //     fetch(ajaxUrl + '?action=employees')
        //         .then(r => r.json())
        //         .then(data => {
        //             initDataTable('employeeTable', data, (e) => `
        //                 <td>${e.employee_id}</td>
        //                 <td><strong>${e.name}</strong></td>
        //                 <td>${e.email}</td>
        //                 <td>${e.department_name}</td>
        //                 <td>
        //                     <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                         style="cursor: pointer;" 
        //                         onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${e.name.replace(/'/g, "\\'")}')" 
        //                         title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
        //                         ${e.is_active == 1 ? 'Active' : 'Inactive'}
        //                     </span>
        //                 </td>
        //                 <td>
        //                     ${e.total_visits || 0}
        //                     ${e.total_visits > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${e.name}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
        //                 </td>
        //             `);
        //         })
        //         .catch(e => console.error('Error loading employees:', e));
        // }

        // function loadEmployees() {
        //     loadDepartmentsForSelect();
        //     fetch(ajaxUrl + '?action=employees' + filterParam)
        //         .then(r => r.json())
        //         .then(data => {
        //             initDataTable('employeeTable', data, (e) => {
        //                 const canEdit = canEditEmployee(e.company_owned_by);
                        
        //                 return `
        //                     <td>${e.employee_id}</td>
        //                     <td><strong>${e.name}</strong></td>
        //                     <td>${e.email}</td>
        //                     <td>${e.department_name}</td>
        //                     <td>
        //                         <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                             style="cursor: pointer;" 
        //                             onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${e.name.replace(/'/g, "\\'")}')" 
        //                             title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
        //                             ${e.is_active == 1 ? 'Active' : 'Inactive'}
        //                         </span>
        //                     </td>
        //                     <td>
        //                         ${e.total_visits || 0}
        //                         ${e.total_visits > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${e.name}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
        //                     </td>
        //                     <td>
        //                         <span class="badge ${getCompanyOwnershipBadge(e.company_owned_by)}">
        //                             ${e.company_owned_by}
        //                         </span>
        //                     </td>
        //                     <td>
        //                         ${canEdit ? `
        //                             <button class="action-btn edit" onclick="editEmployee('${e.employee_id}')" title="Edit Employee">
        //                                 <i class="bi bi-pencil-square"></i>
        //                             </button>
        //                         ` : ''}
        //                     </td>
        //                 `;
        //             });
        //         })
        //         .catch(e => console.error('Error loading employees:', e));
        // }

        // ============================================
        // EMPLOYEE FILTER FUNCTIONS
        // ============================================

        function loadEmployees() {
            loadDepartmentsForSelect();
            loadDepartmentsForEmployeeFilter(); // Load departments for filter dropdown
            
            fetch(ajaxUrl + '?action=employees' + filterParam)
                .then(r => r.json())
                .then(data => {
                    allEmployeesData = data; // Store all data
                    renderEmployeeTable(data);
                })
                .catch(e => console.error('Error loading employees:', e));
        }

        function loadDepartmentsForEmployeeFilter() {
            fetch(ajaxUrl + '?action=departments')
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('employeeDeptFilter');
                    select.innerHTML = '<option value="">All Departments</option>';
                    // Filter to only show active departments
                    data.filter(d => d.is_active == 1).forEach(d => {
                        select.innerHTML += `<option value="${d.department_code}">${d.name}</option>`;
                    });
                });
        }

        // function renderEmployeeTable(data) {
        //     initDataTable('employeeTable', data, (e) => {
        //         const canEdit = canEditEmployee(e.company_owned_by);
                
        //         return `
        //             <td>${e.employee_id}</td>
        //             <td><strong>${e.name}</strong></td>
        //             <td>${e.email}</td>
        //             <td>${e.department_name}</td>
        //             <td>
        //                 <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${e.name.replace(/'/g, "\\'")}')" 
        //                     title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${e.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${e.total_visits || 0}
        //                 ${e.total_visits > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${e.name}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
        //             </td>
        //             <td>
        //                 <span class="badge ${getCompanyOwnershipBadge(e.company_owned_by)}">
        //                     ${e.company_owned_by}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${canEdit ? `
        //                     <button class="action-btn edit" onclick="editEmployee('${e.employee_id}')" title="Edit Employee">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 ` : ''}
        //             </td>
        //         `;
        //     });
        // }

        // function applyEmployeeFilters() {
        //     const deptFilter = document.getElementById('employeeDeptFilter').value;
        //     const statusFilter = document.getElementById('employeeStatusFilter').value;
        //     const companyFilterValue = document.getElementById('employeeCompanyFilter').value;
            
        //     if (!allEmployeesData || allEmployeesData.length === 0) {
        //         Swal.fire({
        //             toast: true,
        //             position: 'top-end',
        //             icon: 'warning',
        //             title: 'No data to filter. Please wait for data to load.',
        //             showConfirmButton: false,
        //             timer: 2000
        //         });
        //         return;
        //     }
            
        //     let filteredData = allEmployeesData.filter(e => {
        //         let matchDept = deptFilter === '' || e.department_code === deptFilter;
        //         let matchStatus = statusFilter === '' || String(e.is_active) === statusFilter;
        //         let matchCompany = companyFilterValue === '' || e.company_owned_by === companyFilterValue;
                
        //         return matchDept && matchStatus && matchCompany;
        //     });
            
        //     renderEmployeeTable(filteredData);
        //     showFilterResultToast('Employee', filteredData.length, allEmployeesData.length);
        // }

        function applyEmployeeFilters() {
            const deptFilter = document.getElementById('employeeDeptFilter').value;
            const statusFilter = document.getElementById('employeeStatusFilter').value;
            const companyFilterValue = document.getElementById('employeeCompanyFilter').value;
            
            console.log('Applying employee filters:', { deptFilter, statusFilter, companyFilterValue });
            
            if (!allEmployeesData || allEmployeesData.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'No data to filter. Please wait for data to load.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            
            let filteredData = allEmployeesData.filter(e => {
                // Department filter
                let matchDept = deptFilter === '' || e.department_code === deptFilter;
                
                // Status filter - compare as strings since select values are strings
                let matchStatus = statusFilter === '' || String(e.is_active) === statusFilter;
                
                // Company filter
                let matchCompany = companyFilterValue === '' || e.company_owned_by === companyFilterValue;
                
                return matchDept && matchStatus && matchCompany;
            });
            
            console.log('Filtered employees:', filteredData.length, 'of', allEmployeesData.length);
            
            renderEmployeeTable(filteredData);
            showFilterResultToast('Employee', filteredData.length, allEmployeesData.length);
        }

        // function canEditEmployee(companyOwnedBy) {
        //     // Super admin can edit everything
        //     if (companyFilter === null) {
        //         return true;
        //     }
            
        //     // Both companies can be edited by any admin
        //     if (companyOwnedBy === 'Both') {
        //         return true;
        //     }
            
        //     // Check if the employee belongs to the admin's company
        //     return companyOwnedBy === companyFilter;
        // }

        function canEditEmployee(companyOwnedBy) {
            console.log('Comparing:', {
                companyOwnedBy: companyOwnedBy,
                companyFilter: companyFilter,
                isMatch: companyOwnedBy === companyFilter
            });

            // Super admin can edit everything
            if (!companyFilter) {
                return true;
            }
            
            // Both companies can be edited by any admin
            if (companyOwnedBy === 'Both') {
                return true;
            }
            
            // Case-insensitive comparison
            const filterLower = companyFilter.toLowerCase().trim();
            const companyLower = (companyOwnedBy || '').toLowerCase().trim();
            
            return companyLower === filterLower;
        }

        // function editEmployee(employeeId) {
        //     fetch(ajaxUrl + `?action=get_employee&employee_id=${employeeId}`)
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success') {
        //                 const employee = data.employee;
                        
        //                 // Populate form fields
        //                 document.getElementById('editEmployeeId').value = employee.employee_id;
        //                 document.getElementById('editEmployeeIdDisplay').value = employee.employee_id;
        //                 document.getElementById('editEmployeeName').value = employee.name;
        //                 document.getElementById('editEmployeeEmail').value = employee.email;
        //                 document.getElementById('editCompanyOwnedBy').value = employee.company_owned_by;
        //                 document.getElementById('editIsActiveCheck').checked = employee.is_active == 1;
                        
        //                 // Load departments and set selected
        //                 loadDepartmentsForEditSelect(employee.department_code);
                        
        //                 new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
        //             } else {
        //                 Swal.fire('Error', data.message || 'Failed to load employee details', 'error');
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error:', e);
        //             Swal.fire('Error', 'Failed to load employee details', 'error');
        //         });
        // }

        function editEmployee(employeeId) {
            fetch(ajaxUrl + `?action=get_employee&employee_id=${employeeId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const employee = data.employee;
                        
                        // Populate form fields
                        document.getElementById('editEmployeeId').value = employee.employee_id;
                        document.getElementById('editEmployeeIdDisplay').value = employee.employee_id;
                        document.getElementById('editEmployeeIdBadge').textContent = employee.employee_id;
                        document.getElementById('editEmployeeName').value = employee.name;
                        document.getElementById('editEmployeeEmail').value = employee.email;
                        document.getElementById('editEmployeePhone').value = employee.phone_number || '';
                        document.getElementById('editEmployeePosition').value = employee.position || '';
                        document.getElementById('editCompanyOwnedBy').value = employee.company_owned_by;
                        document.getElementById('editIsActiveCheck').checked = employee.is_active == 1;
                        
                        // Set profile picture
                        const profilePic = document.getElementById('editEmployeeProfilePic');
                        if (employee.profile_pic && employee.profile_pic !== '') {
                            // Check if it's a full URL, base64, or relative path
                            if (employee.profile_pic.startsWith('http') || employee.profile_pic.startsWith('data:image')) {
                                profilePic.src = employee.profile_pic;
                            } else if (employee.profile_pic.startsWith('/') || employee.profile_pic.startsWith('assets/')) {
                                profilePic.src = employee.profile_pic;
                            } else {
                                // Assume it's stored in a specific folder or needs base URL
                                profilePic.src = baseUrl + employee.profile_pic;
                            }
                        } else {
                            profilePic.src = baseUrl + 'assets/images/icons/default-avatar.png';
                        }
                        
                        // Set status badge
                        const statusBadge = document.getElementById('editEmployeeStatusBadge');
                        if (employee.is_active == 1) {
                            statusBadge.innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>';
                        } else {
                            statusBadge.innerHTML = '<span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Inactive</span>';
                        }
                        
                        // Load departments and set selected
                        loadDepartmentsForEditSelect(employee.department_code);
                        
                        new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to load employee details', 'error');
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load employee details', 'error');
                });
        }

        // function loadDepartmentsForEditSelect(selectedDeptCode) {
        //     fetch(ajaxUrl + '?action=departments')
        //         .then(r => r.json())
        //         .then(data => {
        //             const select = document.getElementById('editEmployeeDepartment');
        //             select.innerHTML = '<option value="">Select Department</option>';
        //             data.forEach(d => {
        //                 const selected = d.department_code === selectedDeptCode ? 'selected' : '';
        //                 select.innerHTML += `<option value="${d.department_code}" ${selected}>${d.name}</option>`;
        //             });
        //         });
        // }

        function loadDepartmentsForEditSelect(selectedDeptCode) {
            fetch(ajaxUrl + '?action=departments')
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('editEmployeeDepartment');
                    select.innerHTML = '<option value="">Select Department</option>';
                    // Filter to only show active departments (but keep selected if it's inactive)
                    data.forEach(d => {
                        if (d.is_active == 1 || d.department_code === selectedDeptCode) {
                            const selected = d.department_code === selectedDeptCode ? 'selected' : '';
                            const inactiveLabel = d.is_active != 1 ? ' (Inactive)' : '';
                            select.innerHTML += `<option value="${d.department_code}" ${selected}>${d.name}${inactiveLabel}</option>`;
                        }
                    });
                });
        }

        // Add form submit handler
        document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(ajaxUrl + '?action=update_employee', { 
                method: 'POST', 
                body: formData 
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal')).hide();
                    Swal.fire({ 
                        toast: true, 
                        position: 'top-end', 
                        icon: 'success', 
                        title: 'Employee updated successfully', 
                        showConfirmButton: false, 
                        timer: 2000 
                    });
                    loadEmployees();
                } else {
                    Swal.fire('Error', data.message || 'Failed to update employee', 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
                Swal.fire('Error', 'Failed to update employee', 'error');
            });
        });

        function toggleEmployeeStatus(employeeId, currentStatus, employeeName) {
            const newStatus = currentStatus == 1 ? 0 : 1;
            const actionText = newStatus == 1 ? 'activate' : 'deactivate';
            const statusText = newStatus == 1 ? 'Active' : 'Inactive';
            
            Swal.fire({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Employee?`,
                html: `Are you sure you want to ${actionText} <strong>${employeeName}</strong>?<br><small class="text-muted">Status will be changed to: ${statusText}</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newStatus == 1 ? '#27ae60' : '#95a5a6',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: `Yes, ${actionText.charAt(0).toUpperCase() + actionText.slice(1)}`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('employee_id', employeeId);
                    formData.append('new_status', newStatus);
                    
                    fetch(ajaxUrl + '?action=toggle_employee_status', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: `Employee ${actionText}d successfully`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            loadEmployees();
                        } else {
                            Swal.fire('Error', data.error || 'Failed to update employee status', 'error');
                        }
                    })
                    .catch(e => {
                        console.error('Error:', e);
                        Swal.fire('Error', 'Failed to update employee status', 'error');
                    });
                }
            });
        }

        // function loadDepartments() {
        //     fetch(ajaxUrl + '?action=departments')
        //         .then(r => r.json())
        //         .then(data => {
        //             initDataTable('departmentTable', data, (d) => `
        //                 <td><span class="badge bg-secondary">${d.department_code}</span></td>
        //                 <td><strong>${d.name}</strong></td>
        //                 <td>
        //                     ${d.employee_count || 0}
        //                     ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
        //                 </td>
        //                 <td>${d.visit_count || 0}</td>
        //                 <td>${d.created_at || 'N/A'}</td>
        //             `);
        //         })
        //         .catch(e => console.error('Error loading departments:', e));
        // }

        // // Update loadDepartments to include edit button
        // function loadDepartments() {
        //     fetch(ajaxUrl + '?action=departments')
        //         .then(r => r.json())
        //         .then(data => {
        //             initDataTable('departmentTable', data, (d) => `
        //                 <td><span class="badge bg-secondary">${d.department_code}</span></td>
        //                 <td><strong>${d.name}</strong></td>
        //                 <td>
        //                     ${d.employee_count || 0}
        //                     ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
        //                 </td>
        //                 <td>${d.visit_count || 0}</td>
        //                 <td>${d.created_at || 'N/A'}</td>
        //                 <td>
        //                     <button class="action-btn edit" onclick="editDepartment('${d.department_code}')" title="Edit Department">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 </td>
        //             `);
        //         })
        //         .catch(e => console.error('Error loading departments:', e));
        // }

        // function loadDepartments() {
        //     fetch(ajaxUrl + '?action=departments')
        //         .then(r => r.json())
        //         .then(data => {
        //             initDataTable('departmentTable', data, (d) => `
        //                 <td><span class="badge bg-secondary">${d.department_code}</span></td>
        //                 <td><strong>${d.name}</strong></td>
        //                 <td>
        //                     ${d.employee_count || 0}
        //                     ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
        //                 </td>
        //                 <td>${d.visit_count || 0}</td>
        //                 <td>
        //                     <span class="badge ${d.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                         style="cursor: pointer;" 
        //                         onclick="toggleDepartmentStatus('${d.department_code}', ${d.is_active}, '${d.name.replace(/'/g, "\\'")}')" 
        //                         title="Click to ${d.is_active == 1 ? 'deactivate' : 'activate'}">
        //                         ${d.is_active == 1 ? 'Active' : 'Inactive'}
        //                     </span>
        //                 </td>
        //                 <td>${d.created_at || 'N/A'}</td>
        //                 <td>
        //                     <button class="action-btn edit" onclick="editDepartment('${d.department_code}')" title="Edit Department">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 </td>
        //             `);
        //         })
        //         .catch(e => console.error('Error loading departments:', e));
        // }

        // ============================================
        // DEPARTMENT FILTER FUNCTIONS
        // ============================================

        function loadDepartments() {
            fetch(ajaxUrl + '?action=departments')
                .then(r => r.json())
                .then(data => {
                    allDepartmentsData = data; // Store all data
                    renderDepartmentTable(data);
                })
                .catch(e => console.error('Error loading departments:', e));
        }

        // function renderDepartmentTable(data) {
        //     initDataTable('departmentTable', data, (d) => `
        //         <td><span class="badge bg-secondary">${d.department_code}</span></td>
        //         <td><strong>${d.name}</strong></td>
        //         <td>
        //             ${d.employee_count || 0}
        //             ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
        //         </td>
        //         <td>${d.visit_count || 0}</td>
        //         <td>
        //             <span class="badge ${d.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                 style="cursor: pointer;" 
        //                 onclick="toggleDepartmentStatus('${d.department_code}', ${d.is_active}, '${d.name.replace(/'/g, "\\'")}')" 
        //                 title="Click to ${d.is_active == 1 ? 'deactivate' : 'activate'}">
        //                 ${d.is_active == 1 ? 'Active' : 'Inactive'}
        //             </span>
        //         </td>
        //         <td>${d.created_at || 'N/A'}</td>
        //         <td>
        //             <button class="action-btn edit" onclick="editDepartment('${d.department_code}')" title="Edit Department">
        //                 <i class="bi bi-pencil-square"></i>
        //             </button>
        //         </td>
        //     `);
        // }

        // function renderEmployeeTable(data) {
        //     // ALWAYS destroy existing DataTable first
        //     if ($.fn.DataTable.isDataTable('#employeeTable')) {
        //         $('#employeeTable').DataTable().clear().destroy();
        //     }
            
        //     const tbody = document.getElementById('employeeTableBody');
        //     tbody.innerHTML = '';
            
        //     if (!data || data.length === 0) {
        //         tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No employees found matching your filters</td></tr>';
        //         $('#employeeTable').DataTable({
        //             pageLength: 10,
        //             order: [],
        //             language: {
        //                 emptyTable: "No employees found matching your filters",
        //                 zeroRecords: "No matching records found"
        //             }
        //         });
        //         return;
        //     }
            
        //     data.forEach(e => {
        //         const canEdit = canEditEmployee(e.company_owned_by);
                
        //         const tr = document.createElement('tr');
        //         tr.innerHTML = `
        //             <td>${e.employee_id}</td>
        //             <td><strong>${e.name}</strong></td>
        //             <td>${e.email}</td>
        //             <td>${e.department_name}</td>
        //             <td>
        //                 <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${e.name.replace(/'/g, "\\'")}')" 
        //                     title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${e.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${e.total_visits || 0}
        //                 ${e.total_visits > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${e.name.replace(/'/g, "\\'")}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
        //             </td>
        //             <td>
        //                 <span class="badge ${getCompanyOwnershipBadge(e.company_owned_by)}">
        //                     ${e.company_owned_by}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${canEdit ? `
        //                     <button class="action-btn edit" onclick="editEmployee('${e.employee_id}')" title="Edit Employee">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 ` : ''}
        //             </td>
        //         `;
        //         tbody.appendChild(tr);
        //     });
            
        //     $('#employeeTable').DataTable({
        //         pageLength: 10,
        //         order: [[0, 'asc']],
        //         language: {
        //             emptyTable: "No employees found",
        //             zeroRecords: "No matching records found"
        //         }
        //     });
        // }

        // function renderEmployeeTable(data) {
        //     // ALWAYS destroy existing DataTable first - with extra safety checks
        //     if ($.fn.DataTable.isDataTable('#employeeTable')) {
        //         try {
        //             $('#employeeTable').DataTable().clear().destroy();
        //         } catch (e) {
        //             console.warn('Error destroying DataTable:', e);
        //         }
        //     }
            
        //     // Also remove any DataTable classes/attributes that might persist
        //     $('#employeeTable').removeClass('dataTable no-footer');
        //     $('#employeeTable').removeAttr('aria-describedby');
            
        //     const tbody = document.getElementById('employeeTableBody');
        //     tbody.innerHTML = '';
            
        //     // Handle empty data - create proper empty rows with correct column count
        //     if (!data || data.length === 0) {
        //         // Create 8 empty cells to match header column count
        //         const tr = document.createElement('tr');
        //         tr.innerHTML = `
        //             <td colspan="8" class="text-center text-muted">No employees found matching your filters</td>
        //         `;
        //         tbody.appendChild(tr);
                
        //         // DON'T initialize DataTable when empty - just show the message
        //         // This avoids the column count mismatch error
        //         return;
        //     }
            
        //     data.forEach(e => {
        //         const canEdit = canEditEmployee(e.company_owned_by);
                
        //         const tr = document.createElement('tr');
        //         // Ensure exactly 8 <td> elements to match 8 <th> in header
        //         tr.innerHTML = `
        //             <td>${e.employee_id || ''}</td>
        //             <td><strong>${e.name || ''}</strong></td>
        //             <td>${e.email || ''}</td>
        //             <td>${e.department_name || ''}</td>
        //             <td>
        //                 <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${(e.name || '').replace(/'/g, "\\'")}')" 
        //                     title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${e.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${e.total_visits || 0}
        //                 ${(e.total_visits || 0) > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${(e.name || '').replace(/'/g, "\\'")}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
        //             </td>
        //             <td>
        //                 <span class="badge ${getCompanyOwnershipBadge(e.company_owned_by)}">
        //                     ${e.company_owned_by || 'N/A'}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${canEdit ? `
        //                     <button class="action-btn edit" onclick="editEmployee('${e.employee_id}')" title="Edit Employee">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 ` : ''}
        //             </td>
        //         `;
        //         tbody.appendChild(tr);
        //     });
            
        //     // Only initialize DataTable when we have data
        //     try {
        //         $('#employeeTable').DataTable({
        //             pageLength: 10,
        //             order: [[0, 'asc']],
        //             destroy: true, // Add this for safety
        //             language: {
        //                 emptyTable: "No employees found",
        //                 zeroRecords: "No matching records found"
        //             }
        //         });
        //     } catch (e) {
        //         console.error('Error initializing DataTable:', e);
        //     }
        // }

        function renderEmployeeTable(data) {
            // ALWAYS destroy existing DataTable first
            if ($.fn.DataTable.isDataTable('#employeeTable')) {
                try {
                    $('#employeeTable').DataTable().clear().destroy();
                } catch (e) {
                    console.warn('Error destroying DataTable:', e);
                }
            }
            
            $('#employeeTable').removeClass('dataTable no-footer');
            $('#employeeTable').removeAttr('aria-describedby');
            
            const tbody = document.getElementById('employeeTableBody');
            tbody.innerHTML = '';
            
            if (!data || data.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="10" class="text-center text-muted">No employees found matching your filters</td>`;
                tbody.appendChild(tr);
                return;
            }
            
            data.forEach(e => {
                const canEdit = canEditEmployee(e.company_owned_by);
                
                // Profile pic handling
                let profilePicHtml = '';
                if (e.profile_pic && e.profile_pic !== '') {
                    let picSrc = e.profile_pic;
                    if (!picSrc.startsWith('http') && !picSrc.startsWith('data:image') && !picSrc.startsWith('/')) {
                        picSrc = baseUrl + picSrc;
                    }
                    profilePicHtml = `<img src="${picSrc}" class="rounded-circle me-2" 
                                    style="width: 50px; height: 45px; object-fit: cover;" 
                                    onerror="this.src='${baseUrl}assets/images/icons/default-avatar.png'">`;
                } else {
                    profilePicHtml = `<div class="rounded-circle bg-secondary text-white me-2 d-inline-flex 
                                    align-items-center justify-content-center" 
                                    style="width: 50px; height: 45px; font-size: 14px;">
                                    ${(e.name || 'E')[0].toUpperCase()}
                                    </div>`;
                }
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${e.employee_id || ''}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            ${profilePicHtml}                           
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            
                            <div>
                                <strong>${e.name || ''}</strong>
                                ${e.position ? `<br><small class="text-muted">${e.position}</small>` : ''}
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="mailto:${e.email}" class="text-decoration-none">${e.email || ''}</a>
                        ${e.phone_number ? `<br><small class="text-muted"><i class="bi bi-telephone"></i> ${e.phone_number}</small>` : ''}
                    </td>
                    <td>${e.department_name || ''}</td>
                    <td>
                        <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
                            style="cursor: pointer;" 
                            onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${(e.name || '').replace(/'/g, "\\'")}')" 
                            title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
                            ${e.is_active == 1 ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        ${e.total_visits || 0}
                        ${(e.total_visits || 0) > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${(e.name || '').replace(/'/g, "\\'")}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
                    </td>
                    <td>
                        <span class="badge ${getCompanyOwnershipBadge(e.company_owned_by)}">
                            ${e.company_owned_by || 'N/A'}
                        </span>
                    </td>
                    <td>
                        ${canEdit ? `
                            <button class="action-btn edit" onclick="editEmployee('${e.employee_id}')" title="Edit Employee">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            try {
                $('#employeeTable').DataTable({
                    pageLength: 10,
                    order: [[0, 'asc']],
                    destroy: true,
                    language: {
                        emptyTable: "No employees found",
                        zeroRecords: "No matching records found"
                    }
                });
            } catch (e) {
                console.error('Error initializing DataTable:', e);
            }
        }

        // function renderDepartmentTable(data) {
        //     // ALWAYS destroy existing DataTable first
        //     if ($.fn.DataTable.isDataTable('#departmentTable')) {
        //         $('#departmentTable').DataTable().clear().destroy();
        //     }
            
        //     const tbody = document.getElementById('departmentTableBody');
        //     tbody.innerHTML = '';
            
        //     if (!data || data.length === 0) {
        //         tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No departments found matching your filters</td></tr>';
        //         $('#departmentTable').DataTable({
        //             pageLength: 10,
        //             order: [],
        //             language: {
        //                 emptyTable: "No departments found matching your filters",
        //                 zeroRecords: "No matching records found"
        //             }
        //         });
        //         return;
        //     }
            
        //     data.forEach(d => {
        //         const tr = document.createElement('tr');
        //         tr.innerHTML = `
        //             <td><span class="badge bg-secondary">${d.department_code}</span></td>
        //             <td><strong>${d.name}</strong></td>
        //             <td>
        //                 ${d.employee_count || 0}
        //                 ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name.replace(/'/g, "\\'")}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
        //             </td>
        //             <td>${d.visit_count || 0}</td>
        //             <td>
        //                 <span class="badge ${d.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="toggleDepartmentStatus('${d.department_code}', ${d.is_active}, '${d.name.replace(/'/g, "\\'")}')" 
        //                     title="Click to ${d.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${d.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>${d.created_at || 'N/A'}</td>
        //             <td>
        //                 <button class="action-btn edit" onclick="editDepartment('${d.department_code}')" title="Edit Department">
        //                     <i class="bi bi-pencil-square"></i>
        //                 </button>
        //             </td>
        //         `;
        //         tbody.appendChild(tr);
        //     });
            
        //     $('#departmentTable').DataTable({
        //         pageLength: 10,
        //         order: [[0, 'asc']],
        //         language: {
        //             emptyTable: "No departments found",
        //             zeroRecords: "No matching records found"
        //         }
        //     });
        // }

        function renderDepartmentTable(data) {
            // Destroy existing DataTable with safety checks
            if ($.fn.DataTable.isDataTable('#departmentTable')) {
                try {
                    $('#departmentTable').DataTable().clear().destroy();
                } catch (e) {
                    console.warn('Error destroying departmentTable:', e);
                }
            }
            $('#departmentTable').removeClass('dataTable no-footer');
            
            const tbody = document.getElementById('departmentTableBody');
            tbody.innerHTML = '';
            
            if (!data || data.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="7" class="text-center text-muted">No departments found matching your filters</td>`;
                tbody.appendChild(tr);
                return; // Don't initialize DataTable for empty results
            }
            
            data.forEach(d => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="badge bg-secondary">${d.department_code}</span></td>
                    <td><strong>${d.name}</strong></td>
                    <td>
                        ${d.employee_count || 0}
                        ${(d.employee_count || 0) > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${(d.name || '').replace(/'/g, "\\'")}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
                    </td>
                    <td>${d.visit_count || 0}</td>
                    <td>
                        <span class="badge ${d.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
                            style="cursor: pointer;" 
                            onclick="toggleDepartmentStatus('${d.department_code}', ${d.is_active}, '${(d.name || '').replace(/'/g, "\\'")}')" 
                            title="Click to ${d.is_active == 1 ? 'deactivate' : 'activate'}">
                            ${d.is_active == 1 ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>${d.created_at || 'N/A'}</td>
                    <td>
                        <button class="action-btn edit" onclick="editDepartment('${d.department_code}')" title="Edit Department">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            try {
                $('#departmentTable').DataTable({
                    pageLength: 10,
                    order: [[0, 'asc']],
                    destroy: true
                });
            } catch (e) {
                console.error('Error initializing departmentTable:', e);
            }
        }

        // function applyDepartmentFilters() {
        //     const statusFilter = document.getElementById('departmentStatusFilter').value;
        //     const employeeFilter = document.getElementById('departmentEmployeeFilter').value;
            
        //     let filteredData = allDepartmentsData.filter(d => {
        //         let matchStatus = statusFilter === '' || d.is_active == statusFilter;
        //         let matchEmployee = true;
                
        //         if (employeeFilter === 'yes') {
        //             matchEmployee = (d.employee_count || 0) > 0;
        //         } else if (employeeFilter === 'no') {
        //             matchEmployee = (d.employee_count || 0) === 0;
        //         }
                
        //         return matchStatus && matchEmployee;
        //     });
            
        //     renderDepartmentTable(filteredData);
        //     showFilterResultToast('Department', filteredData.length, allDepartmentsData.length);
        // }

        function applyDepartmentFilters() {
            const statusFilter = document.getElementById('departmentStatusFilter').value;
            const employeeFilter = document.getElementById('departmentEmployeeFilter').value;
            
            if (!allDepartmentsData || allDepartmentsData.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'No data to filter. Please wait for data to load.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            
            let filteredData = allDepartmentsData.filter(d => {
                let matchStatus = statusFilter === '' || String(d.is_active) === statusFilter;
                let matchEmployee = true;
                
                if (employeeFilter === 'yes') {
                    matchEmployee = (parseInt(d.employee_count) || 0) > 0;
                } else if (employeeFilter === 'no') {
                    matchEmployee = (parseInt(d.employee_count) || 0) === 0;
                }
                
                return matchStatus && matchEmployee;
            });
            
            renderDepartmentTable(filteredData);
            showFilterResultToast('Department', filteredData.length, allDepartmentsData.length);
        }

        function clearDepartmentFilters() {
            document.getElementById('departmentStatusFilter').value = '';
            document.getElementById('departmentEmployeeFilter').value = '';
            renderDepartmentTable(allDepartmentsData);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Department filters cleared',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // function loadDepartments() {
        //     fetch(ajaxUrl + '?action=departments')
        //         .then(r => r.json())
        //         .then(data => {
        //             allDepartmentsData = data; // Store all data
        //             renderDepartmentTable(data);
        //         })
        //         .catch(e => console.error('Error loading departments:', e));
        // }

        // function renderDepartmentTable(data) {
        //     initDataTable('departmentTable', data, (d) => `
        //         <td><span class="badge bg-secondary">${d.department_code}</span></td>
        //         <td><strong>${d.name}</strong></td>
        //         <td>
        //             ${d.employee_count || 0}
        //             ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
        //         </td>
        //         <td>${d.visit_count || 0}</td>
        //         <td>
        //             <span class="badge ${d.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                 style="cursor: pointer;" 
        //                 onclick="toggleDepartmentStatus('${d.department_code}', ${d.is_active}, '${d.name.replace(/'/g, "\\'")}')" 
        //                 title="Click to ${d.is_active == 1 ? 'deactivate' : 'activate'}">
        //                 ${d.is_active == 1 ? 'Active' : 'Inactive'}
        //             </span>
        //         </td>
        //         <td>${d.created_at || 'N/A'}</td>
        //         <td>
        //             <button class="action-btn edit" onclick="editDepartment('${d.department_code}')" title="Edit Department">
        //                 <i class="bi bi-pencil-square"></i>
        //             </button>
        //         </td>
        //     `);
        // }

        // function applyDepartmentFilters() {
        //     const statusFilter = document.getElementById('departmentStatusFilter').value;
        //     const employeeFilter = document.getElementById('departmentEmployeeFilter').value;
            
        //     let filteredData = allDepartmentsData.filter(d => {
        //         let matchStatus = statusFilter === '' || d.is_active == statusFilter;
        //         let matchEmployee = true;
                
        //         if (employeeFilter === 'yes') {
        //             matchEmployee = (d.employee_count || 0) > 0;
        //         } else if (employeeFilter === 'no') {
        //             matchEmployee = (d.employee_count || 0) === 0;
        //         }
                
        //         return matchStatus && matchEmployee;
        //     });
            
        //     renderDepartmentTable(filteredData);
        //     showFilterResultToast('Department', filteredData.length, allDepartmentsData.length);
        // }

        // function clearDepartmentFilters() {
        //     document.getElementById('departmentStatusFilter').value = '';
        //     document.getElementById('departmentEmployeeFilter').value = '';
        //     renderDepartmentTable(allDepartmentsData);
            
        //     Swal.fire({
        //         toast: true,
        //         position: 'top-end',
        //         icon: 'info',
        //         title: 'Department filters cleared',
        //         showConfirmButton: false,
        //         timer: 1500
        //     });
        // }

        function toggleDepartmentStatus(departmentCode, currentStatus, departmentName) {
            const newStatus = currentStatus == 1 ? 0 : 1;
            const actionText = newStatus == 1 ? 'activate' : 'deactivate';
            const statusText = newStatus == 1 ? 'Active' : 'Inactive';
            
            Swal.fire({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Department?`,
                html: `Are you sure you want to ${actionText} <strong>${departmentName}</strong>?<br><small class="text-muted">Status will be changed to: ${statusText}</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newStatus == 1 ? '#27ae60' : '#95a5a6',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: `Yes, ${actionText.charAt(0).toUpperCase() + actionText.slice(1)}`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('department_code', departmentCode);
                    formData.append('new_status', newStatus);
                    
                    fetch(ajaxUrl + '?action=toggle_department_status', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: `Department ${actionText}d successfully`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            loadDepartments();
                        } else {
                            Swal.fire('Error', data.message || 'Failed to update department status', 'error');
                        }
                    })
                    .catch(e => {
                        console.error('Error:', e);
                        Swal.fire('Error', 'Failed to update department status', 'error');
                    });
                }
            });
        }

        // Edit Department function
        function editDepartment(departmentCode) {
            fetch(ajaxUrl + `?action=get_department&department_code=${departmentCode}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const dept = data.department;
                        
                        // Populate form fields
                        document.getElementById('editDepartmentCode').value = dept.department_code;
                        document.getElementById('editDepartmentCodeDisplay').value = dept.department_code;
                        document.getElementById('editDepartmentName').value = dept.name;
                        document.getElementById('editDepartmentDescription').value = dept.description || '';
                        
                        // Populate translation fields
                        document.getElementById('editDepartmentNameEn').value = dept.name_en || '';
                        document.getElementById('editDepartmentNameZhTw').value = dept.name_zh_tw || '';
                        document.getElementById('editDepartmentNameZhCn').value = dept.name_zh_cn || '';
                        document.getElementById('editDepartmentNameFil').value = dept.name_fil || '';
                        document.getElementById('editDepartmentNameJa').value = dept.name_ja || '';
                        
                        // Populate is_active checkbox
                        document.getElementById('editDepartmentActiveCheck').checked = dept.is_active == 1;
                        
                        new bootstrap.Modal(document.getElementById('editDepartmentModal')).show();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to load department details', 'error');
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load department details', 'error');
                });
        }

        function loadDepartmentsForSelect() {
            fetch(ajaxUrl + '?action=departments')
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('employeeDepartmentSelect');
                    select.innerHTML = '<option value="">Select Department</option>';
                    // Filter to only show active departments
                    data.filter(d => d.is_active == 1).forEach(d => {
                        select.innerHTML += `<option value="${d.department_code}">${d.name}</option>`;
                    });
                });
        }

        function getCompanyBadgeHTML(companyVisited) {
            if (companyVisited === 'Toms World') {
                return `<span class="company-badge toms-world"><i class="bi bi-building"></i> Tom's World</span>`;
            } else if (companyVisited === 'Pan Asia') {
                return `<span class="company-badge pan-asia"><i class="bi bi-building"></i> Pan-Asia</span>`;
            }
            return `<span class="badge bg-secondary">${companyVisited || 'Unknown'}</span>`;
        }

        function viewVisitDetails(visitId) {
            fetch(ajaxUrl + `?action=get_visit&visit_id=${visitId}`)
                .then(r => r.json())
                .then(visit => {
                    if (visit.error) {
                        Swal.fire('Error', visit.error, 'error');
                        return;
                    }
                    
                    currentVisitId = visit.visit_id;
                    
                    let photoSrc = '<?= base_url("assets/images/icons/default-avatar.png") ?>';
                    if (visit.photo) {
                        photoSrc = visit.photo.startsWith('data:image') ? visit.photo : 
                                   (visit.photo.startsWith('/') || visit.photo.startsWith('assets/')) ? visit.photo :
                                   'data:image/jpeg;base64,' + visit.photo;
                    }
                    
                    document.getElementById('modalVisitorPhoto').src = photoSrc;
                    document.getElementById('modalVisitorPhoto').onerror = function() { this.src = '<?= base_url("assets/images/icons/default-avatar.png") ?>'; };
                    document.getElementById('modalBadgeNumber').textContent = visit.badge_number;
                    document.getElementById('modalVisitorName').textContent = `${visit.first_name} ${visit.last_name}`;
                    document.getElementById('modalEmail').textContent = visit.email || 'N/A';
                    document.getElementById('modalPhone').textContent = visit.phone || 'N/A';
                    document.getElementById('modalCompany').textContent = visit.company || 'N/A';
                    document.getElementById('modalHost').textContent = visit.host_name;
                    document.getElementById('modalPurpose').textContent = visit.purpose;
                    document.getElementById('modalCompanyVisited').innerHTML = getCompanyBadgeHTML(visit.company_visited);
                    document.getElementById('modalCheckIn').textContent = new Date(visit.check_in_time).toLocaleString();
                    document.getElementById('modalValidUntil').textContent = new Date(visit.valid_until).toLocaleString();
                    
                    if (visit.check_out_time) {
                        document.getElementById('modalStatus').innerHTML = '<span class="badge bg-secondary">Checked Out</span>';
                        document.getElementById('checkOutVisitorBtn').style.display = 'none';
                    } else {
                        document.getElementById('modalStatus').innerHTML = '<span class="badge bg-success">Active</span>';
                        document.getElementById('checkOutVisitorBtn').style.display = 'inline-block';
                    }
                    
                    new bootstrap.Modal(document.getElementById('viewVisitorModal')).show();
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load visitor details', 'error');
                });
        }

        function viewVisitor(visitorId) {
            fetch(ajaxUrl + `?action=get_visitor&visitor_id=${visitorId}`)
                .then(r => r.json())
                .then(visitor => {
                    if (visitor.error) {
                        Swal.fire('Error', visitor.error, 'error');
                        return;
                    }
                    
                    currentVisitorData = visitor;
                    
                    let photoSrc = '<?= base_url("assets/images/icons/default-avatar.png") ?>';
                    if (visitor.photo) {
                        photoSrc = visitor.photo.startsWith('data:image') ? visitor.photo : 
                                   (visitor.photo.startsWith('/') || visitor.photo.startsWith('assets/')) ? visitor.photo :
                                   'data:image/jpeg;base64,' + visitor.photo;
                    }
                    
                    document.getElementById('allVisitorPhoto').src = photoSrc;
                    document.getElementById('allVisitorPhoto').onerror = function() { this.src = '<?= base_url("assets/images/icons/default-avatar.png") ?>'; };
                    document.getElementById('visitorTypeText').textContent = (visitor.visitor_type || 'new').charAt(0).toUpperCase() + (visitor.visitor_type || 'new').slice(1);
                    document.getElementById('allVisitorFullName').textContent = `${visitor.first_name} ${visitor.last_name}`;
                    document.getElementById('allVisitorEmail').textContent = visitor.email || 'Not provided';
                    document.getElementById('allVisitorEmail').href = `mailto:${visitor.email || ''}`;
                    document.getElementById('allVisitorPhone').textContent = visitor.phone || 'Not provided';
                    document.getElementById('allVisitorPhone').href = `tel:${visitor.phone || ''}`;
                    document.getElementById('allVisitorCompany').textContent = visitor.company || 'Not specified';
                    document.getElementById('allVisitorTotalVisits').textContent = visitor.total_visits || '0';
                    document.getElementById('allVisitorLastVisit').textContent = visitor.last_visit ? new Date(visitor.last_visit).toLocaleDateString() : 'N/A';
                    document.getElementById('allVisitorCreated').textContent = visitor.created_at ? new Date(visitor.created_at).toLocaleString() : 'N/A';
                    document.getElementById('allVisitorUpdated').textContent = visitor.updated_at ? new Date(visitor.updated_at).toLocaleString() : 'N/A';
                    
                    new bootstrap.Modal(document.getElementById('viewAllVisitorModal')).show();
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load visitor details', 'error');
                });
        }

        function viewVisitorHistory(visitorId, visitorName) {
            document.getElementById('visitorHistoryName').textContent = visitorName;
            
            if ($.fn.DataTable.isDataTable('#visitorHistoryTable')) {
                $('#visitorHistoryTable').DataTable().clear().destroy();
            }
            
            fetch(ajaxUrl + `?action=visitor_history&visitor_id=${visitorId}`)
                .then(r => r.json())
                .then(visits => {
                    const tbody = document.getElementById('visitorHistoryTableBody');
                    tbody.innerHTML = '';
                    
                    if (visits.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No visit history found</td></tr>';
                        new bootstrap.Modal(document.getElementById('visitorHistoryModal')).show();
                    } else {
                        visits.forEach(visit => {
                            const checkIn = new Date(visit.check_in_time);
                            const checkOut = visit.check_out_time ? new Date(visit.check_out_time) : null;
                            let duration = 'In Progress';
                            
                            if (checkOut) {
                                const diff = checkOut - checkIn;
                                const hours = Math.floor(diff / 3600000);
                                const minutes = Math.floor((diff % 3600000) / 60000);
                                duration = `${hours}h ${minutes}m`;
                            }
                            
                            const status = checkOut 
                                ? '<span class="status-badge checked-out">Checked Out</span>' 
                                : '<span class="status-badge checked-in">Checked In</span>';
                            
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><span class="badge-number">${visit.badge_number || 'N/A'}</span></td>
                                <td>${visit.host_name}</td>
                                <td>${visit.department_name}</td>
                                <td>${getPurposeBadgeHTML(visit.purpose)}</td>
                                <td>${checkIn.toLocaleString()}</td>
                                <td>${checkOut ? checkOut.toLocaleString() : 'N/A'}</td>
                                <td>${duration}</td>
                                <td>${status}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                        
                        dataTableInstances['visitorHistoryTable'] = $('#visitorHistoryTable').DataTable({
                            pageLength: 10,
                            order: [[4, 'desc']],
                            language: {
                                emptyTable: "No visit history found",
                                zeroRecords: "No matching records found"
                            }
                        });
                        
                        new bootstrap.Modal(document.getElementById('visitorHistoryModal')).show();
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load visitor history', 'error');
                });
        }

        // function viewVisitorHistory(visitorId, visitorName) {
        //     document.getElementById('visitorHistoryName').textContent = visitorName;
            
        //     if ($.fn.DataTable.isDataTable('#visitorHistoryTable')) {
        //         $('#visitorHistoryTable').DataTable().clear().destroy();
        //     }
            
        //     fetch(ajaxUrl + `?action=visitor_history&visitor_id=${visitorId}`)
        //         .then(r => r.json())
        //         .then(visits => {
        //             const tbody = document.getElementById('visitorHistoryTableBody');
        //             tbody.innerHTML = '';
                    
        //             if (visits.length === 0) {
        //                 tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No visit history found</td></tr>';
        //                 new bootstrap.Modal(document.getElementById('visitorHistoryModal')).show();
        //             } else {
        //                 visits.forEach(visit => {
        //                     const checkIn = new Date(visit.check_in_time);
        //                     const checkOut = visit.check_out_time ? new Date(visit.check_out_time) : null;
                            
        //                     let duration = 'In Progress';
        //                     let status = '<span class="status-badge checked-in">Checked In</span>';
        //                     let checkOutDisplay = 'N/A';
                            
        //                     if (checkOut) {
        //                         // Calculate duration: checkout - checkin
        //                         const diffMs = checkOut.getTime() - checkIn.getTime();
                                
        //                         if (diffMs >= 0) {
        //                             // Positive duration (normal case)
        //                             const totalMinutes = Math.floor(diffMs / 60000);
        //                             const hours = Math.floor(totalMinutes / 60);
        //                             const minutes = totalMinutes % 60;
                                    
        //                             if (hours > 0) {
        //                                 duration = `${hours}h ${minutes}m`;
        //                             } else {
        //                                 duration = `${minutes}m`;
        //                             }
        //                         } else {
        //                             // Negative duration (data error)
        //                             duration = '<span class="text-danger">Data Error</span>';
        //                         }
                                
        //                         // Display actual checkout time
        //                         checkOutDisplay = checkOut.toLocaleString();
                                
        //                         // Check if it was auto-checked out
        //                         if (visit.auto_checkout == 1) {
        //                             status = '<span class="status-badge checked-out" title="Automatically checked out"><i class="bi bi-clock"></i> Auto Checkout</span>';
        //                         } else {
        //                             status = '<span class="status-badge checked-out">Checked Out</span>';
        //                         }
        //                     }
                            
        //                     const tr = document.createElement('tr');
        //                     tr.innerHTML = `
        //                         <td><span class="badge-number">${visit.badge_number || 'N/A'}</span></td>
        //                         <td>${visit.host_name}</td>
        //                         <td>${visit.department_name}</td>
        //                         <td>${getPurposeBadgeHTML(visit.purpose)}</td>
        //                         <td>${checkIn.toLocaleString()}</td>
        //                         <td>${checkOutDisplay}</td>
        //                         <td><strong>${duration}</strong></td>
        //                         <td>${status}</td>
        //                     `;
        //                     tbody.appendChild(tr);
        //                 });
                        
        //                 dataTableInstances['visitorHistoryTable'] = $('#visitorHistoryTable').DataTable({
        //                     pageLength: 10,
        //                     order: [[4, 'desc']],
        //                     language: {
        //                         emptyTable: "No visit history found",
        //                         zeroRecords: "No matching records found"
        //                     }
        //                 });
                        
        //                 new bootstrap.Modal(document.getElementById('visitorHistoryModal')).show();
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error:', e);
        //             Swal.fire('Error', 'Failed to load visitor history', 'error');
        //         });
        // }

        // // Auto-checkout expired visits
        // function autoCheckoutExpired() {
        //     fetch(ajaxUrl + '?action=auto_checkout_expired' + filterParam)
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success' && data.checked_out_count > 0) {
        //                 console.log(`Auto-checked out ${data.checked_out_count} expired visit(s)`);
                        
        //                 // Refresh active visits if on that section
        //                 if (document.getElementById('active-visitsSection').style.display !== 'none') {
        //                     loadActiveVisits();
        //                 }
                        
        //                 // Refresh dashboard
        //                 refreshDashboard();
                        
        //                 // Optional: Show notification
        //                 Swal.fire({
        //                     toast: true,
        //                     position: 'top-end',
        //                     icon: 'info',
        //                     title: `${data.checked_out_count} visitor(s) auto-checked out`,
        //                     showConfirmButton: false,
        //                     timer: 3000
        //                 });
        //             }
        //         })
        //         .catch(e => console.error('Error auto-checking out:', e));
        // }

        // // Run auto-checkout check every 1 minute
        // setInterval(autoCheckoutExpired, 60000);

        // // Run immediately on page load
        // autoCheckoutExpired();

        // // Run auto-checkout check every 1 minute
        // setInterval(autoCheckoutExpired, 60000);

        // // Run immediately on page load
        // autoCheckoutExpired();

        function viewEmployeeHistory(employeeId, employeeName) {
            document.getElementById('employeeHistoryName').textContent = employeeName;
            
            if ($.fn.DataTable.isDataTable('#employeeHistoryTable')) {
                $('#employeeHistoryTable').DataTable().clear().destroy();
            }
            
            fetch(ajaxUrl + `?action=employee_history&employee_id=${employeeId}`)
                .then(r => r.json())
                .then(visits => {
                    const tbody = document.getElementById('employeeHistoryTableBody');
                    tbody.innerHTML = '';
                    
                    if (visits.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No visits hosted yet</td></tr>';
                        new bootstrap.Modal(document.getElementById('employeeHistoryModal')).show();
                    } else {
                        visits.forEach(visit => {
                            const checkIn = new Date(visit.check_in_time);
                            const checkOut = visit.check_out_time ? new Date(visit.check_out_time) : null;
                            
                            let duration = 'In Progress';
                            let status = '<span class="status-badge checked-in">Checked In</span>';
                            let checkOutDisplay = 'N/A';
                            
                            if (checkOut) {
                                // Calculate duration: checkout - checkin
                                const diffMs = checkOut.getTime() - checkIn.getTime();
                                
                                if (diffMs >= 0) {
                                    const totalMinutes = Math.floor(diffMs / 60000);
                                    const hours = Math.floor(totalMinutes / 60);
                                    const minutes = totalMinutes % 60;
                                    
                                    if (hours > 0) {
                                        duration = `${hours}h ${minutes}m`;
                                    } else {
                                        duration = `${minutes}m`;
                                    }
                                } else {
                                    duration = '<span class="text-danger">Data Error</span>';
                                }
                                
                                checkOutDisplay = checkOut.toLocaleString();
                                status = '<span class="status-badge checked-out">Checked Out</span>';
                            }
                            
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><span class="badge-number">${visit.badge_number || 'N/A'}</span></td>
                                <td><strong>${visit.first_name} ${visit.last_name}</strong></td>
                                <td>${visit.company || 'N/A'}</td>
                                <td>${getPurposeBadgeHTML(visit.purpose)}</td>
                                <td>${checkIn.toLocaleString()}</td>
                                <td>${checkOutDisplay}</td>
                                <td><strong>${duration}</strong></td>
                                <td>${status}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                        
                        dataTableInstances['employeeHistoryTable'] = $('#employeeHistoryTable').DataTable({
                            pageLength: 10,
                            order: [[4, 'desc']],
                            language: {
                                emptyTable: "No visits hosted yet",
                                zeroRecords: "No matching records found"
                            }
                        });
                        
                        new bootstrap.Modal(document.getElementById('employeeHistoryModal')).show();
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load employee history', 'error');
                });
        }

        function viewDepartmentEmployees(departmentCode, departmentName) {
            document.getElementById('departmentEmployeesName').textContent = departmentName;
            
            if ($.fn.DataTable.isDataTable('#departmentEmployeesTable')) {
                $('#departmentEmployeesTable').DataTable().clear().destroy();
            }
            
            fetch(ajaxUrl + `?action=department_employees&department_code=${departmentCode}`)
                .then(r => r.json())
                .then(employees => {
                    const tbody = document.getElementById('departmentEmployeesTableBody');
                    tbody.innerHTML = '';
                    
                    if (employees.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No employees in this department</td></tr>';
                        new bootstrap.Modal(document.getElementById('departmentEmployeesModal')).show();
                    } else {
                        employees.forEach(emp => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${emp.employee_id}</td>
                                <td><strong>${emp.name}</strong></td>
                                <td><a href="mailto:${emp.email}" class="text-decoration-none">${emp.email}</a></td>
                                <td><span class="badge ${emp.is_active == 1 ? 'bg-success' : 'bg-secondary'}">${emp.is_active == 1 ? 'Active' : 'Inactive'}</span></td>
                                <td>${emp.total_visits || 0}</td>
                                <td>
                                    ${emp.total_visits > 0 ? `<button class="action-btn" style="color: #9b59b6;" onclick="viewEmployeeHistory('${emp.employee_id}', '${emp.name.replace(/'/g, "\\'")}');" title="View History"><i class="bi bi-clock-history"></i></button>` : '<span class="text-muted">-</span>'}
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                        
                        dataTableInstances['departmentEmployeesTable'] = $('#departmentEmployeesTable').DataTable({
                            pageLength: 10,
                            order: [[1, 'asc']],
                            language: {
                                emptyTable: "No employees in this department",
                                zeroRecords: "No matching records found"
                            }
                        });
                        
                        new bootstrap.Modal(document.getElementById('departmentEmployeesModal')).show();
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load department employees', 'error');
                });
        }

        function checkOutVisitor(visitId) {
            Swal.fire({
                title: 'Check Out Visitor?',
                text: 'Are you sure you want to check out this visitor?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Yes, Check Out'
            }).then((result) => {
                if (result.isConfirmed) {
                    performCheckoutRequest(visitId);
                }
            });
        }

        function performCheckout() {
            if (currentVisitId) {
                Swal.fire({
                    title: 'Check Out Visitor?',
                    text: 'Are you sure you want to check out this visitor?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Yes, Check Out'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performCheckoutRequest(currentVisitId);
                    }
                });
            }
        }

        function performCheckoutRequest(visitId) {
            const formData = new FormData();
            formData.append('visit_id', visitId);
            
            fetch(ajaxUrl + '?action=checkout', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('viewVisitorModal'))?.hide();
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: 'Visitor checked out successfully',
                        showConfirmButton: false, timer: 2000
                    });
                    loadActiveVisits();
                    refreshDashboard();
                } else {
                    Swal.fire('Error', data.error || 'Failed to check out visitor', 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
                Swal.fire('Error', 'Failed to check out visitor', 'error');
            });
        }

        function refreshDashboard() {
            fetch(ajaxUrl + '?action=dashboard_stats' + filterParam)
                .then(r => r.json())
                .then(stats => {
                    document.getElementById('todayTotal').textContent = stats.today_total;
                    document.getElementById('currentlyIn').textContent = stats.currently_in;
                    document.getElementById('avgDuration').textContent = stats.avg_duration;
                    document.getElementById('activeVisitCount').textContent = stats.currently_in;
                });
            
            fetch(ajaxUrl + '?action=recent_activity' + filterParam)
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('recentActivityTableBody');
                    tbody.innerHTML = '';
                    data.forEach(a => {
                        tbody.innerHTML += `
                            <tr>
                                <td><span class="badge-number">${a.badge_number}</span></td>
                                <td>${a.first_name} ${a.last_name}</td>
                                <td>${a.company}</td>
                                <td>${a.host_name}</td>
                                <td>${getPurposeBadgeHTML(a.purpose)}</td>
                                <td><span class="notes-text" title="${a.additional_notes || ''}">${a.additional_notes || '-'}</span></td>
                                <td>${getCompanyBadgeHTML(a.company_visited)}</td>
                                <td>${new Date(a.check_in_time).toLocaleTimeString()}</td>
                                <td>${a.check_out_time ? '<span class="status-badge checked-out">Checked Out</span>' : '<span class="status-badge checked-in">Checked In</span>'}</td>
                            </tr>
                        `;
                    });
                });
        }

        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url("auth/logout") ?>';
                }
            });
            return false;
        }

        // function loadPurposes() {
        //     fetch(ajaxUrl + '?action=get_all_purposes')
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success') {
        //                 const tbody = document.getElementById('purposeTableBody');
        //                 tbody.innerHTML = '';
                        
        //                 if (data.purposes.length === 0) {
        //                     tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No purposes found</td></tr>';
        //                     return;
        //                 }
                        
        //                 data.purposes.forEach((p, index) => {
        //                     const isFirst = index === 0;
        //                     const isLast = index === data.purposes.length - 1;
                            
        //                     const tr = document.createElement('tr');
        //                     tr.innerHTML = `
        //                         <td>
        //                             <button class="btn btn-sm btn-outline-secondary" 
        //                                     onclick="movePurpose(${p.purpose_id}, 'up')" 
        //                                     ${isFirst ? 'disabled' : ''} 
        //                                     title="Move Up">
        //                                 <i class="bi bi-arrow-up"></i>
        //                             </button>
        //                             <button class="btn btn-sm btn-outline-secondary" 
        //                                     onclick="movePurpose(${p.purpose_id}, 'down')" 
        //                                     ${isLast ? 'disabled' : ''} 
        //                                     title="Move Down">
        //                                 <i class="bi bi-arrow-down"></i>
        //                             </button>
        //                         </td>
        //                         <td><span class="badge bg-secondary">${p.purpose_code}</span></td>
        //                         <td><strong>${p.purpose_name}</strong></td>
        //                         <td><i class="${p.icon_class}" style="font-size: 1.5em;"></i></td>
        //                         <td><span class="${p.color_class}">●</span> ${p.color_class}</td>
        //                         <td>
        //                             <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                                 style="cursor: pointer;" 
        //                                 onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${p.purpose_name.replace(/'/g, "\\'")}')" 
        //                                 title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
        //                                 ${p.is_active == 1 ? 'Active' : 'Inactive'}
        //                             </span>
        //                         </td>
        //                         <td>
        //                             <button class="action-btn view" onclick="viewPurposeDetails(${p.purpose_id})" title="View Details">
        //                                 <i class="bi bi-eye"></i>
        //                             </button>
        //                         </td>
        //                     `;
        //                     tbody.appendChild(tr);
        //                 });
                        
        //                 if ($.fn.DataTable.isDataTable('#purposeTable')) {
        //                     $('#purposeTable').DataTable().destroy();
        //                 }
        //                 $('#purposeTable').DataTable({
        //                     pageLength: 10,
        //                     order: [[0, 'asc']],
        //                     columnDefs: [
        //                         { orderable: false, targets: [0, 6] }
        //                     ]
        //                 });
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error loading purposes:', e);
        //             Swal.fire('Error', 'Failed to load purposes', 'error');
        //         });
        // }

        // function loadPurposes() {
        //     fetch(ajaxUrl + '?action=get_all_purposes')
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success') {
        //                 const tbody = document.getElementById('purposeTableBody');
        //                 tbody.innerHTML = '';
                        
        //                 if (data.purposes.length === 0) {
        //                     tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No purposes found</td></tr>';
        //                     return;
        //                 }
                        
        //                 data.purposes.forEach((p, index) => {
        //                     const isFirst = index === 0;
        //                     const isLast = index === data.purposes.length - 1;
                            
        //                     // Determine if user can edit this purpose
        //                     const canEdit = canEditPurpose(p.company_owned_by);
                            
        //                     const tr = document.createElement('tr');
        //                     tr.innerHTML = `
        //                         <td>
        //                             <button class="btn btn-sm btn-outline-secondary" 
        //                                     onclick="movePurpose(${p.purpose_id}, 'up')" 
        //                                     ${isFirst ? 'disabled' : ''} 
        //                                     title="Move Up">
        //                                 <i class="bi bi-arrow-up"></i>
        //                             </button>
        //                             <button class="btn btn-sm btn-outline-secondary" 
        //                                     onclick="movePurpose(${p.purpose_id}, 'down')" 
        //                                     ${isLast ? 'disabled' : ''} 
        //                                     title="Move Down">
        //                                 <i class="bi bi-arrow-down"></i>
        //                             </button>
        //                         </td>
        //                         <td><span class="badge bg-secondary">${p.purpose_code}</span></td>
        //                         <td><strong>${p.purpose_name}</strong></td>
        //                         <td><i class="${p.icon_class}" style="font-size: 1.5em;"></i></td>
        //                         <td><span class="${p.color_class}">●</span> ${p.color_class}</td>
        //                         <td>
        //                             <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                                 style="cursor: pointer;" 
        //                                 onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${p.purpose_name.replace(/'/g, "\\'")}')" 
        //                                 title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
        //                                 ${p.is_active == 1 ? 'Active' : 'Inactive'}
        //                             </span>
        //                         </td>
        //                         <td>
        //                             <span class="badge ${getCompanyOwnershipBadge(p.company_owned_by)}">
        //                                 ${p.company_owned_by}
        //                             </span>
        //                         </td>
        //                         <td>
        //                             ${canEdit ? `
        //                                 <button class="action-btn edit" onclick="editPurpose(${p.purpose_id})" title="Edit Purpose">
        //                                     <i class="bi bi-pencil-square"></i>
        //                                 </button>
        //                             ` : ''}
                                    
        //                         </td>
        //                     `;
        //                     tbody.appendChild(tr);
        //                 });
                        
        //                 if ($.fn.DataTable.isDataTable('#purposeTable')) {
        //                     $('#purposeTable').DataTable().destroy();
        //                 }
        //                 $('#purposeTable').DataTable({
        //                     pageLength: 10,
        //                     order: [[0, 'asc']],
        //                     columnDefs: [
        //                         { orderable: false, targets: [0, 7] }
        //                     ]
        //                 });
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error loading purposes:', e);
        //             Swal.fire('Error', 'Failed to load purposes', 'error');
        //         });
        // }

        // ============================================
        // PURPOSE FILTER FUNCTIONS
        // ============================================

        // function loadPurposes() {
        //     fetch(ajaxUrl + '?action=get_all_purposes')
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success') {
        //                 allPurposesData = data.purposes; // Store all data
        //                 renderPurposeTable(data.purposes);
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error loading purposes:', e);
        //             Swal.fire('Error', 'Failed to load purposes', 'error');
        //         });
        // }

        // function renderPurposeTable(purposes) {
        //     const tbody = document.getElementById('purposeTableBody');
        //     tbody.innerHTML = '';
            
        //     if (purposes.length === 0) {
        //         tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No purposes found</td></tr>';
        //         return;
        //     }
            
        //     purposes.forEach((p, index) => {
        //         const isFirst = index === 0;
        //         const isLast = index === purposes.length - 1;
        //         const canEdit = canEditPurpose(p.company_owned_by);
                
        //         const tr = document.createElement('tr');
        //         tr.innerHTML = `
        //             <td>
        //                 <button class="btn btn-sm btn-outline-secondary" 
        //                         onclick="movePurpose(${p.purpose_id}, 'up')" 
        //                         ${isFirst ? 'disabled' : ''} 
        //                         title="Move Up">
        //                     <i class="bi bi-arrow-up"></i>
        //                 </button>
        //                 <button class="btn btn-sm btn-outline-secondary" 
        //                         onclick="movePurpose(${p.purpose_id}, 'down')" 
        //                         ${isLast ? 'disabled' : ''} 
        //                         title="Move Down">
        //                     <i class="bi bi-arrow-down"></i>
        //                 </button>
        //             </td>
        //             <td><span class="badge bg-secondary">${p.purpose_code}</span></td>
        //             <td><strong>${p.purpose_name}</strong></td>
        //             <td><i class="${p.icon_class}" style="font-size: 1.5em;"></i></td>
        //             <td><span class="${p.color_class}">●</span> ${p.color_class}</td>
        //             <td>
        //                 <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${p.purpose_name.replace(/'/g, "\\'")}')" 
        //                     title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${p.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>
        //                 <span class="badge ${getCompanyOwnershipBadge(p.company_owned_by)}">
        //                     ${p.company_owned_by}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${canEdit ? `
        //                     <button class="action-btn edit" onclick="editPurpose(${p.purpose_id})" title="Edit Purpose">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 ` : ''}
        //             </td>
        //         `;
        //         tbody.appendChild(tr);
        //     });
            
        //     // Reinitialize DataTable
        //     if ($.fn.DataTable.isDataTable('#purposeTable')) {
        //         $('#purposeTable').DataTable().destroy();
        //     }
        //     $('#purposeTable').DataTable({
        //         pageLength: 10,
        //         order: [[0, 'asc']],
        //         columnDefs: [
        //             { orderable: false, targets: [0, 7] }
        //         ]
        //     });
        // }

        // function applyPurposeFilters() {
        //     const statusFilter = document.getElementById('purposeStatusFilter').value;
        //     const companyFilter = document.getElementById('purposeCompanyFilter').value;
        //     const colorFilter = document.getElementById('purposeColorFilter').value;
            
        //     let filteredData = allPurposesData.filter(p => {
        //         let matchStatus = statusFilter === '' || p.is_active == statusFilter;
        //         let matchCompany = !companyFilter || p.company_owned_by === companyFilter;
        //         let matchColor = !colorFilter || p.color_class === colorFilter;
                
        //         return matchStatus && matchCompany && matchColor;
        //     });
            
        //     renderPurposeTable(filteredData);
        //     showFilterResultToast('Purpose', filteredData.length, allPurposesData.length);
        // }

        // function clearPurposeFilters() {
        //     document.getElementById('purposeStatusFilter').value = '';
        //     document.getElementById('purposeCompanyFilter').value = '';
        //     document.getElementById('purposeColorFilter').value = '';
        //     renderPurposeTable(allPurposesData);
            
        //     Swal.fire({
        //         toast: true,
        //         position: 'top-end',
        //         icon: 'info',
        //         title: 'Purpose filters cleared',
        //         showConfirmButton: false,
        //         timer: 1500
        //     });
        // }

        // ============================================
        // PURPOSE FILTER FUNCTIONS
        // ============================================

        function loadPurposes() {
            fetch(ajaxUrl + '?action=get_all_purposes')
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        allPurposesData = data.purposes; // Store all data
                        renderPurposeTable(data.purposes);
                    }
                })
                .catch(e => {
                    console.error('Error loading purposes:', e);
                    Swal.fire('Error', 'Failed to load purposes', 'error');
                });
        }

        // function renderPurposeTable(purposes) {
        //     // ALWAYS destroy existing DataTable first
        //     if ($.fn.DataTable.isDataTable('#purposeTable')) {
        //         $('#purposeTable').DataTable().clear().destroy();
        //     }
            
        //     const tbody = document.getElementById('purposeTableBody');
        //     tbody.innerHTML = '';
            
        //     if (!purposes || purposes.length === 0) {
        //         tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No purposes found matching your filters</td></tr>';
        //         // Still initialize DataTable even for empty results
        //         $('#purposeTable').DataTable({
        //             pageLength: 10,
        //             order: [],
        //             columnDefs: [
        //                 { orderable: false, targets: [0, 7] }
        //             ],
        //             language: {
        //                 emptyTable: "No purposes found matching your filters",
        //                 zeroRecords: "No matching records found"
        //             }
        //         });
        //         return;
        //     }
            
        //     purposes.forEach((p, index) => {
        //         const isFirst = index === 0;
        //         const isLast = index === purposes.length - 1;
        //         const canEdit = canEditPurpose(p.company_owned_by);
                
        //         const tr = document.createElement('tr');
        //         // Store data attributes for filtering reference
        //         tr.setAttribute('data-status', p.is_active);
        //         tr.setAttribute('data-company', p.company_owned_by);
        //         tr.setAttribute('data-color', p.color_class);
                
        //         tr.innerHTML = `
        //             <td>
        //                 <button class="btn btn-sm btn-outline-secondary" 
        //                         onclick="movePurpose(${p.purpose_id}, 'up')" 
        //                         ${isFirst ? 'disabled' : ''} 
        //                         title="Move Up">
        //                     <i class="bi bi-arrow-up"></i>
        //                 </button>
        //                 <button class="btn btn-sm btn-outline-secondary" 
        //                         onclick="movePurpose(${p.purpose_id}, 'down')" 
        //                         ${isLast ? 'disabled' : ''} 
        //                         title="Move Down">
        //                     <i class="bi bi-arrow-down"></i>
        //                 </button>
        //             </td>
        //             <td><span class="badge bg-secondary">${p.purpose_code}</span></td>
        //             <td><strong>${p.purpose_name}</strong></td>
        //             <td><i class="${p.icon_class}" style="font-size: 1.5em;"></i></td>
        //             <td><span class="${p.color_class}">●</span> ${p.color_class.replace('text-', '')}</td>
        //             <td>
        //                 <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${p.purpose_name.replace(/'/g, "\\'")}')" 
        //                     title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${p.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>
        //                 <span class="badge ${getCompanyOwnershipBadge(p.company_owned_by)}">
        //                     ${p.company_owned_by}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${canEdit ? `
        //                     <button class="action-btn edit" onclick="editPurpose(${p.purpose_id})" title="Edit Purpose">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 ` : ''}
        //             </td>
        //         `;
        //         tbody.appendChild(tr);
        //     });
            
        //     // Reinitialize DataTable
        //     $('#purposeTable').DataTable({
        //         pageLength: 10,
        //         order: [], // Don't auto-sort, preserve display_order
        //         columnDefs: [
        //             { orderable: false, targets: [0, 7] }
        //         ],
        //         language: {
        //             emptyTable: "No purposes found",
        //             zeroRecords: "No matching records found"
        //         }
        //     });
        // }

        function renderPurposeTable(purposes) {
            // Destroy existing DataTable with safety checks
            if ($.fn.DataTable.isDataTable('#purposeTable')) {
                try {
                    $('#purposeTable').DataTable().clear().destroy();
                } catch (e) {
                    console.warn('Error destroying purposeTable:', e);
                }
            }
            $('#purposeTable').removeClass('dataTable no-footer');
            
            const tbody = document.getElementById('purposeTableBody');
            tbody.innerHTML = '';
            
            if (!purposes || purposes.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="8" class="text-center text-muted">No purposes found matching your filters</td>`;
                tbody.appendChild(tr);
                return; // Don't initialize DataTable for empty results
            }
            
            purposes.forEach((p, index) => {
                const isFirst = index === 0;
                const isLast = index === purposes.length - 1;
                const canEdit = canEditPurpose(p.company_owned_by);
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="movePurpose(${p.purpose_id}, 'up')" 
                                ${isFirst ? 'disabled' : ''} 
                                title="Move Up">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" 
                                onclick="movePurpose(${p.purpose_id}, 'down')" 
                                ${isLast ? 'disabled' : ''} 
                                title="Move Down">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                    </td>
                    <td><span class="badge bg-secondary">${p.purpose_code}</span></td>
                    <td><strong>${p.purpose_name}</strong></td>
                    <td><i class="${p.icon_class}" style="font-size: 1.5em;"></i></td>
                    <td><span class="${p.color_class}">●</span> ${(p.color_class || '').replace('text-', '')}</td>
                    <td>
                        <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
                            style="cursor: pointer;" 
                            onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${(p.purpose_name || '').replace(/'/g, "\\'")}')" 
                            title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
                            ${p.is_active == 1 ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td>
                        <span class="badge ${getCompanyOwnershipBadge(p.company_owned_by)}">
                            ${p.company_owned_by || 'N/A'}
                        </span>
                    </td>
                    <td>
                        ${canEdit ? `
                            <button class="action-btn edit" onclick="editPurpose(${p.purpose_id})" title="Edit Purpose">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            try {
                $('#purposeTable').DataTable({
                    pageLength: 10,
                    order: [],
                    destroy: true,
                    columnDefs: [
                        { orderable: false, targets: [0, 7] }
                    ]
                });
            } catch (e) {
                console.error('Error initializing purposeTable:', e);
            }
        }

        // function renderPurposeTable(purposes) {
        //     const tbody = document.getElementById('purposeTableBody');
        //     tbody.innerHTML = '';
            
        //     if (purposes.length === 0) {
        //         tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No purposes found</td></tr>';
        //         return;
        //     }
            
        //     purposes.forEach((p, index) => {
        //         const isFirst = index === 0;
        //         const isLast = index === purposes.length - 1;
        //         const canEdit = canEditPurpose(p.company_owned_by);
                
        //         const tr = document.createElement('tr');
        //         tr.innerHTML = `
        //             <td>
        //                 <button class="btn btn-sm btn-outline-secondary" 
        //                         onclick="movePurpose(${p.purpose_id}, 'up')" 
        //                         ${isFirst ? 'disabled' : ''} 
        //                         title="Move Up">
        //                     <i class="bi bi-arrow-up"></i>
        //                 </button>
        //                 <button class="btn btn-sm btn-outline-secondary" 
        //                         onclick="movePurpose(${p.purpose_id}, 'down')" 
        //                         ${isLast ? 'disabled' : ''} 
        //                         title="Move Down">
        //                     <i class="bi bi-arrow-down"></i>
        //                 </button>
        //             </td>
        //             <td><span class="badge bg-secondary">${p.purpose_code}</span></td>
        //             <td><strong>${p.purpose_name}</strong></td>
        //             <td><i class="${p.icon_class}" style="font-size: 1.5em;"></i></td>
        //             <td><span class="${p.color_class}">●</span> ${p.color_class}</td>
        //             <td>
        //                 <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
        //                     style="cursor: pointer;" 
        //                     onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${p.purpose_name.replace(/'/g, "\\'")}')" 
        //                     title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
        //                     ${p.is_active == 1 ? 'Active' : 'Inactive'}
        //                 </span>
        //             </td>
        //             <td>
        //                 <span class="badge ${getCompanyOwnershipBadge(p.company_owned_by)}">
        //                     ${p.company_owned_by}
        //                 </span>
        //             </td>
        //             <td>
        //                 ${canEdit ? `
        //                     <button class="action-btn edit" onclick="editPurpose(${p.purpose_id})" title="Edit Purpose">
        //                         <i class="bi bi-pencil-square"></i>
        //                     </button>
        //                 ` : ''}
        //             </td>
        //         `;
        //         tbody.appendChild(tr);
        //     });
            
        //     // Reinitialize DataTable
        //     if ($.fn.DataTable.isDataTable('#purposeTable')) {
        //         $('#purposeTable').DataTable().destroy();
        //     }
        //     $('#purposeTable').DataTable({
        //         pageLength: 10,
        //         order: [[0, 'asc']],
        //         columnDefs: [
        //             { orderable: false, targets: [0, 7] }
        //         ]
        //     });
        // }

        // function applyPurposeFilters() {
        //     const statusFilter = document.getElementById('purposeStatusFilter').value;
        //     const companyFilter = document.getElementById('purposeCompanyFilter').value;
        //     const colorFilter = document.getElementById('purposeColorFilter').value;
            
        //     let filteredData = allPurposesData.filter(p => {
        //         let matchStatus = statusFilter === '' || p.is_active == statusFilter;
        //         let matchCompany = !companyFilter || p.company_owned_by === companyFilter;
        //         let matchColor = !colorFilter || p.color_class === colorFilter;
                
        //         return matchStatus && matchCompany && matchColor;
        //     });
            
        //     renderPurposeTable(filteredData);
        //     showFilterResultToast('Purpose', filteredData.length, allPurposesData.length);
        // }

        function applyPurposeFilters() {
            const statusFilter = document.getElementById('purposeStatusFilter').value;
            const companyFilterValue = document.getElementById('purposeCompanyFilter').value;
            const colorFilter = document.getElementById('purposeColorFilter').value;
            
            console.log('Applying filters:', { statusFilter, companyFilterValue, colorFilter }); // Debug log
            console.log('All purposes data:', allPurposesData); // Debug log
            
            if (!allPurposesData || allPurposesData.length === 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'No data to filter. Please wait for data to load.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            
            let filteredData = allPurposesData.filter(p => {
                // Status filter: empty means all, otherwise match exact value
                let matchStatus = statusFilter === '' || String(p.is_active) === statusFilter;
                
                // Company filter: empty means all, otherwise match exact value
                let matchCompany = companyFilterValue === '' || p.company_owned_by === companyFilterValue;
                
                // Color filter: empty means all, otherwise match exact value
                let matchColor = colorFilter === '' || p.color_class === colorFilter;
                
                return matchStatus && matchCompany && matchColor;
            });
            
            console.log('Filtered data:', filteredData); // Debug log
            
            renderPurposeTable(filteredData);
            showFilterResultToast('Purpose', filteredData.length, allPurposesData.length);
        }

        function clearPurposeFilters() {
            document.getElementById('purposeStatusFilter').value = '';
            document.getElementById('purposeCompanyFilter').value = '';
            document.getElementById('purposeColorFilter').value = '';
            
            // Re-render with all data
            renderPurposeTable(allPurposesData);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Purpose filters cleared',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // function clearPurposeFilters() {
        //     document.getElementById('purposeStatusFilter').value = '';
        //     document.getElementById('purposeCompanyFilter').value = '';
        //     document.getElementById('purposeColorFilter').value = '';
        //     renderPurposeTable(allPurposesData);
            
        //     Swal.fire({
        //         toast: true,
        //         position: 'top-end',
        //         icon: 'info',
        //         title: 'Purpose filters cleared',
        //         showConfirmButton: false,
        //         timer: 1500
        //     });
        // }


        function canEditPurpose(companyOwnedBy) {
            // Super admin can edit everything
            if (companyFilter === null) {
                return true;
            }
            
            // Both companies can be edited by any admin
            if (companyOwnedBy === 'Both') {
                return true;
            }
            
            // Check if the purpose belongs to the admin's company
            return companyOwnedBy === companyFilter;
        }

        function getCompanyOwnershipBadge(companyOwnedBy) {
            if (companyOwnedBy === 'Toms World') {
                return 'bg-warning text-dark';
            } else if (companyOwnedBy === 'Pan Asia') {
                return 'bg-success';
            } else {
                return 'bg-info';
            }
        }

        // function editPurpose(purposeId) {
        //     fetch(ajaxUrl + `?action=get_purpose&purpose_id=${purposeId}`)
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success') {
        //                 const purpose = data.purpose;
                        
        //                 document.getElementById('editPurposeId').value = purpose.purpose_id;
        //                 document.getElementById('editPurposeCode').value = purpose.purpose_code;
        //                 document.getElementById('editPurposeName').value = purpose.purpose_name;
        //                 document.getElementById('editIconClass').value = purpose.icon_class;
        //                 document.getElementById('editColorClass').value = purpose.color_class;
        //                 document.getElementById('editCompanyOwnedBy').value = purpose.company_owned_by;
        //                 document.getElementById('editPurposeActiveCheck').checked = purpose.is_active == 1;
                        
        //                 new bootstrap.Modal(document.getElementById('editPurposeModal')).show();
        //             } else {
        //                 Swal.fire('Error', data.message || 'Failed to load purpose details', 'error');
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error:', e);
        //             Swal.fire('Error', 'Failed to load purpose details', 'error');
        //         });
        // }

        // Update editPurpose to populate translation fields
        // function editPurpose(purposeId) {
        //     fetch(ajaxUrl + `?action=get_purpose&purpose_id=${purposeId}`)
        //         .then(r => r.json())
        //         .then(data => {
        //             if (data.status === 'success') {
        //                 const purpose = data.purpose;
                        
        //                 // Populate main fields
        //                 document.getElementById('editPurposeId').value = purpose.purpose_id;
        //                 document.getElementById('editPurposeCode').value = purpose.purpose_code;
        //                 document.getElementById('editPurposeName').value = purpose.purpose_name;
        //                 document.getElementById('editIconClass').value = purpose.icon_class;
        //                 document.getElementById('editColorClass').value = purpose.color_class;
        //                 document.getElementById('editPurposeCompanyOwnedBy').value = purpose.company_owned_by;
        //                 document.getElementById('editPurposeActiveCheck').checked = purpose.is_active == 1;
                        
        //                 // Populate translation fields
        //                 document.getElementById('editPurposeNameEn').value = purpose.name_en || '';
        //                 document.getElementById('editPurposeNameZhTw').value = purpose.name_zh_tw || '';
        //                 document.getElementById('editPurposeNameZhCn').value = purpose.name_zh_cn || '';
        //                 document.getElementById('editPurposeNameFil').value = purpose.name_fil || '';
        //                 document.getElementById('editPurposeNameJa').value = purpose.name_ja || '';
                        
        //                 new bootstrap.Modal(document.getElementById('editPurposeModal')).show();
        //             } else {
        //                 Swal.fire('Error', data.message || 'Failed to load purpose details', 'error');
        //             }
        //         })
        //         .catch(e => {
        //             console.error('Error:', e);
        //             Swal.fire('Error', 'Failed to load purpose details', 'error');
        //         });
        // }

        // UPDATED: Edit Purpose function to properly set icon dropdown
        function editPurpose(purposeId) {
            fetch(ajaxUrl + `?action=get_purpose&purpose_id=${purposeId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const purpose = data.purpose;
                        
                        // Populate main fields
                        document.getElementById('editPurposeId').value = purpose.purpose_id;
                        document.getElementById('editPurposeCode').value = purpose.purpose_code;
                        document.getElementById('editPurposeName').value = purpose.purpose_name;
                        
                        // Set icon dropdown value
                        const iconSelect = document.getElementById('editIconSelect');
                        if (iconSelect) {
                            // Check if the icon exists in our list
                            const iconExists = Array.from(iconSelect.options).some(opt => opt.value === purpose.icon_class);
                            
                            if (iconExists) {
                                iconSelect.value = purpose.icon_class;
                            } else {
                                // If icon doesn't exist in dropdown, add it as a custom option
                                const customOption = document.createElement('option');
                                customOption.value = purpose.icon_class;
                                customOption.textContent = purpose.icon_class + ' (Custom)';
                                iconSelect.insertBefore(customOption, iconSelect.firstChild);
                                iconSelect.value = purpose.icon_class;
                            }
                        }
                        
                        // Set color dropdown value
                        const colorSelect = document.getElementById('editColorSelect');
                        if (colorSelect) {
                            colorSelect.value = purpose.color_class;
                        }
                        
                        // Update previews
                        updateIconPreview('edit');
                        updateColorPreview('edit');
                        
                        // Update the preview text
                        const previewText = document.getElementById('editPurposePreviewText');
                        if (previewText) {
                            previewText.textContent = purpose.purpose_name;
                        }
                        
                        document.getElementById('editPurposeCompanyOwnedBy').value = purpose.company_owned_by;
                        document.getElementById('editPurposeActiveCheck').checked = purpose.is_active == 1;
                        
                        // Populate translation fields
                        document.getElementById('editPurposeNameEn').value = purpose.name_en || '';
                        document.getElementById('editPurposeNameZhTw').value = purpose.name_zh_tw || '';
                        document.getElementById('editPurposeNameZhCn').value = purpose.name_zh_cn || '';
                        document.getElementById('editPurposeNameFil').value = purpose.name_fil || '';
                        document.getElementById('editPurposeNameJa').value = purpose.name_ja || '';
                        
                        new bootstrap.Modal(document.getElementById('editPurposeModal')).show();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to load purpose details', 'error');
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to load purpose details', 'error');
                });
        }

        // Add form submit handler in $(document).ready()
        document.getElementById('editPurposeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(ajaxUrl + '?action=update_purpose', { 
                method: 'POST', 
                body: formData 
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('editPurposeModal')).hide();
                    Swal.fire({ 
                        toast: true, 
                        position: 'top-end', 
                        icon: 'success', 
                        title: 'Purpose updated successfully', 
                        showConfirmButton: false, 
                        timer: 2000 
                    });
                    loadPurposes();
                    loadPurposesMap();
                } else {
                    Swal.fire('Error', data.message || 'Failed to update purpose', 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
                Swal.fire('Error', 'Failed to update purpose', 'error');
            });
        });

        function togglePurposeStatus(purposeId, currentStatus, purposeName) {
            const newStatus = currentStatus == 1 ? 0 : 1;
            const actionText = newStatus == 1 ? 'activate' : 'deactivate';
            const statusText = newStatus == 1 ? 'Active' : 'Inactive';
            
            Swal.fire({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Purpose?`,
                html: `Are you sure you want to ${actionText} <strong>${purposeName}</strong>?<br><small class="text-muted">Status will be changed to: ${statusText}</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newStatus == 1 ? '#27ae60' : '#95a5a6',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: `Yes, ${actionText.charAt(0).toUpperCase() + actionText.slice(1)}`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('purpose_id', purposeId);
                    formData.append('new_status', newStatus);
                    
                    fetch(ajaxUrl + '?action=toggle_purpose_status', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: `Purpose ${actionText}d successfully`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            loadPurposes();
                        } else {
                            Swal.fire('Error', data.message || 'Failed to update purpose status', 'error');
                        }
                    })
                    .catch(e => {
                        console.error('Error:', e);
                        Swal.fire('Error', 'Failed to update purpose status', 'error');
                    });
                }
            });
        }

        // function movePurpose(purposeId, direction) {
        //     const formData = new FormData();
        //     formData.append('purpose_id', purposeId);
        //     formData.append('direction', direction);
            
        //     fetch(ajaxUrl + '?action=update_purpose_order', {
        //         method: 'POST',
        //         body: formData
        //     })
        //     .then(r => r.json())
        //     .then(data => {
        //         if (data.status === 'success') {
        //             loadPurposes();
        //         } else {
        //             Swal.fire('Error', data.message || 'Failed to update order', 'error');
        //         }
        //     })
        //     .catch(e => {
        //         console.error('Error:', e);
        //         Swal.fire('Error', 'Failed to update order', 'error');
        //     });
        // }

        // UPDATED: Move Purpose function with proper reload
        function movePurpose(purposeId, direction) {
            const formData = new FormData();
            formData.append('purpose_id', purposeId);
            formData.append('direction', direction);
            
            // Show loading indicator
            Swal.fire({
                title: 'Updating order...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch(ajaxUrl + '?action=update_purpose_order', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                Swal.close();
                
                if (data.status === 'success') {
                    // Show success toast
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Order updated successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    // Destroy existing DataTable before reloading
                    if ($.fn.DataTable.isDataTable('#purposeTable')) {
                        $('#purposeTable').DataTable().clear().destroy();
                    }
                    
                    // Clear the table body
                    document.getElementById('purposeTableBody').innerHTML = '';
                    
                    // Reload purposes with fresh data
                    loadPurposes();
                } else {
                    Swal.fire('Error', data.message || 'Failed to update order', 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
                Swal.close();
                Swal.fire('Error', 'Failed to update order', 'error');
            });
        }

        function viewPurposeDetails(purposeId) {
            Swal.fire({
                title: 'Purpose Details',
                text: 'Purpose details viewing feature coming soon',
                icon: 'info'
            });
        }

        function showFilterResultToast(module, filtered, total) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Showing ${filtered} of ${total} ${module.toLowerCase()}s`,
                showConfirmButton: false,
                timer: 2000
            });
        }

        document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(ajaxUrl + '?action=add_employee', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal')).hide();
                        this.reset();
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Employee added successfully', showConfirmButton: false, timer: 2000 });
                        loadEmployees();
                    } else {
                        Swal.fire('Error', data.error || 'Failed to add employee', 'error');
                    }
                })
                .catch(e => Swal.fire('Error', 'Failed to add employee', 'error'));
        });

        document.getElementById('addDepartmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(ajaxUrl + '?action=add_department', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addDepartmentModal')).hide();
                        this.reset();
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Department added successfully', showConfirmButton: false, timer: 2000 });
                        loadDepartments();
                    } else {
                        Swal.fire('Error', data.error || 'Failed to add department', 'error');
                    }
                })
                .catch(e => Swal.fire('Error', 'Failed to add department', 'error'));
        });

        document.getElementById('addPurposeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(ajaxUrl + '?action=add_purpose', { 
                method: 'POST', 
                body: formData 
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('addPurposeModal')).hide();
                    this.reset();
                    Swal.fire({ 
                        toast: true, 
                        position: 'top-end', 
                        icon: 'success', 
                        title: 'Purpose added successfully', 
                        showConfirmButton: false, 
                        timer: 2000 
                    });
                    loadPurposes();
                    loadPurposesMap();
                } else {
                    Swal.fire('Error', data.message || 'Failed to add purpose', 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
                Swal.fire('Error', 'Failed to add purpose', 'error');
            });
        });

        $(document).ready(function() {
            // Initialize icon dropdowns
            initIconDropdowns();

            // Update preview text on purpose name change (Add modal)
            $('input[name="purpose_name"]').on('input', function() {
                const modal = $(this).closest('.modal');
                const isAdd = modal.attr('id') === 'addPurposeModal';
                const previewTextId = isAdd ? 'addPurposePreviewText' : 'editPurposePreviewText';
                const previewText = document.getElementById(previewTextId);
                if (previewText) {
                    previewText.textContent = $(this).val() || 'Purpose Preview';
                }
            });
            
            // Reset add form when modal is closed
            $('#addPurposeModal').on('hidden.bs.modal', function() {
                document.getElementById('addPurposeForm').reset();
                document.getElementById('addIconSelect').value = 'bi-circle';
                document.getElementById('addColorSelect').value = 'text-primary';
                updateIconPreview('add');
                updateColorPreview('add');
                document.getElementById('addPurposePreviewText').textContent = 'Purpose Preview';
            }); 

            // Employee filter - apply on Enter key
            $('#employeeDeptFilter, #employeeStatusFilter, #employeeCompanyFilter').on('keypress', function(e) {
                if (e.which === 13) {
                    applyEmployeeFilters();
                }
            });
            
            // Department filter - apply on Enter key
            $('#departmentStatusFilter, #departmentEmployeeFilter').on('keypress', function(e) {
                if (e.which === 13) {
                    applyDepartmentFilters();
                }
            });
            
            // Purpose filter - apply on Enter key
            $('#purposeStatusFilter, #purposeCompanyFilter, #purposeColorFilter').on('keypress', function(e) {
                if (e.which === 13) {
                    applyPurposeFilters();
                }
            });
            
            // Auto-apply filters on change (optional - uncomment if you want instant filtering)
            /*
            $('#employeeDeptFilter, #employeeStatusFilter, #employeeCompanyFilter').on('change', applyEmployeeFilters);
            $('#departmentStatusFilter, #departmentEmployeeFilter').on('change', applyDepartmentFilters);
            $('#purposeStatusFilter, #purposeCompanyFilter, #purposeColorFilter').on('change', applyPurposeFilters);
            */
        
            if (!$.fn.DataTable.isDataTable('#recentActivityTable')) {
                $('#recentActivityTable').DataTable({ pageLength: 10, order: [] });
            }
            
            loadPurposesMap();

            $('#visitorHistoryModal').on('hidden.bs.modal', function () {
                if ($.fn.DataTable.isDataTable('#visitorHistoryTable')) {
                    $('#visitorHistoryTable').DataTable().clear().destroy();
                }
            });
            
            $('#employeeHistoryModal').on('hidden.bs.modal', function () {
                if ($.fn.DataTable.isDataTable('#employeeHistoryTable')) {
                    $('#employeeHistoryTable').DataTable().clear().destroy();
                }
            });
            
            $('#departmentEmployeesModal').on('hidden.bs.modal', function () {
                if ($.fn.DataTable.isDataTable('#departmentEmployeesTable')) {
                    $('#departmentEmployeesTable').DataTable().clear().destroy();
                }
            });

            // Add Department Form with translations
            document.getElementById('addDepartmentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(ajaxUrl + '?action=add_department', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(document.getElementById('addDepartmentModal')).hide();
                            this.reset();
                            Swal.fire({ 
                                toast: true, 
                                position: 'top-end', 
                                icon: 'success', 
                                title: 'Department added successfully', 
                                showConfirmButton: false, 
                                timer: 2000 
                            });
                            loadDepartments();
                        } else {
                            Swal.fire('Error', data.error || 'Failed to add department', 'error');
                        }
                    })
                    .catch(e => Swal.fire('Error', 'Failed to add department', 'error'));
            });
            
            // Edit Department Form
            document.getElementById('editDepartmentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(ajaxUrl + '?action=update_department', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            bootstrap.Modal.getInstance(document.getElementById('editDepartmentModal')).hide();
                            Swal.fire({ 
                                toast: true, 
                                position: 'top-end', 
                                icon: 'success', 
                                title: 'Department updated successfully', 
                                showConfirmButton: false, 
                                timer: 2000 
                            });
                            loadDepartments();
                        } else {
                            Swal.fire('Error', data.message || 'Failed to update department', 'error');
                        }
                    })
                    .catch(e => {
                        console.error('Error:', e);
                        Swal.fire('Error', 'Failed to update department', 'error');
                    });
            });
            
            // Add Purpose Form with translations (update existing handler)
            document.getElementById('addPurposeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(ajaxUrl + '?action=add_purpose', { 
                    method: 'POST', 
                    body: formData 
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('addPurposeModal')).hide();
                        this.reset();
                        Swal.fire({ 
                            toast: true, 
                            position: 'top-end', 
                            icon: 'success', 
                            title: 'Purpose added successfully', 
                            showConfirmButton: false, 
                            timer: 2000 
                        });
                        loadPurposes();
                        loadPurposesMap();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to add purpose', 'error');
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to add purpose', 'error');
                });
            });
            
            // Edit Purpose Form with translations (update existing handler)
            document.getElementById('editPurposeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(ajaxUrl + '?action=update_purpose', { 
                    method: 'POST', 
                    body: formData 
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('editPurposeModal')).hide();
                        Swal.fire({ 
                            toast: true, 
                            position: 'top-end', 
                            icon: 'success', 
                            title: 'Purpose updated successfully', 
                            showConfirmButton: false, 
                            timer: 2000 
                        });
                        loadPurposes();
                        loadPurposesMap();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to update purpose', 'error');
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Failed to update purpose', 'error');
                });
            });
            
        });

        setInterval(() => {
            if (document.getElementById('active-visitsSection').style.display !== 'none') {
                loadActiveVisits();
            }
            refreshDashboard();
            // autoCheckoutExpired(); // Add this line

        }, 30000);

        // Emergency alerts
        function checkEmergencyAlerts() {
            fetch(ajaxUrl + '?action=check_emergency_alerts' + filterParam)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success' && data.alerts.length > 0) {
                        const newAlerts = data.alerts.filter(alert => alert.alert_id > lastAlertId);
                        
                        if (newAlerts.length > 0) {
                            lastAlertId = Math.max(...data.alerts.map(a => a.alert_id));
                            
                            newAlerts.forEach((alert, index) => {
                                setTimeout(() => {
                                    showEmergencyAlertSwal(alert);
                                }, index * 500);
                            });
                        }
                    }
                })
                .catch(e => console.error('Error checking emergency alerts:', e));
        }

        function showEmergencyAlertSwal(alert) {
            let companyIcon, companyColor, companyName, companyBg;
            
            if (alert.company_visited === 'Toms World') {
                companyIcon = '🎮';
                companyColor = '#f39c12';
                companyName = "Tom's World";
                companyBg = '#fff3cd';
            } else if (alert.company_visited === 'Pan Asia') {
                companyIcon = '🏢';
                companyColor = '#1e9338';
                companyName = 'Pan-Asia';
                companyBg = '#d4edda';
            } else {
                companyIcon = '🏢';
                companyColor = '#95a5a6';
                companyName = alert.company_visited;
                companyBg = '#f8f9fa';
            }
            
            Swal.fire({
                title: '<span style="color: #e74c3c;">🚨 EMERGENCY ASSISTANCE NEEDED!</span>',
                html: `
                    <div style="text-align: left; padding: 15px; background: white; border-radius: 8px;">
                        <div style="background: ${companyBg}; padding: 12px; border-radius: 8px; border: 2px solid ${companyColor}; margin-bottom: 15px;">
                            <p style="font-size: 1.3em; margin: 0; color: ${companyColor}; font-weight: bold;">
                                ${companyIcon} ${companyName}
                            </p>
                        </div>
                        
                        <p style="font-size: 1.1em; margin-bottom: 10px; padding: 8px; background: #f8f9fa; border-radius: 5px;">
                            <strong>👤 Visitor:</strong> ${alert.visitor_name}
                        </p>
                        <p style="font-size: 1.1em; margin-bottom: 10px; padding: 8px; background: #f8f9fa; border-radius: 5px;">
                            <strong>📍 Location:</strong> ${alert.location}
                        </p>
                        <p style="font-size: 1.1em; margin-bottom: 0; padding: 8px; background: #f8f9fa; border-radius: 5px;">
                            <strong>🕒 Time:</strong> ${new Date(alert.created_at).toLocaleString()}
                        </p>
                    </div>
                    <div style="background: #fee; padding: 12px; border-radius: 8px; margin-top: 15px; border: 2px solid #e74c3c;">
                        <strong style="color: #e74c3c; font-size: 1.1em;">
                            ⚠️ Immediate assistance required at ${companyName}!
                        </strong>
                    </div>
                `,
                icon: 'error',
                confirmButtonColor: '#27ae60',
                confirmButtonText: '✓ Acknowledged & Responding',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                },
                customClass: {
                    popup: 'emergency-alert-popup'
                },
                width: '600px'
            }).then(() => {
                acknowledgeEmergencyAlert(alert.alert_id);
            });
            
            playEmergencySound();
        }

        function playEmergencySound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
                
                setTimeout(() => {
                    const oscillator2 = audioContext.createOscillator();
                    const gainNode2 = audioContext.createGain();
                    
                    oscillator2.connect(gainNode2);
                    gainNode2.connect(audioContext.destination);
                    
                    oscillator2.frequency.value = 1000;
                    oscillator2.type = 'sine';
                    
                    gainNode2.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                    
                    oscillator2.start(audioContext.currentTime);
                    oscillator2.stop(audioContext.currentTime + 0.5);
                }, 200);
            } catch (e) {
                console.log('Audio not supported:', e);
            }
        }

        function acknowledgeEmergencyAlert(alertId) {
            const formData = new FormData();
            formData.append('alert_id', alertId);
            
            fetch(ajaxUrl + '?action=acknowledge_emergency_alert', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .catch(e => console.error('Error acknowledging alert:', e));
        }

        setInterval(checkEmergencyAlerts, 10000);
        checkEmergencyAlerts();

        fetch(ajaxUrl + '?action=get_last_alert_id' + filterParam)
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    lastAlertId = data.last_alert_id || 0;
                }
            });

        // ============================================
        // ADD EVENT LISTENERS FOR ENTER KEY ON FILTERS
        // ============================================

        // Add this in your $(document).ready() function:
        $(document).ready(function() {
            // Employee filter - apply on Enter key
            $('#employeeDeptFilter, #employeeStatusFilter, #employeeCompanyFilter').on('keypress', function(e) {
                if (e.which === 13) {
                    applyEmployeeFilters();
                }
            });
            
            // Department filter - apply on Enter key
            $('#departmentStatusFilter, #departmentEmployeeFilter').on('keypress', function(e) {
                if (e.which === 13) {
                    applyDepartmentFilters();
                }
            });
            
            // Purpose filter - apply on Enter key
            $('#purposeStatusFilter, #purposeCompanyFilter, #purposeColorFilter').on('keypress', function(e) {
                if (e.which === 13) {
                    applyPurposeFilters();
                }
            });
            
            // Auto-apply filters on change (optional - uncomment if you want instant filtering)
            /*
            $('#employeeDeptFilter, #employeeStatusFilter, #employeeCompanyFilter').on('change', applyEmployeeFilters);
            $('#departmentStatusFilter, #departmentEmployeeFilter').on('change', applyDepartmentFilters);
            $('#purposeStatusFilter, #purposeCompanyFilter, #purposeColorFilter').on('change', applyPurposeFilters);
            */
        });


        // Populate Icon Select with grouped options
        function populateIconSelect(selectId) {
            const select = document.getElementById(selectId);
            if (!select) return;
            
            select.innerHTML = '';
            
            // Group icons by category
            const categories = {};
            bootstrapIconsList.forEach(icon => {
                if (!categories[icon.category]) {
                    categories[icon.category] = [];
                }
                categories[icon.category].push(icon);
            });
            
            // Create optgroups
            Object.keys(categories).forEach(category => {
                const optgroup = document.createElement('optgroup');
                optgroup.label = category;
                
                categories[category].forEach(icon => {
                    const option = document.createElement('option');
                    option.value = icon.value;
                    option.textContent = icon.label;
                    option.setAttribute('data-icon', icon.value);
                    optgroup.appendChild(option);
                });
                
                select.appendChild(optgroup);
            });
        }

        // Update Icon Preview
        function updateIconPreview(mode) {
            const selectId = mode === 'add' ? 'addIconSelect' : 'editIconSelect';
            const previewId = mode === 'add' ? 'addIconPreview' : 'editIconPreview';
            const largePreviewId = mode === 'add' ? 'addPurposeIconPreviewLarge' : 'editPurposeIconPreviewLarge';
            const colorSelectId = mode === 'add' ? 'addColorSelect' : 'editColorSelect';
            
            const select = document.getElementById(selectId);
            const preview = document.getElementById(previewId);
            const largePreview = document.getElementById(largePreviewId);
            const colorSelect = document.getElementById(colorSelectId);
            
            if (select && preview) {
                const iconClass = select.value;
                preview.className = 'icon-select-preview bi ' + iconClass.replace('bi-', '');
                preview.classList.add(iconClass);
            }
            
            if (largePreview && select && colorSelect) {
                const iconClass = select.value;
                const colorClass = colorSelect.value;
                largePreview.className = 'bi ' + iconClass.replace('bi-', '') + ' ' + colorClass;
                largePreview.classList.add(iconClass);
            }
        }

        // Update Color Preview
        function updateColorPreview(mode) {
            const selectId = mode === 'add' ? 'addIconSelect' : 'editIconSelect';
            const colorSelectId = mode === 'add' ? 'addColorSelect' : 'editColorSelect';
            const largePreviewId = mode === 'add' ? 'addPurposeIconPreviewLarge' : 'editPurposeIconPreviewLarge';
            
            const iconSelect = document.getElementById(selectId);
            const colorSelect = document.getElementById(colorSelectId);
            const largePreview = document.getElementById(largePreviewId);
            
            if (largePreview && iconSelect && colorSelect) {
                const iconClass = iconSelect.value;
                const colorClass = colorSelect.value;
                largePreview.className = 'bi ' + iconClass.replace('bi-', '') + ' ' + colorClass;
                largePreview.classList.add(iconClass);
            }
        }

        // function clearEmployeeFilters() {
        //     document.getElementById('employeeDeptFilter').value = '';
        //     document.getElementById('employeeStatusFilter').value = '';
        //     document.getElementById('employeeCompanyFilter').value = '';
        //     renderEmployeeTable(allEmployeesData);
            
        //     Swal.fire({
        //         toast: true,
        //         position: 'top-end',
        //         icon: 'info',
        //         title: 'Employee filters cleared',
        //         showConfirmButton: false,
        //         timer: 1500
        //     });
        // }

        function clearEmployeeFilters() {
            document.getElementById('employeeDeptFilter').value = '';
            document.getElementById('employeeStatusFilter').value = '';
            document.getElementById('employeeCompanyFilter').value = '';
            
            renderEmployeeTable(allEmployeesData);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Employee filters cleared',
                showConfirmButton: false,
                timer: 1500
            });
        }


        
        // Report Variables
        let currentReportType = null;
        let currentReportData = null;
        let reportChart = null;
        let reportPieChart = null;

        // Report Type Definitions
        const reportTypeConfig = {
            department: {
                title: 'Department Report',
                description: 'Visitor statistics grouped by department',
                icon: 'bi-building',
                color: '#3498db',
                showDepartmentFilter: false,
                showVisitorTypeFilter: false,
                defaultDateRange: 30,
                columns: ['Department Code', 'Department Name', 'Employees', 'Total Visits', 'Unique Visitors', 'Avg Duration'],
                dataKeys: ['department_code', 'department_name', 'total_employees', 'total_visits', 'unique_visitors', 'avg_duration_minutes'],
                chartLabel: 'Visits by Department',
                chartType: 'bar'
            },
            employee_visits: {
                title: 'Employee Visits Report',
                description: 'Visit statistics for each employee host',
                icon: 'bi-person-badge',
                color: '#27ae60',
                showDepartmentFilter: true,
                showVisitorTypeFilter: false,
                defaultDateRange: 30,
                columns: ['Host ID', 'Name', 'Department', 'Company', 'Total Visits', 'Unique Visitors', 'Last Visit', 'Avg Duration'],
                dataKeys: ['employee_id', 'employee_name', 'department_name', 'company_owned_by', 'total_visits', 'unique_visitors', 'last_visit', 'avg_duration_minutes'],
                chartLabel: 'Visits by Employee',
                chartType: 'bar'
            },
            visitor_visits: {
                title: 'Visitors Report',
                description: 'Complete visitor activity and history',
                icon: 'bi-people',
                color: '#00bcd4',
                showDepartmentFilter: false,
                showVisitorTypeFilter: true,
                defaultDateRange: 30,
                columns: ['Visitor ID', 'Name', 'Email', 'Phone', 'Company', 'Type', 'Total Visits', 'First Visit', 'Last Visit'],
                dataKeys: ['visitor_id', 'full_name', 'email', 'phone', 'company', 'visitor_type', 'total_visits', 'first_visit', 'last_visit'],
                chartLabel: 'Visitors by Visit Count',
                chartType: 'bar'
            },
            purposes: {
                title: 'Purposes Report',
                description: 'Visit breakdown by purpose type',
                icon: 'bi-flag',
                color: '#f39c12',
                showDepartmentFilter: false,
                showVisitorTypeFilter: false,
                defaultDateRange: 30,
                columns: ['Purpose Code', 'Purpose Name', 'Company', 'Total Visits', 'Unique Visitors', 'Avg Duration'],
                dataKeys: ['purpose_code', 'purpose_name', 'company_owned_by', 'total_visits', 'unique_visitors', 'avg_duration_minutes'],
                chartLabel: 'Visits by Purpose',
                chartType: 'doughnut'
            },
            daily: {
                title: 'Daily Report',
                description: 'Day-by-day visitor statistics',
                icon: 'bi-calendar-day',
                color: '#e74c3c',
                showDepartmentFilter: false,
                showVisitorTypeFilter: false,
                defaultDateRange: 30,
                columns: ['Date', 'Day', 'Total Visits', 'Unique Visitors', 'Checked Out', 'Still In', 'Avg Duration'],
                dataKeys: ['visit_date', 'day_name', 'total_visits', 'unique_visitors', 'checked_out', 'still_in', 'avg_duration_minutes'],
                chartLabel: 'Daily Visits',
                chartType: 'line'
            },
            weekly: {
                title: 'Weekly Report',
                description: 'Week-by-week visitor trends',
                icon: 'bi-calendar-week',
                color: '#95a5a6',
                showDepartmentFilter: false,
                showVisitorTypeFilter: false,
                defaultDateRange: 84,
                columns: ['Week', 'Week Start', 'Week End', 'Total Visits', 'Unique Visitors', 'Avg Duration'],
                dataKeys: ['year_week', 'week_start', 'week_end', 'total_visits', 'unique_visitors', 'avg_duration_minutes'],
                chartLabel: 'Weekly Visits',
                chartType: 'line'
            },
            monthly: {
                title: 'Monthly Report',
                description: 'Monthly visitor analytics',
                icon: 'bi-calendar-month',
                color: '#2c3e50',
                showDepartmentFilter: false,
                showVisitorTypeFilter: false,
                defaultDateRange: 365,
                columns: ['Month', 'Year', 'Total Visits', 'Unique Visitors', 'Avg Duration'],
                dataKeys: ['month_name', 'year', 'total_visits', 'unique_visitors', 'avg_duration_minutes'],
                chartLabel: 'Monthly Visits',
                chartType: 'bar'
            },
            annual: {
                title: 'Annual Report',
                description: 'Yearly visitor overview',
                icon: 'bi-calendar',
                color: '#9b59b6',
                showDepartmentFilter: false,
                showVisitorTypeFilter: false,
                defaultDateRange: null,
                columns: ['Year', 'Total Visits', 'Unique Visitors', 'Active Days', 'Avg Duration'],
                dataKeys: ['year', 'total_visits', 'unique_visitors', 'active_days', 'avg_duration_minutes'],
                chartLabel: 'Annual Visits',
                chartType: 'bar'
            }
        };

        // ============================================
        // HELPER FUNCTIONS FOR CLEANUP
        // ============================================

        // Helper function to properly destroy DataTable
        function destroyReportDataTable() {
            try {
                if ($.fn.DataTable.isDataTable('#reportDataTable')) {
                    $('#reportDataTable').DataTable().clear().destroy();
                }
            } catch (e) {
                console.warn('Error destroying report DataTable:', e);
            }
            
            // Remove DataTable classes and reset table structure
            $('#reportDataTable').removeClass('dataTable no-footer');
            $('#reportDataTable').removeAttr('aria-describedby');
            $('#reportDataTable').removeAttr('role');
            
            // Clear table content
            const thead = document.getElementById('reportTableHead');
            const tbody = document.getElementById('reportTableBody');
            const tfoot = document.getElementById('reportTableFoot');
            
            if (thead) thead.innerHTML = '';
            if (tbody) tbody.innerHTML = '';
            if (tfoot) tfoot.innerHTML = '';
        }

        // Helper function to destroy charts
        function destroyReportCharts() {
            if (reportChart) {
                try {
                    reportChart.destroy();
                } catch (e) {
                    console.warn('Error destroying report chart:', e);
                }
                reportChart = null;
            }
            if (reportPieChart) {
                try {
                    reportPieChart.destroy();
                } catch (e) {
                    console.warn('Error destroying pie chart:', e);
                }
                reportPieChart = null;
            }
        }

        // ============================================
        // MAIN REPORT FUNCTIONS
        // ============================================

        // Select Report Type
        function selectReportType(type) {
            // Destroy existing DataTable and charts when switching report types
            destroyReportDataTable();
            destroyReportCharts();
            
            currentReportType = type;
            currentReportData = null;
            const config = reportTypeConfig[type];
            
            // Update UI - remove selected from all cards
            document.querySelectorAll('.report-card').forEach(card => card.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            
            // Update panel title
            document.getElementById('selectedReportTitle').innerHTML = `<i class="bi ${config.icon}"></i> ${config.title}`;
            document.getElementById('selectedReportDescription').textContent = config.description;
            
            // Set default date range
            const today = new Date();
            document.getElementById('reportDateTo').value = today.toISOString().split('T')[0];
            
            if (config.defaultDateRange) {
                const fromDate = new Date(today);
                fromDate.setDate(fromDate.getDate() - config.defaultDateRange);
                document.getElementById('reportDateFrom').value = fromDate.toISOString().split('T')[0];
            } else {
                document.getElementById('reportDateFrom').value = '';
            }
            
            // Show/hide filters
            document.getElementById('reportDepartmentFilter').style.display = config.showDepartmentFilter ? 'block' : 'none';
            document.getElementById('reportVisitorTypeFilter').style.display = config.showVisitorTypeFilter ? 'block' : 'none';
            
            // Load department options if needed
            if (config.showDepartmentFilter) {
                loadDepartmentsForReport();
            }
            
            // Show panel, hide results
            document.getElementById('reportGeneratorPanel').style.display = 'block';
            document.getElementById('reportResults').style.display = 'none';
            document.getElementById('reportNoData').style.display = 'none';
            document.getElementById('reportLoading').style.display = 'none';
            
            // Clear previous results
            document.getElementById('reportSummaryCards').innerHTML = '';
            
            // Scroll to panel
            document.getElementById('reportGeneratorPanel').scrollIntoView({ behavior: 'smooth' });
        }

        // Hide Report Panel
        function hideReportPanel() {
            // Destroy DataTable and charts when hiding panel
            destroyReportDataTable();
            destroyReportCharts();
            
            document.getElementById('reportGeneratorPanel').style.display = 'none';
            document.querySelectorAll('.report-card').forEach(card => card.classList.remove('selected'));
            currentReportType = null;
            currentReportData = null;
        }

        // Load Departments for Report Filter
        function loadDepartmentsForReport() {
            fetch(ajaxUrl + '?action=departments')
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('reportDepartmentSelect');
                    select.innerHTML = '<option value="">All Departments</option>';
                    data.filter(d => d.is_active == 1).forEach(d => {
                        select.innerHTML += `<option value="${d.department_code}">${d.name}</option>`;
                    });
                });
        }

        // Quick Date Filters
        function setQuickDateFilter(range) {
            const today = new Date();
            let fromDate = new Date();
            let toDate = new Date();
            
            switch(range) {
                case 'today':
                    fromDate = today;
                    toDate = today;
                    break;
                case 'yesterday':
                    fromDate.setDate(today.getDate() - 1);
                    toDate.setDate(today.getDate() - 1);
                    break;
                case 'week':
                    const dayOfWeek = today.getDay();
                    fromDate.setDate(today.getDate() - dayOfWeek);
                    break;
                case 'month':
                    fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    break;
                case 'quarter':
                    const quarter = Math.floor(today.getMonth() / 3);
                    fromDate = new Date(today.getFullYear(), quarter * 3, 1);
                    break;
                case 'year':
                    fromDate = new Date(today.getFullYear(), 0, 1);
                    break;
                case 'last30':
                    fromDate.setDate(today.getDate() - 30);
                    break;
                case 'last90':
                    fromDate.setDate(today.getDate() - 90);
                    break;
            }
            
            document.getElementById('reportDateFrom').value = fromDate.toISOString().split('T')[0];
            document.getElementById('reportDateTo').value = toDate.toISOString().split('T')[0];
        }

        // Generate Report
        function generateReport() {
            if (!currentReportType) {
                Swal.fire('Error', 'Please select a report type first', 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('report_type', currentReportType);
            formData.append('date_from', document.getElementById('reportDateFrom').value);
            formData.append('date_to', document.getElementById('reportDateTo').value);
            formData.append('department_code', document.getElementById('reportDepartmentSelect')?.value || '');
            formData.append('visitor_type', document.getElementById('reportVisitorTypeSelect')?.value || '');
            
            // IMPORTANT: Destroy existing DataTable and charts BEFORE showing loading
            destroyReportDataTable();
            destroyReportCharts();
            
            // Clear summary cards
            document.getElementById('reportSummaryCards').innerHTML = '';
            
            // Show loading
            document.getElementById('reportLoading').style.display = 'block';
            document.getElementById('reportResults').style.display = 'none';
            document.getElementById('reportNoData').style.display = 'none';
            
            fetch(ajaxUrl + '?action=generate_report', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('reportLoading').style.display = 'none';
                
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    currentReportData = data;
                    renderReport(data);
                    document.getElementById('reportResults').style.display = 'block';
                } else {
                    document.getElementById('reportNoData').style.display = 'block';
                }
            })
            .catch(e => {
                console.error('Error generating report:', e);
                document.getElementById('reportLoading').style.display = 'none';
                Swal.fire('Error', 'Failed to generate report', 'error');
            });
        }

        // Render Report
        function renderReport(data) {
            const config = reportTypeConfig[currentReportType];
            
            // Render Summary Cards
            renderSummaryCards(data.totals);
            
            // Render Charts
            renderCharts(data.data, config);
            
            // Render Table
            renderReportTable(data.data, config);
        }

        // Render Summary Cards
        function renderSummaryCards(totals) {
            const container = document.getElementById('reportSummaryCards');
            container.innerHTML = '';
            
            const colors = ['primary', 'success', 'warning', 'danger', 'info'];
            let colorIndex = 0;
            
            for (const [key, value] of Object.entries(totals)) {
                const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                const color = colors[colorIndex % colors.length];
                
                container.innerHTML += `
                    <div class="col-md-3 mb-3">
                        <div class="report-summary-card ${color}">
                            <div class="report-summary-value">${formatNumber(value)}</div>
                            <div class="report-summary-label">${label}</div>
                        </div>
                    </div>
                `;
                colorIndex++;
            }
        }

        // Render Charts
        function renderCharts(data, config) {
            // Destroy existing charts using helper function
            destroyReportCharts();
            
            const ctx = document.getElementById('reportChart');
            const pieCtx = document.getElementById('reportPieChart');
            
            if (!ctx || !pieCtx) {
                console.warn('Chart canvas elements not found');
                return;
            }
            
            const ctxContext = ctx.getContext('2d');
            const pieCtxContext = pieCtx.getContext('2d');
            
            // Prepare chart data
            let labels = [];
            let values = [];
            
            // Determine label and value fields based on report type
            switch(currentReportType) {
                case 'department':
                    labels = data.map(d => d.department_name || d.department_code);
                    values = data.map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'employee_visits':
                    labels = data.slice(0, 15).map(d => d.employee_name);
                    values = data.slice(0, 15).map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'visitor_visits':
                    labels = data.slice(0, 15).map(d => `${d.first_name} ${d.last_name}`);
                    values = data.slice(0, 15).map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'purposes':
                    labels = data.map(d => d.purpose_name);
                    values = data.map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'daily':
                    labels = data.map(d => formatDate(d.visit_date));
                    values = data.map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'weekly':
                    labels = data.map(d => `Week ${d.year_week}`);
                    values = data.map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'monthly':
                    labels = data.map(d => `${d.month_name} ${d.year}`);
                    values = data.map(d => parseInt(d.total_visits) || 0);
                    break;
                case 'annual':
                    labels = data.map(d => d.year);
                    values = data.map(d => parseInt(d.total_visits) || 0);
                    break;
            }
            
            // Generate colors
            const backgroundColors = generateColors(labels.length, 0.7);
            const borderColors = generateColors(labels.length, 1);
            
            // Main Chart
            reportChart = new Chart(ctxContext, {
                type: config.chartType === 'doughnut' ? 'bar' : config.chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: config.chartLabel,
                        data: values,
                        backgroundColor: config.chartType === 'line' ? 'rgba(52, 152, 219, 0.2)' : backgroundColors,
                        borderColor: config.chartType === 'line' ? 'rgba(52, 152, 219, 1)' : borderColors,
                        borderWidth: config.chartType === 'line' ? 2 : 1,
                        fill: config.chartType === 'line',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: config.chartLabel
                        }
                    },
                    scales: config.chartType !== 'doughnut' ? {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    } : {}
                }
            });
            
            // Pie Chart
            reportPieChart = new Chart(pieCtxContext, {
                type: 'doughnut',
                data: {
                    labels: labels.slice(0, 8),
                    datasets: [{
                        data: values.slice(0, 8),
                        backgroundColor: backgroundColors.slice(0, 8),
                        borderColor: borderColors.slice(0, 8),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: { size: 10 }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribution'
                        }
                    }
                }
            });
        }

        // Render Report Table
        function renderReportTable(data, config) {
            // IMPORTANT: Destroy existing DataTable first with extra safety
            destroyReportDataTable();
            
            const thead = document.getElementById('reportTableHead');
            const tbody = document.getElementById('reportTableBody');
            const tfoot = document.getElementById('reportTableFoot');
            
            // Build headers
            let headerRow = '<tr>';
            headerRow += '<th>#</th>';
            config.columns.forEach(col => {
                headerRow += `<th>${col}</th>`;
            });
            headerRow += '</tr>';
            thead.innerHTML = headerRow;
            
            // Build body
            data.forEach((row, index) => {
                let tr = `<tr><td>${index + 1}</td>`;
                
                config.dataKeys.forEach(key => {
                    let value = row[key];
                    
                    // Special formatting
                    if (key === 'full_name') {
                        value = `${row.first_name || ''} ${row.last_name || ''}`.trim();
                    } else if (key === 'avg_duration_minutes') {
                        value = formatDuration(value);
                    } else if (key.includes('visit') && key.includes('date') || key === 'first_visit' || key === 'last_visit') {
                        value = value ? formatDate(value) : 'N/A';
                    } else if (key === 'week_start' || key === 'week_end') {
                        value = value ? formatDate(value) : 'N/A';
                    } else if (key === 'visitor_type') {
                        value = `<span class="badge ${value === 'returning' ? 'bg-success' : 'bg-info'}">${value || 'new'}</span>`;
                    } else if (key === 'company_owned_by') {
                        value = `<span class="badge ${getCompanyOwnershipBadge(value)}">${value || 'N/A'}</span>`;
                    }
                    
                    tr += `<td>${value ?? 'N/A'}</td>`;
                });
                
                tr += '</tr>';
                tbody.innerHTML += tr;
            });
            
            // Initialize DataTable with a slight delay to ensure DOM is ready
            setTimeout(() => {
                try {
                    if (!$.fn.DataTable.isDataTable('#reportDataTable')) {
                        $('#reportDataTable').DataTable({
                            pageLength: 25,
                            order: [],
                            destroy: true,
                            language: {
                                emptyTable: "No data available",
                                zeroRecords: "No matching records found"
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error initializing report DataTable:', e);
                }
            }, 100);
        }

        // ============================================
        // HELPER FUNCTIONS
        // ============================================

        function formatNumber(num) {
            if (num === null || num === undefined) return '0';
            return parseFloat(num).toLocaleString('en-US', { maximumFractionDigits: 1 });
        }

        function formatDate(dateStr) {
            if (!dateStr) return 'N/A';
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function formatDuration(minutes) {
            if (!minutes || isNaN(minutes)) return 'N/A';
            const mins = Math.round(parseFloat(minutes));
            if (mins < 60) return `${mins}m`;
            const hours = Math.floor(mins / 60);
            const remainingMins = mins % 60;
            return `${hours}h ${remainingMins}m`;
        }

        function generateColors(count, alpha) {
            const colors = [
                `rgba(52, 152, 219, ${alpha})`,
                `rgba(46, 204, 113, ${alpha})`,
                `rgba(155, 89, 182, ${alpha})`,
                `rgba(241, 196, 15, ${alpha})`,
                `rgba(231, 76, 60, ${alpha})`,
                `rgba(26, 188, 156, ${alpha})`,
                `rgba(230, 126, 34, ${alpha})`,
                `rgba(149, 165, 166, ${alpha})`,
                `rgba(52, 73, 94, ${alpha})`,
                `rgba(22, 160, 133, ${alpha})`,
                `rgba(39, 174, 96, ${alpha})`,
                `rgba(41, 128, 185, ${alpha})`,
            ];
            
            const result = [];
            for (let i = 0; i < count; i++) {
                result.push(colors[i % colors.length]);
            }
            return result;
        }

        // ============================================
        // EXPORT FUNCTIONS
        // ============================================

        function exportReportToExcel() {
            if (!currentReportData || !currentReportData.data) {
                Swal.fire('Error', 'No data to export', 'warning');
                return;
            }
            
            const config = reportTypeConfig[currentReportType];
            let csv = config.columns.join(',') + '\n';
            
            currentReportData.data.forEach(row => {
                const rowData = config.dataKeys.map(key => {
                    let value = row[key];
                    if (key === 'full_name') {
                        value = `${row.first_name || ''} ${row.last_name || ''}`.trim();
                    } else if (key === 'avg_duration_minutes') {
                        value = formatDuration(value);
                    }
                    // Escape commas and quotes
                    if (typeof value === 'string' && (value.includes(',') || value.includes('"'))) {
                        value = `"${value.replace(/"/g, '""')}"`;
                    }
                    return value ?? '';
                });
                csv += rowData.join(',') + '\n';
            });
            
            // Download
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `${config.title.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
            link.click();
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Report exported to CSV',
                showConfirmButton: false,
                timer: 2000
            });
        }

        function exportReportToPDF() {
            Swal.fire({
                title: 'Export to PDF',
                text: 'PDF export requires a PDF library. Use the Print function and save as PDF.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Print Now',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    printReport();
                }
            });
        }

        function printReport() {
            window.print();
        }

        // ============================================
        // INITIALIZE REPORTS SECTION
        // ============================================

        function initReportsSection() {
            // Destroy existing DataTable and charts
            destroyReportDataTable();
            destroyReportCharts();
            
            // Reset state
            currentReportType = null;
            currentReportData = null;
            
            // Hide panel and results
            const panelEl = document.getElementById('reportGeneratorPanel');
            const resultsEl = document.getElementById('reportResults');
            const noDataEl = document.getElementById('reportNoData');
            const loadingEl = document.getElementById('reportLoading');
            const summaryEl = document.getElementById('reportSummaryCards');
            
            if (panelEl) panelEl.style.display = 'none';
            if (resultsEl) resultsEl.style.display = 'none';
            if (noDataEl) noDataEl.style.display = 'none';
            if (loadingEl) loadingEl.style.display = 'none';
            if (summaryEl) summaryEl.innerHTML = '';
            
            // Deselect all cards
            document.querySelectorAll('.report-card').forEach(card => card.classList.remove('selected'));
        }

        // ============================================
        // REMEMBER TO UPDATE showSection() function!
        // Add 'reports': 'reportsSection' to sectionMap
        // Add case 'reports': initReportsSection(); break;
        // ============================================



        // Track selected visits
        let selectedVisits = new Set();

        // Function to render active visits with checkboxes
        function renderActiveVisits(visits) {
            const tbody = $('#activeVisitsBody');
            tbody.empty();
            
            if (!visits || visits.length === 0) {
                tbody.html(`
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No active visits at the moment
                        </td>
                    </tr>
                `);
                $('#activeVisitCount').text('0');
                updateBulkCheckoutButton();
                return;
            }
            
            $('#activeVisitCount').text(visits.length);


            // Sort visits by check-in time in descending order (most recent first)
            visits.sort((a, b) => new Date(b.check_in_time) - new Date(a.check_in_time));

            // // Sort visits by check-in time in ascending order (oldest first)
            // visits.sort((a, b) => new Date(a.check_in_time) - new Date(b.check_in_time));
            
            visits.forEach(visit => {
                const checkinTime = new Date(visit.check_in_time);
                const now = new Date();
                const duration = Math.floor((now - checkinTime) / (1000 * 60)); // minutes
                const durationDisplay = duration < 60 
                    ? `${duration} min` 
                    : `${Math.floor(duration/60)}h ${duration%60}m`;
                
                const isChecked = selectedVisits.has(visit.visit_id.toString());
                
                const row = `
                    <tr data-visit-id="${visit.visit_id}">
                        <td>
                            <div class="d-flex align-items-center">
                                ${visit.photo 
                                    ? `<img src="${visit.photo}" class="rounded-circle me-2" 
                                        style="width: 40px; height: 40px; object-fit: cover;">` 
                                    : `<div class="rounded-circle bg-secondary text-white me-2 d-flex 
                                            align-items-center justify-content-center" 
                                            style="width: 40px; height: 40px;">
                                        ${(visit.first_name || 'V')[0]}
                                    </div>`
                                }
                                <div>
                                    <strong>${visit.first_name} ${visit.last_name}</strong>
                                    <br><small class="text-muted">${visit.email || ''}</small>
                                </div>
                            </div>
                        </td>
                        <td>${visit.company || 'N/A'}</td>
                        <td>${visit.host_name}</td>
                        <td><span class="badge bg-info">${visit.department_name}</span></td>
                        <td>
                            <span class="text-primary">
                                <i class="bi bi-clock me-1"></i>
                                ${checkinTime.toLocaleTimeString()}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${duration > 240 ? 'bg-warning' : 'bg-success'}">
                                ${durationDisplay}
                            </span>
                        </td>
                        <td>
                            <input type="checkbox" class="form-check-input visit-checkbox" 
                                value="${visit.visit_id}" 
                                ${isChecked ? 'checked' : ''}
                                onchange="toggleVisitSelection(this)">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" 
                                    onclick="checkoutVisitBulk(${visit.visit_id})" 
                                    title="Check Out">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info" 
                                    onclick="viewVisitDetails(${visit.visit_id})" 
                                    title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
            
            updateBulkCheckoutButton();
            updateSelectAllCheckbox();
        }

        // Toggle individual visit selection
        function toggleVisitSelection(checkbox) {
            const visitId = checkbox.value;
            
            if (checkbox.checked) {
                selectedVisits.add(visitId);
            } else {
                selectedVisits.delete(visitId);
            }
            
            updateBulkCheckoutButton();
            updateSelectAllCheckbox();
        }

        // Toggle select all
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.visit-checkbox');
            
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
                if (checkbox.checked) {
                    selectedVisits.add(cb.value);
                } else {
                    selectedVisits.delete(cb.value);
                }
            });
            
            updateBulkCheckoutButton();
        }

        // Update select all checkbox state
        function updateSelectAllCheckbox() {
            const checkboxes = document.querySelectorAll('.visit-checkbox');
            const selectAll = document.getElementById('selectAllVisits');
            
            if (checkboxes.length === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
                return;
            }
            
            const checkedCount = document.querySelectorAll('.visit-checkbox:checked').length;
            
            selectAll.checked = checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }

        // Update bulk checkout button
        function updateBulkCheckoutButton() {
            const btn = document.getElementById('bulkCheckoutBtn');
            const countSpan = document.getElementById('selectedCount');
            const count = selectedVisits.size;
            
            countSpan.textContent = count;
            btn.disabled = count === 0;
        }

        // Update bulk checkout button
        function updateBulkCheckoutButton() {
            const btn = document.getElementById('bulkCheckoutBtn');
            const countSpan = document.getElementById('selectedCount');
            const count = selectedVisits.size;
            
            countSpan.textContent = count;
            btn.disabled = count === 0;
        }

        // Bulk checkout selected visits
        function bulkCheckoutSelected() {
            if (selectedVisits.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one visitor to check out.'
                });
                return;
            }
            
            Swal.fire({
                title: 'Bulk Checkout',
                html: `Are you sure you want to check out <strong>${selectedVisits.size}</strong> visitor(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f0ad4e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Check Out All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkCheckout(Array.from(selectedVisits));
                }
            });
        }

        // Checkout all active visits
        function checkoutAllActive() {
            const activeCount = $('#activeVisitCount').text();
            
            if (parseInt(activeCount) === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Active Visits',
                    text: 'There are no active visits to check out.'
                });
                return;
            }
            
            Swal.fire({
                title: 'Checkout All Active Visits',
                html: `
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                    </div>
                    <p>This will check out <strong>ALL ${activeCount}</strong> active visitor(s).</p>
                    <p class="text-muted">This action cannot be undone.</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Checkout All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    performCheckoutAll();
                }
            });
        }

        // Perform bulk checkout AJAX call
        function performBulkCheckout(visitIds) {
            Swal.fire({
                title: 'Processing...',
                html: 'Checking out visitors...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: "<?= base_url('admin/ajax_handler'); ?>?action=bulk_checkout&company_filter=<?= $companyFilter ?? '' ?>",
                method: 'POST',
                data: {
                    visit_ids: JSON.stringify(visitIds)
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Checkout Complete',
                            html: `
                                <p>${response.message}</p>
                                ${response.failed_count > 0 
                                    ? `<p class="text-warning">Failed IDs: ${response.failed_ids.join(', ')}</p>` 
                                    : ''}
                            `,
                            timer: 3000,
                            showConfirmButton: true
                        });
                        
                        // Clear selections and refresh
                        selectedVisits.clear();
                        loadActiveVisits();
                        if (typeof loadDashboardStats === 'function') loadDashboardStats();
                        if (typeof refreshDashboard === 'function') refreshDashboard();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Failed',
                            text: response.error || 'An error occurred during checkout.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to process bulk checkout: ' + error
                    });
                }
            });
        }

        // Perform checkout all AJAX call
        function performCheckoutAll() {
            Swal.fire({
                title: 'Processing...',
                html: 'Checking out all visitors...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: "<?= base_url('admin/ajax_handler'); ?>?action=checkout_all_active&company_filter=<?= $companyFilter ?? '' ?>",
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'All Visitors Checked Out',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: true
                        });
                        
                        // Clear selections and refresh
                        selectedVisits.clear();
                        loadActiveVisits();
                        if (typeof loadDashboardStats === 'function') loadDashboardStats();
                        if (typeof refreshDashboard === 'function') refreshDashboard();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Failed',
                            text: response.error || 'An error occurred.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to checkout all visitors: ' + error
                    });
                }
            });
        }

        // Single visit checkout (existing function - ensure it exists)
        function checkoutVisit(visitId) {
            Swal.fire({
                title: 'Checkout Visitor',
                text: 'Are you sure you want to check out this visitor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Check Out'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl + 'admin/ajax_handler?action=checkout',
                        method: 'POST',
                        data: { visit_id: visitId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Checked Out',
                                    text: 'Visitor has been checked out successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                selectedVisits.delete(visitId.toString());
                                loadActiveVisits();
                                loadDashboardStats();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error || 'Failed to checkout visitor.'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to process checkout.'
                            });
                        }
                    });
                }
            });
        }

        // Single visit checkout for bulk module
        function checkoutVisitBulk(visitId) {
            Swal.fire({
                title: 'Checkout Visitor',
                text: 'Are you sure you want to check out this visitor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Check Out'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('admin/ajax_handler'); ?>?action=checkout",
                        method: 'POST',
                        data: { visit_id: visitId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Checked Out',
                                    text: 'Visitor has been checked out successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                selectedVisits.delete(visitId.toString());
                                loadActiveVisits();
                                if (typeof loadDashboardStats === 'function') loadDashboardStats();
                                if (typeof refreshDashboard === 'function') refreshDashboard();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error || 'Failed to checkout visitor.'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to process checkout.'
                            });
                        }
                    });
                }
            });
        }


        // Load active visits for bulk checkout module
        function loadActiveVisits() {
            $.ajax({
                url: "<?= base_url('admin/ajax_handler'); ?>?action=active_visits&company_filter=<?= $companyFilter ?? '' ?>",
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderActiveVisits(response);
                },
                error: function() {
                    console.error('Failed to load active visits');
                }
            });
        }

        // Notify IT Department Function
        function notifyITDepartment() {
            Swal.fire({
                title: 'Notify IT Department',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="itNotificationCategory" required>
                                <option value="">Select Category</option>
                                <option value="Technical Issue">Technical Issue</option>
                                <option value="Feature Request">Feature Request</option>
                                <option value="Bug Report">Bug Report</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="itNotificationSubject" 
                                placeholder="Brief description of the issue" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="itNotificationMessage" rows="5" 
                                    placeholder="Detailed description of your issue or request..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Priority</label>
                            <select class="form-select" id="itNotificationPriority">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: '<i class="bi bi-send-fill me-2"></i>Send Notification',
                cancelButtonText: 'Cancel',
                width: '600px',
                preConfirm: () => {
                    const category = document.getElementById('itNotificationCategory').value;
                    const subject = document.getElementById('itNotificationSubject').value;
                    const message = document.getElementById('itNotificationMessage').value;
                    const priority = document.getElementById('itNotificationPriority').value;
                    
                    if (!category) {
                        Swal.showValidationMessage('Please select a category');
                        return false;
                    }
                    if (!subject || subject.trim() === '') {
                        Swal.showValidationMessage('Please enter a subject');
                        return false;
                    }
                    if (!message || message.trim() === '') {
                        Swal.showValidationMessage('Please enter a message');
                        return false;
                    }
                    
                    return {
                        category: category,
                        subject: subject,
                        message: message,
                        priority: priority
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    sendITNotification(result.value);
                }
            });
        }

        // Send IT Notification via AJAX
        function sendITNotification(data) {
            Swal.fire({
                title: 'Sending...',
                html: 'Please wait while we send your notification to the IT Department.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const formData = new FormData();
            formData.append('category', data.category);
            formData.append('subject', data.subject);
            formData.append('message', data.message);
            formData.append('priority', data.priority);
            
            fetch(ajaxUrl + '?action=notify_it_department', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(response => {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification Sent!',
                        html: `
                            <div class="text-start">
                                <p>${response.message}</p>
                                <div class="alert alert-info mt-3">
                                    <strong><i class="bi bi-info-circle me-2"></i>Ticket ID:</strong> ${response.ticket_id || 'Generated'}<br>
                                    <strong><i class="bi bi-clock me-2"></i>Submitted:</strong> ${new Date().toLocaleString()}<br>
                                    <strong><i class="bi bi-flag me-2"></i>Priority:</strong> ${data.priority}
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-envelope me-1"></i>
                                    A confirmation email has been sent to your registered email address.
                                </p>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        timer: 5000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Send',
                        text: response.message || 'An error occurred while sending your notification. Please try again or contact IT directly.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    html: `
                        <p>Could not connect to the server. Please check your internet connection and try again.</p>
                        <div class="alert alert-warning mt-3">
                            <strong>Alternative Contact Methods:</strong><br>
                            📧 Email: ithelpdesk@tomsworld.com.ph
                            📞 Phone: Local 6211
                        </div>
                    `,
                    confirmButtonText: 'OK'
                });
            });
        }

        // ============================================
        // AUTO-LOGOUT SYSTEM
        // ============================================

        const SESSION_TIMEOUT_MINUTES = 30;    // Total session lifetime (match php session.gc_maxlifetime)
        const WARNING_BEFORE_MINUTES   = 2;    // Show warning this many minutes before logout
        const WARNING_DURATION_SECONDS = WARNING_BEFORE_MINUTES * 60;  // = 120

        let sessionTimer       = null;   // fires to show warning
        let countdownTimer     = null;   // ticks every second inside the modal
        let countdownRemaining = WARNING_DURATION_SECONDS;
        let sessionModalInstance = null;

        // ── Boot ─────────────────────────────────────────────────────────────────────
        function initAutoLogout() {
            resetSessionTimer();

            // Reset on any user activity
            ['mousemove', 'keydown', 'mousedown', 'touchstart', 'scroll', 'click']
                .forEach(evt => document.addEventListener(evt, onUserActivity, { passive: true }));
        }

        // Debounce activity so we don't reset the timer hundreds of times per second
        let activityDebounce = null;
        function onUserActivity() {
            if (activityDebounce) return;
            activityDebounce = setTimeout(() => {
                activityDebounce = null;
                // Only reset if the warning modal is NOT open
                if (!sessionModalInstance || !document.getElementById('sessionWarningModal').classList.contains('show')) {
                    resetSessionTimer();
                }
            }, 500);
        }

        // ── Timer Management ─────────────────────────────────────────────────────────
        function resetSessionTimer() {
            clearTimeout(sessionTimer);
            stopCountdown();

            const idleBeforeWarningMs = (SESSION_TIMEOUT_MINUTES - WARNING_BEFORE_MINUTES) * 60 * 1000;

            sessionTimer = setTimeout(() => {
                showSessionWarning();
            }, idleBeforeWarningMs);
        }

        function showSessionWarning() {
            countdownRemaining = WARNING_DURATION_SECONDS;

            // Show modal
            const modalEl = document.getElementById('sessionWarningModal');
            sessionModalInstance = new bootstrap.Modal(modalEl, {
                backdrop: 'static',
                keyboard: false
            });
            sessionModalInstance.show();

            // Play soft alert sound
            playWarningBeep();

            // Start countdown
            updateCountdownUI();
            countdownTimer = setInterval(tickCountdown, 1000);
        }

        function tickCountdown() {
            countdownRemaining--;
            updateCountdownUI();

            if (countdownRemaining <= 0) {
                stopCountdown();
                handleSessionExpired();
            }
        }

        function updateCountdownUI() {
            const secEl        = document.getElementById('countdownSeconds');
            const secTextEl    = document.getElementById('countdownSecondsText');
            const progressEl   = document.getElementById('countdownProgressBar');
            const arcEl        = document.getElementById('countdownArc');
            const bodyEl       = document.getElementById('sessionWarningModal')?.querySelector('.modal-body');

            if (!secEl) return;

            secEl.textContent     = countdownRemaining;
            if (secTextEl) secTextEl.textContent = countdownRemaining;

            // Progress bar
            const pct = (countdownRemaining / WARNING_DURATION_SECONDS) * 100;
            if (progressEl) progressEl.style.width = pct + '%';

            // SVG arc  (circumference = 2π × 56 ≈ 351.86)
            const circumference = 351.86;
            if (arcEl) {
                const offset = circumference * (1 - countdownRemaining / WARNING_DURATION_SECONDS);
                arcEl.style.strokeDashoffset = offset;

                // Colour shift: green → orange → red
                if (countdownRemaining > WARNING_DURATION_SECONDS * 0.5) {
                    arcEl.style.stroke = '#27ae60';
                    if (progressEl) progressEl.className = 'progress-bar bg-success';
                } else if (countdownRemaining > WARNING_DURATION_SECONDS * 0.25) {
                    arcEl.style.stroke = '#f39c12';
                    if (progressEl) progressEl.className = 'progress-bar bg-warning';
                } else {
                    arcEl.style.stroke = '#e74c3c';
                    if (progressEl) progressEl.className = 'progress-bar bg-danger';
                }
            }

            // Urgency class when <= 10 s
            const modalContent = document.getElementById('sessionWarningModal');
            if (countdownRemaining <= 10) {
                if (secEl) secEl.closest('.text-center')?.classList.add('countdown-urgent');
            } else {
                if (secEl) secEl.closest('.text-center')?.classList.remove('countdown-urgent');
            }
        }

        function stopCountdown() {
            clearInterval(countdownTimer);
            countdownTimer = null;
        }

        // ── Actions ───────────────────────────────────────────────────────────────────
        function extendSession() {
            // Ping the server to keep the PHP session alive
            fetch(ajaxUrl + '?action=keep_alive', { method: 'POST' })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        closeSessionWarning();
                        resetSessionTimer();

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Session extended successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        // Server says session is already dead — log out
                        handleSessionExpired();
                    }
                })
                .catch(() => {
                    // Network error — assume session is gone
                    handleSessionExpired();
                });
        }

        function performSessionLogout() {
            closeSessionWarning();
            window.location.href = '<?= base_url("auth/logout") ?>';
        }

        function handleSessionExpired() {
            closeSessionWarning();

            // Show expired modal, then redirect
            const expiredEl = document.getElementById('sessionExpiredModal');
            if (expiredEl) {
                new bootstrap.Modal(expiredEl, { backdrop: 'static', keyboard: false }).show();
            }

            setTimeout(() => {
                window.location.href = '<?= base_url("auth/logout") ?>?reason=timeout';
            }, 2500);
        }

        function closeSessionWarning() {
            stopCountdown();
            if (sessionModalInstance) {
                sessionModalInstance.hide();
                sessionModalInstance = null;
            }
        }

        // ── Optional: soft beep ───────────────────────────────────────────────────────
        function playWarningBeep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                [440, 520].forEach((freq, i) => {
                    const osc  = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.value = freq;
                    osc.type = 'sine';
                    const t = ctx.currentTime + i * 0.18;
                    gain.gain.setValueAtTime(0.15, t);
                    gain.gain.exponentialRampToValueAtTime(0.001, t + 0.25);
                    osc.start(t);
                    osc.stop(t + 0.25);
                });
            } catch (_) {}
        }

        // ── Start ─────────────────────────────────────────────────────────────────────
        initAutoLogout();

    </script>

</body>

</html>