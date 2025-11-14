<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for dashboard
function getDashboardStats($conn) {
    $stats = array();
    
    // Total visitors today
    $sql = "SELECT COUNT(DISTINCT visitor_id) as today_total FROM visits WHERE DATE(check_in_time) = CURDATE()";
    $result = $conn->query($sql);
    $stats['today_total'] = $result->fetch_assoc()['today_total'];
    
    // Currently in building (active visits)
    $sql = "SELECT COUNT(*) as currently_in FROM visits WHERE check_out_time IS NULL";
    $result = $conn->query($sql);
    $stats['currently_in'] = $result->fetch_assoc()['currently_in'];
    
    // Scheduled today
    $sql = "SELECT COUNT(*) as scheduled_today FROM pre_scheduled_visits WHERE DATE(scheduled_time) = CURDATE()";
    $result = $conn->query($sql);
    $stats['scheduled_today'] = $result->fetch_assoc()['scheduled_today'];
    
    // Average visit duration
    $sql = "SELECT AVG(TIMESTAMPDIFF(HOUR, check_in_time, IFNULL(check_out_time, NOW()))) as avg_duration 
            FROM visits WHERE DATE(check_in_time) = CURDATE()";
    $result = $conn->query($sql);
    $avg = $result->fetch_assoc()['avg_duration'];
    $stats['avg_duration'] = $avg ? round($avg, 1) . 'h' : '0h';
    
    return $stats;
}

// Fetch recent activity
function getRecentActivity($conn) {
    $sql = "SELECT v.*, vi.first_name, vi.last_name, vi.company, e.name as host_name 
            FROM visits v 
            JOIN visitors vi ON v.visitor_id = vi.visitor_id 
            JOIN employees e ON v.host_employee_id = e.employee_id 
            ORDER BY v.check_in_time DESC LIMIT 10";
    
    $result = $conn->query($sql);
    $activities = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
    }
    
    return $activities;
}

// Fetch active visits
function getActiveVisits($conn) {
    $sql = "SELECT v.*, vi.first_name, vi.last_name, vi.company, vi.email, vi.phone,
            e.name as host_name, d.name as department_name
            FROM visits v 
            JOIN visitors vi ON v.visitor_id = vi.visitor_id 
            JOIN employees e ON v.host_employee_id = e.employee_id
            JOIN departments d ON e.department_code = d.department_code
            WHERE v.check_out_time IS NULL";
    
    $result = $conn->query($sql);
    $visits = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $visits[] = $row;
        }
    }
    
    return $visits;
}

// Fetch all visitors
function getAllVisitors($conn) {
    $sql = "SELECT vi.*, COUNT(v.visit_id) as total_visits, MAX(v.check_in_time) as last_visit 
            FROM visitors vi 
            LEFT JOIN visits v ON vi.visitor_id = v.visitor_id 
            GROUP BY vi.visitor_id";
    
    $result = $conn->query($sql);
    $visitors = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $visitors[] = $row;
        }
    }
    
    return $visitors;
}

// Fetch scheduled visits
function getScheduledVisits($conn) {
    $sql = "SELECT ps.*, e.name as host_name 
            FROM pre_scheduled_visits ps 
            JOIN employees e ON ps.host_employee_id = e.employee_id 
            WHERE ps.status = 'scheduled' 
            ORDER BY ps.scheduled_time";
    
    $result = $conn->query($sql);
    $visits = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $visits[] = $row;
        }
    }
    
    return $visits;
}

// Fetch employees
function getEmployees($conn) {
    $sql = "SELECT e.*, d.name as department_name, COUNT(v.visit_id) as total_visits 
            FROM employees e 
            JOIN departments d ON e.department_code = d.department_code
            LEFT JOIN visits v ON e.employee_id = v.host_employee_id
            GROUP BY e.employee_id";
    
    $result = $conn->query($sql);
    $employees = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
    }
    
    return $employees;
}

// Fetch departments
function getDepartments($conn) {
    $sql = "SELECT d.*, COUNT(DISTINCT e.employee_id) as employee_count, 
            COUNT(DISTINCT v.visit_id) as visit_count
            FROM departments d
            LEFT JOIN employees e ON d.department_code = e.department_code
            LEFT JOIN visits v ON e.employee_id = v.host_employee_id
            GROUP BY d.department_code";
    
    $result = $conn->query($sql);
    $departments = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }
    
    return $departments;
}

