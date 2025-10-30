<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management - Admin Dashboard</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f6fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: var(--sidebar-bg);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            background: linear-gradient(135deg, #f39c12, #1e9338);
            padding: 20px 0px 20px 0px;
            text-align: center;
            color: white;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.5em;
            font-weight: 600;
        }

        .sidebar.collapsed .sidebar-header h3 {
            display: none;
        }

        .sidebar-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-item {
            padding: 15px 20px;
            color: #ecf0f1;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-item:hover {
            background: var(--sidebar-hover);
            padding-left: 25px;
        }

        .sidebar-item.active {
            background: var(--sidebar-hover);
            border-left: 4px solid var(--primary-color);
        }

        .sidebar-item i {
            font-size: 1.3em;
            width: 30px;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-item span {
            display: none;
        }

        .sidebar-badge {
            background: var(--danger-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            margin-left: auto;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        /* Top Bar */
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            font-size: 1.5em;
            cursor: pointer;
            color: var(--sidebar-bg);
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 8px 40px 8px 15px;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 0.95em;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 1.3em;
            color: #7f8c8d;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7em;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 20px;
            transition: background 0.3s ease;
        }

        .user-profile:hover {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
        }

        .page-title {
            font-size: 2em;
            color: var(--sidebar-bg);
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3em;
            opacity: 0.1;
        }

        .stat-value {
            font-size: 2.5em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95em;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85em;
        }

        .stat-change.positive {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .stat-change.negative {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 1.2em;
            font-weight: 600;
            color: var(--sidebar-bg);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-action-btn {
            padding: 5px 12px;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 6px;
            font-size: 0.9em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-action-btn:hover {
            background: #f8f9fa;
        }

        .chart-action-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Canvas specific sizing */
        .chart-canvas-container {
            position: relative;
            height: 300px;
        }

        .chart-canvas-container.small {
            height: 200px;
        }

        /* Visitor Table */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .visitor-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: #f8f9fa;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .visitor-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .status-badge.checked-in {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .status-badge.checked-out {
            background: rgba(52, 152, 219, 0.1);
            color: var(--info-color);
        }

        .status-badge.pending {
            background: rgba(243, 156, 18, 0.1);
            color: var(--primary-color);
        }

        /* Action Buttons */
        .action-btn {
            padding: 5px 10px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 1.1em;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: scale(1.2);
        }

        .action-btn.view {
            color: var(--info-color);
        }

        .action-btn.edit {
            color: var(--primary-color);
        }

        .action-btn.delete {
            color: var(--danger-color);
        }

        /* Activity List */
        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-icon.check-in {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .activity-icon.check-out {
            background: rgba(52, 152, 219, 0.1);
            color: var(--info-color);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .activity-desc {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .activity-time {
            color: #95a5a6;
            font-size: 0.85em;
            white-space: nowrap;
        }

        /* Quick Stats Bar */
        .quick-stats {
            display: flex;
            gap: 20px;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 12px;
            color: white;
            margin-bottom: 20px;
        }

        .quick-stat-item {
            text-align: center;
            flex: 1;
        }

        .quick-stat-value {
            font-size: 1.8em;
            font-weight: 600;
        }

        .quick-stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }

        /* Filters Panel */
        .filters-panel {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        /* Export Menu */
        .export-menu {
            position: relative;
            display: inline-block;
        }

        .export-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-top: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 100;
            display: none;
            min-width: 150px;
        }

        .export-dropdown.show {
            display: block;
        }

        .export-option {
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .export-option:hover {
            background: #f8f9fa;
        }

        /* Modal Customization */
        .modal-header {
            background: var(--primary-color);
            color: white;
        }

        /* Calendar Styles */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #dee2e6;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .calendar-header {
            background: var(--primary-color);
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: 600;
            font-size: 0.85em;
        }

        .calendar-day {
            background: white;
            padding: 8px;
            min-height: 50px;
            text-align: center;
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }

        .calendar-day:hover {
            background: #f8f9fa;
        }

        .calendar-day.other-month {
            color: #95a5a6;
            background: #f8f9fa;
        }

        .calendar-day.today {
            background: rgba(243, 156, 18, 0.1);
            font-weight: 600;
        }

        .calendar-day.has-events {
            font-weight: 600;
        }

        .calendar-day-number {
            font-size: 0.9em;
        }

        .calendar-event-count {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background: var(--primary-color);
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7em;
        }

        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .calendar-nav button {
            border: none;
            background: none;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.2s;
        }

        .calendar-nav button:hover {
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .search-box {
                width: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" 
                    alt="Toms World" 
                style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;">
            </div>
            <div class="sidebar-logo">
                <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" 
                    alt="Pan-Asia" 
                style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;">
            </div>
            <h3>VISITOR</h3>
        </div>
        <div class="sidebar-menu">
            <div class="sidebar-item active" onclick="showSection('dashboard')">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </div>
            <div class="sidebar-item" onclick="showSection('visitors')">
                <i class="bi bi-people"></i>
                <span>Visitors</span>
                <span class="sidebar-badge">12</span>
            </div>
            <div class="sidebar-item" onclick="showSection('pre-scheduled')">
                <i class="bi bi-calendar-check"></i>
                <span>Pre-Scheduled</span>
            </div>
            <div class="sidebar-item" onclick="showSection('employees')">
                <i class="bi bi-person-badge"></i>
                <span>Employees</span>
            </div>
            <div class="sidebar-item" onclick="showSection('reports')">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
            </div>
            <div class="sidebar-item" onclick="showSection('analytics')">
                <i class="bi bi-graph-up"></i>
                <span>Analytics</span>
            </div>
            <div class="sidebar-item" onclick="showSection('security')">
                <i class="bi bi-shield-check"></i>
                <span>Security Logs</span>
            </div>
            <div class="sidebar-item" onclick="showSection('settings')">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <i class="bi bi-list menu-toggle" onclick="toggleSidebar()"></i>
                <div class="search-box">
                    <input type="text" placeholder="Search visitors, employees...">
                    <i class="bi bi-search"></i>
                </div>
            </div>
            <div class="topbar-right">
                <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">JD</div>
                    <span>John Doe</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content" id="dashboardSection">
            <h1 class="page-title">Visitor Management Dashboard</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening today.</p>

            <!-- Quick Stats Bar -->
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="todayTotal">47</div>
                    <div class="quick-stat-label">Today's Visitors</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="currentlyIn">23</div>
                    <div class="quick-stat-label">Currently In Building</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="scheduledToday">8</div>
                    <div class="quick-stat-label">Scheduled Today</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="avgDuration">2.5h</div>
                    <div class="quick-stat-label">Avg. Visit Duration</div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-people stat-card-icon"></i>
                        <div class="stat-value text-primary">1,234</div>
                        <div class="stat-label">Total Visitors This Month</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>12% from last month</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-person-check stat-card-icon"></i>
                        <div class="stat-value text-success">892</div>
                        <div class="stat-label">Returning Visitors</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>8% increase</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-clock stat-card-icon"></i>
                        <div class="stat-value text-info">3.2h</div>
                        <div class="stat-label">Average Visit Time</div>
                        <div class="stat-change negative">
                            <i class="bi bi-arrow-down"></i>
                            <span>5% decrease</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-check stat-card-icon"></i>
                        <div class="stat-value text-warning">156</div>
                        <div class="stat-label">Pre-Scheduled</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>23% increase</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Recent Visitors Table -->
            <div class="row mb-4">
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="chart-title">Recent Visitors</h3>
                        <div class="table-actions">
                            <button class="btn btn-outline-secondary btn-sm" onclick="refreshVisitorTable()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="showAddVisitorModal()">
                                <i class="bi bi-plus-circle"></i> Add Visitor
                            </button>
                        </div>
                    </div>
                    <table class="table table-hover" id="visitorTable">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Host</th>
                                <th>Purpose</th>
                                <th>Check-In</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="visitorTableBody">
                            <!-- Table rows will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Visitor Traffic</h3>
                            <div class="chart-actions">
                                <button class="chart-action-btn active">Week</button>
                                <button class="chart-action-btn">Month</button>
                                <button class="chart-action-btn">Year</button>
                            </div>
                        </div>
                        <div class="chart-canvas-container">
                            <canvas id="visitorChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Visit Purpose</h3>
                        </div>
                        <div class="chart-canvas-container small">
                            <canvas id="purposeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visitors Section -->
        <div class="dashboard-content" id="visitorsSection" style="display: none;">
            <h1 class="page-title">Visitor Management</h1>
            <p class="page-subtitle">Manage all visitor records and check-ins</p>

            <!-- Filters -->
            <div class="filters-panel">
                <div class="filter-group">
                    <div class="filter-item">
                        <div class="filter-label">Date Range</div>
                        <input type="date" class="form-control" id="filterStartDate">
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">&nbsp;</div>
                        <input type="date" class="form-control" id="filterEndDate">
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Status</div>
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Checked In</option>
                            <option>Checked Out</option>
                            <option>Pending</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Purpose</div>
                        <select class="form-select">
                            <option>All Purposes</option>
                            <option>Meeting</option>
                            <option>Interview</option>
                            <option>Delivery</option>
                            <option>Service</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">&nbsp;</div>
                        <button class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                </div>
            </div>

            <!-- Full Visitor Table -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Visitors</h3>
                    <div class="table-actions">
                        <div class="export-menu">
                            <button class="btn btn-outline-secondary btn-sm" onclick="toggleExportMenu()">
                                <i class="bi bi-download"></i> Export
                            </button>
                            <div class="export-dropdown" id="exportDropdown">
                                <div class="export-option" onclick="exportData('csv')">
                                    <i class="bi bi-file-earmark-csv"></i>
                                    <span>Export as CSV</span>
                                </div>
                                <div class="export-option" onclick="exportData('excel')">
                                    <i class="bi bi-file-earmark-excel"></i>
                                    <span>Export as Excel</span>
                                </div>
                                <div class="export-option" onclick="exportData('pdf')">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    <span>Export as PDF</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="showAddVisitorModal()">
                            <i class="bi bi-plus-circle"></i> Add New Visitor
                        </button>
                    </div>
                </div>
                <table class="table table-hover" id="fullVisitorTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Host</th>
                            <th>Purpose</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="fullVisitorTableBody">
                        <!-- Data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pre-Scheduled Visits Section -->
        <div class="dashboard-content" id="pre-scheduledSection" style="display: none;">
            <h1 class="page-title">Pre-Scheduled Visits</h1>
            <p class="page-subtitle">Manage upcoming scheduled visits</p>

            <!-- Quick Stats for Scheduled Visits -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-plus stat-card-icon"></i>
                        <div class="stat-value text-primary">24</div>
                        <div class="stat-label">Today's Scheduled</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-week stat-card-icon"></i>
                        <div class="stat-value text-info">156</div>
                        <div class="stat-label">This Week</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-check-circle stat-card-icon"></i>
                        <div class="stat-value text-success">18</div>
                        <div class="stat-label">Confirmed Today</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-clock-history stat-card-icon"></i>
                        <div class="stat-value text-warning">3</div>
                        <div class="stat-label">Pending Approval</div>
                    </div>
                </div>
            </div>

            <!-- Calendar View and List -->
            <div class="row">
                <div class="col-md-4">
                    <div class="table-container">
                        <h4>Calendar View</h4>
                        <div id="miniCalendar" style="padding: 20px;">
                            <div class="calendar-nav">
                                <button onclick="changeMonth(-1)"><i class="bi bi-chevron-left"></i></button>
                                <h5 id="calendarMonth">December 2024</h5>
                                <button onclick="changeMonth(1)"><i class="bi bi-chevron-right"></i></button>
                            </div>
                            <div class="calendar-grid" id="calendarGrid">
                                <!-- Calendar will be generated here -->
                            </div>
                        </div>
                        <div class="mt-3 p-3 bg-light rounded">
                            <h6>Legend</h6>
                            <small><span class="badge bg-primary">3</span> Scheduled visits</small><br>
                            <small><span class="badge bg-success">✓</span> Confirmed</small><br>
                            <small><span class="badge bg-warning">!</span> Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-container">
                        <div class="table-header">
                            <h4>Scheduled Visits</h4>
                            <div class="table-actions">
                                <button class="btn btn-outline-secondary btn-sm" onclick="showCalendarView()">
                                    <i class="bi bi-calendar3"></i> Full Calendar
                                </button>
                                <button class="btn btn-primary btn-sm" onclick="showScheduleModal()">
                                    <i class="bi bi-plus-circle"></i> Schedule Visit
                                </button>
                            </div>
                        </div>
                        <table class="table table-hover" id="scheduledTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Visitor</th>
                                    <th>Company</th>
                                    <th>Date & Time</th>
                                    <th>Host</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="scheduledTableBody">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Directory Section -->
        <div class="dashboard-content" id="employeesSection" style="display: none;">
            <h1 class="page-title">Employee Directory</h1>
            <p class="page-subtitle">Manage employee records and host assignments</p>

            <!-- Department Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="table-container">
                        <h4>Department Overview</h4>
                        <div class="row mt-3">
                            <div class="col-md-2 text-center">
                                <h5 class="text-primary">35</h5>
                                <small>Sales</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="text-info">28</h5>
                                <small>Marketing</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="text-success">42</h5>
                                <small>IT</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="text-warning">15</h5>
                                <small>HR</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="text-danger">22</h5>
                                <small>Finance</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <h5 class="text-secondary">18</h5>
                                <small>Operations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Search and Filters -->
            <div class="filters-panel">
                <div class="filter-group">
                    <div class="filter-item">
                        <div class="filter-label">Department</div>
                        <select class="form-select" id="deptFilter" onchange="filterEmployees()">
                            <option value="">All Departments</option>
                            <option>Sales</option>
                            <option>Marketing</option>
                            <option>IT</option>
                            <option>HR</option>
                            <option>Finance</option>
                            <option>Operations</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Status</div>
                        <select class="form-select" id="statusFilter" onchange="filterEmployees()">
                            <option value="">All Status</option>
                            <option>Active</option>
                            <option>On Leave</option>
                            <option>Remote</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Can Host Visitors</div>
                        <select class="form-select" id="hostFilter" onchange="filterEmployees()">
                            <option value="">All</option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Search</div>
                        <input type="text" class="form-control" placeholder="Name or Email" id="employeeSearch" onkeyup="filterEmployees()">
                    </div>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="table-container">
                <div class="table-header">
                    <h4>Employee List</h4>
                    <div class="table-actions">
                        <button class="btn btn-outline-secondary btn-sm" onclick="importEmployees()">
                            <i class="bi bi-upload"></i> Import
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="showAddEmployeeModal()">
                            <i class="bi bi-person-plus"></i> Add Employee
                        </button>
                    </div>
                </div>
                <table class="table table-hover" id="employeeTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Extension</th>
                            <th>Status</th>
                            <th>Can Host</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="dashboard-content" id="reportsSection" style="display: none;">
            <h1 class="page-title">Reports</h1>
            <p class="page-subtitle">Generate and download comprehensive reports</p>

            <!-- Report Type Selection -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="selectReportType('daily')">
                        <i class="bi bi-calendar-day stat-card-icon"></i>
                        <h4>Daily Report</h4>
                        <p class="text-muted">Today's visitor summary</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="selectReportType('weekly')">
                        <i class="bi bi-calendar-week stat-card-icon"></i>
                        <h4>Weekly Report</h4>
                        <p class="text-muted">7-day visitor analysis</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="selectReportType('monthly')">
                        <i class="bi bi-calendar-month stat-card-icon"></i>
                        <h4>Monthly Report</h4>
                        <p class="text-muted">Monthly statistics</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="selectReportType('custom')">
                        <i class="bi bi-gear stat-card-icon"></i>
                        <h4>Custom Report</h4>
                        <p class="text-muted">Build your own report</p>
                    </div>
                </div>
            </div>

            <!-- Report Configuration -->
            <div class="table-container" id="reportConfig">
                <h4>Configure Report</h4>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Report Type</label>
                            <select class="form-select" id="reportType">
                                <option>Visitor Summary</option>
                                <option>Security Log</option>
                                <option>Host Statistics</option>
                                <option>Department Analysis</option>
                                <option>Compliance Report</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="reportStartDate">
                                <span class="input-group-text">to</span>
                                <input type="date" class="form-control" id="reportEndDate">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Format</label>
                            <select class="form-select" id="reportFormat">
                                <option>PDF</option>
                                <option>Excel</option>
                                <option>CSV</option>
                                <option>HTML</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Include Options</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includePhotos" checked>
                                <label class="form-check-label" for="includePhotos">Visitor Photos</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includeCharts" checked>
                                <label class="form-check-label" for="includeCharts">Charts & Graphs</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includeDetails" checked>
                                <label class="form-check-label" for="includeDetails">Detailed Breakdown</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includeSummary" checked>
                                <label class="form-check-label" for="includeSummary">Executive Summary</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="generateReport()">
                    <i class="bi bi-file-earmark-text"></i> Generate Report
                </button>
            </div>

            <!-- Recent Reports -->
            <div class="table-container mt-4">
                <h4>Recent Reports</h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Generated</th>
                            <th>Generated By</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="bi bi-file-pdf text-danger"></i> Monthly_Visitor_Report_Nov2024.pdf</td>
                            <td>Monthly Summary</td>
                            <td>Dec 1, 2024 09:00 AM</td>
                            <td>John Doe</td>
                            <td>2.4 MB</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-file-excel text-success"></i> Weekly_Security_Log_W48.xlsx</td>
                            <td>Security Log</td>
                            <td>Nov 30, 2024 05:00 PM</td>
                            <td>System</td>
                            <td>845 KB</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></button>
                                <button class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-content" id="analyticsSection" style="display: none;">
            <h1 class="page-title">Analytics & Insights</h1>
            <p class="page-subtitle">Detailed visitor analytics and trends</p>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Peak Hours</h3>
                        </div>
                        <div class="chart-canvas-container">
                            <canvas id="peakHoursChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Department Distribution</h3>
                        </div>
                        <div class="chart-canvas-container">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content" id="securitySection" style="display: none;">
            <h1 class="page-title">Security Logs</h1>
            <p class="page-subtitle">Monitor security events and alerts</p>
            <div class="table-container">
                <p class="text-center text-muted p-5">Security logs coming soon...</p>
            </div>
        </div>

        <div class="dashboard-content" id="settingsSection" style="display: none;">
            <h1 class="page-title">System Settings</h1>
            <p class="page-subtitle">Configure visitor management system</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-container">
                        <h4>General Settings</h4>
                        <form>
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" value="TOMS WORLD">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Auto Check-out Time</label>
                                <select class="form-select">
                                    <option>After 8 hours</option>
                                    <option>After 12 hours</option>
                                    <option>End of day</option>
                                    <option>Manual only</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-container">
                        <h4>Notification Settings</h4>
                        <form>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="notifyHost" checked>
                                <label class="form-check-label" for="notifyHost">
                                    Notify host on visitor arrival
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="notifySecurity" checked>
                                <label class="form-check-label" for="notifySecurity">
                                    Alert security for unscheduled visits
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Notifications</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Details Modal -->
    <div class="modal fade" id="visitorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Visitor Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="visitor-photo" style="width: 150px; height: 150px; margin: 0 auto;">
                                <i class="bi bi-person-circle" style="font-size: 8em; color: #dee2e6;"></i>
                            </div>
                            <h4 class="mt-3" id="modalVisitorName">-</h4>
                            <p class="text-muted" id="modalVisitorCompany">-</p>
                        </div>
                        <div class="col-md-8">
                            <h6>Visit Information</h6>
                            <table class="table table-sm">
                                <tbody id="modalVisitorInfo">
                                    <!-- Will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Print Badge</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sample visitor data
        let sampleVisitors = [
            { id: 1, name: 'Alice Johnson', company: 'Tech Solutions Inc.', email: 'alice@tech.com', phone: '555-0101', host: 'John Anderson', purpose: 'Meeting', checkIn: '09:30 AM', checkOut: '', status: 'checked-in' },
            { id: 2, name: 'Bob Smith', company: 'Career Seekers', email: 'bob@career.com', phone: '555-0102', host: 'Sarah Williams', purpose: 'Interview', checkIn: '10:15 AM', checkOut: '', status: 'checked-in' },
            { id: 3, name: 'Carol Davis', company: 'Logistics Co', email: 'carol@logistics.com', phone: '555-0103', host: 'Reception', purpose: 'Delivery', checkIn: '08:45 AM', checkOut: '09:15 AM', status: 'checked-out' },
            { id: 4, name: 'David Wilson', company: 'Consulting Group', email: 'david@consulting.com', phone: '555-0104', host: 'Michael Chen', purpose: 'Training', checkIn: '11:00 AM', checkOut: '', status: 'checked-in' },
            { id: 5, name: 'Emma Brown', company: 'Marketing Agency', email: 'emma@marketing.com', phone: '555-0105', host: 'Emily Johnson', purpose: 'Meeting', checkIn: '02:30 PM', checkOut: '', status: 'checked-in' }
        ];

        // Sample scheduled visits data (mutable for editing)
        let scheduledVisits = [
            { code: 'MEET-2024-001', visitor: 'Alice Johnson', company: 'Tech Solutions Inc.', date: '2024-12-05', time: '10:00 AM', host: 'John Anderson', purpose: 'Sales Meeting', status: 'confirmed' },
            { code: 'INT-2024-042', visitor: 'Bob Smith', company: 'Career Seekers', date: '2024-12-05', time: '2:00 PM', host: 'Sarah Williams', purpose: 'Job Interview', status: 'confirmed' },
            { code: 'TRAIN-2024-015', visitor: 'Charlie Davis', company: 'Learning Corp', date: '2024-12-06', time: '9:00 AM', host: 'Michael Chen', purpose: 'IT Training', status: 'pending' },
            { code: 'EVENT-2024-008', visitor: 'Diana Martinez', company: 'Event Planners LLC', date: '2024-12-06', time: '11:30 AM', host: 'Emily Johnson', purpose: 'Event Planning', status: 'confirmed' },
            { code: 'TOUR-2024-003', visitor: 'Edward Wilson', company: 'University Tours', date: '2024-12-07', time: '3:00 PM', host: 'Lisa Martinez', purpose: 'Facility Tour', status: 'pending' },
            { code: 'MEET-2024-045', visitor: 'Frank Chen', company: 'Import Export Co', date: '2024-12-08', time: '10:30 AM', host: 'David Brown', purpose: 'Business Meeting', status: 'confirmed' }
        ];

        // Sample employee data with mutable canHost property
        let employees = [
            { id: 'E001', name: 'John Anderson', email: 'j.anderson@company.com', department: 'Sales', position: 'Sales Manager', extension: '2001', status: 'active', canHost: true },
            { id: 'E002', name: 'Sarah Williams', email: 's.williams@company.com', department: 'HR', position: 'HR Director', extension: '2002', status: 'active', canHost: true },
            { id: 'E003', name: 'Michael Chen', email: 'm.chen@company.com', department: 'IT', position: 'IT Support Lead', extension: '2003', status: 'active', canHost: true },
            { id: 'E004', name: 'Emily Johnson', email: 'e.johnson@company.com', department: 'Marketing', position: 'Marketing Manager', extension: '2004', status: 'active', canHost: true },
            { id: 'E005', name: 'David Brown', email: 'd.brown@company.com', department: 'Finance', position: 'Finance Director', extension: '2005', status: 'active', canHost: true },
            { id: 'E006', name: 'Lisa Martinez', email: 'l.martinez@company.com', department: 'Operations', position: 'Operations Manager', extension: '2006', status: 'on-leave', canHost: false },
            { id: 'E007', name: 'Robert Taylor', email: 'r.taylor@company.com', department: 'Legal', position: 'Legal Counsel', extension: '2007', status: 'active', canHost: true },
            { id: 'E008', name: 'Jennifer Davis', email: 'j.davis@company.com', department: 'Sales', position: 'Sales Rep', extension: '2008', status: 'active', canHost: false },
            { id: 'E009', name: 'William Garcia', email: 'w.garcia@company.com', department: 'IT', position: 'Developer', extension: '2009', status: 'remote', canHost: true },
            { id: 'E010', name: 'Maria Rodriguez', email: 'm.rodriguez@company.com', department: 'HR', position: 'HR Specialist', extension: '2010', status: 'active', canHost: true }
        ];

        // Chart instances
        let visitorChart = null;
        let purposeChart = null;
        let peakHoursChart = null;
        let departmentChart = null;

        // Calendar state
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            populateVisitorTable();
            populateScheduledVisits();
            populateEmployeeDirectory();
            generateCalendar();
        });

        // Initialize Dashboard
        function initializeDashboard() {
            initMainCharts();
        }

        // Initialize main dashboard charts
        function initMainCharts() {
            // Visitor Traffic Chart
            const ctx1 = document.getElementById('visitorChart');
            if (ctx1) {
                visitorChart = new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Visitors',
                            data: [65, 78, 90, 81, 56, 45, 40],
                            borderColor: '#f39c12',
                            backgroundColor: 'rgba(243, 156, 18, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Purpose Chart
            const ctx2 = document.getElementById('purposeChart');
            if (ctx2) {
                purposeChart = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Meeting', 'Interview', 'Delivery', 'Service', 'Other'],
                        datasets: [{
                            data: [35, 25, 15, 15, 10],
                            backgroundColor: [
                                '#f39c12',
                                '#3498db',
                                '#27ae60',
                                '#e74c3c',
                                '#95a5a6'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Initialize Analytics Charts
        function initAnalyticsCharts() {
            // Peak Hours Chart
            const peakHoursCtx = document.getElementById('peakHoursChart');
            if (peakHoursCtx && !peakHoursChart) {
                peakHoursChart = new Chart(peakHoursCtx, {
                    type: 'bar',
                    data: {
                        labels: ['8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM'],
                        datasets: [{
                            label: 'Average Visitors',
                            data: [12, 35, 45, 38, 25, 30, 42, 38, 22, 15],
                            backgroundColor: '#f39c12'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Department Chart
            const deptCtx = document.getElementById('departmentChart');
            if (deptCtx && !departmentChart) {
                departmentChart = new Chart(deptCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Sales', 'HR', 'IT', 'Marketing', 'Operations'],
                        datasets: [{
                            data: [30, 20, 15, 25, 10],
                            backgroundColor: [
                                '#f39c12',
                                '#3498db',
                                '#27ae60',
                                '#e74c3c',
                                '#9b59b6'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        }

        // Calendar Functions
        function generateCalendar() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            
            document.getElementById('calendarMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;
            
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentYear, currentMonth, 0).getDate();
            
            const today = new Date();
            const isCurrentMonth = today.getMonth() === currentMonth && today.getFullYear() === currentYear;
            const todayDate = today.getDate();
            
            let html = '';
            
            // Add day headers
            const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(day => {
                html += `<div class="calendar-header">${day}</div>`;
            });
            
            // Previous month days
            for (let i = firstDay - 1; i >= 0; i--) {
                html += `<div class="calendar-day other-month">${daysInPrevMonth - i}</div>`;
            }
            
            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                let classes = 'calendar-day';
                
                if (isCurrentMonth && day === todayDate) {
                    classes += ' today';
                }
                
                // Check if this day has scheduled visits
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const visitsOnDay = scheduledVisits.filter(v => v.date === dateStr);
                
                if (visitsOnDay.length > 0) {
                    classes += ' has-events';
                    html += `<div class="${classes}" onclick="showDayVisits('${dateStr}')">
                        <div class="calendar-day-number">${day}</div>
                        <div class="calendar-event-count">${visitsOnDay.length}</div>
                    </div>`;
                } else {
                    html += `<div class="${classes}">
                        <div class="calendar-day-number">${day}</div>
                    </div>`;
                }
            }
            
            // Next month days
            const totalCells = 42; // 6 weeks * 7 days
            const cellsFilled = firstDay + daysInMonth;
            const remainingCells = totalCells - cellsFilled;
            
            for (let day = 1; day <= remainingCells; day++) {
                html += `<div class="calendar-day other-month">${day}</div>`;
            }
            
            document.getElementById('calendarGrid').innerHTML = html;
        }

        function changeMonth(direction) {
            currentMonth += direction;
            
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            
            generateCalendar();
        }

        function showDayVisits(date) {
            const visitsOnDay = scheduledVisits.filter(v => v.date === date);
            
            if (visitsOnDay.length > 0) {
                let visitList = visitsOnDay.map(v => 
                    `<li><strong>${v.time}</strong> - ${v.visitor} (${v.purpose})</li>`
                ).join('');
                
                Swal.fire({
                    title: `Scheduled Visits for ${date}`,
                    html: `<ul class="text-start">${visitList}</ul>`,
                    confirmButtonColor: '#f39c12'
                });
            }
        }

        // Populate visitor tables
        function populateVisitorTable() {
            // Populate main dashboard table
            const tbody = document.getElementById('visitorTableBody');
            if (tbody) {
                tbody.innerHTML = '';
                sampleVisitors.slice(0, 5).forEach(visitor => {
                    tbody.innerHTML += createVisitorRow(visitor);
                });
            }

            // Populate full visitor table
            const fullTbody = document.getElementById('fullVisitorTableBody');
            if (fullTbody) {
                fullTbody.innerHTML = '';
                sampleVisitors.forEach(visitor => {
                    fullTbody.innerHTML += createFullVisitorRow(visitor);
                });
            }

            // Initialize DataTables if not already initialized
            if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#visitorTable')) {
                $('#visitorTable').DataTable({
                    pageLength: 10,
                    responsive: true,
                    order: [[5, 'desc']]
                });
            }

            if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#fullVisitorTable')) {
                $('#fullVisitorTable').DataTable({
                    pageLength: 25,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            }
        }

        // Populate scheduled visits
        function populateScheduledVisits() {
            const tbody = document.getElementById('scheduledTableBody');
            if (tbody) {
                tbody.innerHTML = '';
                scheduledVisits.forEach(visit => {
                    const statusBadge = visit.status === 'confirmed' 
                        ? '<span class="badge bg-success">Confirmed</span>' 
                        : '<span class="badge bg-warning">Pending</span>';
                    
                    tbody.innerHTML += `
                        <tr>
                            <td><span class="badge bg-secondary">${visit.code}</span></td>
                            <td>${visit.visitor}</td>
                            <td>${visit.company}</td>
                            <td>${visit.date} ${visit.time}</td>
                            <td>${visit.host}</td>
                            <td>${visit.purpose}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewScheduledVisit('${visit.code}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editScheduledVisit('${visit.code}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="confirmScheduledVisit('${visit.code}')">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelScheduledVisit('${visit.code}')">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                // Initialize DataTable for scheduled visits
                if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#scheduledTable')) {
                    $('#scheduledTable').DataTable({
                        pageLength: 10,
                        responsive: true,
                        order: [[3, 'asc']]
                    });
                }
            }
        }

        // Populate employee directory
        function populateEmployeeDirectory() {
            const tbody = document.getElementById('employeeTableBody');
            if (tbody) {
                tbody.innerHTML = '';
                employees.forEach(emp => {
                    const statusBadge = emp.status === 'active' 
                        ? '<span class="badge bg-success">Active</span>'
                        : emp.status === 'on-leave'
                        ? '<span class="badge bg-warning">On Leave</span>'
                        : '<span class="badge bg-info">Remote</span>';
                    
                    const canHostBadge = emp.canHost 
                        ? '<span class="badge bg-primary">Yes</span>'
                        : '<span class="badge bg-secondary">No</span>';
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${emp.id}</td>
                            <td>
                                <div class="visitor-photo">
                                    <i class="bi bi-person-circle" style="font-size: 1.5em; color: #dee2e6;"></i>
                                </div>
                            </td>
                            <td><strong>${emp.name}</strong></td>
                            <td>${emp.email}</td>
                            <td>${emp.department}</td>
                            <td>${emp.position}</td>
                            <td>${emp.extension}</td>
                            <td>${statusBadge}</td>
                            <td>${canHostBadge}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewEmployee('${emp.id}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                
                                <button class="btn btn-sm btn-outline-success" onclick="toggleEmployeeHosting('${emp.id}')">
                                    <i class="bi bi-toggle-${emp.canHost ? 'on' : 'off'}"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                                // <button class="btn btn-sm btn-outline-warning" onclick="editEmployee('${emp.id}')">
                                //     <i class="bi bi-pencil"></i>
                                // </button>
                });

                // Initialize DataTable for employees
                if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#employeeTable')) {
                    $('#employeeTable').DataTable({
                        pageLength: 10,
                        responsive: true,
                        order: [[2, 'asc']]
                    });
                }
            }
        }

        // Toggle Employee Hosting Permission (replaced delete function)
        function toggleEmployeeHosting(id) {
            const emp = employees.find(e => e.id === id);
            if (emp) {
                const currentStatus = emp.canHost ? 'can' : 'cannot';
                const newStatus = !emp.canHost ? 'can' : 'cannot';
                
                Swal.fire({
                    title: 'Change Hosting Permission?',
                    text: `${emp.name} currently ${currentStatus} host visitors. Change to ${newStatus} host?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Yes, Change'
                }).then((result) => {
                    if (result.isConfirmed) {
                        emp.canHost = !emp.canHost;
                        populateEmployeeDirectory();
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `${emp.name} ${newStatus} now host visitors`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                });
            }
        }

        // Filter employees
        function filterEmployees() {
            // This would implement actual filtering logic
            const dept = document.getElementById('deptFilter').value;
            const status = document.getElementById('statusFilter').value;
            const canHost = document.getElementById('hostFilter').value;
            const search = document.getElementById('employeeSearch').value.toLowerCase();
            
            console.log('Filtering employees:', { dept, status, canHost, search });
        }

        // Create visitor row for main table
        function createVisitorRow(visitor) {
            const statusClass = visitor.status === 'checked-in' ? 'checked-in' : 'checked-out';
            const statusText = visitor.status === 'checked-in' ? 'Checked In' : 'Checked Out';
            
            return `
                <tr>
                    <td>
                        <div class="visitor-photo">
                            <i class="bi bi-person-circle" style="font-size: 1.5em; color: #dee2e6;"></i>
                        </div>
                    </td>
                    <td><strong>${visitor.name}</strong></td>
                    <td>${visitor.company}</td>
                    <td>${visitor.host}</td>
                    <td>${visitor.purpose}</td>
                    <td>${visitor.checkIn}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <button class="action-btn view" onclick="viewVisitor(${visitor.id})"><i class="bi bi-eye"></i></button>
                        <button class="action-btn edit" onclick="editVisitor(${visitor.id})"><i class="bi bi-pencil"></i></button>
                        <button class="action-btn delete" onclick="checkOutVisitor(${visitor.id})"><i class="bi bi-box-arrow-right"></i></button>
                    </td>
                </tr>
            `;
        }

        // Create visitor row for full table
        function createFullVisitorRow(visitor) {
            const statusClass = visitor.status === 'checked-in' ? 'checked-in' : 'checked-out';
            const statusText = visitor.status === 'checked-in' ? 'Checked In' : 'Checked Out';
            
            return `
                <tr>
                    <td>${visitor.id}</td>
                    <td>
                        <div class="visitor-photo">
                            <i class="bi bi-person-circle" style="font-size: 1.5em; color: #dee2e6;"></i>
                        </div>
                    </td>
                    <td><strong>${visitor.name}</strong></td>
                    <td>${visitor.email}</td>
                    <td>${visitor.phone}</td>
                    <td>${visitor.company}</td>
                    <td>${visitor.host}</td>
                    <td>${visitor.purpose}</td>
                    <td>${visitor.checkIn}</td>
                    <td>${visitor.checkOut || '-'}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <button class="action-btn view" onclick="viewVisitor(${visitor.id})"><i class="bi bi-eye"></i></button>
                        <button class="action-btn edit" onclick="editVisitor(${visitor.id})"><i class="bi bi-pencil"></i></button>
                        <button class="action-btn delete" onclick="checkOutVisitor(${visitor.id})"><i class="bi bi-box-arrow-right"></i></button>
                    </td>
                </tr>
            `;
        }

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        // Show Section
        function showSection(section) {
            // Hide all sections
            document.querySelectorAll('.dashboard-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active from all sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section
            const sectionMap = {
                'dashboard': 'dashboardSection',
                'visitors': 'visitorsSection',
                'pre-scheduled': 'pre-scheduledSection',
                'employees': 'employeesSection',
                'reports': 'reportsSection',
                'analytics': 'analyticsSection',
                'security': 'securitySection',
                'settings': 'settingsSection'
            };
            
            if (sectionMap[section]) {
                document.getElementById(sectionMap[section]).style.display = 'block';
                
                // Add active class to clicked item
                event.target.closest('.sidebar-item').classList.add('active');
                
                // Initialize charts if analytics section
                if (section === 'analytics') {
                    setTimeout(() => initAnalyticsCharts(), 100);
                }
                
                // Load data for specific sections
                if (section === 'pre-scheduled') {
                    populateScheduledVisits();
                    generateCalendar();
                } else if (section === 'employees') {
                    populateEmployeeDirectory();
                }
            }
        }

        // View Visitor Details
        function viewVisitor(id) {
            const visitor = sampleVisitors.find(v => v.id === id);
            if (visitor) {
                document.getElementById('modalVisitorName').textContent = visitor.name;
                document.getElementById('modalVisitorCompany').textContent = visitor.company;
                
                const infoHtml = `
                    <tr><td><strong>Badge Number:</strong></td><td>V-2024-000${visitor.id}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${visitor.email}</td></tr>
                    <tr><td><strong>Phone:</strong></td><td>${visitor.phone}</td></tr>
                    <tr><td><strong>Host:</strong></td><td>${visitor.host}</td></tr>
                    <tr><td><strong>Purpose:</strong></td><td>${visitor.purpose}</td></tr>
                    <tr><td><strong>Check-In Time:</strong></td><td>${visitor.checkIn}</td></tr>
                    <tr><td><strong>Check-Out Time:</strong></td><td>${visitor.checkOut || 'Still in building'}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="status-badge ${visitor.status}">${visitor.status === 'checked-in' ? 'Checked In' : 'Checked Out'}</span></td></tr>
                `;
                document.getElementById('modalVisitorInfo').innerHTML = infoHtml;
                
                const modal = new bootstrap.Modal(document.getElementById('visitorModal'));
                modal.show();
            }
        }

        // Edit Visitor - FIXED IMPLEMENTATION
        function editVisitor(id) {
            const visitor = sampleVisitors.find(v => v.id === id);
            if (visitor) {
                Swal.fire({
                    title: 'Edit Visitor Information',
                    html: `
                        <form id="editVisitorForm">
                            <div class="mb-3 text-start">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="editName" value="${visitor.name}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" id="editCompany" value="${visitor.company}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" value="${visitor.email}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="editPhone" value="${visitor.phone}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Host</label>
                                <select class="form-select" id="editHost">
                                    ${employees.filter(e => e.canHost).map(e => 
                                        `<option ${e.name === visitor.host ? 'selected' : ''}>${e.name}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Purpose</label>
                                <select class="form-select" id="editPurpose">
                                    <option ${visitor.purpose === 'Meeting' ? 'selected' : ''}>Meeting</option>
                                    <option ${visitor.purpose === 'Interview' ? 'selected' : ''}>Interview</option>
                                    <option ${visitor.purpose === 'Training' ? 'selected' : ''}>Training</option>
                                    <option ${visitor.purpose === 'Delivery' ? 'selected' : ''}>Delivery</option>
                                    <option ${visitor.purpose === 'Service' ? 'selected' : ''}>Service</option>
                                </select>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    confirmButtonColor: '#f39c12',
                    preConfirm: () => {
                        return {
                            name: document.getElementById('editName').value,
                            company: document.getElementById('editCompany').value,
                            email: document.getElementById('editEmail').value,
                            phone: document.getElementById('editPhone').value,
                            host: document.getElementById('editHost').value,
                            purpose: document.getElementById('editPurpose').value
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update visitor data
                        visitor.name = result.value.name;
                        visitor.company = result.value.company;
                        visitor.email = result.value.email;
                        visitor.phone = result.value.phone;
                        visitor.host = result.value.host;
                        visitor.purpose = result.value.purpose;
                        
                        // Refresh tables
                        populateVisitorTable();
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Visitor information updated successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                });
            }
        }

        // Check Out Visitor
        function checkOutVisitor(id) {
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
                    // Update visitor status
                    const visitor = sampleVisitors.find(v => v.id === id);
                    if (visitor) {
                        visitor.status = 'checked-out';
                        visitor.checkOut = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                        populateVisitorTable();
                    }
                    
                    Swal.fire(
                        'Checked Out!',
                        'Visitor has been checked out successfully.',
                        'success'
                    );
                }
            });
        }

        // Scheduled Visit Functions
        function showScheduleModal() {
            Swal.fire({
                title: 'Schedule New Visit',
                html: `
                    <form>
                        <div class="mb-3 text-start">
                            <label class="form-label">Visitor Name</label>
                            <input type="text" class="form-control" placeholder="Enter visitor name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" placeholder="Enter company">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Host</label>
                            <select class="form-select">
                                <option>Select host...</option>
                                ${employees.filter(e => e.canHost).map(e => `<option>${e.name}</option>`).join('')}
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Schedule',
                confirmButtonColor: '#f39c12'
            });
        }

        function viewScheduledVisit(code) {
            const visit = scheduledVisits.find(v => v.code === code);
            if (visit) {
                Swal.fire({
                    title: 'Scheduled Visit Details',
                    html: `
                        <div class="text-start">
                            <p><strong>Booking Code:</strong> ${visit.code}</p>
                            <p><strong>Visitor:</strong> ${visit.visitor}</p>
                            <p><strong>Company:</strong> ${visit.company}</p>
                            <p><strong>Date & Time:</strong> ${visit.date} ${visit.time}</p>
                            <p><strong>Host:</strong> ${visit.host}</p>
                            <p><strong>Purpose:</strong> ${visit.purpose}</p>
                            <p><strong>Status:</strong> ${visit.status}</p>
                        </div>
                    `,
                    confirmButtonColor: '#f39c12'
                });
            }
        }

        function confirmScheduledVisit(code) {
            Swal.fire({
                title: 'Confirm Visit?',
                text: 'Send confirmation to visitor?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#27ae60'
            }).then((result) => {
                if (result.isConfirmed) {
                    const visit = scheduledVisits.find(v => v.code === code);
                    if (visit) {
                        visit.status = 'confirmed';
                        populateScheduledVisits();
                    }
                    Swal.fire('Confirmed!', 'Visit has been confirmed.', 'success');
                }
            });
        }

        function cancelScheduledVisit(code) {
            Swal.fire({
                title: 'Cancel Visit?',
                text: 'Are you sure you want to cancel this scheduled visit?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Cancelled!', 'Visit has been cancelled.', 'success');
                }
            });
        }

        function showCalendarView() {
            Swal.fire({
                title: 'Full Calendar View',
                html: '<p>Opening full calendar...</p>',
                timer: 1500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Employee Functions
        function showAddEmployeeModal() {
            Swal.fire({
                title: 'Add New Employee',
                html: `
                    <form>
                        <div class="mb-3 text-start">
                            <label class="form-label">Employee Name</label>
                            <input type="text" class="form-control" placeholder="Full name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="email@company.com">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Department</label>
                            <select class="form-select">
                                <option>Sales</option>
                                <option>Marketing</option>
                                <option>IT</option>
                                <option>HR</option>
                                <option>Finance</option>
                                <option>Operations</option>
                            </select>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Can Host Visitors</label>
                            <select class="form-select">
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Add Employee',
                confirmButtonColor: '#f39c12'
            });
        }

        function viewEmployee(id) {
            const emp = employees.find(e => e.id === id);
            if (emp) {
                Swal.fire({
                    title: 'Employee Details',
                    html: `
                        <div class="text-start">
                            <p><strong>ID:</strong> ${emp.id}</p>
                            <p><strong>Name:</strong> ${emp.name}</p>
                            <p><strong>Email:</strong> ${emp.email}</p>
                            <p><strong>Department:</strong> ${emp.department}</p>
                            <p><strong>Position:</strong> ${emp.position}</p>
                            <p><strong>Extension:</strong> ${emp.extension}</p>
                            <p><strong>Status:</strong> ${emp.status}</p>
                            <p><strong>Can Host:</strong> ${emp.canHost ? 'Yes' : 'No'}</p>
                        </div>
                    `,
                    confirmButtonColor: '#f39c12'
                });
            }
        }

        function editEmployee(id) {
            Swal.fire({
                title: 'Edit Employee',
                text: 'Edit employee functionality will be implemented',
                icon: 'info',
                confirmButtonColor: '#f39c12'
            });
        }

        function editScheduledVisit(code) {
            const visit = scheduledVisits.find(v => v.code === code);
            if (visit) {
                // Parse the date for the datetime-local input
                const dateTime = `${visit.date}T${convertTo24Hour(visit.time)}`;
                
                Swal.fire({
                    title: 'Edit Scheduled Visit',
                    html: `
                        <form id="editScheduledForm">
                            <div class="mb-3 text-start">
                                <label class="form-label">Booking Code</label>
                                <input type="text" class="form-control" value="${visit.code}" disabled>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Visitor Name</label>
                                <input type="text" class="form-control" id="editScheduledVisitor" value="${visit.visitor}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" id="editScheduledCompany" value="${visit.company}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Date & Time</label>
                                <input type="datetime-local" class="form-control" id="editScheduledDateTime" value="${dateTime}">
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Host</label>
                                <select class="form-select" id="editScheduledHost">
                                    ${employees.filter(e => e.canHost).map(e => 
                                        `<option ${e.name === visit.host ? 'selected' : ''}>${e.name}</option>`
                                    ).join('')}
                                </select>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Purpose</label>
                                <select class="form-select" id="editScheduledPurpose">
                                    <option ${visit.purpose === 'Sales Meeting' ? 'selected' : ''}>Sales Meeting</option>
                                    <option ${visit.purpose === 'Job Interview' ? 'selected' : ''}>Job Interview</option>
                                    <option ${visit.purpose === 'IT Training' ? 'selected' : ''}>IT Training</option>
                                    <option ${visit.purpose === 'Event Planning' ? 'selected' : ''}>Event Planning</option>
                                    <option ${visit.purpose === 'Facility Tour' ? 'selected' : ''}>Facility Tour</option>
                                    <option ${visit.purpose === 'Business Meeting' ? 'selected' : ''}>Business Meeting</option>
                                    <option ${visit.purpose === 'Consultation' ? 'selected' : ''}>Consultation</option>
                                    <option ${visit.purpose === 'Other' ? 'selected' : ''}>Other</option>
                                </select>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="editScheduledStatus">
                                    <option value="pending" ${visit.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="confirmed" ${visit.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                </select>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    confirmButtonColor: '#f39c12',
                    width: '600px',
                    preConfirm: () => {
                        const dateTimeValue = document.getElementById('editScheduledDateTime').value;
                        const dateObj = new Date(dateTimeValue);
                        
                        return {
                            visitor: document.getElementById('editScheduledVisitor').value,
                            company: document.getElementById('editScheduledCompany').value,
                            date: dateObj.toISOString().split('T')[0],
                            time: dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }),
                            host: document.getElementById('editScheduledHost').value,
                            purpose: document.getElementById('editScheduledPurpose').value,
                            status: document.getElementById('editScheduledStatus').value
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update scheduled visit data
                        visit.visitor = result.value.visitor;
                        visit.company = result.value.company;
                        visit.date = result.value.date;
                        visit.time = result.value.time;
                        visit.host = result.value.host;
                        visit.purpose = result.value.purpose;
                        visit.status = result.value.status;
                        
                        // Refresh the scheduled visits table and calendar
                        populateScheduledVisits();
                        generateCalendar();
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Scheduled visit updated successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                });
            }
        }
        
        // Helper function to convert 12-hour time to 24-hour for datetime-local input
        function convertTo24Hour(time12h) {
            const [time, modifier] = time12h.split(' ');
            let [hours, minutes] = time.split(':');
            
            if (hours === '12') {
                hours = '00';
            }
            
            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }
            
            return `${hours.toString().padStart(2, '0')}:${minutes}`;
        }

        function importEmployees() {
            Swal.fire({
                title: 'Import Employees',
                text: 'CSV/Excel import functionality will be implemented',
                icon: 'info',
                confirmButtonColor: '#f39c12'
            });
        }

        // Report Functions
        function selectReportType(type) {
            document.getElementById('reportType').value = 
                type === 'daily' ? 'Visitor Summary' :
                type === 'weekly' ? 'Host Statistics' :
                type === 'monthly' ? 'Department Analysis' : 'Compliance Report';
            
            // Set appropriate date range
            const today = new Date();
            const startDate = document.getElementById('reportStartDate');
            const endDate = document.getElementById('reportEndDate');
            
            endDate.value = today.toISOString().split('T')[0];
            
            if (type === 'daily') {
                startDate.value = today.toISOString().split('T')[0];
            } else if (type === 'weekly') {
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                startDate.value = weekAgo.toISOString().split('T')[0];
            } else if (type === 'monthly') {
                const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
                startDate.value = monthAgo.toISOString().split('T')[0];
            }
        }

        function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const format = document.getElementById('reportFormat').value;
            
            Swal.fire({
                title: 'Generating Report',
                html: 'Please wait while we generate your report...',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then((result) => {
                Swal.fire({
                    title: 'Report Ready!',
                    text: `Your ${reportType} report has been generated in ${format} format.`,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Download',
                    cancelButtonText: 'View',
                    confirmButtonColor: '#f39c12'
                });
            });
        }

        function scheduleReport() {
            Swal.fire({
                title: 'Schedule Report',
                html: `
                    <form>
                        <div class="mb-3 text-start">
                            <label class="form-label">Report Type</label>
                            <select class="form-select">
                                <option>Daily Visitor Summary</option>
                                <option>Weekly Analysis</option>
                                <option>Monthly Report</option>
                            </select>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Frequency</label>
                            <select class="form-select">
                                <option>Daily</option>
                                <option>Weekly</option>
                                <option>Monthly</option>
                            </select>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Send To</label>
                            <input type="email" class="form-control" placeholder="email@company.com">
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Schedule',
                confirmButtonColor: '#f39c12'
            });
        }

        // Refresh Table
        function refreshVisitorTable() {
            populateVisitorTable();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Table refreshed',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Show Add Visitor Modal
        function showAddVisitorModal() {
            Swal.fire({
                title: 'Add New Visitor',
                html: `
                    <form>
                        <div class="mb-3 text-start">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="newVisitorName" placeholder="Full name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" id="newVisitorCompany" placeholder="Company name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="newVisitorEmail" placeholder="email@example.com">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="newVisitorPhone" placeholder="Phone number">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Host</label>
                            <select class="form-select" id="newVisitorHost">
                                <option>Select host...</option>
                                ${employees.filter(e => e.canHost).map(e => `<option>${e.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Purpose</label>
                            <select class="form-select" id="newVisitorPurpose">
                                <option>Meeting</option>
                                <option>Interview</option>
                                <option>Training</option>
                                <option>Delivery</option>
                                <option>Service</option>
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Add Visitor',
                confirmButtonColor: '#f39c12',
                preConfirm: () => {
                    const name = document.getElementById('newVisitorName').value;
                    const company = document.getElementById('newVisitorCompany').value;
                    const email = document.getElementById('newVisitorEmail').value;
                    const phone = document.getElementById('newVisitorPhone').value;
                    const host = document.getElementById('newVisitorHost').value;
                    const purpose = document.getElementById('newVisitorPurpose').value;
                    
                    if (!name || !company || !email || host === 'Select host...') {
                        Swal.showValidationMessage('Please fill all required fields');
                        return false;
                    }
                    
                    return { name, company, email, phone, host, purpose };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add new visitor to the array
                    const newVisitor = {
                        id: sampleVisitors.length + 1,
                        name: result.value.name,
                        company: result.value.company,
                        email: result.value.email,
                        phone: result.value.phone,
                        host: result.value.host,
                        purpose: result.value.purpose,
                        checkIn: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                        checkOut: '',
                        status: 'checked-in'
                    };
                    
                    sampleVisitors.push(newVisitor);
                    populateVisitorTable();
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Visitor added successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        // Toggle Export Menu
        function toggleExportMenu() {
            const dropdown = document.getElementById('exportDropdown');
            dropdown.classList.toggle('show');
        }

        // Export Data
        function exportData(format) {
            Swal.fire({
                title: 'Export Data',
                text: `Exporting data as ${format.toUpperCase()}...`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            document.getElementById('exportDropdown').classList.remove('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.export-menu')) {
                const dropdown = document.getElementById('exportDropdown');
                if (dropdown) dropdown.classList.remove('show');
            }
        });

        // Update real-time stats
        setInterval(function() {
            // Simulate real-time updates
            const currentlyIn = document.getElementById('currentlyIn');
            if (currentlyIn) {
                const current = parseInt(currentlyIn.textContent);
                const change = Math.random() > 0.5 ? 1 : -1;
                const newValue = Math.max(0, current + change);
                currentlyIn.textContent = newValue;
            }
        }, 30000); // Update every 30 seconds

        // Replace sample data with database calls
        function loadVisitors() {
            $.ajax({
                url: '<?= base_url("visitor/search") ?>',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    populateVisitorTable(data);
                }
            });
        }

        // Check-in visitor via AJAX
        function checkInVisitor() {
            const formData = new FormData($('#checkinForm')[0]);
            
            $.ajax({
                url: '<?= base_url("visitor/checkin") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        Swal.fire('Success', 'Visitor checked in', 'success');
                        loadVisitors();
                    }
                }
            });
        }

        // Check-out visitor
        function checkOutVisitor(visitId) {
            $.post('<?= base_url("visitor/checkout/") ?>' + visitId, function(response) {
                if(response.success) {
                    Swal.fire('Success', 'Visitor checked out', 'success');
                    loadVisitors();
                }
            });
        }
    </script>
</body>
</html>