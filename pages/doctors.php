<?php
require_once '../config/config.php'; // Memuat konfigurasi dan koneksi database
checkLogin(); // Cek apakah user sudah login

// Search functionality
$search = isset($_GET['search']) ? sanitize($_GET['search']) : ''; // Ambil input pencarian
$where = $search ? "WHERE d.name LIKE '%$search%' OR d.specialization LIKE '%$search%'" : ''; // Query filter pencarian

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Jika ada request POST
    if (isset($_POST['add'])) { // Jika tambah dokter
        $name = sanitize($_POST['name']); // Sanitasi nama dokter
        $department_id = sanitize($_POST['department_id']); // Sanitasi id departemen
        $specialization = sanitize($_POST['specialization']); // Sanitasi spesialisasi
        $phone = sanitize($_POST['phone']); // Sanitasi telepon
        $email = sanitize($_POST['email']); // Sanitasi email
        
        $query = "INSERT INTO doctors (name, department_id, specialization, phone, email) 
                  VALUES ('$name', $department_id, '$specialization', '$phone', '$email')"; // Query insert dokter
        $conn->query($query); // Eksekusi query
    } elseif (isset($_POST['delete'])) { // Jika hapus dokter
        $id = sanitize($_POST['id']); // Sanitasi id dokter
        $conn->query("DELETE FROM doctors WHERE id = $id"); // Query hapus dokter
    } elseif (isset($_POST['edit'])) { // Jika edit dokter
        $id = sanitize($_POST['id']); // Sanitasi id dokter
        $name = sanitize($_POST['name']); // Sanitasi nama dokter
        $department_id = sanitize($_POST['department_id']); // Sanitasi id departemen
        $specialization = sanitize($_POST['specialization']); // Sanitasi spesialisasi
        $phone = sanitize($_POST['phone']); // Sanitasi telepon
        $email = sanitize($_POST['email']); // Sanitasi email
        
        $query = "UPDATE doctors SET 
                  name = '$name',
                  department_id = $department_id,
                  specialization = '$specialization',
                  phone = '$phone',
                  email = '$email'
                  WHERE id = $id"; // Query update dokter
        $conn->query($query); // Eksekusi query
    }
}

// Fetch doctors with department names
$query = "SELECT d.*, dept.name as department_name 
          FROM doctors d 
          LEFT JOIN departments dept ON d.department_id = dept.id 
          $where 
          ORDER BY d.created_at DESC"; // Query untuk mengambil data dokter beserta nama departemen
$result = $conn->query($query); // Eksekusi query

// Fetch departments for dropdown
$departments = $conn->query("SELECT * FROM departments"); // Query untuk mengambil semua departemen
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logoimg.png">
    <title>Doctors - Hospital System</title>
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
                gap: 0.5rem;
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
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }
            .action-buttons .btn {
                width: 100%;
                padding: 0.5rem;
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
            .card {
                border-radius: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-5 main-content">
        <h2 class="mb-4 text-primary">Manage Doctors</h2>
        
        <div class="card mb-4">
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search doctors..." 
                               value="<?php echo $search; ?>">
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>
                
                <!-- Add Doctor Button -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add Doctor
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <!-- Doctors Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Specialization</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td data-label="Name"><?php echo $row['name']; ?></td>
                                    <td data-label="Department"><?php echo $row['department_name']; ?></td>
                                    <td data-label="Specialization"><?php echo $row['specialization']; ?></td>
                                    <td data-label="Contact">
                                        <strong>Email:</strong> <?php echo $row['email']; ?><br>
                                        <strong>Phone:</strong> <?php echo $row['phone']; ?>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#editModal<?php echo $row['id']; ?>">
                                                Edit
                                            </button>
                                            <form method="POST" class="w-100">
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
        </div>
    </div>
    
    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Department:</label>
                            <select name="department_id" class="form-control" required>
                                <?php while($dept = $departments->fetch_assoc()): ?>
                                    <option value="<?php echo $dept['id']; ?>">
                                        <?php echo $dept['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Specialization:</label>
                            <input type="text" name="specialization" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone:</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Add Doctor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Doctor Modals -->
    <?php 
    $result->data_seek(0);
    while($row = $result->fetch_assoc()): 
    ?>
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Doctor</h5>
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
                                <label>Department:</label>
                                <select name="department_id" class="form-control" required>
                                    <?php 
                                    $departments->data_seek(0);
                                    while($dept = $departments->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $dept['id']; ?>"
                                                <?php echo $dept['id'] == $row['department_id'] ? 'selected' : ''; ?>>
                                            <?php echo $dept['name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Specialization:</label>
                                <input type="text" name="specialization" class="form-control" 
                                       value="<?php echo $row['specialization']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Phone:</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?php echo $row['phone']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo $row['email']; ?>" required>
                            </div>
                            <button type="submit" name="edit" class="btn btn-primary">Update Doctor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <?php include '../includes/footer.php'; ?>
    <script src="<?php echo BASE_URL; ?>assets/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
