<?php
// app/views/admin/users/list.php

if (!defined('BASE_URL')) { /* ... BASE_URL definition ... */ }
$userFullName = $_SESSION['user_fullname'] ?? 'Admin';
$userAvatar = $_SESSION['user_avatar'] ?? BASE_URL . '/public/assets/img/default_admin_avatar.png';
// $data = $data ?? [ /* ... existing dummy data ... */ ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Manage Users'); ?> - Healthcare System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: #343a40; display: flex; min-height: 100vh; }

        .dashboard-sidebar-cutie {
            width: 260px; 
            /* MÀU GRADIENT MỚI CỦA CẬU ĐÂY NÈ */
            background: linear-gradient(90deg, rgba(10,57,32,1) 0%, rgba(13,142,100,1) 90%); 
            color: #ecf0f1; 
            padding: 25px 0; 
            display: flex; 
            flex-direction: column;
        }
        .sidebar-header-cutie { 
            text-align: center; 
            margin-bottom: 30px; 
            padding: 0 20px; 
        }
        .sidebar-logo-cutie { 
            font-size: 24px; 
            font-weight: 700; 
            color: #fff; 
            text-decoration: none; 
        }
        .sidebar-nav-cutie ul { 
            list-style: none; 
            padding: 0;
            margin: 0; 
        }
        .sidebar-nav-cutie li a { 
            display: flex; 
            align-items: center; 
            padding: 15px 25px; 
            color: #dfe6e9; /* Màu chữ hơi sáng hơn cho dễ đọc trên gradient */
            text-decoration: none; 
            font-size: 15px; 
            font-weight: 500; 
            border-left: 4px solid transparent; 
            transition: all 0.2s ease; 
        }
        .sidebar-nav-cutie li a:hover, 
        .sidebar-nav-cutie li a.active-nav-cutie { 
            background-color: rgba(255, 255, 255, 0.15); /* Nền hơi sáng hơn khi hover/active */
            color: #fff; 
            border-left-color: #55efc4; /* Màu nhấn xanh mint sáng cho active (tương phản) */
        }
        .sidebar-nav-cutie li a .nav-icon-cutie { 
            margin-right: 12px; 
            font-size: 18px; 
            width: 20px; 
            text-align: center; 
        }
        .sidebar-footer-cutie { 
            margin-top: auto; 
            padding: 20px 25px; 
            text-align: center; 
            font-size: 13px; 
            color: #bdc3c7; /* Màu chữ cho footer */
        }


        .dashboard-main-content-cutie { flex: 1; padding: 30px; overflow-y: auto; }
        .main-header-cutie { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #dee2e6; }
        .page-title-cutie h2 { font-size: 26px; font-weight: 600; color: #2c3e50; }
        .user-actions-cutie { display: flex; align-items: center; gap: 20px; }
        .user-actions-cutie .icon-button-cutie { background: none; border: none; font-size: 22px; color: #7f8c8d; cursor: pointer; }
        .user-profile-cutie { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .user-profile-cutie img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .user-profile-cutie span { font-weight: 500; font-size: 15px; color: #34495e; }

        .controls-toolbar-cutie { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 15px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); flex-wrap: wrap; gap:15px;}
        .filters-area-cutie { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
        .filter-group-admin-cutie label { font-size: 14px; color: #495057; margin-right: 5px; }
        .filter-group-admin-cutie select, .filter-group-admin-cutie input[type="text"] {
            padding: 8px 10px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; min-width: 150px;
        }
        .btn-admin-action-cutie { padding: 9px 18px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background-color 0.2s ease; }
        .btn-filter-users-cutie { background-color: #5dade2; color: white; }
        .btn-filter-users-cutie:hover { background-color: #3498db; }
        .btn-add-user-cutie { background-color: #2ecc71; color: white; }
        .btn-add-user-cutie:hover { background-color: #27ae60; }

        .users-table-container-cutie { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow-x: auto; }
        .users-table-cutie { width: 100%; border-collapse: collapse; }
        .users-table-cutie th, .users-table-cutie td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ecf0f1; font-size: 14px; }
        .users-table-cutie th { background-color: #f7f9f9; font-weight: 600; color: #34495e; white-space: nowrap; }
        .users-table-cutie tbody tr:hover { background-color: #fdfdfe; }
        .status-badge-admin-cutie { padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 500; color: white; display: inline-block; }
        .status-active { background-color: #2ecc71; }
        .status-inactive { background-color: #e74c3c; }
        .status-pending { background-color: #f39c12; }
        .action-buttons-admin-cutie a, .action-buttons-admin-cutie button {
            padding: 6px 10px; font-size: 13px; border-radius: 5px; text-decoration: none;
            border: none; cursor: pointer; transition: opacity 0.2s ease; margin-right: 5px; margin-bottom: 5px; display: inline-block;
        }
        .action-buttons-admin-cutie a:hover, .action-buttons-admin-cutie button:hover { opacity: 0.8; }
        .btn-edit-user-cutie { background-color: #3498db; color: white; }
        .btn-deactivate-user-cutie { background-color: #f39c12; color: white; }
        .btn-activate-user-cutie { background-color: #2ecc71; color: white; }
        .btn-delete-user-cutie { background-color: #e74c3c; color: white; }
        
        .message-cutie { padding: 10px 15px; margin-bottom: 15px; border-radius: 6px; font-size: 14px; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .no-users-msg-cutie { text-align: center; padding: 40px 20px; color: #7f8c8d; font-style: italic; }

        @media (max-width: 768px) { /* Sidebar responsive */ }
    </style>
</head>
<body>
    <aside class="dashboard-sidebar-cutie">
        <div class="sidebar-header-cutie"><a href="<?php echo BASE_URL; ?>" class="sidebar-logo-cutie">HealthSys</a></div>
        <nav class="sidebar-nav-cutie">
        <nav class="sidebar-nav-cutie">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>/admin/dashboard" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/dashboard') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">🏠</span>Dashboard</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/listUsers" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/listUsers') !== false || strpos($_GET['url'] ?? '', 'admin/createUser') !== false || strpos($_GET['url'] ?? '', 'admin/editUser') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">👥</span>Manage Users</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/manageSpecializations" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/manageSpecializations') !== false || strpos($_GET['url'] ?? '', 'admin/editSpecialization') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">🏷️</span>Specializations</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/listMedicines" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/listMedicines') !== false || strpos($_GET['url'] ?? '', 'admin/createMedicine') !== false || strpos($_GET['url'] ?? '', 'admin/editMedicine') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">💊</span>Manage Medicines</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/listAllAppointments" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/listAllAppointments') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">🗓️</span>All Appointments</a></li>
                <li><a href="<?php echo BASE_URL; ?>/report/overview" class="<?php echo (strpos($_GET['url'] ?? '', 'report/overview') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">📊</span>Reports</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/manageLeaveRequests" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/manageLeaveRequests') !== false || strpos($_GET['url'] ?? '', 'admin/reviewLeaveRequest') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">✈️</span>Leave Requests</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/manageFeedbacks" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/manageFeedbacks') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">⭐</span>Patient Feedbacks</a></li>
                <li><a href="<?php echo BASE_URL; ?>/admin/updateProfile" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/updateProfile') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">👤</span>My Profile</a></li>
                <!-- Thêm các mục khác cho Admin nếu cần, ví dụ: System Settings -->
                <!-- <li><a href="<?php echo BASE_URL; ?>/admin/systemSettings" class="<?php echo (strpos($_GET['url'] ?? '', 'admin/systemSettings') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">⚙️</span>System Settings</a></li> -->
            </ul>
        </nav>
        <div class="sidebar-footer-cutie">© <?php echo date('Y'); ?> Healthcare System</div>
    </aside>

    <main class="dashboard-main-content-cutie">
        <header class="main-header-cutie">
            <div class="page-title-cutie"><h2><?php echo htmlspecialchars($data['title'] ?? 'Manage Users'); ?></h2></div>
            <div class="user-actions-cutie">
                <button class="icon-button-cutie" title="Notifications">🔔</button>
                <div class="user-profile-cutie">
                    <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Admin Avatar">
                    <span><?php echo htmlspecialchars($userFullName); ?></span> ▼
                </div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="icon-button-cutie" title="Logout" style="text-decoration:none;">🚪</a>
            </div>
        </header>

        <?php if (isset($_SESSION['user_management_message_success'])): ?>
            <p class="message-cutie success-message"><?php echo $_SESSION['user_management_message_success']; unset($_SESSION['user_management_message_success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_management_message_error'])): ?>
            <p class="message-cutie error-message"><?php echo $_SESSION['user_management_message_error']; unset($_SESSION['user_management_message_error']); ?></p>
        <?php endif; ?>

        <div class="controls-toolbar-cutie">
            <form method="GET" action="<?php echo BASE_URL; ?>/admin/listUsers" class="filters-area-cutie">
                <div class="filter-group-admin-cutie">
                    <label for="role_filter">Role:</label>
                    <select name="role" id="role_filter">
                        <option value="All" <?php echo (($data['currentRoleFilter'] ?? 'All') == 'All') ? 'selected' : ''; ?>>All Roles</option>
                        <?php $roles = ['Admin', 'Doctor', 'Nurse', 'Patient']; ?>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role; ?>" <?php echo (($data['currentRoleFilter'] ?? '') == $role) ? 'selected' : ''; ?>><?php echo $role; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group-admin-cutie">
                    <label for="status_filter">Status:</label>
                    <select name="status" id="status_filter">
                        <option value="All" <?php echo (($data['currentStatusFilter'] ?? 'All') == 'All') ? 'selected' : ''; ?>>All Statuses</option>
                        <?php $statuses = ['Active', 'Inactive', 'Pending', 'Active_and_Pending']; ?>
                        <?php foreach ($statuses as $statusKey => $statusValue): 
                            $actualStatusValue = is_array($data['allStatuses'][$statusKey] ?? null) ? $statusKey : $statusValue; // Handle 'Active_and_Pending'
                            $displayStatus = str_replace('_and_', ' & ', $statusValue);
                        ?>
                            <option value="<?php echo htmlspecialchars($actualStatusValue); ?>" <?php echo (($data['currentStatusFilter'] ?? '') == $actualStatusValue) ? 'selected' : ''; ?>><?php echo htmlspecialchars($displayStatus); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group-admin-cutie">
                    <label for="search_term">Search:</label>
                    <input type="text" name="search" id="search_term" value="<?php echo htmlspecialchars($data['currentSearchTerm'] ?? ''); ?>" placeholder="Name, Email...">
                </div>
                <button type="submit" class="btn-admin-action-cutie btn-filter-users-cutie">Filter</button>
            </form>
            <a href="<?php echo BASE_URL; ?>/admin/createUser" class="btn-admin-action-cutie btn-add-user-cutie">+ Add New User</a>
        </div>

        <div class="users-table-container-cutie">
            <?php if (!empty($data['users'])): ?>
                <table class="users-table-cutie">
                    <thead>
                        <tr><th>#</th><th>Full Name</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Registered</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; ?>
                        <?php foreach ($data['users'] as $user): ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td><?php echo htmlspecialchars($user['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($user['Username']); ?></td>
                                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                                <td><?php echo htmlspecialchars($user['Role']); ?></td>
                                <td><span class="status-badge-admin-cutie status-<?php echo strtolower(htmlspecialchars($user['Status'])); ?>"><?php echo htmlspecialchars($user['Status']); ?></span></td>
                                <td><?php echo htmlspecialchars(date('M j, Y', strtotime($user['CreatedAt']))); ?></td>
                                <td class="action-buttons-admin-cutie">
                                    <a href="<?php echo BASE_URL . '/admin/editUser/' . $user['UserID']; ?>" class="btn-edit-user-cutie">Edit</a>
                                    <?php if ($user['UserID'] != $_SESSION['user_id']): ?>
                                        <?php if ($user['Status'] === 'Active'): ?>
                                            <form action="<?php echo BASE_URL; ?>/admin/updateUserStatus" method="POST" style="display:inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                                <input type="hidden" name="new_status" value="Inactive">
                                                <?php if (function_exists('generateCsrfInput')) { echo generateCsrfInput(); } ?>
                                                <button type="submit" class="btn-deactivate-user-cutie" onclick="return confirm('Deactivate this user?');">Deactivate</button>
                                            </form>
                                        <?php elseif (in_array($user['Status'], ['Inactive', 'Pending'])): ?>
                                            <form action="<?php echo BASE_URL; ?>/admin/updateUserStatus" method="POST" style="display:inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                                <input type="hidden" name="new_status" value="Active">
                                                <?php if (function_exists('generateCsrfInput')) { echo generateCsrfInput(); } ?>
                                                <button type="submit" class="btn-activate-user-cutie" onclick="return confirm('Activate this user?');">Activate</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if ($user['Status'] !== 'Archived'): // Assuming 'Archived' is a soft delete status ?>
                                            <form action="<?php echo BASE_URL; ?>/admin/deleteUser" method="POST" style="display:inline;">
                                                <input type="hidden" name="user_id_to_delete" value="<?php echo $user['UserID']; ?>">
                                                <?php if (function_exists('generateCsrfInput')) { echo generateCsrfInput(); } ?>
                                                <button type="submit" class="btn-delete-user-cutie" onclick="return confirm('SOFT DELETE this user (mark as Inactive/Archived)?');">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-users-msg-cutie">No users found matching your criteria. Try adjusting the filters or add a new user!</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>