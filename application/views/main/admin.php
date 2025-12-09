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
        .table td, .table tr, .table th { overflow: hidden; text-overflow: ellipsis; word-wrap: break-word; word-break: break-word; white-space: normal; }
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
                            <th>Badge #</th><th>Visitor</th><th>Company</th><th>Host</th><th>Purpose</th><th>Notes</th><th>Visiting</th><th>Check-In</th><th>Status</th>
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
                <table class="table table-hover" id="activeVisitsTable">
                    <thead>
                        <tr>
                            <th>Badge #</th><th>Visitor</th><th>Company</th><th>Host</th><th>Department</th><th>Purpose</th><th>Notes</th><th>Visiting</th><th>Check-In</th><th>Valid Until</th><th>Actions</th>
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

        <!-- Purposes Section -->
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
                <table class="table table-hover" id="purposeTable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Purpose Code</th>
                            <th>Purpose Name</th>
                            <th>Icon</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="purposeTableBody"></tbody>
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
                        <input type="text" class="form-control" value="<?php echo $pageTitle; ?>">
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
                <div class="modal-header <?php echo $modalHeaderClass; ?>">
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

    <!-- Add Purpose Modal -->
    <div class="modal fade" id="addPurposeModal" tabindex="-1">
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
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const companyFilter = <?php echo json_encode($companyFilter); ?>;
        const filterParam = companyFilter ? `&company_filter=${encodeURIComponent(companyFilter)}` : '&company_filter=null';
        const ajaxUrl = '<?= base_url("admin/ajax_handler") ?>';
        
        let currentVisitId = null;
        let currentVisitorData = null;
        let dataTableInstances = {};
        let purposesMap = {};
        let lastAlertId = 0;

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
                        <td>${v.last_visit ? new Date(v.last_visit).toLocaleDateString() : 'N/A'}</td>
                        <td>
                            <button class="action-btn view" onclick="viewVisitor(${v.visitor_id})" title="View Details"><i class="bi bi-eye"></i></button>
                            <button class="action-btn" style="color: #3498db;" onclick="viewVisitorHistory(${v.visitor_id}, '${v.first_name} ${v.last_name}')" title="View History"><i class="bi bi-clock-history"></i></button>
                        </td>
                    `);
                })
                .catch(e => console.error('Error loading visitors:', e));
        }

        function loadEmployees() {
            loadDepartmentsForSelect();
            fetch(ajaxUrl + '?action=employees')
                .then(r => r.json())
                .then(data => {
                    initDataTable('employeeTable', data, (e) => `
                        <td>${e.employee_id}</td>
                        <td><strong>${e.name}</strong></td>
                        <td>${e.email}</td>
                        <td>${e.department_name}</td>
                        <td>
                            <span class="badge ${e.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
                                style="cursor: pointer;" 
                                onclick="toggleEmployeeStatus('${e.employee_id}', ${e.is_active}, '${e.name.replace(/'/g, "\\'")}')" 
                                title="Click to ${e.is_active == 1 ? 'deactivate' : 'activate'}">
                                ${e.is_active == 1 ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>
                            ${e.total_visits || 0}
                            ${e.total_visits > 0 ? `<button class="btn btn-sm btn-link" onclick="viewEmployeeHistory('${e.employee_id}', '${e.name}')" title="View History"><i class="bi bi-clock-history"></i></button>` : ''}
                        </td>
                    `);
                })
                .catch(e => console.error('Error loading employees:', e));
        }

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

        function loadDepartments() {
            fetch(ajaxUrl + '?action=departments')
                .then(r => r.json())
                .then(data => {
                    initDataTable('departmentTable', data, (d) => `
                        <td><span class="badge bg-secondary">${d.department_code}</span></td>
                        <td><strong>${d.name}</strong></td>
                        <td>
                            ${d.employee_count || 0}
                            ${d.employee_count > 0 ? `<button class="btn btn-sm btn-link" onclick="viewDepartmentEmployees('${d.department_code}', '${d.name}')" title="View Employees"><i class="bi bi-people-fill"></i></button>` : ''}
                        </td>
                        <td>${d.visit_count || 0}</td>
                        <td>${d.created_at || 'N/A'}</td>
                    `);
                })
                .catch(e => console.error('Error loading departments:', e));
        }

        function loadDepartmentsForSelect() {
            fetch(ajaxUrl + '?action=departments')
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
            fetch(ajaxUrl + `?action=get_visit&visit_id=${visitId}`)
                .then(r => r.json())
                .then(visit => {
                    if (visit.error) {
                        Swal.fire('Error', visit.error, 'error');
                        return;
                    }
                    
                    currentVisitId = visit.visit_id;
                    
                    let photoSrc = '<?= base_url("assets/images/default-avatar.png") ?>';
                    if (visit.photo) {
                        photoSrc = visit.photo.startsWith('data:image') ? visit.photo : 
                                   (visit.photo.startsWith('/') || visit.photo.startsWith('assets/')) ? visit.photo :
                                   'data:image/jpeg;base64,' + visit.photo;
                    }
                    
                    document.getElementById('modalVisitorPhoto').src = photoSrc;
                    document.getElementById('modalVisitorPhoto').onerror = function() { this.src = '<?= base_url("assets/images/default-avatar.png") ?>'; };
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
                    
                    let photoSrc = '<?= base_url("assets/images/default-avatar.png") ?>';
                    if (visitor.photo) {
                        photoSrc = visitor.photo.startsWith('data:image') ? visitor.photo : 
                                   (visitor.photo.startsWith('/') || visitor.photo.startsWith('assets/')) ? visitor.photo :
                                   'data:image/jpeg;base64,' + visitor.photo;
                    }
                    
                    document.getElementById('allVisitorPhoto').src = photoSrc;
                    document.getElementById('allVisitorPhoto').onerror = function() { this.src = '<?= base_url("assets/images/default-avatar.png") ?>'; };
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

        function loadPurposes() {
            fetch(ajaxUrl + '?action=get_all_purposes')
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const tbody = document.getElementById('purposeTableBody');
                        tbody.innerHTML = '';
                        
                        if (data.purposes.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No purposes found</td></tr>';
                            return;
                        }
                        
                        data.purposes.forEach((p, index) => {
                            const isFirst = index === 0;
                            const isLast = index === data.purposes.length - 1;
                            
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
                                <td><span class="${p.color_class}">●</span> ${p.color_class}</td>
                                <td>
                                    <span class="badge ${p.is_active == 1 ? 'bg-success' : 'bg-secondary'}" 
                                        style="cursor: pointer;" 
                                        onclick="togglePurposeStatus(${p.purpose_id}, ${p.is_active}, '${p.purpose_name.replace(/'/g, "\\'")}')" 
                                        title="Click to ${p.is_active == 1 ? 'deactivate' : 'activate'}">
                                        ${p.is_active == 1 ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>
                                    <button class="action-btn view" onclick="viewPurposeDetails(${p.purpose_id})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                        
                        if ($.fn.DataTable.isDataTable('#purposeTable')) {
                            $('#purposeTable').DataTable().destroy();
                        }
                        $('#purposeTable').DataTable({
                            pageLength: 10,
                            order: [[0, 'asc']],
                            columnDefs: [
                                { orderable: false, targets: [0, 6] }
                            ]
                        });
                    }
                })
                .catch(e => {
                    console.error('Error loading purposes:', e);
                    Swal.fire('Error', 'Failed to load purposes', 'error');
                });
        }

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

        function movePurpose(purposeId, direction) {
            const formData = new FormData();
            formData.append('purpose_id', purposeId);
            formData.append('direction', direction);
            
            fetch(ajaxUrl + '?action=update_purpose_order', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    loadPurposes();
                } else {
                    Swal.fire('Error', data.message || 'Failed to update order', 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
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
    </script>
</body>
</html>