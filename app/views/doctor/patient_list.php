<?php
// app/views/doctor/patient_list.php

if (!defined('BASE_URL')) { /* ... BASE_URL definition ... */ }
$userFullName = $_SESSION['user_fullname'] ?? 'Doctor';
$userAvatar = $_SESSION['user_avatar'] ?? BASE_URL . '/public/assets/img/default_avatar.png';

// $data = $data ?? [ /* ... existing dummy data ... */ ];
// $patients = $data['patients'] ?? [];
// $todaysAppointments = $data['todays_appointments'] ?? [];
// $quickStats = $data['quick_stats'] ?? ['total_patients' => 0, 'new_this_month' => 0, 'avg_visits' => 0, 'high_risk' => 0];
// $prescriptionStatsData = $data['prescription_stats_data'] ?? ['labels' => [], 'counts' => []];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Patient Management'); ?> - Healthcare System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Reuse sidebar, header, main content styles from doctor/dashboard.php */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: #343a40; display: flex; min-height: 100vh; }
        .dashboard-sidebar-cutie { width: 260px; background-color: #2c3e50; color: #ecf0f1; padding: 25px 0; display: flex; flex-direction: column; }
        .sidebar-header-cutie { text-align: center; margin-bottom: 30px; padding: 0 20px; }
        .sidebar-logo-cutie { font-size: 24px; font-weight: 700; color: #fff; text-decoration: none; }
        .sidebar-nav-cutie ul { list-style: none; }
        .sidebar-nav-cutie li a { display: flex; align-items: center; padding: 15px 25px; color: #bdc3c7; text-decoration: none; font-size: 15px; font-weight: 500; border-left: 4px solid transparent; transition: all 0.2s ease; }
        .sidebar-nav-cutie li a:hover, .sidebar-nav-cutie li a.active-nav-cutie { background-color: #34495e; color: #fff; border-left-color: #3498db; }
        .sidebar-nav-cutie li a .nav-icon-cutie { margin-right: 12px; font-size: 18px; width: 20px; text-align: center; }
        .sidebar-footer-cutie { margin-top: auto; padding: 20px 25px; text-align: center; font-size: 13px; color: #7f8c8d; }

        .dashboard-main-content-cutie { flex: 1; padding: 30px; overflow-y: auto; }
        .main-header-cutie { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #dee2e6; }
        .page-title-header-cutie { display: flex; justify-content: space-between; align-items: center; width: 100%;}
        .page-title-cutie h2 { font-size: 26px; font-weight: 600; color: #2c3e50; }
        .page-actions-cutie { display: flex; gap: 10px; }
        .btn-search-patient-cutie, .btn-add-patient-modal-cutie {
            padding: 9px 18px; border: 1px solid #bdc3c7; border-radius: 6px; font-size: 14px;
            font-weight: 500; cursor: pointer; transition: background-color 0.2s ease;
        }
        .btn-search-patient-cutie { background-color: #fff; color: #34495e; }
        .btn-add-patient-modal-cutie { background-color: #3498db; color: white; border-color: #3498db; }
        .btn-add-patient-modal-cutie:hover { background-color: #2980b9; }

        .user-actions-cutie { display: flex; align-items: center; gap: 20px; margin-left: auto; /* Pushes to the right of page title actions */ }
        .user-actions-cutie .icon-button-cutie { background: none; border: none; font-size: 22px; color: #7f8c8d; cursor: pointer; }
        .user-profile-cutie { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .user-profile-cutie img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .user-profile-cutie span { font-weight: 500; font-size: 15px; color: #34495e; }
        
        .patient-stats-grid-cutie { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card-patient-cutie { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); text-align: center; }
        .stat-card-patient-cutie h4 { font-size: 13px; font-weight: 500; color: #7f8c8d; margin-bottom: 8px; text-transform: uppercase; }
        .stat-card-patient-cutie .stat-value-patient-cutie { font-size: 28px; font-weight: 700; color: #3498db; }

        .patient-content-layout-cutie { display: grid; grid-template-columns: 1fr 1.5fr; gap: 25px; } /* Adjusted grid */
        .patient-main-area-cutie { display: flex; flex-direction: column; gap: 25px; }
        .content-panel-patient-cutie { background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .content-panel-patient-cutie h3 { font-size: 18px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; }
        .chart-placeholder-patient-cutie { height: 250px; display: flex; justify-content: center; align-items: center; border: 1px dashed #bdc3c7; color: #7f8c8d; font-style: italic; }
        
        /* Today's Appointments List (similar to doctor dashboard) */
        .appointments-list-today-cutie ul { list-style: none; }
        .appointments-list-today-cutie li { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #ecf0f1; font-size: 14px; }
        .appointments-list-today-cutie li:last-child { border-bottom: none; }
        .appointments-list-today-cutie .time-patient-name-cutie { flex-grow: 1; }
        .appointments-list-today-cutie .time-cutie { font-weight: 600; color: #3498db; margin-right: 10px; }
        .appointments-list-today-cutie .patient-name-cutie { color: #34495e; }
        .no-appointments-msg-cutie { text-align: center; padding: 20px; color: #7f8c8d; font-style: italic; }

        /* Patient List Table */
        .patient-list-table-cutie { width: 100%; border-collapse: collapse; }
        .patient-list-table-cutie th, .patient-list-table-cutie td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #ecf0f1; font-size: 14px; }
        .patient-list-table-cutie th { background-color: #f7f9f9; font-weight: 600; color: #34495e; }
        .patient-list-table-cutie tbody tr:hover { background-color: #fdfdfe; }
        .patient-list-table-cutie .action-view-profile-cutie a { color: #3498db; text-decoration: none; font-weight: 500; }

        /* Modal for Add Patient (similar to add availability modal) */
        .modal-overlay-cutie { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; padding:20px; }
        .modal-overlay-cutie.visible-modal-cutie { display: flex; }
        .modal-content-cutie { background-color: #fff; padding: 25px 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); width: 100%; max-width: 650px; position: relative; max-height: 90vh; overflow-y: auto;}
        .modal-header-cutie { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header-cutie h3 { font-size: 20px; color: #2c3e50; }
        .modal-close-btn-cutie { background: none; border: none; font-size: 24px; cursor: pointer; color: #7f8c8d; }
        .modal-form-grid-patient-cutie { display: grid; grid-template-columns: 1fr 1fr; gap: 15px 20px; }
        .modal-form-grid-patient-cutie .form-group-cutie { margin-bottom: 0; }
        .modal-form-grid-patient-cutie .full-width-modal-cutie { grid-column: 1 / -1; }
        .modal-form-grid-patient-cutie label { font-size: 13px; color: #495057; margin-bottom: 6px; display: block; }
        .modal-form-grid-patient-cutie input, .modal-form-grid-patient-cutie select, .modal-form-grid-patient-cutie textarea { width: 100%; padding: 9px 10px; border: 1px solid #ced4da; border-radius: 5px; font-size: 14px; }
        .modal-form-grid-patient-cutie input:focus, .modal-form-grid-patient-cutie select:focus, .modal-form-grid-patient-cutie textarea:focus { border-color: #3498db; box-shadow: 0 0 0 0.15rem rgba(52,152,219,.25); outline: none; }
        .modal-actions-cutie { margin-top: 25px; text-align: right; }
        .btn-save-patient-cutie { background-color: #2ecc71; color: white; padding: 10px 20px; border:none; border-radius: 6px; font-size: 15px; cursor: pointer; }
        .btn-save-patient-cutie:hover { background-color: #27ae60; }
        .message-modal-cutie { margin-top: 10px; font-size: 13px; }

        @media (max-width: 1200px) { .patient-content-layout-cutie { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { /* Sidebar responsive */ }
    </style>
</head>
<body>
    <aside class="dashboard-sidebar-cutie">
        <div class="sidebar-header-cutie"><a href="<?php echo BASE_URL; ?>" class="sidebar-logo-cutie">HealthSys</a></div>
        <nav class="sidebar-nav-cutie">
            <ul>
                <li><a href="<?php echo BASE_URL; ?>/doctor/dashboard"><span class="nav-icon-cutie">🏠</span>Dashboard</a></li>
                <li><a href="<?php echo BASE_URL; ?>/doctor/mySchedule"><span class="nav-icon-cutie">🗓️</span>View My Schedule</a></li>
                <li><a href="<?php echo BASE_URL; ?>/medicalrecord/viewConsultationDetails"><span class="nav-icon-cutie">📝</span>EMR</a></li>
                <li><a href="<?php echo BASE_URL; ?>/doctor/manageAvailability"><span class="nav-icon-cutie">⏱️</span>Manage Availability</a></li>
                <li><a href="<?php echo BASE_URL; ?>/doctor/patientList" class="active-nav-cutie"><span class="nav-icon-cutie">👥</span>Patient List</a></li>
                <li><a href="#"><span class="nav-icon-cutie">🔔</span>Notifications</a></li>
                <li><a href="#"><span class="nav-icon-cutie">✈️</span>Request Time Off</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer-cutie">© <?php echo date('Y'); ?> Healthcare System</div>
    </aside>

    <main class="dashboard-main-content-cutie">
        <header class="main-header-cutie">
            <div class="page-title-header-cutie">
                <div class="page-title-cutie"><h2><?php echo htmlspecialchars($data['title'] ?? 'Patient Management'); ?></h2></div>
                <div class="page-actions-cutie">
                    <button type="button" class="btn-search-patient-cutie">🔍 Search</button> <!-- Add search functionality later -->
                    <button type="button" class="btn-add-patient-modal-cutie" id="openAddPatientModalBtn">+ Add Patient</button>
                </div>
            </div>
            <div class="user-actions-cutie">
                <button class="icon-button-cutie" title="Notifications">🔔</button>
                <div class="user-profile-cutie">
                    <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="User Avatar">
                    <span>Dr. <?php echo htmlspecialchars($userFullName); ?></span> ▼
                </div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="icon-button-cutie" title="Logout" style="text-decoration:none;">🚪</a>
            </div>
        </header>

        <section class="patient-stats-grid-cutie">
            <div class="stat-card-patient-cutie"><h4>Total Patients</h4><div class="stat-value-patient-cutie"><?php echo htmlspecialchars($data['quick_stats']['total_patients'] ?? 0); ?></div></div>
            <div class="stat-card-patient-cutie"><h4>New This Month</h4><div class="stat-value-patient-cutie"><?php echo htmlspecialchars($data['quick_stats']['new_this_month'] ?? 0); ?></div></div>
            <div class="stat-card-patient-cutie"><h4>Avg. Visits/Patient</h4><div class="stat-value-patient-cutie"><?php echo htmlspecialchars($data['quick_stats']['avg_visits'] ?? 0); ?></div></div>
            <div class="stat-card-patient-cutie"><h4>High-Risk</h4><div class="stat-value-patient-cutie"><?php echo htmlspecialchars($data['quick_stats']['high_risk'] ?? 0); ?></div></div>
        </section>

        <div class="patient-content-layout-cutie">
            <div class="content-panel-patient-cutie">
                <h3>Patient List</h3>
                <div class="patient-list-table-cutie">
                    <table>
                        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Last Visit</th><th>Action</th></tr></thead>
                        <tbody>
                        <?php if (!empty($data['patients'])): ?>
                            <?php foreach ($data['patients'] as $patient): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($patient['FullName']); ?></td>
                                <td><?php echo htmlspecialchars($patient['Email']); ?></td>
                                <td><?php echo htmlspecialchars($patient['PhoneNumber'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($patient['LastVisitDate'] ?? 'N/A'); ?></td>
                                <td class="action-view-profile-cutie"><a href="<?php echo BASE_URL . '/doctor/viewPatientProfile/' . $patient['PatientID']; ?>">View Profile</a></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center; padding:20px;">No patients found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="patient-main-area-cutie">
                <div class="content-panel-patient-cutie">
                    <h3>Prescriptions Stats</h3>
                    <div class="chart-placeholder-patient-cutie" style="height:180px;"><canvas id="prescriptionStatsChart"></canvas></div>
                </div>
                <div class="content-panel-patient-cutie appointments-list-today-cutie">
                    <h3>Today's Appointments</h3>
                    <?php if (!empty($data['todays_appointments'])): ?>
                        <ul>
                            <?php foreach ($data['todays_appointments'] as $appt): ?>
                                <li>
                                    <div class="time-patient-name-cutie">
                                        <span class="time-cutie"><?php echo htmlspecialchars(date("h:i A", strtotime($appt['AppointmentDateTime']))); ?></span>
                                        <span class="patient-name-cutie"><?php echo htmlspecialchars($appt['PatientName']); ?></span>
                                    </div>
                                    <a href="<?php echo BASE_URL . '/medicalrecord/viewConsultationDetails/' . $appt['AppointmentID']; ?>" style="font-size:13px;">Details</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-appointments-msg-cutie">No appointments for today.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Patient Modal -->
    <div class="modal-overlay-cutie" id="addPatientModal">
        <div class="modal-content-cutie">
            <div class="modal-header-cutie">
                <h3>Add New Patient</h3>
                <button type="button" class="modal-close-btn-cutie" id="closeAddPatientModalBtn">×</button>
            </div>
            <form id="addPatientFormModal">
                <?php if (function_exists('generateCsrfInput')) { echo generateCsrfInput(); } ?>
                <div class="modal-form-grid-patient-cutie">
                    <div class="form-group-cutie"><label for="modal_fullname">Full Name:</label><input type="text" id="modal_fullname" name="fullname" required></div>
                    <div class="form-group-cutie"><label for="modal_username">Username:</label><input type="text" id="modal_username" name="username" required></div>
                    <div class="form-group-cutie"><label for="modal_email">Email:</label><input type="email" id="modal_email" name="email" required></div>
                    <div class="form-group-cutie"><label for="modal_phone_number">Phone Number:</label><input type="text" id="modal_phone_number" name="phone_number"></div>
                    <div class="form-group-cutie"><label for="modal_password">Temporary Password:</label><input type="password" id="modal_password" name="password" required></div>
                    <div class="form-group-cutie"><label for="modal_confirm_password">Confirm Password:</label><input type="password" id="modal_confirm_password" name="confirm_password" required></div>
                    <div class="form-group-cutie"><label for="modal_date_of_birth">Date of Birth:</label><input type="date" id="modal_date_of_birth" name="date_of_birth"></div>
                    <div class="form-group-cutie"><label for="modal_gender">Gender:</label><select id="modal_gender" name="gender"><option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select></div>
                    <div class="form-group-cutie full-width-modal-cutie"><label for="modal_address">Address:</label><textarea id="modal_address" name="address" rows="2"></textarea></div>
                </div>
                <div class="modal-actions-cutie">
                    <button type="submit" class="btn-save-patient-cutie">Add Patient</button>
                </div>
            </form>
            <div id="addPatientModalResult" class="message-modal-cutie"></div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addPatientModal = document.getElementById('addPatientModal');
    const openAddPatientBtn = document.getElementById('openAddPatientModalBtn');
    const closeAddPatientBtn = document.getElementById('closeAddPatientModalBtn');
    const addPatientForm = document.getElementById('addPatientFormModal');
    const addPatientResultDiv = document.getElementById('addPatientModalResult');

    openAddPatientBtn?.addEventListener('click', () => addPatientModal.classList.add('visible-modal-cutie'));
    closeAddPatientBtn?.addEventListener('click', () => addPatientModal.classList.remove('visible-modal-cutie'));
    window.addEventListener('click', (event) => { if (event.target == addPatientModal) addPatientModal.classList.remove('visible-modal-cutie'); });

    addPatientForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        addPatientResultDiv.innerHTML = '<p><em>Adding patient...</em></p>';
        const formData = new FormData(this);
        // Add CSRF if needed and not already in form by generateCsrfInput()
        
        fetch('<?php echo BASE_URL; ?>/doctor/addPatient', { method: 'POST', body: formData })
        .then(response => response.json().then(data => ({status: response.status, body: data })))
        .then(res => {
            if (res.body.success) {
                addPatientResultDiv.innerHTML = `<p style="color:green;">${res.body.message}</p>`;
                setTimeout(() => {
                    addPatientModal.classList.remove('visible-modal-cutie');
                    addPatientResultDiv.innerHTML = '';
                    this.reset();
                    window.location.reload(); // Reload to see new patient in list
                }, 1500);
            } else {
                let errorMsg = res.body.message || 'Error adding patient.';
                if (res.body.errors) { // Display field-specific errors if available
                    errorMsg += '<ul>';
                    for (const field in res.body.errors) {
                        errorMsg += `<li>${res.body.errors[field]}</li>`;
                    }
                    errorMsg += '</ul>';
                }
                addPatientResultDiv.innerHTML = `<p style="color:red;">${errorMsg}</p>`;
            }
        })
        .catch(error => {
            console.error('Add patient error:', error);
            addPatientResultDiv.innerHTML = `<p style="color:red;">Request failed: ${error.message}</p>`;
        });
    });

    // Prescription Stats Chart (Placeholder)
    const prescCtx = document.getElementById('prescriptionStatsChart');
    if (prescCtx) {
        new Chart(prescCtx, {
            type: 'doughnut', // Example chart type
            data: {
                labels: <?php echo json_encode($data['prescription_stats_data']['labels'] ?? ['Common', 'Rare', 'Controlled']); ?>,
                datasets: [{
                    label: 'Prescription Types',
                    data: <?php echo json_encode($data['prescription_stats_data']['counts'] ?? [65, 25, 10]); ?>,
                    backgroundColor: ['#3498db', '#f1c40f', '#e74c3c'],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom'}}}
        });
    }
});
</script>
</body>
</html>