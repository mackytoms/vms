<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS Admin Dashboard - Tom's World & Pan-Asia</title>
    <!-- <title><?php echo $title; ?></title> -->
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
            padding: 20px;
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
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .sidebar-logo img {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            padding: 5px;
            object-fit: contain;
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

        /* Purpose Badge Colors */
        .purpose-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .purpose-badge.meeting {
            background: rgba(52, 152, 219, 0.1);
            color: var(--info-color);
        }

        .purpose-badge.interview {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
        }

        .purpose-badge.delivery {
            background: rgba(243, 156, 18, 0.1);
            color: var(--primary-color);
        }

        .purpose-badge.service {
            background: rgba(41, 128, 185, 0.1);
            color: #2980b9;
        }

        .purpose-badge.training {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .purpose-badge.tour {
            background: rgba(26, 188, 156, 0.1);
            color: #1abc9c;
        }

        .purpose-badge.event {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .purpose-badge.other {
            background: rgba(149, 165, 166, 0.1);
            color: #95a5a6;
        }

        /* Department Stats */
        .dept-stats {
            display: flex;
            justify-content: space-around;
            padding: 20px 0;
            flex-wrap: wrap;
        }

        .dept-stat-item {
            text-align: center;
            flex: 0 0 14%;
            margin-bottom: 15px;
        }

        .dept-stat-value {
            font-size: 1.8em;
            font-weight: 600;
            color: var(--primary-color);
        }

        .dept-stat-label {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 5px;
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

        /* Badge Number Display */
        .badge-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.85em;
            font-weight: 600;
            letter-spacing: 0.5px;
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

            .dept-stat-item {
                flex: 0 0 30%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" alt="TOMS WORLD">
                <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" alt="PAN-ASIA">
            </div>
            <h3>VMS ADMIN</h3>
        </div>
        <div class="sidebar-menu">
            <div class="sidebar-item active" onclick="showSection('dashboard')">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </div>
            <div class="sidebar-item" onclick="showSection('active-visits')">
                <i class="bi bi-person-check"></i>
                <span>Active Visits</span>
                <span class="sidebar-badge" id="activeVisitCount">2</span>
            </div>
            <div class="sidebar-item" onclick="showSection('visitors')">
                <i class="bi bi-people"></i>
                <span>All Visitors</span>
            </div>
            <div class="sidebar-item" onclick="showSection('pre-scheduled')">
                <i class="bi bi-calendar-check"></i>
                <span>Pre-Scheduled</span>
            </div>
            <div class="sidebar-item" onclick="showSection('employees')">
                <i class="bi bi-person-badge"></i>
                <span>Employees</span>
            </div>
            <div class="sidebar-item" onclick="showSection('departments')">
                <i class="bi bi-building"></i>
                <span>Departments</span>
            </div>
            <div class="sidebar-item" onclick="showSection('reports')">
                <i class="bi bi-file-earmark-text"></i>
                <span>Reports</span>
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
                    <input type="text" placeholder="Search visitors, employees..." id="globalSearch" onkeyup="performGlobalSearch()">
                    <i class="bi bi-search"></i>
                </div>
            </div>
            <div class="topbar-right">
                <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge">5</span>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <span>Admin</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div class="dashboard-content" id="dashboardSection">
            <h1 class="page-title">Visitor Management Dashboard</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening today at Tom's World & Pan-Asia.</p>

            <!-- Quick Stats Bar -->
            <div class="quick-stats">
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="todayTotal">2</div>
                    <div class="quick-stat-label">Today's Visitors</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="currentlyIn">2</div>
                    <div class="quick-stat-label">Currently In Building</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="scheduledToday">1</div>
                    <div class="quick-stat-label">Scheduled Today</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="avgDuration">4.5h</div>
                    <div class="quick-stat-label">Avg. Visit Duration</div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-people stat-card-icon"></i>
                        <div class="stat-value text-primary">2</div>
                        <div class="stat-label">Total Visitors</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i>
                            <span>New this week</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-building stat-card-icon"></i>
                        <div class="stat-value text-success">18</div>
                        <div class="stat-label">Departments</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-person-badge stat-card-icon"></i>
                        <div class="stat-value text-info">44</div>
                        <div class="stat-label">Active Employees</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-check stat-card-icon"></i>
                        <div class="stat-value text-warning">1</div>
                        <div class="stat-label">Pre-Scheduled</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h3 class="chart-title">Recent Check-ins</h3>
                    <div class="table-actions">
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshDashboard()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Badge #</th>
                            <th>Visitor</th>
                            <th>Company</th>
                            <th>Host</th>
                            <th>Purpose</th>
                            <th>Check-In</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentActivityTable">
                        <!-- Will be populated from database -->
                    </tbody>
                </table>
            </div>

            <!-- Department Distribution -->
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Visit Purposes Overview</h3>
                        </div>
                        <div class="chart-canvas-container">
                            <canvas id="purposeChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Department Visits</h3>
                        </div>
                        <div class="chart-canvas-container small">
                            <canvas id="deptChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Visits Section -->
        <div class="dashboard-content" id="active-visitsSection" style="display: none;">
            <h1 class="page-title">Active Visits</h1>
            <p class="page-subtitle">Visitors currently in the building</p>

            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">Currently Checked In</h3>
                    <div class="table-actions">
                        <button class="btn btn-primary btn-sm" onclick="printAllBadges()">
                            <i class="bi bi-printer"></i> Print All Badges
                        </button>
                    </div>
                </div>
                <table class="table table-hover" id="activeVisitsTable">
                    <thead>
                        <tr>
                            <th>Badge Number</th>
                            <th>Photo</th>
                            <th>Visitor Name</th>
                            <th>Company</th>
                            <th>Host</th>
                            <th>Department</th>
                            <th>Purpose</th>
                            <th>Check-In Time</th>
                            <th>Valid Until</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeVisitsTableBody">
                        <!-- Populated from active_visits view -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Visitors Section -->
        <div class="dashboard-content" id="visitorsSection" style="display: none;">
            <h1 class="page-title">Visitor Management</h1>
            <p class="page-subtitle">Complete visitor records</p>

            <!-- Filters -->
            <div class="filters-panel">
                <div class="filter-group">
                    <div class="filter-item">
                        <div class="filter-label">Visitor Type</div>
                        <select class="form-select" id="filterVisitorType">
                            <option value="">All Types</option>
                            <option value="new">New</option>
                            <option value="returning">Returning</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Company</div>
                        <input type="text" class="form-control" id="filterCompany" placeholder="Filter by company">
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Date Range</div>
                        <input type="date" class="form-control" id="filterStartDate">
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">&nbsp;</div>
                        <input type="date" class="form-control" id="filterEndDate">
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">&nbsp;</div>
                        <button class="btn btn-primary w-100" onclick="applyFilters()">Apply Filters</button>
                    </div>
                </div>
            </div>

            <!-- Visitors Table -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Visitors</h3>
                    <div class="table-actions">
                        <button class="btn btn-outline-secondary btn-sm" onclick="exportVisitors()">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
                <table class="table table-hover" id="allVisitorsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Company</th>
                            <th>Type</th>
                            <th>Total Visits</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allVisitorsTableBody">
                        <!-- Populated from visitors table -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pre-Scheduled Section -->
        <div class="dashboard-content" id="pre-scheduledSection" style="display: none;">
            <h1 class="page-title">Pre-Scheduled Visits</h1>
            <p class="page-subtitle">Manage upcoming scheduled visits</p>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-plus stat-card-icon"></i>
                        <div class="stat-value text-primary">1</div>
                        <div class="stat-label">Scheduled Today</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-week stat-card-icon"></i>
                        <div class="stat-value text-info">1</div>
                        <div class="stat-label">This Week</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-check-circle stat-card-icon"></i>
                        <div class="stat-value text-success">0</div>
                        <div class="stat-label">Checked In</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-clock-history stat-card-icon"></i>
                        <div class="stat-value text-warning">1</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">Scheduled Visits</h3>
                    <div class="table-actions">
                        <button class="btn btn-primary btn-sm" onclick="showScheduleModal()">
                            <i class="bi bi-plus-circle"></i> Schedule Visit
                        </button>
                    </div>
                </div>
                <table class="table table-hover" id="scheduledTable">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Visitor</th>
                            <th>Company</th>
                            <th>Scheduled Time</th>
                            <th>Host</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="scheduledTableBody">
                        <!-- Populated from pre_scheduled_visits table -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Employees Section -->
        <div class="dashboard-content" id="employeesSection" style="display: none;">
            <h1 class="page-title">Employee Directory</h1>
            <p class="page-subtitle">Manage employee records and host assignments</p>

            <!-- Department Stats -->
            <div class="table-container mb-4">
                <h4>Department Distribution</h4>
                <div class="dept-stats" id="deptStats">
                    <!-- Will be populated dynamically -->
                </div>
            </div>

            <!-- Employee Filters -->
            <div class="filters-panel">
                <div class="filter-group">
                    <div class="filter-item">
                        <div class="filter-label">Department</div>
                        <select class="form-select" id="deptFilter">
                            <option value="">All Departments</option>
                            <!-- Populated from departments table -->
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Status</div>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">Search</div>
                        <input type="text" class="form-control" placeholder="Name or Email" id="employeeSearch">
                    </div>
                    <div class="filter-item">
                        <div class="filter-label">&nbsp;</div>
                        <button class="btn btn-primary w-100" onclick="filterEmployees()">Search</button>
                    </div>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="table-container">
                <div class="table-header">
                    <h4>Employee List</h4>
                    <div class="table-actions">
                        <button class="btn btn-primary btn-sm" onclick="showAddEmployeeModal()">
                            <i class="bi bi-person-plus"></i> Add Employee
                        </button>
                    </div>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                        <!-- Populated from employees table -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Departments Section -->
        <div class="dashboard-content" id="departmentsSection" style="display: none;">
            <h1 class="page-title">Department Management</h1>
            <p class="page-subtitle">Manage organizational departments</p>

            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">All Departments</h3>
                    <div class="table-actions">
                        <button class="btn btn-primary btn-sm" onclick="showAddDepartmentModal()">
                            <i class="bi bi-plus-circle"></i> Add Department
                        </button>
                    </div>
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
                    <tbody id="departmentTableBody">
                        <!-- Populated from departments table -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="dashboard-content" id="reportsSection" style="display: none;">
            <h1 class="page-title">Reports & Analytics</h1>
            <p class="page-subtitle">Generate comprehensive reports</p>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="generateReport('daily')">
                        <i class="bi bi-calendar-day stat-card-icon"></i>
                        <h4>Daily Report</h4>
                        <p class="text-muted">Today's visitor summary</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="generateReport('weekly')">
                        <i class="bi bi-calendar-week stat-card-icon"></i>
                        <h4>Weekly Report</h4>
                        <p class="text-muted">7-day visitor analysis</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="generateReport('monthly')">
                        <i class="bi bi-calendar-month stat-card-icon"></i>
                        <h4>Monthly Report</h4>
                        <p class="text-muted">Monthly statistics</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" onclick="generateReport('security')">
                        <i class="bi bi-shield-check stat-card-icon"></i>
                        <h4>Security Report</h4>
                        <p class="text-muted">Security audit log</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <h4>Report Configuration</h4>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Report Type</label>
                        <select class="form-select" id="reportType">
                            <option>Visitor Summary</option>
                            <option>Department Analysis</option>
                            <option>Employee Activity</option>
                            <option>Security Log</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="reportStartDate">
                            <span class="input-group-text">to</span>
                            <input type="date" class="form-control" id="reportEndDate">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Format</label>
                        <select class="form-select" id="reportFormat">
                            <option>PDF</option>
                            <option>Excel</option>
                            <option>CSV</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary mt-3" onclick="generateCustomReport()">
                    <i class="bi bi-file-earmark-text"></i> Generate Report
                </button>
            </div>
        </div>

        <!-- Settings Section -->
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
                                <input type="text" class="form-control" value="Tom's World Philippines, Inc.">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Default Visit Duration (Hours)</label>
                                <input type="number" class="form-control" value="8" min="1" max="24">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Auto Check-out Time</label>
                                <select class="form-select">
                                    <option selected>After 8 hours</option>
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
                        <h4>Language Settings</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Language</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>English</td>
                                    <td>en</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-secondary" disabled>Default</button></td>
                                </tr>
                                <tr>
                                    <td>Filipino</td>
                                    <td>fil</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                                <tr>
                                    <td>简体中文</td>
                                    <td>zh-CN</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                                <tr>
                                    <td>繁體中文</td>
                                    <td>zh-TW</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                                <tr>
                                    <td>日本語</td>
                                    <td>ja</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td><button class="btn btn-sm btn-outline-primary">Edit</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
        // Database Data from SQL file
        const dbData = {
            visitors: [
                { visitor_id: 1, first_name: 'Lucky', last_name: 'Yunque', email: 'ithelpdesk@tomsworld.com.ph', phone: '09672406964', company: 'TOMS WORLD', visitor_type: 'new' },
                { visitor_id: 2, first_name: 'Chad', last_name: 'Cortes', email: 'itdesk@tomsworld.com.ph', phone: '09672406963', company: 'TOMS WORLD', visitor_type: 'new' }
            ],
            visits: [
                { visit_id: 1, visitor_id: 1, host_employee_id: 'HR002', badge_number: 'V-2025-5054', purpose: 'meeting', check_in_time: '2025-10-30 04:04:36', check_out_time: null, valid_until: '2025-10-30 12:04:36' },
                { visit_id: 2, visitor_id: 2, host_employee_id: 'MRK001', badge_number: 'V-2025-2877', purpose: 'interview', check_in_time: '2025-10-30 04:18:04', check_out_time: null, valid_until: '2025-10-30 12:18:04' }
            ],
            employees: [
                { employee_id: 'HR002', name: 'Kevin Harris', email: 'k.harris@company.com', department_code: 'HR' },
                { employee_id: 'MRK001', name: 'Andrew Wright', email: 'a.wright@company.com', department_code: 'MRK' }
            ],
            departments: [
                { department_code: 'ADM', name: 'Admin' },
                { department_code: 'BDD', name: 'Design & Construction' },
                { department_code: 'CRT', name: 'Creatives' },
                { department_code: 'ED', name: 'Ent. Risk Management' },
                { department_code: 'EXE', name: 'Executive' },
                { department_code: 'FIN', name: 'Finance' },
                { department_code: 'HR', name: 'Human Resource' },
                { department_code: 'IMP', name: 'Importation' },
                { department_code: 'ITSD', name: 'Information Technology & Services' },
                { department_code: 'MER', name: 'Audit & Merchandising' },
                { department_code: 'MRK', name: 'Marketing' },
                { department_code: 'ODSM', name: 'Org. Development & Strat. Mngt.' },
                { department_code: 'OP', name: 'Operations' },
                { department_code: 'PA', name: 'Pan Asia HR' },
                { department_code: 'SD', name: 'Stocks Department' },
                { department_code: 'SPD', name: 'Special Projects' },
                { department_code: 'TD', name: 'Technical' },
                { department_code: 'WLD', name: 'Warehouse & Logistics' }
            ],
            pre_scheduled_visits: [
                { booking_id: 1, booking_code: 'MEET-2024-001', visitor_name: 'Alice Johnson', visitor_email: 'alice.johnson@techsolutions.com', visitor_company: 'Tech Solutions Inc.', host_employee_id: 'ADM001', scheduled_time: '2025-10-29 18:06:35', purpose: 'Sales Meeting', status: 'scheduled' }
            ]
        };

        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            initializeCharts();
            loadActiveVisits();
        });

        // Load Dashboard Data
        function loadDashboardData() {
            // Recent Activity
            const recentActivity = document.getElementById('recentActivityTable');
            if (recentActivity) {
                recentActivity.innerHTML = '';
                dbData.visits.forEach(visit => {
                    const visitor = dbData.visitors.find(v => v.visitor_id === visit.visitor_id);
                    const host = dbData.employees.find(e => e.employee_id === visit.host_employee_id);
                    
                    recentActivity.innerHTML += `
                        <tr>
                            <td><span class="badge-number">${visit.badge_number}</span></td>
                            <td>${visitor.first_name} ${visitor.last_name}</td>
                            <td>${visitor.company}</td>
                            <td>${host ? host.name : 'N/A'}</td>
                            <td><span class="purpose-badge ${visit.purpose}">${visit.purpose}</span></td>
                            <td>${new Date(visit.check_in_time).toLocaleTimeString()}</td>
                            <td><span class="status-badge checked-in">Checked In</span></td>
                        </tr>
                    `;
                });
            }
        }

        // Load Active Visits
        function loadActiveVisits() {
            const activeTable = document.getElementById('activeVisitsTableBody');
            if (activeTable) {
                activeTable.innerHTML = '';
                dbData.visits.filter(v => !v.check_out_time).forEach(visit => {
                    const visitor = dbData.visitors.find(v => v.visitor_id === visit.visitor_id);
                    const host = dbData.employees.find(e => e.employee_id === visit.host_employee_id);
                    const dept = host ? dbData.departments.find(d => d.department_code === host.department_code) : null;
                    
                    activeTable.innerHTML += `
                        <tr>
                            <td><span class="badge-number">${visit.badge_number}</span></td>
                            <td>
                                <div class="visitor-photo">
                                    <i class="bi bi-person-circle" style="font-size: 1.5em; color: #dee2e6;"></i>
                                </div>
                            </td>
                            <td><strong>${visitor.first_name} ${visitor.last_name}</strong></td>
                            <td>${visitor.company}</td>
                            <td>${host ? host.name : 'N/A'}</td>
                            <td>${dept ? dept.name : 'N/A'}</td>
                            <td><span class="purpose-badge ${visit.purpose}">${visit.purpose}</span></td>
                            <td>${new Date(visit.check_in_time).toLocaleString()}</td>
                            <td>${new Date(visit.valid_until).toLocaleString()}</td>
                            <td>
                                <button class="action-btn view" onclick="viewVisitDetails(${visit.visit_id})">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn delete" onclick="checkOutVisitor(${visit.visit_id})">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            // Update active visit count
            const activeCount = dbData.visits.filter(v => !v.check_out_time).length;
            document.getElementById('activeVisitCount').textContent = activeCount;
            document.getElementById('currentlyIn').textContent = activeCount;
        }

        // Load All Visitors
        function loadAllVisitors() {
            const visitorsTable = document.getElementById('allVisitorsTableBody');
            if (visitorsTable) {
                visitorsTable.innerHTML = '';
                dbData.visitors.forEach(visitor => {
                    const visitCount = dbData.visits.filter(v => v.visitor_id === visitor.visitor_id).length;
                    const lastVisit = dbData.visits
                        .filter(v => v.visitor_id === visitor.visitor_id)
                        .sort((a, b) => new Date(b.check_in_time) - new Date(a.check_in_time))[0];
                    
                    visitorsTable.innerHTML += `
                        <tr>
                            <td>${visitor.visitor_id}</td>
                            <td>
                                <div class="visitor-photo">
                                    <i class="bi bi-person-circle" style="font-size: 1.5em; color: #dee2e6;"></i>
                                </div>
                            </td>
                            <td><strong>${visitor.first_name} ${visitor.last_name}</strong></td>
                            <td>${visitor.email}</td>
                            <td>${visitor.phone}</td>
                            <td>${visitor.company}</td>
                            <td><span class="badge bg-info">${visitor.visitor_type}</span></td>
                            <td>${visitCount}</td>
                            <td>${lastVisit ? new Date(lastVisit.check_in_time).toLocaleDateString() : 'N/A'}</td>
                            <td>
                                <button class="action-btn view" onclick="viewVisitor(${visitor.visitor_id})">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn edit" onclick="editVisitor(${visitor.visitor_id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                // Initialize DataTable
                if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#allVisitorsTable')) {
                    $('#allVisitorsTable').DataTable();
                }
            }
        }

        // Load Scheduled Visits
        function loadScheduledVisits() {
            const scheduledTable = document.getElementById('scheduledTableBody');
            if (scheduledTable) {
                scheduledTable.innerHTML = '';
                dbData.pre_scheduled_visits.forEach(visit => {
                    const statusBadge = visit.status === 'scheduled' 
                        ? '<span class="badge bg-warning">Scheduled</span>'
                        : visit.status === 'checked_in' 
                        ? '<span class="badge bg-success">Checked In</span>'
                        : '<span class="badge bg-secondary">Cancelled</span>';
                    
                    scheduledTable.innerHTML += `
                        <tr>
                            <td><span class="badge bg-secondary">${visit.booking_code}</span></td>
                            <td>${visit.visitor_name}</td>
                            <td>${visit.visitor_company}</td>
                            <td>${new Date(visit.scheduled_time).toLocaleString()}</td>
                            <td>${visit.host_employee_id}</td>
                            <td>${visit.purpose}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewScheduledVisit('${visit.booking_code}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="checkInScheduledVisit('${visit.booking_code}')">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        }

        // Load Employee Directory
        function loadEmployees() {
            const employeeTable = document.getElementById('employeeTableBody');
            if (employeeTable) {
                employeeTable.innerHTML = '';
                
                // Get all employees from database (sample shown)
                const allEmployees = [
                    { employee_id: 'ADM001', name: 'John Smith', email: 'j.smith@company.com', department_code: 'ADM', is_active: 1 },
                    { employee_id: 'HR002', name: 'Kevin Harris', email: 'k.harris@company.com', department_code: 'HR', is_active: 1 },
                    { employee_id: 'MRK001', name: 'Andrew Wright', email: 'a.wright@company.com', department_code: 'MRK', is_active: 1 },
                    // Add more employees as needed
                ];

                allEmployees.forEach(emp => {
                    const dept = dbData.departments.find(d => d.department_code === emp.department_code);
                    const visitCount = dbData.visits.filter(v => v.host_employee_id === emp.employee_id).length;
                    
                    employeeTable.innerHTML += `
                        <tr>
                            <td>${emp.employee_id}</td>
                            <td><strong>${emp.name}</strong></td>
                            <td>${emp.email}</td>
                            <td>${dept ? dept.name : 'N/A'}</td>
                            <td>
                                <span class="badge ${emp.is_active ? 'bg-success' : 'bg-secondary'}">
                                    ${emp.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                            <td>${visitCount}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewEmployee('${emp.employee_id}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editEmployee('${emp.employee_id}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                // Load department filter
                const deptFilter = document.getElementById('deptFilter');
                if (deptFilter && deptFilter.options.length === 1) {
                    dbData.departments.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.department_code;
                        option.textContent = dept.name;
                        deptFilter.appendChild(option);
                    });
                }
            }
        }

        // Load Departments
        function loadDepartments() {
            const deptTable = document.getElementById('departmentTableBody');
            if (deptTable) {
                deptTable.innerHTML = '';
                
                // Get employee count for each department (simplified)
                const employeeCounts = {
                    'ADM': 3, 'BDD': 2, 'CRT': 3, 'ED': 2, 'EXE': 2,
                    'FIN': 3, 'HR': 3, 'IMP': 2, 'ITSD': 4, 'MER': 2,
                    'MRK': 3, 'ODSM': 2, 'OP': 3, 'PA': 2, 'SD': 2,
                    'SPD': 2, 'TD': 2, 'WLD': 3
                };

                dbData.departments.forEach(dept => {
                    const empCount = employeeCounts[dept.department_code] || 0;
                    const visitCount = dbData.visits.filter(v => {
                        const emp = dbData.employees.find(e => e.employee_id === v.host_employee_id);
                        return emp && emp.department_code === dept.department_code;
                    }).length;
                    
                    deptTable.innerHTML += `
                        <tr>
                            <td><span class="badge bg-secondary">${dept.department_code}</span></td>
                            <td><strong>${dept.name}</strong></td>
                            <td>${empCount}</td>
                            <td>${visitCount}</td>
                            <td>2025-10-28</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewDepartment('${dept.department_code}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editDepartment('${dept.department_code}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            // Load department stats
            const deptStats = document.getElementById('deptStats');
            if (deptStats) {
                deptStats.innerHTML = '';
                const mainDepts = ['ADM', 'HR', 'MRK', 'ITSD', 'FIN', 'OP'];
                mainDepts.forEach(code => {
                    const dept = dbData.departments.find(d => d.department_code === code);
                    if (dept) {
                        deptStats.innerHTML += `
                            <div class="dept-stat-item">
                                <div class="dept-stat-value">${Math.floor(Math.random() * 10) + 2}</div>
                                <div class="dept-stat-label">${dept.name}</div>
                            </div>
                        `;
                    }
                });
            }
        }

        // Initialize Charts
        function initializeCharts() {
            // Purpose Chart
            const purposeCtx = document.getElementById('purposeChart');
            if (purposeCtx) {
                const purposeCounts = {};
                dbData.visits.forEach(visit => {
                    purposeCounts[visit.purpose] = (purposeCounts[visit.purpose] || 0) + 1;
                });

                new Chart(purposeCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(purposeCounts),
                        datasets: [{
                            label: 'Visit Purpose',
                            data: Object.values(purposeCounts),
                            backgroundColor: ['#3498db', '#9b59b6', '#e74c3c', '#f39c12', '#27ae60']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Department Chart
            const deptCtx = document.getElementById('deptChart');
            if (deptCtx) {
                const deptCounts = { 'HR': 1, 'Marketing': 1 };
                
                new Chart(deptCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(deptCounts),
                        datasets: [{
                            data: Object.values(deptCounts),
                            backgroundColor: ['#f39c12', '#3498db', '#27ae60', '#e74c3c', '#9b59b6']
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
                'active-visits': 'active-visitsSection',
                'visitors': 'visitorsSection',
                'pre-scheduled': 'pre-scheduledSection',
                'employees': 'employeesSection',
                'departments': 'departmentsSection',
                'reports': 'reportsSection',
                'settings': 'settingsSection'
            };
            
            if (sectionMap[section]) {
                document.getElementById(sectionMap[section]).style.display = 'block';
                
                // Add active class to clicked item
                event.target.closest('.sidebar-item').classList.add('active');
                
                // Load section-specific data
                switch(section) {
                    case 'active-visits':
                        loadActiveVisits();
                        break;
                    case 'visitors':
                        loadAllVisitors();
                        break;
                    case 'pre-scheduled':
                        loadScheduledVisits();
                        break;
                    case 'employees':
                        loadEmployees();
                        break;
                    case 'departments':
                        loadDepartments();
                        break;
                }
            }
        }

        // View Visit Details
        function viewVisitDetails(visitId) {
            const visit = dbData.visits.find(v => v.visit_id === visitId);
            const visitor = dbData.visitors.find(v => v.visitor_id === visit.visitor_id);
            const host = dbData.employees.find(e => e.employee_id === visit.host_employee_id);
            
            Swal.fire({
                title: 'Visit Details',
                html: `
                    <div class="text-start">
                        <p><strong>Badge Number:</strong> <span class="badge-number">${visit.badge_number}</span></p>
                        <p><strong>Visitor:</strong> ${visitor.first_name} ${visitor.last_name}</p>
                        <p><strong>Company:</strong> ${visitor.company}</p>
                        <p><strong>Email:</strong> ${visitor.email}</p>
                        <p><strong>Phone:</strong> ${visitor.phone}</p>
                        <p><strong>Host:</strong> ${host ? host.name : 'N/A'}</p>
                        <p><strong>Purpose:</strong> <span class="purpose-badge ${visit.purpose}">${visit.purpose}</span></p>
                        <p><strong>Check-In:</strong> ${new Date(visit.check_in_time).toLocaleString()}</p>
                        <p><strong>Valid Until:</strong> ${new Date(visit.valid_until).toLocaleString()}</p>
                        <p><strong>Status:</strong> <span class="status-badge ${visit.check_out_time ? 'checked-out' : 'checked-in'}">${visit.check_out_time ? 'Checked Out' : 'Checked In'}</span></p>
                    </div>
                `,
                confirmButtonColor: '#f39c12',
                showCancelButton: true,
                confirmButtonText: 'Print Badge',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    printBadge(visit.badge_number);
                }
            });
        }

        // Check Out Visitor
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
                    const visit = dbData.visits.find(v => v.visit_id === visitId);
                    if (visit) {
                        visit.check_out_time = new Date().toISOString();
                        loadActiveVisits();
                        loadDashboardData();
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Visitor checked out successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }

        // View Visitor
        function viewVisitor(visitorId) {
            const visitor = dbData.visitors.find(v => v.visitor_id === visitorId);
            const visits = dbData.visits.filter(v => v.visitor_id === visitorId);
            
            let visitHistory = visits.map(visit => {
                const host = dbData.employees.find(e => e.employee_id === visit.host_employee_id);
                return `
                    <tr>
                        <td>${visit.badge_number}</td>
                        <td>${new Date(visit.check_in_time).toLocaleDateString()}</td>
                        <td>${host ? host.name : 'N/A'}</td>
                        <td><span class="purpose-badge ${visit.purpose}">${visit.purpose}</span></td>
                    </tr>
                `;
            }).join('');
            
            Swal.fire({
                title: 'Visitor Profile',
                width: '600px',
                html: `
                    <div class="text-start">
                        <h5>${visitor.first_name} ${visitor.last_name}</h5>
                        <p><strong>Company:</strong> ${visitor.company}</p>
                        <p><strong>Email:</strong> ${visitor.email}</p>
                        <p><strong>Phone:</strong> ${visitor.phone}</p>
                        <p><strong>Type:</strong> <span class="badge bg-info">${visitor.visitor_type}</span></p>
                        <hr>
                        <h6>Visit History (${visits.length} visits)</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Badge</th>
                                    <th>Date</th>
                                    <th>Host</th>
                                    <th>Purpose</th>
                                </tr>
                            </thead>
                            <tbody>${visitHistory}</tbody>
                        </table>
                    </div>
                `,
                confirmButtonColor: '#f39c12'
            });
        }

        // Edit Visitor
        function editVisitor(visitorId) {
            const visitor = dbData.visitors.find(v => v.visitor_id === visitorId);
            
            Swal.fire({
                title: 'Edit Visitor Information',
                html: `
                    <form id="editVisitorForm">
                        <div class="mb-3 text-start">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" value="${visitor.first_name}">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" value="${visitor.last_name}">
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
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" id="editCompany" value="${visitor.company}">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Visitor Type</label>
                            <select class="form-select" id="editType">
                                <option value="new" ${visitor.visitor_type === 'new' ? 'selected' : ''}>New</option>
                                <option value="returning" ${visitor.visitor_type === 'returning' ? 'selected' : ''}>Returning</option>
                                <option value="delivery" ${visitor.visitor_type === 'delivery' ? 'selected' : ''}>Delivery</option>
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Save Changes',
                confirmButtonColor: '#f39c12',
                preConfirm: () => {
                    visitor.first_name = document.getElementById('editFirstName').value;
                    visitor.last_name = document.getElementById('editLastName').value;
                    visitor.email = document.getElementById('editEmail').value;
                    visitor.phone = document.getElementById('editPhone').value;
                    visitor.company = document.getElementById('editCompany').value;
                    visitor.visitor_type = document.getElementById('editType').value;
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    loadAllVisitors();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Visitor information updated',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        // View Scheduled Visit
        function viewScheduledVisit(bookingCode) {
            const visit = dbData.pre_scheduled_visits.find(v => v.booking_code === bookingCode);
            
            Swal.fire({
                title: 'Scheduled Visit Details',
                html: `
                    <div class="text-start">
                        <p><strong>Booking Code:</strong> <span class="badge bg-secondary">${visit.booking_code}</span></p>
                        <p><strong>Visitor:</strong> ${visit.visitor_name}</p>
                        <p><strong>Email:</strong> ${visit.visitor_email || 'N/A'}</p>
                        <p><strong>Company:</strong> ${visit.visitor_company}</p>
                        <p><strong>Host:</strong> ${visit.host_employee_id}</p>
                        <p><strong>Scheduled Time:</strong> ${new Date(visit.scheduled_time).toLocaleString()}</p>
                        <p><strong>Purpose:</strong> ${visit.purpose}</p>
                        <p><strong>Status:</strong> <span class="badge bg-warning">${visit.status}</span></p>
                    </div>
                `,
                confirmButtonColor: '#f39c12'
            });
        }

        // Check In Scheduled Visit
        function checkInScheduledVisit(bookingCode) {
            Swal.fire({
                title: 'Check In Visitor',
                text: 'Proceed with check-in for this scheduled visit?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                confirmButtonText: 'Check In'
            }).then((result) => {
                if (result.isConfirmed) {
                    const visit = dbData.pre_scheduled_visits.find(v => v.booking_code === bookingCode);
                    if (visit) {
                        visit.status = 'checked_in';
                        loadScheduledVisits();
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Visitor checked in successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }

        // Show Schedule Modal
        function showScheduleModal() {
            const employeeOptions = dbData.employees.map(emp => 
                `<option value="${emp.employee_id}">${emp.name} - ${emp.department_code}</option>`
            ).join('');
            
            Swal.fire({
                title: 'Schedule New Visit',
                html: `
                    <form>
                        <div class="mb-3 text-start">
                            <label class="form-label">Visitor Name</label>
                            <input type="text" class="form-control" id="schedVisitorName" placeholder="Enter visitor name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="schedEmail" placeholder="visitor@email.com">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" id="schedCompany" placeholder="Company name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Host</label>
                            <select class="form-select" id="schedHost">
                                ${employeeOptions}
                            </select>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" id="schedDateTime">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Purpose</label>
                            <select class="form-select" id="schedPurpose">
                                <option value="Meeting">Meeting</option>
                                <option value="Interview">Interview</option>
                                <option value="Training">Training</option>
                                <option value="Tour">Tour</option>
                                <option value="Event">Event</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Schedule Visit',
                confirmButtonColor: '#f39c12',
                preConfirm: () => {
                    const newScheduled = {
                        booking_code: `BOOK-${Date.now()}`,
                        visitor_name: document.getElementById('schedVisitorName').value,
                        visitor_email: document.getElementById('schedEmail').value,
                        visitor_company: document.getElementById('schedCompany').value,
                        host_employee_id: document.getElementById('schedHost').value,
                        scheduled_time: document.getElementById('schedDateTime').value,
                        purpose: document.getElementById('schedPurpose').value,
                        status: 'scheduled'
                    };
                    
                    if (!newScheduled.visitor_name || !newScheduled.scheduled_time) {
                        Swal.showValidationMessage('Please fill in all required fields');
                        return false;
                    }
                    
                    return newScheduled;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    dbData.pre_scheduled_visits.push(result.value);
                    loadScheduledVisits();
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Visit scheduled successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        // View Employee
        function viewEmployee(employeeId) {
            const emp = dbData.employees.find(e => e.employee_id === employeeId);
            const dept = dbData.departments.find(d => d.department_code === emp.department_code);
            const hostedVisits = dbData.visits.filter(v => v.host_employee_id === employeeId);
            
            Swal.fire({
                title: 'Employee Details',
                html: `
                    <div class="text-start">
                        <p><strong>Employee ID:</strong> ${emp.employee_id}</p>
                        <p><strong>Name:</strong> ${emp.name}</p>
                        <p><strong>Email:</strong> ${emp.email}</p>
                        <p><strong>Department:</strong> ${dept ? dept.name : 'N/A'}</p>
                        <p><strong>Total Visits Hosted:</strong> ${hostedVisits.length}</p>
                    </div>
                `,
                confirmButtonColor: '#f39c12'
            });
        }

        // Add Employee Modal
        function showAddEmployeeModal() {
            const deptOptions = dbData.departments.map(dept => 
                `<option value="${dept.department_code}">${dept.name}</option>`
            ).join('');
            
            Swal.fire({
                title: 'Add New Employee',
                html: `
                    <form>
                        <div class="mb-3 text-start">
                            <label class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="newEmpId" placeholder="E001">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="newEmpName" placeholder="Full name">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="newEmpEmail" placeholder="email@company.com">
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label">Department</label>
                            <select class="form-select" id="newEmpDept">
                                ${deptOptions}
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Add Employee',
                confirmButtonColor: '#f39c12'
            });
        }

        // View Department
        function viewDepartment(deptCode) {
            const dept = dbData.departments.find(d => d.department_code === deptCode);
            
            Swal.fire({
                title: 'Department Details',
                html: `
                    <div class="text-start">
                        <p><strong>Department Code:</strong> ${dept.department_code}</p>
                        <p><strong>Department Name:</strong> ${dept.name}</p>
                        <p><strong>Created:</strong> 2025-10-28</p>
                    </div>
                `,
                confirmButtonColor: '#f39c12'
            });
        }

        // Print Badge
        function printBadge(badgeNumber) {
            Swal.fire({
                title: 'Print Badge',
                text: `Printing badge ${badgeNumber}...`,
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Print All Badges
        function printAllBadges() {
            const activeVisits = dbData.visits.filter(v => !v.check_out_time);
            
            Swal.fire({
                title: 'Print All Active Badges',
                text: `Print ${activeVisits.length} visitor badges?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f39c12',
                confirmButtonText: 'Print All'
            });
        }

        // Export Visitors
        function exportVisitors() {
            Swal.fire({
                title: 'Export Visitors',
                text: 'Select export format',
                input: 'select',
                inputOptions: {
                    'csv': 'CSV',
                    'excel': 'Excel',
                    'pdf': 'PDF'
                },
                inputPlaceholder: 'Select format',
                showCancelButton: true,
                confirmButtonColor: '#f39c12'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: `Exporting as ${result.value.toUpperCase()}...`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        // Generate Report
        function generateReport(type) {
            Swal.fire({
                title: `Generate ${type.charAt(0).toUpperCase() + type.slice(1)} Report`,
                text: 'Report generation in progress...',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Report generated successfully',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        }

        // Generate Custom Report
        function generateCustomReport() {
            const reportType = document.getElementById('reportType').value;
            const format = document.getElementById('reportFormat').value;
            
            Swal.fire({
                title: 'Generating Report',
                html: `Generating ${reportType} in ${format} format...`,
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Apply Filters
        function applyFilters() {
            // Get filter values
            const visitorType = document.getElementById('filterVisitorType').value;
            const company = document.getElementById('filterCompany').value;
            const startDate = document.getElementById('filterStartDate').value;
            const endDate = document.getElementById('filterEndDate').value;
            
            // Apply filters to table (simplified)
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Filters applied',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Filter Employees
        function filterEmployees() {
            const dept = document.getElementById('deptFilter').value;
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('employeeSearch').value.toLowerCase();
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Filters applied',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Global Search
        function performGlobalSearch() {
            const searchTerm = document.getElementById('globalSearch').value.toLowerCase();
            console.log('Searching for:', searchTerm);
        }

        // Refresh Dashboard
        function refreshDashboard() {
            loadDashboardData();
            loadActiveVisits();
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Dashboard refreshed',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Auto-refresh active visits every 30 seconds
        setInterval(() => {
            if (document.getElementById('active-visitsSection').style.display !== 'none') {
                loadActiveVisits();
            }
        }, 30000);

        // // File: api/index.php - Main API Router

        // header("Access-Control-Allow-Origin: *");
        // header("Content-Type: application/json; charset=UTF-8");
        // header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
        // header("Access-Control-Max-Age: 3600");
        // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        // include_once '../config/database.php';
        // include_once '../models/Visitor.php';
        // include_once '../models/Visit.php';
        // include_once '../models/Employee.php';
        // include_once '../models/PreScheduledVisit.php';
        // include_once '../models/Department.php';
        // include_once '../controllers/DashboardController.php';

        // $database = new Database();
        // $db = $database->getConnection();

        // $request_method = $_SERVER["REQUEST_METHOD"];
        // $request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        // $endpoint = isset($request_uri[2]) ? $request_uri[2] : '';
        // $id = isset($request_uri[3]) ? $request_uri[3] : null;

        // // Route requests based on endpoint
        // switch($endpoint) {
        //     case 'dashboard':
        //         $controller = new DashboardController($db);
        //         if($request_method == "GET") {
        //             $stats = $controller->getDashboardStats();
        //             $recent = $controller->getRecentActivity();
        //             echo json_encode([
        //                 "success" => true,
        //                 "stats" => $stats,
        //                 "recent_activity" => $recent
        //             ]);
        //         }
        //         break;

        //     case 'visitors':
        //         $visitor = new Visitor($db);
                
        //         switch($request_method) {
        //             case 'GET':
        //                 if($id) {
        //                     $visitor->visitor_id = $id;
        //                     if($visitor->readOne()) {
        //                         echo json_encode([
        //                             "success" => true,
        //                             "data" => $visitor
        //                         ]);
        //                     } else {
        //                         echo json_encode([
        //                             "success" => false,
        //                             "message" => "Visitor not found"
        //                         ]);
        //                     }
        //                 } else {
        //                     $stmt = $visitor->read();
        //                     $visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                     echo json_encode([
        //                         "success" => true,
        //                         "data" => $visitors
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'POST':
        //                 $data = json_decode(file_get_contents("php://input"));
                        
        //                 $visitor->first_name = $data->first_name;
        //                 $visitor->last_name = $data->last_name;
        //                 $visitor->email = $data->email;
        //                 $visitor->phone = $data->phone;
        //                 $visitor->company = $data->company;
        //                 $visitor->photo = isset($data->photo) ? $data->photo : null;
        //                 $visitor->visitor_type = $data->visitor_type;
                        
        //                 if($visitor->create()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Visitor created successfully",
        //                         "visitor_id" => $visitor->visitor_id
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to create visitor"
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'PUT':
        //                 $data = json_decode(file_get_contents("php://input"));
                        
        //                 $visitor->visitor_id = $id;
        //                 $visitor->first_name = $data->first_name;
        //                 $visitor->last_name = $data->last_name;
        //                 $visitor->email = $data->email;
        //                 $visitor->phone = $data->phone;
        //                 $visitor->company = $data->company;
        //                 $visitor->visitor_type = $data->visitor_type;
                        
        //                 if($visitor->update()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Visitor updated successfully"
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to update visitor"
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'DELETE':
        //                 $visitor->visitor_id = $id;
                        
        //                 if($visitor->delete()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Visitor deleted successfully"
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to delete visitor"
        //                     ]);
        //                 }
        //                 break;
        //         }
        //         break;

        //     case 'visits':
        //         $visit = new Visit($db);
                
        //         switch($request_method) {
        //             case 'GET':
        //                 if(isset($_GET['active'])) {
        //                     $stmt = $visit->getActiveVisits();
        //                     $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                     echo json_encode([
        //                         "success" => true,
        //                         "data" => $visits
        //                     ]);
        //                 } else {
        //                     $stmt = $visit->getAllVisits();
        //                     $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                     echo json_encode([
        //                         "success" => true,
        //                         "data" => $visits
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'POST':
        //                 if(isset($_GET['action']) && $_GET['action'] == 'checkin') {
        //                     $data = json_decode(file_get_contents("php://input"));
                            
        //                     $visit->visitor_id = $data->visitor_id;
        //                     $visit->host_employee_id = $data->host_employee_id;
        //                     $visit->purpose = $data->purpose;
        //                     $visit->additional_notes = isset($data->additional_notes) ? $data->additional_notes : null;
        //                     $visit->terms_accepted = isset($data->terms_accepted) ? $data->terms_accepted : 1;
        //                     $visit->photo_consent = isset($data->photo_consent) ? $data->photo_consent : 1;
                            
        //                     if($visit->checkIn()) {
        //                         echo json_encode([
        //                             "success" => true,
        //                             "message" => "Visitor checked in successfully",
        //                             "badge_number" => $visit->badge_number,
        //                             "visit_id" => $visit->visit_id
        //                         ]);
        //                     } else {
        //                         echo json_encode([
        //                             "success" => false,
        //                             "message" => "Unable to check in visitor"
        //                         ]);
        //                     }
        //                 } elseif(isset($_GET['action']) && $_GET['action'] == 'checkout') {
        //                     $data = json_decode(file_get_contents("php://input"));
                            
        //                     $visit->visit_id = $data->visit_id;
                            
        //                     if($visit->checkOut()) {
        //                         echo json_encode([
        //                             "success" => true,
        //                             "message" => "Visitor checked out successfully"
        //                         ]);
        //                     } else {
        //                         echo json_encode([
        //                             "success" => false,
        //                             "message" => "Unable to check out visitor"
        //                         ]);
        //                     }
        //                 }
        //                 break;
        //         }
        //         break;

        //     case 'employees':
        //         $employee = new Employee($db);
                
        //         switch($request_method) {
        //             case 'GET':
        //                 if($id) {
        //                     $employee->employee_id = $id;
        //                     if($employee->readOne()) {
        //                         echo json_encode([
        //                             "success" => true,
        //                             "data" => $employee
        //                         ]);
        //                     } else {
        //                         echo json_encode([
        //                             "success" => false,
        //                             "message" => "Employee not found"
        //                         ]);
        //                     }
        //                 } else {
        //                     $stmt = $employee->read();
        //                     $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                     echo json_encode([
        //                         "success" => true,
        //                         "data" => $employees
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'POST':
        //                 $data = json_decode(file_get_contents("php://input"));
                        
        //                 $employee->employee_id = $data->employee_id;
        //                 $employee->name = $data->name;
        //                 $employee->email = $data->email;
        //                 $employee->department_code = $data->department_code;
        //                 $employee->is_active = isset($data->is_active) ? $data->is_active : 1;
                        
        //                 if($employee->create()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Employee created successfully"
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to create employee"
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'PUT':
        //                 $data = json_decode(file_get_contents("php://input"));
                        
        //                 $employee->employee_id = $id;
        //                 $employee->name = $data->name;
        //                 $employee->email = $data->email;
        //                 $employee->department_code = $data->department_code;
        //                 $employee->is_active = $data->is_active;
                        
        //                 if($employee->update()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Employee updated successfully"
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to update employee"
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'DELETE':
        //                 $employee->employee_id = $id;
                        
        //                 if($employee->delete()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Employee deleted successfully"
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to delete employee"
        //                     ]);
        //                 }
        //                 break;
        //         }
        //         break;

        //     case 'scheduled':
        //         $scheduled = new PreScheduledVisit($db);
                
        //         switch($request_method) {
        //             case 'GET':
        //                 if(isset($_GET['today'])) {
        //                     $stmt = $scheduled->getTodayScheduled();
        //                     $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                     echo json_encode([
        //                         "success" => true,
        //                         "data" => $visits
        //                     ]);
        //                 } else {
        //                     $stmt = $scheduled->read();
        //                     $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                     echo json_encode([
        //                         "success" => true,
        //                         "data" => $visits
        //                     ]);
        //                 }
        //                 break;
                        
        //             case 'POST':
        //                 $data = json_decode(file_get_contents("php://input"));
                        
        //                 $scheduled->visitor_name = $data->visitor_name;
        //                 $scheduled->visitor_email = isset($data->visitor_email) ? $data->visitor_email : null;
        //                 $scheduled->visitor_company = isset($data->visitor_company) ? $data->visitor_company : null;
        //                 $scheduled->host_employee_id = $data->host_employee_id;
        //                 $scheduled->scheduled_time = $data->scheduled_time;
        //                 $scheduled->purpose = $data->purpose;
                        
        //                 if($scheduled->create()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Visit scheduled successfully",
        //                         "booking_code" => $scheduled->booking_code
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to schedule visit"
        //                     ]);
        //                 }
        //                 break;
        //         }
        //         break;

        //     case 'departments':
        //         $department = new Department($db);
                
        //         switch($request_method) {
        //             case 'GET':
        //                 $stmt = $department->read();
        //                 $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //                 echo json_encode([
        //                     "success" => true,
        //                     "data" => $departments
        //                 ]);
        //                 break;
                        
        //             case 'POST':
        //                 $data = json_decode(file_get_contents("php://input"));
                        
        //                 $department->department_code = $data->department_code;
        //                 $department->name = $data->name;
                        
        //                 if($department->create()) {
        //                     echo json_encode([
        //                         "success" => true,
        //                         "message" => "Department created successfully"
        //                     ]);
        //                 } else {
        //                     echo json_encode([
        //                         "success" => false,
        //                         "message" => "Unable to create department"
        //                     ]);
        //                 }
        //                 break;
        //         }
        //         break;

        //     default:
        //         echo json_encode([
        //             "success" => false,
        //             "message" => "Invalid endpoint"
        //         ]);
        //         break;
        // }
    </script>

</body>

</html>