// Get visit purpose statistics
function getVisitPurposeStats($conn) {
    $sql = "SELECT purpose, COUNT(*) as count FROM visits GROUP BY purpose";
    
    $result = $conn->query($sql);
    $stats = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $stats[$row['purpose']] = $row['count'];
        }
    }
    
    return $stats;
}

// Handle AJAX requests
if(isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch($_GET['action']) {
        case 'dashboard_stats':
            echo json_encode(getDashboardStats($conn));
            break;
        case 'recent_activity':
            echo json_encode(getRecentActivity($conn));
            break;
        case 'active_visits':
            echo json_encode(getActiveVisits($conn));
            break;
        case 'all_visitors':
            echo json_encode(getAllVisitors($conn));
            break;
        case 'scheduled_visits':
            echo json_encode(getScheduledVisits($conn));
            break;
        case 'employees':
            echo json_encode(getEmployees($conn));
            break;
        case 'departments':
            echo json_encode(getDepartments($conn));
            break;
        case 'visit_purpose_stats':
            echo json_encode(getVisitPurposeStats($conn));
            break;
        case 'checkout':
            if(isset($_POST['visit_id'])) {
                $visit_id = $conn->real_escape_string($_POST['visit_id']);
                $sql = "UPDATE visits SET check_out_time = NOW() WHERE visit_id = $visit_id";
                if($conn->query($sql)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $conn->error]);
                }
            }
            break;
    }
    exit;
}

