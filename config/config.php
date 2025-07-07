<?php
// definisi base URL
define('BASE_URL', 'https://rio.aquorder.my.id/'); // URL dasar aplikasi

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) { // Cek status session
    session_start(); // Mulai session jika belum ada
}

// Konfigurasi db
$db_config = [ // Konfigurasi database
    'host' => 'rio.aquorder.my.id', // Host database
    'username' => 'aquorder_user_rio', // Username database
    'password' => 'Aj94ndnNSJRJundu93583JAENUENDWSJ12jhsS', // Password database
    'database' => 'aquorder_db_rio' // Nama database
];

try {
    $conn = new mysqli(
        $db_config['host'], // Host
        $db_config['username'], // Username
        $db_config['password'], // Password
        $db_config['database'] // Database
    );

    if ($conn->connect_error) { // Jika koneksi gagal
        throw new Exception("Connection failed: " . $conn->connect_error); // Lempar error koneksi
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage()); // Tampilkan pesan error dan hentikan eksekusi
}

// Helper function to check if user is logged in
function isLoggedIn() { // Mengecek apakah user sudah login
    return isset($_SESSION['user_id']); // True jika user_id ada di session
}

// Helper function for sanitizing input
function sanitize($value) { // Membersihkan input user
    global $conn;
    return $conn->real_escape_string(trim($value)); // Escape string dan hapus spasi
}

// Check if user is logged in
function checkLogin() { // Redirect ke login jika belum login
    if (!isLoggedIn()) { // Jika belum login
        header("Location: " . BASE_URL . "login.php"); // Redirect ke halaman login
        exit(); // Hentikan eksekusi
    }
}

// Common Arrays
$departments = [ // Daftar departemen
    'Cardiology', // Kardiologi
    'Neurology', // Neurologi
    'Pediatrics',
    'Orthopedics',
    'Oncology'
];

$roles = [ // Daftar role user
    'admin' => 'Administrator',
    'doctor' => 'Doctor',
    'staff' => 'Staff'
];
