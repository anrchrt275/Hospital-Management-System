<?php
session_start(); // Mulai session (untuk akses session yang aktif)
session_destroy(); // Hapus semua data session (logout)
header("Location: login.php"); // Redirect ke halaman login
exit(); // Hentikan eksekusi script
