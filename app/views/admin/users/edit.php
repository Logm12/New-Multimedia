<?php
// app/views/admin/users/edit.php

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
    <title><?php echo htmlspecialchars($data['title'] ?? 'Edit User'); ?> - Healthcare System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reuse styles from create.php and list.php for consistency */
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

        .form-container-admin-cutie { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px; max-width: 800px; margin: 0 auto; }
        .form-container-admin-cutie fieldset { border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .form-container-admin-cutie legend { font-size: 18px; font-weight: 600; color: #34495e; padding: 0 10px; margin-left: 10px; }
        .form-grid-admin-cutie { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px 20px; }
        .form-group-admin-cutie { margin-bottom: 10px; }
        .form-group-admin-cutie label { display: block; font-size: 14px; color: #495057; margin-bottom: 6px; font-weight: 500; }
        .form-group-admin-cutie input[type="text"], .form-group-admin-cutie input[type="email"], .form-group-admin-cutie input[type="password"], .form-group-admin-cutie input[type="number"], .form-group-admin-cutie select, .form-group-admin-cutie textarea {
            width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px;
        }
        .form-group-admin-cutie input[readonly] { background-color: #e9ecef; cursor: not-allowed; }
        .form-group-admin-cutie textarea { min-height: 70px; resize: vertical; }
        .form-group-admin-cutie input:focus, .form-group-admin-cutie select:focus, .form-group-admin-cutie textarea:focus { border-color: #3498db; box-shadow: 0 0 0 0.15rem rgba(52,152,219,.25); outline: none; }
        
        .form-actions-admin-cutie { margin-top: 25px; display: flex; gap: 15px; }
        .btn-admin-action, .btn-admin-secondary { padding: 10px 20px; border: none; border-radius: 6px; font-size: 15px; font-weight: 500; cursor: pointer; text-decoration: none; }
        .btn-admin-action { background-color: #3498db; color: white; } .btn-admin-action:hover { background-color: #2980b9; }
        .btn-admin-secondary { background-color: #7f8c8d; color: white; } .btn-admin-secondary:hover { background-color: #6c757d; }
        
        .message-cutie { padding: 10px 15px; margin-bottom: 15px; border-radius: 6px; font-size: 14px; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error-message-container { margin-bottom: 15px; padding:10px; border:1px solid #e74c3c; background-color:#fdedec; border-radius: 6px; color: #c0392b;}
        .error-message-container ul { list-style-position: inside; padding-left: 5px; }
    </style>
</head>
<body>
    <aside class="dashboard-sidebar-cutie">
        <div class="sidebar-header-cutie"><a href="<?php echo BASE_URL; ?>" class="sidebar-logo-cutie">HealthSys</a></div>
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
            <div class="page-title-cutie"><h2><?php echo htmlspecialchars($data['title'] ?? 'Edit User'); ?></h2></div>
            <div class="user-actions-cutie">
                <button class="icon-button-cutie" title="Notifications">🔔</button>
                <div class="user-profile-cutie">
                    <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Admin Avatar">
                    <span><?php echo htmlspecialchars($userFullName); ?></span> ▼
                </div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="icon-button-cutie" title="Logout" style="text-decoration:none;">🚪</a>
            </div>
        </header>

        <?php if (!empty($data['errors'])): ?>
            <div class="error-message-container"><strong>Please correct the following errors:</strong><ul>
                <?php foreach ($data['errors'] as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?>
            </ul></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_management_message_success'])): ?>
            <p class="message-cutie success-message"><?php echo $_SESSION['user_management_message_success']; unset($_SESSION['user_management_message_success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_management_message_error'])): ?>
            <p class="message-cutie error-message"><?php echo $_SESSION['user_management_message_error']; unset($_SESSION['user_management_message_error']); ?></p>
        <?php endif; ?>

        <div class="form-container-admin-cutie">
            <form action="<?php echo BASE_URL . '/admin/editUser/' . ($data['userId'] ?? ''); ?>" method="POST">
                <?php if (function_exists('generateCsrfInput')) { echo generateCsrfInput(); } ?>
                <input type="hidden" name="userId" value="<?php echo htmlspecialchars($data['userId'] ?? ''); ?>">
                <fieldset>
                    <legend>Account Information</legend>
                    <div class="form-grid-admin-cutie">
                        <div class="form-group-admin-cutie"><label for="FullName">Full Name: *</label><input type="text" id="FullName" name="FullName" value="<?php echo htmlspecialchars($data['input']['FullName'] ?? ''); ?>" required></div>
                        <div class="form-group-admin-cutie"><label for="Username">Username: (Cannot change)</label><input type="text" id="Username" name="Username" value="<?php echo htmlspecialchars($data['input']['Username'] ?? ''); ?>" readonly></div>
                        <div class="form-group-admin-cutie"><label for="Email">Email: *</label><input type="email" id="Email" name="Email" value="<?php echo htmlspecialchars($data['input']['Email'] ?? ''); ?>" required></div>
                        <div class="form-group-admin-cutie"><label for="PhoneNumber">Phone Number:</label><input type="text" id="PhoneNumber" name="PhoneNumber" value="<?php echo htmlspecialchars($data['input']['PhoneNumber'] ?? ''); ?>"></div>
                        <div class="form-group-admin-cutie"><label for="Role">Role: *</label><select id="Role" name="Role" required onchange="toggleDoctorFieldsEdit()"><?php foreach (($data['roles'] ?? []) as $roleOption): ?><option value="<?php echo $roleOption; ?>" <?php echo (($data['input']['Role'] ?? '') == $roleOption) ? 'selected' : ''; ?>><?php echo $roleOption; ?></option><?php endforeach; ?></select></div>
                        <div class="form-group-admin-cutie"><label for="Status">Status: *</label><select id="Status" name="Status" required><?php foreach (($data['statuses'] ?? []) as $statusOption): ?><option value="<?php echo $statusOption; ?>" <?php echo (($data['input']['Status'] ?? '') == $statusOption) ? 'selected' : ''; ?>><?php echo $statusOption; ?></option><?php endforeach; ?></select></div>
                        <div class="form-group-admin-cutie" style="grid-column: 1 / -1;"><label for="Address">Address:</label><textarea name="Address" id="Address" rows="2"><?php echo htmlspecialchars($data['input']['Address'] ?? ''); ?></textarea></div>
                    </div>
                </fieldset>
                <fieldset id="doctorFieldsEdit" style="display: <?php echo (($data['input']['Role'] ?? '') == 'Doctor') ? 'block' : 'none'; ?>;">
                    <legend>Doctor Specific Information</legend>
                    <div class="form-grid-admin-cutie">
                        <div class="form-group-admin-cutie"><label for="SpecializationIDEdit">Specialization:</label><select id="SpecializationIDEdit" name="SpecializationID"><option value="">-- Select --</option><?php if (!empty($data['specializations'])) foreach ($data['specializations'] as $spec): ?><option value="<?php echo $spec['SpecializationID']; ?>" <?php echo (($data['input']['SpecializationID'] ?? '') == $spec['SpecializationID']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($spec['Name']); ?></option><?php endforeach; ?></select></div>
                        <div class="form-group-admin-cutie"><label for="ExperienceYearsEdit">Years of Experience:</label><input type="number" id="ExperienceYearsEdit" name="ExperienceYears" value="<?php echo htmlspecialchars($data['input']['ExperienceYears'] ?? '0'); ?>" min="0"></div>
                        <div class="form-group-admin-cutie"><label for="ConsultationFeeEdit">Consultation Fee:</label><input type="number" id="ConsultationFeeEdit" name="ConsultationFee" value="<?php echo htmlspecialchars($data['input']['ConsultationFee'] ?? '0.00'); ?>" step="0.01" min="0"></div>
                        <div class="form-group-admin-cutie" style="grid-column: 1 / -1;"><label for="BioEdit">Bio:</label><textarea name="Bio" id="BioEdit" rows="3"><?php echo htmlspecialchars($data['input']['Bio'] ?? ''); ?></textarea></div>
                    </div>
                </fieldset>
                <fieldset><legend>Change Password (Optional)</legend>
                    <div class="form-grid-admin-cutie">
                        <div class="form-group-admin-cutie"><label for="NewPassword">New Password:</label><input type="password" id="NewPassword" name="NewPassword"></div>
                        <div class="form-group-admin-cutie"><label for="ConfirmNewPassword">Confirm New Password:</label><input type="password" id="ConfirmNewPassword" name="ConfirmNewPassword"></div>
                    </div>
                </fieldset>
                <div class="form-actions-admin-cutie">
                    <button type="submit" class="btn-admin-action">Update User</button>
                    <a href="<?php echo BASE_URL; ?>/admin/listUsers" class="btn-admin-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
<script>
    function toggleDoctorFieldsEdit() {
        const roleSelect = document.getElementById('Role');
        const doctorFields = document.getElementById('doctorFieldsEdit');
        if (roleSelect && doctorFields) { doctorFields.style.display = (roleSelect.value === 'Doctor') ? 'block' : 'none';}
    }
    document.addEventListener('DOMContentLoaded', toggleDoctorFieldsEdit);
</script>
</body>
</html>