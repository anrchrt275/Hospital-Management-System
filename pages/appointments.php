<?php
require_once '../config/config.php'; // Memuat konfigurasi dan koneksi database
checkLogin(); // Cek apakah user sudah login

// Search
$search = isset($_GET['search']) ? sanitize($_GET['search']) : ''; // Ambil input pencarian
$where = $search ? "WHERE p.name LIKE '%$search%' OR d.name LIKE '%$search%'" : ''; // Query filter pencarian

// CRUD 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Jika ada request POST
    if (isset($_POST['add'])) { // Jika tambah appointment
        $patient_id = sanitize($_POST['patient_id']); // Sanitasi id pasien
        $doctor_id = sanitize($_POST['doctor_id']); // Sanitasi id dokter
        $date = sanitize($_POST['date']); // Sanitasi tanggal
        $time = sanitize($_POST['time']); // Sanitasi waktu
        $status = 'scheduled'; // Status default
        $notes = sanitize($_POST['notes']); // Sanitasi catatan
        
        $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status, notes) 
                  VALUES ($patient_id, $doctor_id, '$date', '$time', '$status', '$notes')"; // Query insert appointment
        $conn->query($query); // Eksekusi query
    } elseif (isset($_POST['edit'])) { // Jika edit appointment
        $id = sanitize($_POST['id']); // Sanitasi id appointment
        $patient_id = sanitize($_POST['patient_id']); // Sanitasi id pasien
        $doctor_id = sanitize($_POST['doctor_id']); // Sanitasi id dokter
        $date = sanitize($_POST['date']); // Sanitasi tanggal
        $time = sanitize($_POST['time']); // Sanitasi waktu
        $notes = sanitize($_POST['notes']); // Sanitasi catatan
        
        $query = "UPDATE appointments SET 
                  patient_id = $patient_id,
                  doctor_id = $doctor_id,
                  appointment_date = '$date',
                  appointment_time = '$time',
                  notes = '$notes'
                  WHERE id = $id"; // Query update appointment
        $conn->query($query); // Eksekusi query
    } elseif (isset($_POST['delete'])) { // Jika hapus appointment
        $id = sanitize($_POST['id']); // Sanitasi id appointment
        $conn->query("DELETE FROM appointments WHERE id = $id"); // Query hapus appointment
    } elseif (isset($_POST['update_status'])) { // Jika update status appointment
        $id = sanitize($_POST['id']); // Sanitasi id appointment
        $status = sanitize($_POST['status']); // Sanitasi status
        $conn->query("UPDATE appointments SET status = '$status' WHERE id = $id"); // Query update status
    }
}

// Fetch appointments with patient and doctor names
$query = "SELECT a.*, p.name as patient_name, d.name as doctor_name 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.id 
          JOIN doctors d ON a.doctor_id = d.id 
          $where 
          ORDER BY a.appointment_date DESC"; // Query untuk mengambil data appointment beserta nama pasien dan dokter
$result = $conn->query($query); // Eksekusi query

