<nav class="navbar navbar-expand-lg navbar-dark bg-primary"> <!-- Navbar utama dengan warna biru -->
    <div class="container"> <!-- Kontainer navbar -->
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">Sentosa Hospital</a> <!-- Logo Rumah Sakit -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span> <!-- Tombol toggle untuk mobile -->
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown"> <!-- Menu yang bisa collapse -->
            <ul class="navbar-nav me-auto"> <!-- Menu kiri -->
                <?php if(isLoggedIn()): ?> <!-- Jika user sudah login -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Home</a> <!-- Link Beranda -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>pages/patients.php">Patients</a> <!-- Link Pasien -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>pages/doctors.php">Doctors</a> <!-- Link Dokter -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>pages/appointments.php">Appointments</a> <!-- Link Janji Temu -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>pages/departments.php">Departments</a> <!-- Link Departemen -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>pages/about.php">About Us</a> <!-- Link Tentang Kami -->
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav"> <!-- Menu kanan -->
                <?php if(isLoggedIn()): ?> <!-- Jika user sudah login -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">Logout</a> <!-- Link Logout -->
                    </li>
                <?php else: ?> <!-- Jika belum login -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Login</a> <!-- Link Login -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>register.php">Register</a> <!-- Link Register -->
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
