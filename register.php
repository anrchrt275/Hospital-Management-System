<?php 
require_once 'config/config.php'; // Memuat file konfigurasi dan koneksi database

$error = '';    // Variabel untuk menyimpan pesan error
$success = '';  // Variabel untuk menyimpan pesan sukses

// Cek jika form dengan tombol 'register' telah disubmit
if (isset($_POST['register'])) {
    $username = sanitize($_POST['username']); // Sanitasi input username
    $passwordRaw = $_POST['password'];        // Ambil password mentah dari input
    $role = sanitize($_POST['role']);         // Sanitasi input role

    // Validasi input: username minimal 4 karakter, password minimal 6 karakter
    if (strlen($username) < 4 || strlen($passwordRaw) < 6) {
        $error = "Username minimal 4 karakter dan password minimal 6 karakter.";
    } 
    // Validasi apakah role yang dipilih terdapat dalam daftar role yang diizinkan
    elseif (!array_key_exists($role, $roles)) {
        $error = "Role tidak valid.";
    } 
    else {
        // Cek apakah username sudah digunakan di database
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);  // Bind parameter untuk prepared statement
        $check->execute();                   // Eksekusi query
        $result = $check->get_result();      // Ambil hasil query

        // Jika username sudah ada
        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan. Silakan pilih yang lain.";
        } 
        else {
            // Hash password sebelum disimpan untuk keamanan
            $passwordHashed = password_hash($passwordRaw, PASSWORD_DEFAULT);

            // Siapkan query untuk menyimpan user baru ke tabel users
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $passwordHashed, $role); // Bind parameter

            // Eksekusi query dan cek keberhasilannya
            if ($stmt->execute()) {
                $success = "Registrasi berhasil. Anda akan diarahkan ke halaman login...";
                header("refresh:2;url=login.php"); // Redirect otomatis ke login setelah 2 detik
            } 
            else {
                $error = "Registrasi gagal. Coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive layout -->
    <title>Register - Hospital System</title> <!-- Judul halaman -->
    <link rel="icon" type="image/png" href="logoimg.png"> <!-- Favicon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->

    <!-- CSS kustom untuk tampilan -->
    <style>
        body {
            background: linear-gradient(to bottom right, #0d4b70, #dce1e7);
            min-height: 100vh;
        }
        .card { border-radius: 10px; box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2); }
        .card-header { background-color: #003c5f; color: white; font-weight: bold; text-align: center; border-radius: 10px 10px 0 0; }
        .btn-primary { background-color: #003c5f; border: none; }
        .btn-primary:hover { background-color: #002a42; }
        .btn-outline-secondary { border-color: #003c5f; color: #003c5f; }
        .btn-outline-secondary:hover { background-color: #003c5f; color: white; }
        a { color: #003c5f; }
        a:hover { color: #001d2e; text-decoration: underline; }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?> <!-- Menyisipkan file navbar -->

<!-- Container utama -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card"> <!-- Kartu untuk form -->
                <div class="card-header">Register</div> <!-- Header kartu -->

                <div class="card-body">
                    <!-- Tampilkan pesan error jika ada -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <!-- Tampilkan pesan sukses jika ada -->
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                    <?php endif; ?>

                    <!-- Form registrasi -->
                    <form method="POST">
                        <div class="mb-3">
                            <label>Username:</label>
                            <input type="text" name="username" class="form-control" required> <!-- Input username -->
                        </div>
                        <div class="mb-3">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" required> <!-- Input password -->
                        </div>
                        <div class="mb-3">
                            <label>Role:</label>
                            <select name="role" class="form-control" required> <!-- Pilihan role -->
                                <!-- Loop semua role yang tersedia -->
                                <?php foreach($roles as $key => $value): ?>
                                    <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Tombol Register dan link kembali -->
                        <div class="d-grid gap-2">
                            <button type="submit" name="register" class="btn btn-primary">Register</button>
                            <a href="login.php" class="btn btn-outline-secondary">Back to Login</a>
                        </div>
                        <!-- Teks tambahan untuk login -->
                        <div class="text-center mt-3">
                            <p class="mb-0">Already have an account? <a href="login.php">Login here</a></p>
                        </div>
                    </form>
                </div> <!-- End card-body -->
            </div> <!-- End card -->
        </div>
    </div>
</div>

<!-- Bootstrap JS untuk komponen interaktif -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