// Fetch patients and doctors for dropdowns
$patients = $conn->query("SELECT id, name FROM patients"); // Query untuk mengambil data pasien
$doctors = $conn->query("SELECT id, name FROM doctors"); // Query untuk mengambil data dokter
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logoimg.png">
    <title>Appointments - Hospital System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            display: inline-block;
            margin-bottom: 5px;
            width: 100%;
            text-align: center;
        }
        .status-scheduled { background-color: #6c757d; }
        .status-completed { background-color: #28a745; }
        .status-cancelled { background-color: #dc3545; }

        /* Desktop Styles (will not affect mobile) */
        @media (min-width: 769px) {
            .action-buttons {
                display: flex;
                gap: 0.5rem;
                align-items: center;
                justify-content: flex-end;
            }
            .action-buttons form {
                margin: 0;
            }
            .action-buttons .btn {
                min-width: 100px;
                margin: 0;
            }
            .status-select {
                min-width: 200px;
            }
            .form-select-sm {
                padding: 0.25rem 1rem;
                height: 31px;
            }
            .btn-sm {
                padding: 0.25rem 1rem;
                height: 31px;
                line-height: 1.5;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 120px;
            }
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .table-responsive {
                border: 0;
            }
            .table thead {
                display: none;
            }
            .table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .table td {
                display: block;
                text-align: right;
                padding: 0.5rem;
                border: none;
                position: relative;
                padding-left: 50%;
            }
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0.5rem;
                width: 45%;
                text-align: left;
                font-weight: bold;
            }
            .action-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .action-buttons .btn {
                width: 100%;
                margin: 0;
            }
            .status-select {
                max-width: 100%;
                margin-bottom: 0.5rem;
            }
            .modal-dialog {
                margin: 0.5rem;
            }
            .input-group {
                flex-direction: column;
            }
            .input-group .form-control,
            .input-group .btn {
                width: 100%;
                margin: 0.25rem 0;
                border-radius: 4px !important;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-5 main-content">
        <h2 class="mb-4 text-primary">Manage Appointments</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search appointments..." 
                               value="<?php echo $search; ?>">
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>
                
                <!-- Add Appointment Button -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Appointment
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <!-- Appointments Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td data-label="Patient"><?php echo $row['patient_name']; ?></td>
                                    <td data-label="Doctor"><?php echo $row['doctor_name']; ?></td>
                                    <td data-label="Date & Time">
                                        Date: <?php echo $row['appointment_date']; ?><br>
                                        Time: <?php echo $row['appointment_time']; ?>
                                    </td>
                                    <td data-label="Status">
                                        <?php
                                        $statusClass = 'status-' . $row['status'];
                                        $statusText = ucfirst($row['status']);
                                        if($row['status'] == 'scheduled') {
                                            $statusText .= ' (Pending)';
                                        }
                                        ?>
                                        <div class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo $statusText; ?>
                                        </div>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <form method="POST" class="status-select">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <?php foreach(['scheduled', 'completed', 'cancelled'] as $status): ?>
                                                        <option value="<?php echo $status; ?>" 
                                                                <?php echo $row['status'] == $status ? 'selected' : ''; ?>>
                                                            Change to: <?php echo ucfirst($status); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#notesModal<?php echo $row['id']; ?>">
                                                Notes
                                            </button>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#editModal<?php echo $row['id']; ?>">
                                                Edit
                                            </button>
                                            <form method="POST">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this appointment?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Appointment Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Patient:</label>
                            <select name="patient_id" class="form-control" required>
                                <?php while($patient = $patients->fetch_assoc()): ?>
                                    <option value="<?php echo $patient['id']; ?>">
                                        <?php echo $patient['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Doctor:</label>
                            <select name="doctor_id" class="form-control" required>
                                <?php while($doctor = $doctors->fetch_assoc()): ?>
                                    <option value="<?php echo $doctor['id']; ?>">
                                        <?php echo $doctor['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date:</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Time:</label>
                            <input type="time" name="time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Notes:</label>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Add Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Appointment Modals -->
    <?php 
    $result->data_seek(0);
    while($row = $result->fetch_assoc()): 
    ?>
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="mb-3">
                                <label>Patient:</label>
                                <select name="patient_id" class="form-control" required>
                                    <?php 
                                    $patients->data_seek(0);
                                    while($patient = $patients->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $patient['id']; ?>"
                                                <?php echo $patient['id'] == $row['patient_id'] ? 'selected' : ''; ?>>
                                            <?php echo $patient['name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Doctor:</label>
                                <select name="doctor_id" class="form-control" required>
                                    <?php 
                                    $doctors->data_seek(0);
                                    while($doctor = $doctors->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $doctor['id']; ?>"
                                                <?php echo $doctor['id'] == $row['doctor_id'] ? 'selected' : ''; ?>>
                                            <?php echo $doctor['name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Date:</label>
                                <input type="date" name="date" class="form-control" 
                                       value="<?php echo $row['appointment_date']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Time:</label>
                                <input type="time" name="time" class="form-control" 
                                       value="<?php echo $row['appointment_time']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Notes:</label>
                                <textarea name="notes" class="form-control"><?php echo $row['notes']; ?></textarea>
                            </div>
                            <button type="submit" name="edit" class="btn btn-primary">Update Appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- Notes Modals - Add this before closing body tag -->
    <?php 
    $result->data_seek(0); // Reset result pointer
    while($row = $result->fetch_assoc()): 
    ?>
        <div class="modal fade" id="notesModal<?php echo $row['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Appointment Notes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Patient:</strong> <?php echo $row['patient_name']; ?></p>
                        <p><strong>Doctor:</strong> <?php echo $row['doctor_name']; ?></p>
                        <p><strong>Date:</strong> <?php echo $row['appointment_date']; ?></p>
                        <p><strong>Time:</strong> <?php echo $row['appointment_time']; ?></p>
                        <hr>
                        <p><strong>Notes:</strong></p>
                        <p><?php echo nl2br($row['notes']) ?: 'No notes available.'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
