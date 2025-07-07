<?php
require_once 'config/config.php'; // Memuat konfigurasi dan koneksi database

// Add cache control headers
header("Cache-Control: no-cache, no-store, must-revalidate"); // Mencegah cache
header("Pragma: no-cache"); // Mencegah cache (HTTP 1.0)
header("Expires: 0"); // Mencegah cache (tanggal kadaluarsa)

// Redirect if already logged in
if (isLoggedIn()) { // Jika sudah login
    header("Location: index.php"); // Redirect ke halaman utama
    exit(); // Hentikan eksekusi
}

if (isset($_POST['login'])) { // Jika form login disubmit
    $username = sanitize($_POST['username']); // Sanitasi username
    $password = $_POST['password']; // Ambil password

    $query = "SELECT * FROM users WHERE username = '$username'"; // Query cari user
    $result = $conn->query($query); // Eksekusi query

    if ($result->num_rows > 0) { // Jika user ditemukan
        $user = $result->fetch_assoc(); // Ambil data user
        if (password_verify($password, $user['password'])) { // Verifikasi password
            $_SESSION['user_id'] = $user['id']; // Set session user id
            $_SESSION['role'] = $user['role']; // Set session role
            $success = "Login successful! Redirecting..."; // Pesan sukses
            header("refresh:1;url=index.php"); // Redirect setelah 1 detik
        } else {
            $error = "Invalid credentials"; // Password salah
        }
    } else {
        $error = "Invalid credentials"; // Username tidak ditemukan
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <title>Login - Hospital System</title> <!-- Judul halaman -->
    <link rel="icon" type="image/png" href="logoimg.png"> <!-- Favicon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #00416A 0%, #E4E5E6 100%); /* Background gradasi */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
            background: rgba(255,255,255,0.95);
            transition: all 0.3s ease;
            width: 100%;
            max-width: 420px;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: #00416A;
            color: white;
            font-size: 1.5rem;
            font-weight: 500;
            padding: 1rem;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #e1e1e1;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #00416A;
            box-shadow: 0 0 0 0.2rem rgba(0,65,106,0.25);
        }
        .btn-primary {
            background: #00416A;
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #005688;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,65,106,0.3);
        }
        .btn-outline-secondary {
            border: 2px solid #00416A;
            color: #00416A;
            font-weight: 500;
            padding: 12px;
            border-radius: 8px;
        }
        .btn-outline-secondary:hover {
            background: #00416A;
            color: white;
        }
        .alert {
            border-radius: 8px;
            border: none;
            color: white;
        }
        .alert-danger {
            background: #ff6b6b;
        }
        .alert-success {
            background: #28a745;
        }
        a {
            color: #00416A;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        a:hover {
            color: #005688;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?> <!-- Navbar -->

    <div class="container">
        <div class="card">
            <div class="card-header">Login</div> <!-- Judul card -->
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div> <!-- Pesan error -->
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div> <!-- Pesan sukses -->
                <?php endif; ?>

                <form method="POST"> <!-- Form login -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required> <!-- Input username -->
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required> <!-- Input password -->
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="login" class="btn btn-primary">Login</button> <!-- Tombol login -->
                        <a href="register.php" class="btn btn-outline-secondary">Create New Account</a> <!-- Link daftar -->
                    </div>
                    <div class="text-center mt-3">
                        <p class="mb-0">Don't have an account? <a href="register.php">Register here</a></p> <!-- Link daftar -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    <script>
        // Prevent back button (client-side)
        window.onload = function () { // Saat halaman dimuat
            if (typeof history.pushState === "function") { // Jika pushState tersedia
                history.pushState("jibberish", null, null); // Tambah state palsu
                window.onpopstate = function () { // Jika tombol back ditekan
                    history.pushState('newjibberish', null, null); // Cegah kembali
                };
            }
        }
    </script>
</body>
</html>
