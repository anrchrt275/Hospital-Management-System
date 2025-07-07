<?php
require_once '../config/config.php'; // Memuat konfigurasi dan koneksi database
checkLogin(); // Cek apakah user sudah login

// Search functionality
$search = isset($_GET['search']) ? sanitize($_GET['search']) : ''; // Ambil input pencarian
$where = $search ? "WHERE name LIKE '%$search%' OR phone LIKE '%$search%'" : ''; // Query filter pencarian

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Jika ada request POST
    if (isset($_POST['add'])) { // Jika tambah pasien
        $name = sanitize($_POST['name']); // Sanitasi nama
        $dob = sanitize($_POST['dob']); // Sanitasi tanggal lahir
        $gender = sanitize($_POST['gender']); // Sanitasi gender
        $phone = sanitize($_POST['phone']); // Sanitasi telepon
        $address = sanitize($_POST['address']); // Sanitasi alamat
        
        $query = "INSERT INTO patients (name, dob, gender, phone, address) 
                  VALUES ('$name', '$dob', '$gender', '$phone', '$address')"; // Query insert pasien
        $conn->query($query); // Eksekusi query
        
    } elseif (isset($_POST['delete'])) { // Jika hapus pasien
        $id = sanitize($_POST['id']); // Sanitasi id pasien
        $conn->query("DELETE FROM patients WHERE id = $id"); // Query hapus pasien
    } elseif (isset($_POST['edit'])) { // Jika edit pasien
        $id = sanitize($_POST['id']); // Sanitasi id pasien
        $name = sanitize($_POST['name']); // Sanitasi nama
        $dob = sanitize($_POST['dob']); // Sanitasi tanggal lahir
        $gender = sanitize($_POST['gender']); // Sanitasi gender
        $phone = sanitize($_POST['phone']); // Sanitasi telepon
        $address = sanitize($_POST['address']); // Sanitasi alamat
        
        $query = "UPDATE patients SET 
                  name = '$name',
                  dob = '$dob',
                  gender = '$gender',
                  phone = '$phone',
                  address = '$address'
                  WHERE id = $id"; // Query update pasien
        $conn->query($query); // Eksekusi query
    }
}

// Fetch patients
$result = $conn->query("SELECT * FROM patients $where ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logoimg.png">
    <title>Patients - Hospital System</title>
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

        /* Desktop Styles */
        @media (min-width: 769px) {
            .action-buttons {
                display: flex;
                gap: 0.75rem;
                align-items: center;
                justify-content: flex-end;
            }
            .action-buttons form {
                margin: 0;
            }
            .action-buttons .btn {
                min-width: 80px;
                height: 31px;
                padding: 0.25rem 1rem;
                line-height: 1.5;
            }
            .table td[data-label="Actions"] {
                min-width: 200px;
                white-space: nowrap;
            }
            .btn-sm {
                font-size: 0.875rem;
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
                padding: 0.5rem;
            }
            .table td {
                display: block;
                text-align: left;
                padding: 0.5rem;
                border: none;
                position: relative;
            }
            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 0.25rem;
            }
            .action-buttons {
                display: flex;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }
            .action-buttons .btn,
            .action-buttons form {
                flex: 1;
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
            .modal-dialog {
                margin: 0.5rem;
            }
            .modal-content {
                border-radius: 0.5rem;
            }
            .btn {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-5 main-content">
        <h2 class="mb-4 text-primary">Manage Patients</h2>
        
        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search patients..." 
                               value="<?php echo $search; ?>">
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Add Patient Form -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            Add Patient
        </button>
        
        <!-- Patients Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Name"><?php echo $row['name']; ?></td>
                            <td data-label="DOB"><?php echo $row['dob']; ?></td>
                            <td data-label="Gender"><?php echo $row['gender']; ?></td>
                            <td data-label="Phone"><?php echo $row['phone']; ?></td>
                            <td data-label="Actions">
                                <div class="action-buttons">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo $row['id']; ?>">
                                        Edit
                                    </button>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm w-100"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Add Patient Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Date of Birth:</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Gender:</label>
                            <select name="gender" class="form-control" required>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Phone:</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Address:</label>
                            <textarea name="address" class="form-control" required></textarea>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Add Patient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Patient Modals -->
    <?php 
    $result->data_seek(0);
    while($row = $result->fetch_assoc()): 
    ?>
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="mb-3">
                                <label>Name:</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?php echo $row['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Date of Birth:</label>
                                <input type="date" name="dob" class="form-control" 
                                       value="<?php echo $row['dob']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Gender:</label>
                                <select name="gender" class="form-control" required>
                                    <option value="M" <?php echo $row['gender'] == 'M' ? 'selected' : ''; ?>>Male</option>
                                    <option value="F" <?php echo $row['gender'] == 'F' ? 'selected' : ''; ?>>Female</option>
                                    <option value="O" <?php echo $row['gender'] == 'O' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Phone:</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?php echo $row['phone']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Address:</label>
                                <textarea name="address" class="form-control" required><?php echo $row['address']; ?></textarea>
                            </div>
                            <button type="submit" name="edit" class="btn btn-primary">Update Patient</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/custom.js"></script>
</body>
</html>