// Get initial data for page load
$dashboardStats = getDashboardStats($conn);
$recentActivity = getRecentActivity($conn);
$activeVisits = getActiveVisits($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS Admin Dashboard - Tom's World & Pan-Asia</title>
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

        /* Table Container */
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
            background: rgba(13, 202, 240, 0.1);
            color: #0dcaf0;
        }
        
        .purpose-badge.training {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .purpose-badge.tour {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .purpose-badge.event {
            background: rgba(128, 0, 128, 0.1);
            color: #800080;
        }

        .badge-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.85em;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Visitor Modal Styles */
        #viewVisitorModal .visitor-photo-container {
            position: relative;
            display: inline-block;
        }

        #viewVisitorModal .info-grid {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        #viewVisitorModal .info-grid .row {
            padding: 5px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        #viewVisitorModal .info-grid .row:last-child {
            border-bottom: none;
        }

        #viewVisitorModal .modal-header {
            background: linear-gradient(135deg, #f39c12, #1e9338);
        }

        /* All Visitors Modal Styles */
        #viewAllVisitorModal .info-section {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        #viewAllVisitorModal .stat-item {
            padding: 5px 0;
            font-size: 14px;
        }

        #viewAllVisitorModal .visit-history-section {
            max-height: 200px;
            overflow-y: auto;
        }

        .photo-section {
            position: relative;
        }

        .visitor-type-badge {
            margin: 10px 0;
        }

        /* Hover effect for action buttons */
        .action-btn.view:hover {
            color: #3498db;
            transform: scale(1.2);
            transition: all 0.3s ease;
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
                <span class="sidebar-badge" id="activeVisitCount"><?php echo $dashboardStats['currently_in']; ?></span>
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
                    <input type="text" placeholder="Search visitors, employees..." id="globalSearch">
                    <i class="bi bi-search"></i>
                </div>
            </div>
            <div class="topbar-right">
                <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"><?php echo $dashboardStats['currently_in']; ?></span>
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
                    <div class="quick-stat-value" id="todayTotal"><?php echo $dashboardStats['today_total']; ?></div>
                    <div class="quick-stat-label">Today's Visitors</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="currentlyIn"><?php echo $dashboardStats['currently_in']; ?></div>
                    <div class="quick-stat-label">Currently In Building</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="scheduledToday"><?php echo $dashboardStats['scheduled_today']; ?></div>
                    <div class="quick-stat-label">Scheduled Today</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" id="avgDuration"><?php echo $dashboardStats['avg_duration']; ?></div>
                    <div class="quick-stat-label">Avg. Visit Duration</div>
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
                        <?php foreach($recentActivity as $activity): ?>
                        <tr>
                            <td><span class="badge-number"><?php echo $activity['badge_number']; ?></span></td>
                            <td><?php echo $activity['first_name'] . ' ' . $activity['last_name']; ?></td>
                            <td><?php echo $activity['company']; ?></td>
                            <td><?php echo $activity['host_name']; ?></td>
                            <td><span class="purpose-badge <?php echo $activity['purpose']; ?>"><?php echo $activity['purpose']; ?></span></td>
                            <td><?php echo date('H:i:s', strtotime($activity['check_in_time'])); ?></td>
                            <td>
                                <?php if($activity['check_out_time']): ?>
                                    <span class="status-badge checked-out">Checked Out</span>
                                <?php else: ?>
                                    <span class="status-badge checked-in">Checked In</span>
                                <?php endif; ?>
                            </td>
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
                </div>
                <table class="table table-hover" id="activeVisitsTable">
                    <thead>
                        <tr>
                            <th>Badge Number</th>
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
                        <!-- Will be populated via AJAX -->
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
                            <th>ID</th>
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
                        <!-- Will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pre-Scheduled Section -->
        <div class="dashboard-content" id="pre-scheduledSection" style="display: none;">
            <h1 class="page-title">Pre-Scheduled Visits</h1>
            <p class="page-subtitle">Manage upcoming scheduled visits</p>

            <div class="table-container">
                <div class="table-header">
                    <h3 class="chart-title">Scheduled Visits</h3>
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
                        </tr>
                    </thead>
                    <tbody id="scheduledTableBody">
                        <!-- Will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Employees Section -->
        <div class="dashboard-content" id="employeesSection" style="display: none;">
            <h1 class="page-title">Employee Directory</h1>
            <p class="page-subtitle">Manage employee records and host assignments</p>

            <div class="table-container">
                <h4>Employee List</h4>
                <table class="table table-hover" id="employeeTable">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Total Visits Hosted</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                        <!-- Will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Departments Section -->
        <div class="dashboard-content" id="departmentsSection" style="display: none;">
            <h1 class="page-title">Department Management</h1>
            <p class="page-subtitle">Manage organizational departments</p>

            <div class="table-container">
                <h3 class="chart-title">All Departments</h3>
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
                    <tbody id="departmentTableBody">
                        <!-- Will be populated via AJAX -->
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
                    <div class="stat-card">
                        <i class="bi bi-calendar-day stat-card-icon"></i>
                        <h4>Daily Report</h4>
                        <p class="text-muted">Today's visitor summary</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-week stat-card-icon"></i>
                        <h4>Weekly Report</h4>
                        <p class="text-muted">7-day visitor analysis</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-calendar-month stat-card-icon"></i>
                        <h4>Monthly Report</h4>
                        <p class="text-muted">Monthly statistics</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <i class="bi bi-shield-check stat-card-icon"></i>
                        <h4>Security Report</h4>
                        <p class="text-muted">Security audit log</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="dashboard-content" id="settingsSection" style="display: none;">
            <h1 class="page-title">System Settings</h1>
            <p class="page-subtitle">Configure visitor management system</p>

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
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>

        <!-- Add this modal structure before closing body tag or after your main content -->
        <div class="modal fade" id="viewVisitorModal" tabindex="-1" aria-labelledby="viewVisitorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="viewVisitorModalLabel">
                            <i class="bi bi-person-badge"></i> Visitor Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Photo Column -->
                            <div class="col-md-4 text-center">
                                <div class="visitor-photo-container mb-3">
                                    <img id="modalVisitorPhoto" src="" alt="Visitor Photo" class="img-fluid rounded-circle border border-3 border-primary" style="max-width: 200px; height: 200px; object-fit: cover;">
                                </div>
                                <div class="badge bg-primary text-white p-2 mb-2">
                                    <i class="bi bi-card-text"></i> Badge: <span id="modalBadgeNumber"></span>
                                </div>
                            </div>
                            
                            <!-- Information Column -->
                            <div class="col-md-8">
                                <div class="visitor-info">
                                    <h4 class="mb-3 text-primary" id="modalVisitorName"></h4>
                                    
                                    <div class="info-grid">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-envelope"></i> Email:
                                            </div>
                                            <div class="col-sm-8" id="modalEmail"></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-telephone"></i> Phone:
                                            </div>
                                            <div class="col-sm-8" id="modalPhone"></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-building"></i> Company:
                                            </div>
                                            <div class="col-sm-8" id="modalCompany"></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-person-check"></i> Host:
                                            </div>
                                            <div class="col-sm-8" id="modalHost"></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-flag"></i> Purpose:
                                            </div>
                                            <div class="col-sm-8">
                                                <span class="badge bg-info text-white" id="modalPurpose"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-clock"></i> Check-In:
                                            </div>
                                            <div class="col-sm-8" id="modalCheckIn"></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-hourglass-split"></i> Valid Until:
                                            </div>
                                            <div class="col-sm-8" id="modalValidUntil"></div>
                                        </div>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-card-checklist"></i> Status:
                                            </div>
                                            <div class="col-sm-8" id="modalStatus"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="checkOutVisitorBtn" onclick="checkOutVisitor()" style="display: none;">
                            <i class="bi bi-box-arrow-right"></i> Check Out
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this modal for All Visitors section -->
        <div class="modal fade" id="viewAllVisitorModal" tabindex="-1" aria-labelledby="viewAllVisitorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #f39c12, #1e9338); color: white;">
                        <h5 class="modal-title" id="viewAllVisitorModalLabel">
                            <i class="bi bi-person-vcard"></i> Visitor Information
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Photo Section -->
                            <div class="col-md-4 text-center">
                                <div class="photo-section mb-3">
                                    <img id="allVisitorPhoto" src="" alt="Visitor Photo" class="rounded-circle shadow" style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #667eea;">
                                </div>
                                <div class="visitor-type-badge mb-2">
                                    <span class="badge bg-primary p-2" id="allVisitorType">
                                        <i class="bi bi-person-badge"></i> Type: <span id="visitorTypeText"></span>
                                    </span>
                                </div>
                                <div class="visit-stats p-3 bg-light rounded">
                                    <h6 class="text-muted mb-2">Visit Statistics</h6>
                                    <div class="stat-item">
                                        <i class="bi bi-bar-chart"></i>
                                        <strong>Total Visits:</strong> <span id="allVisitorTotalVisits">0</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="bi bi-calendar-check"></i>
                                        <strong>Last Visit:</strong> <span id="allVisitorLastVisit">N/A</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Details Section -->
                            <div class="col-md-8">
                                <div class="visitor-details">
                                    <h4 class="mb-3 text-primary border-bottom pb-2">
                                        <span id="allVisitorFullName"></span>
                                    </h4>
                                    
                                    <!-- Contact Information -->
                                    <div class="info-section mb-3">
                                        <h6 class="text-muted mb-2">Contact Information</h6>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-envelope-fill text-primary"></i> Email:
                                            </div>
                                            <div class="col-sm-8">
                                                <a href="#" id="allVisitorEmail" class="text-decoration-none"></a>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-telephone-fill text-primary"></i> Phone:
                                            </div>
                                            <div class="col-sm-8">
                                                <a href="#" id="allVisitorPhone" class="text-decoration-none"></a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Company Information -->
                                    <div class="info-section mb-3">
                                        <h6 class="text-muted mb-2">Organization</h6>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-building text-primary"></i> Company:
                                            </div>
                                            <div class="col-sm-8" id="allVisitorCompany"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Information -->
                                    <div class="info-section mb-3">
                                        <h6 class="text-muted mb-2">Additional Details</h6>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-calendar-plus text-primary"></i> First Registered:
                                            </div>
                                            <div class="col-sm-8" id="allVisitorCreated"></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-bold">
                                                <i class="bi bi-clock-history text-primary"></i> Last Updated:
                                            </div>
                                            <div class="col-sm-8" id="allVisitorUpdated"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Visit History Preview -->
                                    <div class="visit-history-section" id="visitHistorySection" style="display: none;">
                                        <h6 class="text-muted mb-2">Recent Visit History</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Purpose</th>
                                                        <th>Host</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recentVisitsTable">
                                                    <!-- Dynamic content -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="printVisitorCard()" title="Print Visitor Card">
                            <i class="bi bi-printer"></i> Print Card
                        </button>
                        <button type="button" class="btn btn-info" onclick="viewFullHistory()" id="viewHistoryBtn" title="View Full History">
                            <i class="bi bi-clock-history"></i> Full History
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    
    <script>
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

        // Load Active Visits
        function loadActiveVisits() {
            fetch('?action=active_visits')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('activeVisitsTableBody');
                    tbody.innerHTML = '';
                    
                    data.forEach(visit => {
                        tbody.innerHTML += `
                            <tr>
                                <td><span class="badge-number">${visit.badge_number}</span></td>
                                <td><strong>${visit.first_name} ${visit.last_name}</strong></td>
                                <td>${visit.company}</td>
                                <td>${visit.host_name}</td>
                                <td>${visit.department_name}</td>
                                <td><span class="purpose-badge ${visit.purpose}">${visit.purpose}</span></td>
                                <td>${new Date(visit.check_in_time).toLocaleString()}</td>
                                <td>${new Date(visit.valid_until).toLocaleString()}</td>
                                <td>
                                    <!-- To this: -->
                                    <button class="action-btn view" onclick="viewVisitorDetails(${visit.visit_id})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="checkOutVisitor(${visit.visit_id})">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    // Update badge count
                    document.getElementById('activeVisitCount').textContent = data.length;
                });
        }

        // Load All Visitors
        function loadAllVisitors() {
            fetch('?action=all_visitors')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('allVisitorsTableBody');
                    tbody.innerHTML = '';
                    
                    data.forEach(visitor => {
                        tbody.innerHTML +=
                            // <tr>
                            //     <td>${visitor.visitor_id}</td>
                            //     <td><strong>${visitor.first_name} ${visitor.last_name}</strong></td>
                            //     <td>${visitor.email}</td>
                            //     <td>${visitor.phone}</td>
                            //     <td>${visitor.company}</td>
                            //     <td><span class="badge bg-info">${visitor.visitor_type}</span></td>
                            //     <td>${visitor.total_visits}</td>
                            //     <td>${visitor.last_visit ? new Date(visitor.last_visit).toLocaleDateString() : 'N/A'}</td>
                            //     <td>
                            //         <button class="action-btn view" onclick="viewVisitor(${visitor.visitor_id})">
                            //             <i class="bi bi-eye"></i>
                            //         </button>
                            //     </td>
                            // </tr>
                         `
                            <tr>
                                <td>${visitor.visitor_id}</td>
                                <td><strong>${visitor.first_name} ${visitor.last_name}</strong></td>
                                <td>${visitor.email}</td>
                                <td>${visitor.phone}</td>
                                <td>${visitor.company}</td>
                                <td><span class="badge bg-info">${visitor.visitor_type}</span></td>
                                <td>${visitor.total_visits}</td>
                                <td>${visitor.last_visit ? new Date(visitor.last_visit).toLocaleDateString() : 'N/A'}</td>
                                <td>
                                    <button class="action-btn view" onclick="viewVisitor(${visitor.visitor_id})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    // Initialize DataTable if not already
                    if (!$.fn.DataTable.isDataTable('#allVisitorsTable')) {
                        $('#allVisitorsTable').DataTable();
                    }
                });
        }

        // Load Scheduled Visits
        function loadScheduledVisits() {
            fetch('?action=scheduled_visits')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('scheduledTableBody');
                    tbody.innerHTML = '';
                    
                    data.forEach(visit => {
                        tbody.innerHTML += `
                            <tr>
                                <td><span class="badge bg-secondary">${visit.booking_code}</span></td>
                                <td>${visit.visitor_name}</td>
                                <td>${visit.visitor_company || 'N/A'}</td>
                                <td>${new Date(visit.scheduled_time).toLocaleString()}</td>
                                <td>${visit.host_name}</td>
                                <td>${visit.purpose}</td>
                                <td><span class="badge bg-warning">Scheduled</span></td>
                            </tr>
                        `;
                    });
                });
        }

        // Load Employees
        function loadEmployees() {
            fetch('?action=employees')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('employeeTableBody');
                    tbody.innerHTML = '';
                    
                    data.forEach(emp => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${emp.employee_id}</td>
                                <td><strong>${emp.name}</strong></td>
                                <td>${emp.email}</td>
                                <td>${emp.department_name}</td>
                                <td>
                                    <span class="badge ${emp.is_active == 1 ? 'bg-success' : 'bg-secondary'}">
                                        ${emp.is_active == 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>${emp.total_visits || 0}</td>
                            </tr>
                        `;
                    });
                });
        }

        // Load Departments
        function loadDepartments() {
            fetch('?action=departments')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('departmentTableBody');
                    tbody.innerHTML = '';
                    
                    data.forEach(dept => {
                        tbody.innerHTML += `
                            <tr>
                                <td><span class="badge bg-secondary">${dept.department_code}</span></td>
                                <td><strong>${dept.name}</strong></td>
                                <td>${dept.employee_count || 0}</td>
                                <td>${dept.visit_count || 0}</td>
                                <td>${dept.created_at}</td>
                            </tr>
                        `;
                    });
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
                    fetch('?action=checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'visit_id=' + visitId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Visitor checked out successfully',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            loadActiveVisits();
                            refreshDashboard();
                        } else {
                            Swal.fire('Error', 'Failed to check out visitor', 'error');
                        }
                    });
                }
            });
        }

        // // View Visit Details
        // function viewVisitDetails(visitId) {
        //     // You can expand this function to show more details
        //     console.log('View details for visit:', visitId);
        // }

        // // View Visitor
        // function viewVisitor(visitorId) {
        //     // You can expand this function to show visitor profile
        //     console.log('View visitor:', visitorId);
        // }


        let currentVisitorData = null;

        function viewVisitor(visitorId) {
            // Fetch visitor details
            fetch('?action=all_visitors')
                .then(response => response.json())
                .then(data => {
                    const visitor = data.find(v => v.visitor_id == visitorId);
                    
                    if (visitor) {
                        currentVisitorData = visitor;
                        
                        // Update modal with visitor information
                        const photoSrc = visitor.photo || 'assets/images/default-avatar.png';
                        document.getElementById('allVisitorPhoto').src = photoSrc;
                        
                        // Set visitor type and badge color
                        const visitorType = visitor.visitor_type || 'new';
                        document.getElementById('visitorTypeText').textContent = visitorType.charAt(0).toUpperCase() + visitorType.slice(1);
                        
                        // Update type badge color based on type
                        const typeBadge = document.querySelector('#allVisitorType');
                        typeBadge.className = 'badge p-2';
                        switch(visitorType) {
                            case 'returning':
                                typeBadge.classList.add('bg-success');
                                break;
                            case 'delivery':
                                typeBadge.classList.add('bg-warning');
                                break;
                            default:
                                typeBadge.classList.add('bg-primary');
                        }
                        
                        // Set visitor information
                        document.getElementById('allVisitorFullName').textContent = 
                            `${visitor.first_name} ${visitor.last_name}`;
                        
                        // Contact Information
                        const email = visitor.email || 'Not provided';
                        document.getElementById('allVisitorEmail').textContent = email;
                        document.getElementById('allVisitorEmail').href = `mailto:${email}`;
                        
                        const phone = visitor.phone || 'Not provided';
                        document.getElementById('allVisitorPhone').textContent = phone;
                        document.getElementById('allVisitorPhone').href = `tel:${phone}`;
                        
                        // Company
                        document.getElementById('allVisitorCompany').textContent = 
                            visitor.company || 'Not specified';
                        
                        // Statistics
                        document.getElementById('allVisitorTotalVisits').textContent = 
                            visitor.total_visits || '1';
                        
                        // Format dates
                        if (visitor.last_visit) {
                            const lastVisitDate = new Date(visitor.last_visit);
                            document.getElementById('allVisitorLastVisit').textContent = 
                                lastVisitDate.toLocaleDateString();
                        } else {
                            document.getElementById('allVisitorLastVisit').textContent = 'Current';
                        }
                        
                        const createdDate = new Date(visitor.created_at);
                        document.getElementById('allVisitorCreated').textContent = 
                            createdDate.toLocaleString();
                        
                        const updatedDate = new Date(visitor.updated_at);
                        document.getElementById('allVisitorUpdated').textContent = 
                            updatedDate.toLocaleString();
                        
                        // Load recent visits if available
                        loadRecentVisits(visitorId);
                        
                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById('viewAllVisitorModal'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error fetching visitor details:', error);
                    Swal.fire('Error', 'Failed to load visitor details', 'error');
                });
        }

        function loadRecentVisits(visitorId) {
            // This would typically fetch from a visits endpoint
            // For now, showing a placeholder
            fetch(`?action=visitor_visits&visitor_id=${visitorId}`)
                .then(response => response.json())
                .then(visits => {
                    if (visits && visits.length > 0) {
                        const tbody = document.getElementById('recentVisitsTable');
                        tbody.innerHTML = '';
                        
                        // Show only last 5 visits
                        visits.slice(0, 5).forEach(visit => {
                            const row = `
                                <tr>
                                    <td>${new Date(visit.check_in_time).toLocaleDateString()}</td>
                                    <td><span class="badge bg-info">${visit.purpose}</span></td>
                                    <td>${visit.host_name || 'N/A'}</td>
                                    <td>
                                        ${visit.check_out_time ? 
                                            '<span class="badge bg-success">Completed</span>' : 
                                            '<span class="badge bg-warning">Active</span>'}
                                    </td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                        
                        document.getElementById('visitHistorySection').style.display = 'block';
                    }
                })
                .catch(error => {
                    // If error or no visits, hide the section
                    document.getElementById('visitHistorySection').style.display = 'none';
                });
        }

        function printVisitorCard() {
            if (currentVisitorData) {
                // Create a printable visitor card
                const printWindow = window.open('', '_blank');
                const html = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Visitor Card - ${currentVisitorData.first_name} ${currentVisitorData.last_name}</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .card { 
                                width: 350px; 
                                margin: 20px auto; 
                                border: 2px solid #333; 
                                padding: 20px;
                                text-align: center;
                            }
                            .photo { 
                                width: 150px; 
                                height: 150px; 
                                border-radius: 50%; 
                                margin: 0 auto 20px;
                            }
                            .name { 
                                font-size: 24px; 
                                font-weight: bold; 
                                margin: 10px 0;
                            }
                            .detail { 
                                margin: 5px 0; 
                                text-align: left;
                            }
                            .company { 
                                font-size: 18px; 
                                color: #666;
                                margin: 10px 0;
                            }
                        </style>
                    </head>
                    <body onload="window.print(); window.close();">
                        <div class="card">
                            <img src="${currentVisitorData.photo || 'assets/images/default-avatar.png'}" class="photo">
                            <div class="name">${currentVisitorData.first_name} ${currentVisitorData.last_name}</div>
                            <div class="company">${currentVisitorData.company || 'Guest'}</div>
                            <hr>
                            <div class="detail"><strong>Email:</strong> ${currentVisitorData.email}</div>
                            <div class="detail"><strong>Phone:</strong> ${currentVisitorData.phone}</div>
                            <div class="detail"><strong>Type:</strong> ${currentVisitorData.visitor_type}</div>
                        </div>
                    </body>
                    </html>
                `;
                printWindow.document.write(html);
                printWindow.document.close();
            }
        }

        function viewFullHistory() {
            if (currentVisitorData) {
                // Redirect to a detailed history page or open another modal
                Swal.fire({
                    title: 'Visit History',
                    html: `
                        <p>Total Visits: ${currentVisitorData.total_visits || 1}</p>
                        <p>Member Since: ${new Date(currentVisitorData.created_at).toLocaleDateString()}</p>
                        <p>Last Visit: ${currentVisitorData.last_visit ? new Date(currentVisitorData.last_visit).toLocaleDateString() : 'Current'}</p>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close'
                });
            }
        }

        // Store current visitor data for check-out
        let currentVisitId = null;

        function viewVisitorDetails(visitId) {
            // Fetch visitor details via AJAX
            fetch('?action=active_visits')
                .then(response => response.json())
                .then(data => {
                    // Find the specific visitor
                    const visit = data.find(v => v.visit_id == visitId);
                    
                    if (visit) {
                        // Update modal with visitor information
                        document.getElementById('modalVisitorPhoto').src = visit.photo || 'assets/images/default-avatar.png';
                        document.getElementById('modalBadgeNumber').textContent = visit.badge_number;
                        document.getElementById('modalVisitorName').textContent = `${visit.first_name} ${visit.last_name}`;
                        document.getElementById('modalEmail').textContent = visit.email;
                        document.getElementById('modalPhone').textContent = visit.phone;
                        document.getElementById('modalCompany').textContent = visit.company;
                        document.getElementById('modalHost').textContent = visit.host_name;
                        document.getElementById('modalPurpose').textContent = visit.purpose.charAt(0).toUpperCase() + visit.purpose.slice(1);
                        
                        // Format dates
                        const checkInTime = new Date(visit.check_in_time);
                        const validUntil = new Date(visit.valid_until);
                        
                        document.getElementById('modalCheckIn').textContent = checkInTime.toLocaleString();
                        document.getElementById('modalValidUntil').textContent = validUntil.toLocaleString();
                        
                        // Set status
                        if (visit.check_out_time) {
                            document.getElementById('modalStatus').innerHTML = '<span class="badge bg-secondary">Checked Out</span>';
                            document.getElementById('checkOutVisitorBtn').style.display = 'none';
                        } else {
                            document.getElementById('modalStatus').innerHTML = '<span class="badge bg-success">Active</span>';
                            document.getElementById('checkOutVisitorBtn').style.display = 'inline-block';
                            currentVisitId = visit.visit_id;
                        }
                        
                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById('viewVisitorModal'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error fetching visitor details:', error);
                    Swal.fire('Error', 'Failed to load visitor details', 'error');
                });
        }

        // Function to check out visitor from modal
        function checkOutVisitor() {
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
                        fetch('?action=checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'visit_id=' + currentVisitId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                // Close the modal
                                bootstrap.Modal.getInstance(document.getElementById('viewVisitorModal')).hide();
                                
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Visitor checked out successfully',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                
                                // Refresh the active visits
                                loadActiveVisits();
                                refreshDashboard();
                            } else {
                                Swal.fire('Error', 'Failed to check out visitor', 'error');
                            }
                        });
                    }
                });
            }
        }



        // Refresh Dashboard
        function refreshDashboard() {
            fetch('?action=dashboard_stats')
                .then(response => response.json())
                .then(stats => {
                    document.getElementById('todayTotal').textContent = stats.today_total;
                    document.getElementById('currentlyIn').textContent = stats.currently_in;
                    document.getElementById('scheduledToday').textContent = stats.scheduled_today;
                    document.getElementById('avgDuration').textContent = stats.avg_duration;
                    document.getElementById('activeVisitCount').textContent = stats.currently_in;
                });
            
            fetch('?action=recent_activity')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('recentActivityTable');
                    tbody.innerHTML = '';
                    
                    data.forEach(activity => {
                        tbody.innerHTML += `
                            <tr>
                                <td><span class="badge-number">${activity.badge_number}</span></td>
                                <td>${activity.first_name} ${activity.last_name}</td>
                                <td>${activity.company}</td>
                                <td>${activity.host_name}</td>
                                <td><span class="purpose-badge ${activity.purpose}">${activity.purpose}</span></td>
                                <td>${new Date(activity.check_in_time).toLocaleTimeString()}</td>
                                <td>
                                    ${activity.check_out_time 
                                        ? '<span class="status-badge checked-out">Checked Out</span>'
                                        : '<span class="status-badge checked-in">Checked In</span>'
                                    }
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        // Auto-refresh active visits every 30 seconds
        setInterval(() => {
            if (document.getElementById('active-visitsSection').style.display !== 'none') {
                loadActiveVisits();
            }
            // Always refresh dashboard stats
            refreshDashboard();
        }, 30000);
    </script>
</body>
</html>