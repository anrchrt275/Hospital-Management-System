<?php
require_once '../config/config.php'; // Memuat konfigurasi dan koneksi database
checkLogin(); // Cek apakah user sudah login

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Jika ada request POST
    if (isset($_POST['add'])) { // Jika tambah departemen
        $name = sanitize($_POST['name']); // Sanitasi nama departemen
        $description = sanitize($_POST['description']); // Sanitasi deskripsi
        $query = "INSERT INTO departments (name, description) VALUES ('$name', '$description')"; // Query insert departemen
        $conn->query($query); // Eksekusi query
    } elseif (isset($_POST['delete'])) { // Jika hapus departemen
        $id = sanitize($_POST['id']); // Sanitasi id departemen
        // Check if department has doctors
        $check = $conn->query("SELECT COUNT(*) as count FROM doctors WHERE department_id = $id")->fetch_assoc(); // Cek jumlah dokter di departemen
        if ($check['count'] == 0) { // Jika tidak ada dokter
            $conn->query("DELETE FROM departments WHERE id = $id"); // Hapus departemen
        }
    } elseif (isset($_POST['edit'])) { // Jika edit departemen
        $id = sanitize($_POST['id']); // Sanitasi id departemen
        $name = sanitize($_POST['name']); // Sanitasi nama departemen
        $description = sanitize($_POST['description']); // Sanitasi deskripsi
        $query = "UPDATE departments SET name = '$name', description = '$description' WHERE id = $id"; // Query update departemen
        $conn->query($query); // Eksekusi query
    }
}

// Fetch departments with doctor count
$query = "SELECT d.*, COUNT(doc.id) as doctor_count 
          FROM departments d 
          LEFT JOIN doctors doc ON doc.department_id = d.id 
          GROUP BY d.id 
          ORDER BY d.name";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - Hospital System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-5 main-content">
        <h2 class="mb-4 text-primary">Manage Departments</h2>
        
        <!-- Add Department Button -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            Add Department
        </button>
        
        <!-- Departments Display -->
        <div class="row g-4">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="text-muted">Doctors: <?php echo $row['doctor_count']; ?></p>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                        data-bs-target="#editModal<?php echo $row['id']; ?>">
                                    Edit
                                </button>
                                <?php if($row['doctor_count'] == 0): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this department?')">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Add Department Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description:</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary">Add Department</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Department Modals -->
    <?php 
    $result->data_seek(0);
    while($row = $result->fetch_assoc()): 
    ?>
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Department</h5>
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
                                <label>Description:</label>
                                <textarea name="description" class="form-control" required><?php echo $row['description']; ?></textarea>
                            </div>
                            <button type="submit" name="edit" class="btn btn-primary">Update Department</button>
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
</body>
</html>
