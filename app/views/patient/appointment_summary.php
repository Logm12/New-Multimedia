<?php
// app/views/patient/appointment_summary.php

if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script_name_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base_path = ($script_name_dir == '/' || $script_name_dir == '\\') ? '' : rtrim($script_name_dir, '/');
    define('BASE_URL', $protocol . $host . $base_path);
}

$userFullName = $_SESSION['user_fullname'] ?? 'Valued Patient';
// Bằng khối code này:
// Determine avatar source carefully
$currentAvatarPath = $_SESSION['user_avatar'] ?? null; // Get from session first
$avatarSrc = BASE_URL . '/public/assets/images/default_avatar.png'; // Default
if (!empty($currentAvatarPath) && $currentAvatarPath !== 'default_avatar.png') {
    if (filter_var($currentAvatarPath, FILTER_VALIDATE_URL)) {
        $avatarSrc = htmlspecialchars($currentAvatarPath);
    } elseif (file_exists(PUBLIC_PATH . $currentAvatarPath)) {
         $avatarSrc = BASE_URL . '/' . htmlspecialchars($currentAvatarPath);
    }
}

// $data = $data ?? [ /* ... existing dummy data ... */ ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Appointment Summary'); ?> - Healthcare System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ... (All CSS styles remain the same as the previous version) ... */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: #343a40; display: flex; min-height: 100vh; }

        .dashboard-sidebar-cutie {
            width: 260px; background-color: rgb(10,46,106);; color: #fff;
            padding: 25px 0; display: flex; flex-direction: column;
        }
        .sidebar-header-cutie { text-align: center; margin-bottom: 30px; padding: 0 20px; }
        .sidebar-logo-cutie { font-size: 24px; font-weight: 700; color: #fff; text-decoration: none; display: block; }
        
        .sidebar-nav-cutie ul { list-style: none; }
        .sidebar-nav-cutie li a {
            display: flex; align-items: center; padding: 15px 25px; color: #e0e0e0; text-decoration: none;
            font-size: 15px; font-weight: 500; transition: background-color 0.2s ease, color 0.2s ease;
            border-left: 4px solid transparent;
        }
        .sidebar-nav-cutie li a:hover, .sidebar-nav-cutie li a.active-nav-cutie {
            background-color: rgba(255,255,255,0.1); color: #fff; border-left-color: #fff;
        }
        .sidebar-nav-cutie li a .nav-icon-cutie { margin-right: 12px; font-size: 18px; width: 20px; text-align: center; }
        .sidebar-footer-cutie { margin-top: auto; padding: 20px 25px; text-align: center; font-size: 13px; color: #c0c0c0; }

        .dashboard-main-content-cutie { flex: 1; padding: 30px; overflow-y: auto; }
        .main-header-cutie {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;
            padding-bottom: 20px; border-bottom: 1px solid #dee2e6;
        }
        .page-title-cutie h2 { font-size: 26px; font-weight: 600; color: #212529; }
        
       /* Container chung cho các hành động của user */
.user-actions {
    display: flex;
    align-items: center;
    gap: 15px; /* Khoảng cách giữa các phần tử */
}

/* Style cho các nút icon như chuông thông báo */
.icon-button {
    background: none;
    border: none;
    font-size: 20px; /* Kích thước icon lớn hơn một chút */
    color: #6c757d;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background-color 0.2s ease, color 0.2s ease;
}
.icon-button:hover {
    background-color: #f1f3f5;
    color: #343a40;
}

/* --- Phần Dropdown Profile --- */
.profile-dropdown {
    position: relative; /* Quan trọng để định vị menu con */
}

/* Nút bấm để mở menu */
.profile-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    background-color: transparent;
    border: none;
    padding: 4px 8px;
    border-radius: 20px;
    transition: background-color 0.2s ease;
}
.profile-trigger:hover {
    background-color: #e9ecef;
}

.profile-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-name {
    font-weight: 500;
    font-size: 15px;
    color: #495057;
}

.dropdown-arrow {
    font-size: 12px;
    color: #6c757d;
}

/* Menu dropdown con */
.dropdown-menu {
    position: absolute;
    top: calc(100% + 10px); /* Vị trí dưới nút trigger, có khoảng cách 10px */
    right: 0;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    min-width: 200px; /* Độ rộng tối thiểu */
    z-index: 1000;
    border: 1px solid #e9ecef;
    padding: 8px 0;
    overflow: hidden;
    transition: opacity 0.2s ease, transform 0.2s ease;
}

/* Trạng thái ẩn của menu (dùng cho JS) */
.dropdown-menu.hidden {
    opacity: 0;
    transform: translateY(-10px);
    pointer-events: none; /* Không thể click khi đang ẩn */
}

/* Các mục trong menu */
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    font-size: 14px;
    color: #495057;
    text-decoration: none;
    transition: background-color 0.2s ease;
}
.dropdown-item i {
    width: 16px; /* Căn chỉnh icon */
    text-align: center;
    color: #868e96;
}
.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Mục logout có màu đỏ để nhấn mạnh */
.dropdown-item-logout:hover {
    background-color: #fff5f5;
    color: #e03131;
}
.dropdown-item-logout:hover i {
    color: #e03131;
}

/* Đường kẻ phân cách */
.dropdown-divider {
    height: 1px;
    background-color: #e9ecef;
    margin: 8px 0;
}

        .summary-page-container-cutie { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px; }
        
        .appointment-info-header-cutie { margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px dashed #e0e0e0;}
        .appointment-info-header-cutie p { font-size: 15px; color: #495057; margin-bottom: 8px; line-height: 1.6; }
        .appointment-info-header-cutie p strong { font-weight: 600; color: #343a40; min-width: 130px; display: inline-block; }
        
        .consultation-details-section-cutie { background-color: #f8f9fa; border-radius: 8px; padding: 25px; margin-bottom: 25px; }
        .consultation-details-section-cutie h3 { font-size: 18px; font-weight: 600; color: #343a40; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0;}
        .consultation-details-section-cutie p { font-size: 14px; color: #495057; margin-bottom: 12px; line-height: 1.7; }
        .consultation-details-section-cutie p strong { font-weight: 600; color: #343a40; display: block; margin-bottom: 4px; }
        .consultation-details-section-cutie .no-details-msg-cutie { font-style: italic; color: #6c757d; }

        .view-prescription-btn-cutie {
            display: inline-block; background-color: #667EEA; color: white; padding: 10px 20px;
            border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;
            text-decoration: none; transition: background-color 0.2s ease; margin-top: 15px; float: right;
        }
        .view-prescription-btn-cutie:hover { background-color: #5a67d8; }
        
        .prescription-table-container-cutie { margin-top: 20px; display: none; }
        .prescription-table-container-cutie.visible-cutie { display: block; animation: fadeInTableCutie 0.5s ease; }
        @keyframes fadeInTableCutie { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .prescription-table-cutie { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .prescription-table-cutie th, .prescription-table-cutie td {
            padding: 10px 12px; text-align: left; border: 1px solid #e0e0e0; font-size: 14px;
        }
        .prescription-table-cutie th { background-color: #f1f3f5; font-weight: 600; color: #495057; }

        .action-footer-cutie { margin-top: 30px; text-align: left; }
        .action-footer-cutie .btn-back-cutie {
            padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none;
            border-radius: 6px; font-size: 14px; transition: background-color 0.2s ease;
        }
        .action-footer-cutie .btn-back-cutie:hover { background-color: #5a6268; }

        .message-cutie { padding: 10px 15px; margin-bottom: 15px; border-radius: 6px; font-size: 14px; }
        .error-message { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .dashboard-sidebar-cutie { width: 100%; height: auto; flex-direction: row; overflow-x: auto; padding: 10px 0; justify-content: flex-start; }
            .sidebar-header-cutie { display: none; }
            .sidebar-nav-cutie ul { display: flex; flex-direction: row; }
            .sidebar-nav-cutie li a { padding: 10px 15px; border-left: none; border-bottom: 3px solid transparent; }
            .sidebar-nav-cutie li a:hover, .sidebar-nav-cutie li a.active-nav-cutie { border-bottom-color: #fff; background-color: transparent; }
            .sidebar-footer-cutie { display: none; }
            .dashboard-main-content-cutie { padding: 20px; }
            .summary-page-container-cutie { padding: 20px; }
            .consultation-details-section-cutie { padding: 20px; }
            .view-prescription-btn-cutie { float: none; display: block; text-align: center; margin-bottom: 15px;}
        }
    </style>
</head>
<body>
    <aside class="dashboard-sidebar-cutie">
        <div class="sidebar-header-cutie">
            <a href="<?php echo BASE_URL; ?>" class="sidebar-logo-cutie">PulseCare</a>
        </div>
        <nav class="sidebar-nav-cutie">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>/patient/dashboard" class="<?php echo (strpos($_GET['url'] ?? '', 'patient/dashboard') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">🏠</span>Dashboard</a></li>
                <li><a href="<?php echo $data['browse_doctors_link'] ?? BASE_URL . '/patient/browseDoctors'; ?>" class="<?php echo (strpos($_GET['url'] ?? '', 'patient/browseDoctors') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">🩺</span>Browse Doctors</a></li>
                <li><a href="<?php echo BASE_URL; ?>/appointment/myAppointments" class="<?php echo (strpos($_GET['url'] ?? '', 'appointment/myAppointments') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">🗓️</span>My Appointments</a></li>
                <li><a href="<?php echo BASE_URL; ?>/patient/viewAllMedicalRecords" class="<?php echo (strpos($_GET['url'] ?? '', 'patient/viewAllMedicalRecords') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">📜</span>Medical Records</a></li>
                <li><a href="<?php echo BASE_URL; ?>/feedback/list" class="<?php echo (strpos($_GET['url'] ?? '', 'feedback/list') !== false || strpos($_GET['url'] ?? '', 'feedback/submit') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">⭐</span>Feedback</a></li>
                <li><a href="<?php echo BASE_URL; ?>/patient/updateProfile" class="<?php echo (strpos($_GET['url'] ?? '', 'patient/updateProfile') !== false) ? 'active-nav-cutie' : ''; ?>"><span class="nav-icon-cutie">👤</span>Profile</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer-cutie">
            © <?php echo date('Y'); ?> Healthcare System
        </div>
    </aside>

    <main class="dashboard-main-content-cutie">
        <header class="main-header-cutie">
            <div class="page-title-cutie">
                <h2><?php echo htmlspecialchars($data['title'] ?? 'Appointment Summary'); ?></h2>
            </div>
           <div class="user-actions">
    <!-- Nút thông báo với icon từ Font Awesome -->
    <button class="icon-button" title="Notifications">
        <i class="fas fa-bell"></i>
    </button>

    <!-- Khu vực profile, bao gồm cả trigger và menu dropdown -->
    <div class="profile-dropdown">
        <!-- Phần này là nút bấm để mở menu -->
        <button class="profile-trigger" id="profileDropdownTrigger">
            <img src="<?php echo htmlspecialchars($avatarSrc); ?>" alt="User Avatar" class="profile-avatar">
            <span class="profile-name"><?php echo htmlspecialchars($userFullName); ?></span>
            <i class="fas fa-caret-down dropdown-arrow"></i>
        </button>

        <!-- Menu dropdown, mặc định sẽ bị ẩn -->
        <div class="dropdown-menu hidden" id="profileDropdownMenu">
            <a href="<?php echo BASE_URL; ?>/patient/updateProfile" class="dropdown-item">
                <i class="fas fa-user-circle"></i> My Profile
            </a>
            <a href="#" class="dropdown-item">
                <i class="fas fa-cog"></i> Settings
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo BASE_URL; ?>/auth/logout" class="dropdown-item dropdown-item-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>
        </header>

        <?php if (isset($_SESSION['summary_message_error'])): ?>
            <p class="message-cutie error-message"><?php echo $_SESSION['summary_message_error']; unset($_SESSION['summary_message_error']); ?></p>
        <?php endif; ?>

        <?php if (isset($data['appointment']) && $data['appointment']): ?>
        <div class="summary-page-container-cutie">
            <div class="appointment-info-header-cutie">
                <p><strong>Date & Time:</strong> <?php echo htmlspecialchars(date('D, M j, Y \a\t g:i A', strtotime($data['appointment']['AppointmentDateTime']))); ?></p>
                <p><strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($data['appointment']['DoctorName']); ?></p>
                <p><strong>Specialization:</strong> <?php echo htmlspecialchars($data['appointment']['SpecializationName'] ?? 'N/A'); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($data['appointment']['Status']); ?></p>
                <?php if (!empty($data['appointment']['ReasonForVisit'])): ?>
                    <p><strong>Reason for Visit:</strong> <?php echo nl2br(htmlspecialchars($data['appointment']['ReasonForVisit'])); ?></p>
                <?php endif; ?>
            </div>

            <div class="consultation-details-section-cutie">
                <h3>Consultation Summary</h3>
                <?php if (isset($data['medicalRecord']) && $data['medicalRecord']): ?>
                    <?php if (!empty($data['medicalRecord']['Symptoms'])): ?>
                        <p><strong>Symptoms:</strong><br><?php echo nl2br(htmlspecialchars($data['medicalRecord']['Symptoms'])); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($data['medicalRecord']['TestResults'])): ?>
                        <p><strong>Test Results:</strong><br><?php echo nl2br(htmlspecialchars($data['medicalRecord']['TestResults'])); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($data['medicalRecord']['Diagnosis'])): ?>
                        <p><strong>Diagnosis:</strong><br><?php echo nl2br(htmlspecialchars($data['medicalRecord']['Diagnosis'])); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($data['medicalRecord']['TreatmentPlan'])): ?>
                        <p><strong>Treatment Plan:</strong><br><?php echo nl2br(htmlspecialchars($data['medicalRecord']['TreatmentPlan'])); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($data['medicalRecord']['Notes'])): ?>
                        <p><strong>Doctor's Notes (Conclusion):</strong><br><?php echo nl2br(htmlspecialchars($data['medicalRecord']['Notes'])); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($data['prescriptions'])): ?>
                        <button type="button" class="view-prescription-btn-cutie" id="togglePrescriptionBtn">View Prescription Details</button>
                        <div class="prescription-table-container-cutie" id="prescriptionTableContainer">
                            <table class="prescription-table-cutie">
                                <thead>
                                    <tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Instructions</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['prescriptions'] as $prescribed): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($prescribed['MedicineName']); ?> (<?php echo htmlspecialchars($prescribed['MedicineUnit'] ?? ''); ?>)</td>
                                        <td><?php echo htmlspecialchars($prescribed['Dosage']); ?></td>
                                        <td><?php echo htmlspecialchars($prescribed['Frequency']); ?></td>
                                        <td><?php echo htmlspecialchars($prescribed['Duration']); ?></td>
                                        <td><?php echo htmlspecialchars($prescribed['Instructions'] ?? ''); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="no-details-msg-cutie" style="margin-top:15px;">No medication was prescribed for this consultation.</p>
                    <?php endif; ?>

                <?php else: ?>
                    <p class="no-details-msg-cutie">Consultation details have not been recorded yet.</p>
                <?php endif; ?>
            </div>

            <div class="action-footer-cutie">
                <!-- MODIFIED LINE BELOW -->
                <a href="<?php echo BASE_URL; ?>/patient/viewAllMedicalRecords" class="btn-back-cutie">« Back to Medical Records</a>
            </div>
        </div>
        <?php else: ?>
            <p class="message-cutie error-message">Sorry, sweetie, we couldn't find the details for this appointment.</p>
             <div class="action-footer-cutie">
                <!-- MODIFIED LINE BELOW -->
                <a href="<?php echo BASE_URL; ?>/patient/viewAllMedicalRecords" class="btn-back-cutie">« Back to Medical Records</a>
            </div>
        <?php endif; ?>
    </main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePrescriptionBtn');
        const prescriptionTable = document.getElementById('prescriptionTableContainer');

        if (toggleBtn && prescriptionTable) {
            toggleBtn.addEventListener('click', function() {
                prescriptionTable.classList.toggle('visible-cutie');
                if (prescriptionTable.classList.contains('visible-cutie')) {
                    this.textContent = 'Hide Prescription Details';
                } else {
                    this.textContent = 'View Prescription Details';
                }
            });
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('profileDropdownTrigger');
    const menu = document.getElementById('profileDropdownMenu');

    if (trigger && menu) {
        // Sự kiện khi click vào nút trigger
        trigger.addEventListener('click', function(event) {
            event.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
            menu.classList.toggle('hidden');
        });

        // Sự kiện khi click ra ngoài menu thì đóng menu lại
        window.addEventListener('click', function(event) {
            if (!menu.contains(event.target) && !trigger.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    }
});
</script>
</body>
</html>