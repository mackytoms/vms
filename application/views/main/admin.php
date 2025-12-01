<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
// $username = "itsdT0ms";
// $password = "(GrYXU4fOY)wVOr4";
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
    
    $sql = "SELECT COUNT(DISTINCT visitor_id) as today_total FROM visits WHERE DATE(check_in_time) = CURDATE()";
    $result = $conn->query($sql);
    $stats['today_total'] = $result->fetch_assoc()['today_total'];
    
    $sql = "SELECT COUNT(*) as currently_in FROM visits WHERE check_out_time IS NULL";
    $result = $conn->query($sql);
    $stats['currently_in'] = $result->fetch_assoc()['currently_in'];
    
    $sql = "SELECT AVG(TIMESTAMPDIFF(HOUR, check_in_time, IFNULL(check_out_time, NOW()))) as avg_duration 
            FROM visits WHERE DATE(check_in_time) = CURDATE()";
    $result = $conn->query($sql);
    $avg = $result->fetch_assoc()['avg_duration'];
    $stats['avg_duration'] = $avg ? round($avg, 1) . 'h' : '0h';
    
    return $stats;
}

function getRecentActivity($conn) {
    $sql = "SELECT v.*, vi.first_name, vi.last_name, vi.company, e.name as host_name, v.company_visited
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

function getActiveVisits($conn) {
    $sql = "SELECT v.*, vi.first_name, vi.last_name, vi.company, vi.email, vi.phone, vi.photo,
            e.name as host_name, d.name as department_name, v.company_visited
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

function getDashboardStatsByCompany($conn) {
    $stats = array();
    
    $sql = "SELECT COUNT(DISTINCT visitor_id) as count FROM visits 
            WHERE DATE(check_in_time) = CURDATE() AND company_visited = 'Toms World'";
    $result = $conn->query($sql);
    $stats['toms_world_today'] = $result->fetch_assoc()['count'];
    
    $sql = "SELECT COUNT(*) as count FROM visits 
            WHERE check_out_time IS NULL AND company_visited = 'Toms World'";
    $result = $conn->query($sql);
    $stats['toms_world_active'] = $result->fetch_assoc()['count'];
    
    $sql = "SELECT COUNT(DISTINCT visitor_id) as count FROM visits 
            WHERE DATE(check_in_time) = CURDATE() AND company_visited = 'Pan Asia'";
    $result = $conn->query($sql);
    $stats['pan_asia_today'] = $result->fetch_assoc()['count'];
    
    $sql = "SELECT COUNT(*) as count FROM visits 
            WHERE check_out_time IS NULL AND company_visited = 'Pan Asia'";
    $result = $conn->query($sql);
    $stats['pan_asia_active'] = $result->fetch_assoc()['count'];
    
    return $stats;
}

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

function getVisitorById($conn, $visitor_id) {
    $visitor_id = $conn->real_escape_string($visitor_id);
    $sql = "SELECT vi.*, COUNT(v.visit_id) as total_visits, MAX(v.check_in_time) as last_visit 
            FROM visitors vi 
            LEFT JOIN visits v ON vi.visitor_id = v.visitor_id 
            WHERE vi.visitor_id = $visitor_id
            GROUP BY vi.visitor_id";
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function getVisitById($conn, $visit_id) {
    $visit_id = $conn->real_escape_string($visit_id);
    $sql = "SELECT v.*, vi.first_name, vi.last_name, vi.company, vi.email, vi.phone, vi.photo,
            e.name as host_name, d.name as department_name, v.company_visited
            FROM visits v 
            JOIN visitors vi ON v.visitor_id = vi.visitor_id 
            JOIN employees e ON v.host_employee_id = e.employee_id
            JOIN departments d ON e.department_code = d.department_code
            WHERE v.visit_id = $visit_id";
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
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
        case 'get_visitor':
            if(isset($_GET['visitor_id'])) {
                $visitor = getVisitorById($conn, $_GET['visitor_id']);
                echo json_encode($visitor ? $visitor : ['error' => 'Visitor not found']);
            }
            break;
        case 'get_visit':
            if(isset($_GET['visit_id'])) {
                $visit = getVisitById($conn, $_GET['visit_id']);
                echo json_encode($visit ? $visit : ['error' => 'Visit not found']);
            }
            break;
        case 'employees':
            echo json_encode(getEmployees($conn));
            break;
        case 'departments':
            echo json_encode(getDepartments($conn));
            break;
        case 'dashboard_stats_by_company':
            echo json_encode(getDashboardStatsByCompany($conn));
            break;
        case 'checkout':
            if(isset($_POST['visit_id'])) {
                $visit_id = $conn->real_escape_string($_POST['visit_id']);
                $sql = "UPDATE visits SET check_out_time = NOW() WHERE visit_id = $visit_id AND check_out_time IS NULL";
                if($conn->query($sql) && $conn->affected_rows > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $conn->error ?: 'Already checked out or invalid visit']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Visit ID required']);
            }
            break;
        case 'add_employee':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $conn->real_escape_string($_POST['name']);
                $email = $conn->real_escape_string($_POST['email']);
                $department_code = $conn->real_escape_string($_POST['department_code']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                $sql = "INSERT INTO employees (name, email, department_code, is_active, created_at) 
                        VALUES ('$name', '$email', '$department_code', $is_active, NOW())";
                
                if($conn->query($sql)) {
                    echo json_encode(['success' => true, 'employee_id' => $conn->insert_id]);
                } else {
                    echo json_encode(['success' => false, 'error' => $conn->error]);
                }
            }
            break;
        case 'add_department':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $department_code = $conn->real_escape_string($_POST['department_code']);
                $name = $conn->real_escape_string($_POST['name']);
                $description = $conn->real_escape_string($_POST['description'] ?? '');
                
                $sql = "INSERT INTO departments (department_code, name, description, created_at) 
                        VALUES ('$department_code', '$name', '$description', NOW())";
                
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

$dashboardStats = getDashboardStats($conn);
$recentActivity = getRecentActivity($conn);
$activeVisits = getActiveVisits($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk V-Pass Admin - Tom's World & Pan-Asia</title>
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
        .search-box { position: relative; width: 300px; }
        .search-box input { width: 100%; padding: 8px 40px 8px 15px; border: 1px solid #dee2e6; border-radius: 20px; font-size: 0.95em; }
        .search-box i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #95a5a6; }
        .topbar-right { display: flex; align-items: center; gap: 20px; }
        .notification-icon { position: relative; cursor: pointer; font-size: 1.3em; color: #7f8c8d; }
        .notification-badge { position: absolute; top: -5px; right: -5px; background: var(--danger-color); color: white; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7em; }
        .user-profile { display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 5px 10px; border-radius: 20px; transition: background 0.3s ease; }
        .user-profile:hover { background: #f8f9fa; }
        .user-avatar { width: 35px; height: 35px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; }
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
        .table td, .table tr, .table th { overflow: hidden; text-overflow: ellipsis; word-wrap: break-word; word-break: break-word; white-space: normal; }
        .visitor-photo { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #f8f9fa; display: inline-flex; align-items: center; justify-content: center; }
        .visitor-photo img { width: 100%; height: 100%; object-fit: cover; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 500; }
        .status-badge.checked-in { background: rgba(39, 174, 96, 0.1); color: var(--success-color); }
        .status-badge.checked-out { background: rgba(52, 152, 219, 0.1); color: var(--info-color); }
        .purpose-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 500; }
        .purpose-badge.meeting { background: rgba(52, 152, 219, 0.1); color: var(--info-color); }
        .purpose-badge.interview { background: rgba(155, 89, 182, 0.1); color: #9b59b6; }
        .purpose-badge.delivery { background: rgba(243, 156, 18, 0.1); color: var(--primary-color); }
        .purpose-badge.service { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
        .purpose-badge.training { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
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
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .search-box { width: 150px; }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?= base_url('assets/images/icons/stufftoy - Copy.png') ?>" alt="TOMS WORLD" onerror="this.style.display='none'">
                <img src="<?= base_url('assets/images/icons/473762608_905226608452197_3072891570387687458_n.jpg') ?>" alt="PAN-ASIA" onerror="this.style.display='none'">
            </div>
            <h3>KIOSK V-PASS</h3>
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
            <div class="sidebar-item" onclick="showSection('settings')">
                <i class="bi bi-gear"></i><span>Settings</span>
            </div>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        <div class="topbar">
            <div class="topbar-left">
                <i class="bi bi-list menu-toggle" onclick="toggleSidebar()"></i>
                <!-- <div class="search-box">
                    <input type="text" placeholder="Search visitors, employees..." id="globalSearch">
                    <i class="bi bi-search"></i>
                </div> -->
            </div>
            <div class="topbar-right">
                <!-- <div class="notification-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"><?php echo $dashboardStats['currently_in']; ?></span>
                </div> -->
                <!-- <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <span>Admin</span>
                    <i class="bi bi-chevron-down"></i>
                </div> -->
            <a href="<?= base_url('auth/logout') ?>" class="sidebar-item logout" onclick="return confirmLogout(event)">
                <i class="bi bi-box-arrow-left"></i><span>Logout</span>
            </a>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div class="dashboard-content" id="dashboardSection">
            <h1 class="page-title">Visitor Management Dashboard</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening today at Tom's World & Pan-Asia.</p>
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
                            <th>Badge #</th><th>Visitor</th><th>Company</th><th>Host</th><th>Purpose</th><th>Visiting</th><th>Check-In</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentActivityTableBody">
                        <?php foreach($recentActivity as $activity): 
                            $companyBadge = '';
                            if ($activity['company_visited'] == 'Toms World') {
                                $companyBadge = '<span class="company-badge toms-world"><i class="bi bi-building"></i> Tom\'s World</span>';
                            } elseif ($activity['company_visited'] == 'Pan Asia') {
                                $companyBadge = '<span class="company-badge pan-asia"><i class="bi bi-building"></i> Pan-Asia</span>';
                            } else {
                                $companyBadge = '<span class="badge bg-secondary">' . ($activity['company_visited'] ?? 'N/A') . '</span>';
                            }
                        ?>
                        <tr>
                            <td><span class="badge-number"><?php echo $activity['badge_number']; ?></span></td>
                            <td><?php echo $activity['first_name'] . ' ' . $activity['last_name']; ?></td>
                            <td><?php echo $activity['company']; ?></td>
                            <td><?php echo $activity['host_name']; ?></td>
                            <td><span class="purpose-badge <?php echo strtolower($activity['purpose']); ?>"><?php echo $activity['purpose']; ?></span></td>
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
                <table class="table table-hover" id="activeVisitsTable">
                    <thead>
                        <tr>
                            <th>Badge #</th><th>Visitor</th><th>Company</th><th>Host</th><th>Department</th><th>Purpose</th><th>Visiting</th><th>Check-In</th><th>Valid Until</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activeVisitsTableBody"></tbody>
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
                            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Company</th><th>Type</th><th>Total Visits</th><th>Last Visit</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allVisitorsTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Employees Section -->
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
                <table class="table table-hover" id="employeeTable">
                    <thead>
                        <tr>
                            <th>Employee ID</th><th>Name</th><th>Email</th><th>Department</th><th>Status</th><th>Total Visits Hosted</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody"></tbody>
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
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                        <i class="bi bi-plus-circle"></i> Add Department
                    </button>
                </div>
                <table class="table table-hover" id="departmentTable">
                    <thead>
                        <tr>
                            <th>Department Code</th><th>Department Name</th><th>Total Employees</th><th>Total Visits</th><th>Created</th>
                        </tr>
                    </thead>
                    <tbody id="departmentTableBody"></tbody>
                </table>
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
    </div>

    <!-- View Visitor Modal (Active Visits) -->
    <div class="modal fade" id="viewVisitorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #f39c12, #1e9338); color: white;">
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
                <div class="modal-header" style="background: linear-gradient(135deg, #f39c12, #1e9338); color: white;">
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
                <div class="modal-header bg-primary text-white">
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
                        <div class="mb-3">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select" name="department_code" id="employeeDepartmentSelect" required>
                                <option value="">Select Department</option>
                            </select>
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

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-building-add"></i> Add New Department</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addDepartmentForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="department_code" placeholder="e.g., IT, HR, SALES" required maxlength="20">
                            <small class="text-muted">Unique identifier for the department</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="e.g., Information Technology" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the department"></textarea>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let currentVisitId = null;
        let currentVisitorData = null;
        let dataTableInstances = {};

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

        function loadActiveVisits() {
            fetch('?action=active_visits')
                .then(r => r.json())
                .then(data => {
                    initDataTable('activeVisitsTable', data, (v) => `
                        <td><span class="badge-number">${v.badge_number}</span></td>
                        <td><strong>${v.first_name} ${v.last_name}</strong></td>
                        <td>${v.company}</td>
                        <td>${v.host_name}</td>
                        <td>${v.department_name}</td>
                        <td><span class="purpose-badge ${(v.purpose||'').toLowerCase()}">${v.purpose}</span></td>
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

        function loadAllVisitors() {
            fetch('?action=all_visitors')
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
                        <td>${v.last_visit ? new Date(v.last_visit).toLocaleDateString() : 'N/A'}</td>
                        <td><button class="action-btn view" onclick="viewVisitor(${v.visitor_id})" title="View"><i class="bi bi-eye"></i></button></td>
                    `);
                })
                .catch(e => console.error('Error loading visitors:', e));
        }

        function loadEmployees() {
            loadDepartmentsForSelect();
            fetch('?action=employees')
                .then(r => r.json())
                .then(data => {
                    initDataTable('employeeTable', data, (e) => `
                        <td>${e.employee_id}</td>
                        <td><strong>${e.name}</strong></td>
                        <td>${e.email}</td>
                        <td>${e.department_name}</td>
                        <td><span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}">${e.is_active == 1 ? 'Active' : 'Inactive'}</span></td>
                        <td>${e.total_visits || 0}</td>
                    `);
                })
                .catch(e => console.error('Error loading employees:', e));
        }

        function loadDepartments() {
            fetch('?action=departments')
                .then(r => r.json())
                .then(data => {
                    initDataTable('departmentTable', data, (d) => `
                        <td><span class="badge bg-secondary">${d.department_code}</span></td>
                        <td><strong>${d.name}</strong></td>
                        <td>${d.employee_count || 0}</td>
                        <td>${d.visit_count || 0}</td>
                        <td>${d.created_at || 'N/A'}</td>
                    `);
                })
                .catch(e => console.error('Error loading departments:', e));
        }

        function loadDepartmentsForSelect() {
            fetch('?action=departments')
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('employeeDepartmentSelect');
                    select.innerHTML = '<option value="">Select Department</option>';
                    data.forEach(d => {
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
            fetch(`?action=get_visit&visit_id=${visitId}`)
                .then(r => r.json())
                .then(visit => {
                    if (visit.error) {
                        Swal.fire('Error', visit.error, 'error');
                        return;
                    }
                    
                    currentVisitId = visit.visit_id;
                    
                    let photoSrc = 'assets/images/default-avatar.png';
                    if (visit.photo) {
                        photoSrc = visit.photo.startsWith('data:image') ? visit.photo : 
                                   (visit.photo.startsWith('/') || visit.photo.startsWith('assets/')) ? visit.photo :
                                   'data:image/jpeg;base64,' + visit.photo;
                    }
                    
                    document.getElementById('modalVisitorPhoto').src = photoSrc;
                    document.getElementById('modalVisitorPhoto').onerror = function() { this.src = 'assets/images/default-avatar.png'; };
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
            fetch(`?action=get_visitor&visitor_id=${visitorId}`)
                .then(r => r.json())
                .then(visitor => {
                    if (visitor.error) {
                        Swal.fire('Error', visitor.error, 'error');
                        return;
                    }
                    
                    currentVisitorData = visitor;
                    
                    let photoSrc = 'assets/images/default-avatar.png';
                    if (visitor.photo) {
                        photoSrc = visitor.photo.startsWith('data:image') ? visitor.photo : 
                                   (visitor.photo.startsWith('/') || visitor.photo.startsWith('assets/')) ? visitor.photo :
                                   'data:image/jpeg;base64,' + visitor.photo;
                    }
                    
                    document.getElementById('allVisitorPhoto').src = photoSrc;
                    document.getElementById('allVisitorPhoto').onerror = function() { this.src = 'assets/images/default-avatar.png'; };
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
            
            fetch('?action=checkout', {
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
            fetch('?action=dashboard_stats')
                .then(r => r.json())
                .then(stats => {
                    document.getElementById('todayTotal').textContent = stats.today_total;
                    document.getElementById('currentlyIn').textContent = stats.currently_in;
                    document.getElementById('avgDuration').textContent = stats.avg_duration;
                    document.getElementById('activeVisitCount').textContent = stats.currently_in;
                    document.querySelector('.notification-badge').textContent = stats.currently_in;
                });
            
            fetch('?action=recent_activity')
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
                                <td><span class="purpose-badge ${(a.purpose||'').toLowerCase()}">${a.purpose}</span></td>
                                <td>${getCompanyBadgeHTML(a.company_visited)}</td>
                                <td>${new Date(a.check_in_time).toLocaleTimeString()}</td>
                                <td>${a.check_out_time ? '<span class="status-badge checked-out">Checked Out</span>' : '<span class="status-badge checked-in">Checked In</span>'}</td>
                            </tr>
                        `;
                    });
                });
        }

        // Confirm logout
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

        // Add Employee Form
        document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('?action=add_employee', { method: 'POST', body: formData })
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

        // Add Department Form
        document.getElementById('addDepartmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('?action=add_department', { method: 'POST', body: formData })
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

        // Initialize recent activity table with pagination
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#recentActivityTable')) {
                $('#recentActivityTable').DataTable({ pageLength: 10, order: [] });
            }
        });

        // Auto-refresh every 30 seconds
        setInterval(() => {
            if (document.getElementById('active-visitsSection').style.display !== 'none') {
                loadActiveVisits();
            }
            refreshDashboard();
        }, 30000);
    </script>
</body>
</html>