<?php
require_once 'config/config.php'; // Memuat konfigurasi dan koneksi database
checkLogin(); // Mengecek apakah user sudah login

// Mengambil statistik jumlah pasien, dokter, dan janji temu dari database
$stats = [
    'patients' => $conn->query("SELECT COUNT(*) as count FROM patients")->fetch_assoc()['count'], // Jumlah pasien
    'doctors' => $conn->query("SELECT COUNT(*) as count FROM doctors")->fetch_assoc()['count'], // Jumlah dokter
    'appointments' => $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'] // Jumlah janji temu
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> <!-- Set karakter encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive viewport -->
    <link rel="icon" type="image/png" href="logoimg.png"> <!-- Favicon -->
    <title>Dashboard - Sentosa Hospital</title> <!-- Judul halaman -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
    <link href="<?php echo BASE_URL; ?>assets/css/custom.css" rel="stylesheet"> <!-- Custom CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js untuk grafik -->
    <style>
        body {
            min-height: 100vh; /* Tinggi minimum layar penuh */
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1; /* Konten utama fleksibel */
        }
        footer {
            margin-top: auto; /* Footer di bawah */
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: slideUp 0.6s ease forwards; /* Animasi naik */
            opacity: 0;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; } /* Delay animasi kartu 1 */
        .stat-card:nth-child(2) { animation-delay: 0.3s; } /* Delay animasi kartu 2 */
        .stat-card:nth-child(3) { animation-delay: 0.5s; } /* Delay animasi kartu 3 */

        .stats-number {
            font-size: 2rem; /* Ukuran angka statistik */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?> <!-- Navbar -->

    <div class="container mt-5 main-content">
        <h2 class="mb-4 text-primary">Welcome, <?php echo $_SESSION['role']; ?>!</h2> <!-- Sambutan user -->

        <div class="row mt-4 g-4">
            <div class="col-md-4 stat-card">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Patients</h5>
                        <h2><span class="stats-number" data-target="<?php echo $stats['patients']; ?>">0</span></h2> <!-- Jumlah pasien -->
                    </div>
                </div>
            </div>

            <div class="col-md-4 stat-card">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Doctors</h5>
                        <h2><span class="stats-number" data-target="<?php echo $stats['doctors']; ?>">0</span></h2> <!-- Jumlah dokter -->
                    </div>
                </div>
            </div>

            <div class="col-md-4 stat-card">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Appointments</h5>
                        <h2><span class="stats-number" data-target="<?php echo $stats['appointments']; ?>">0</span></h2> <!-- Jumlah janji temu -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Grafik -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Patient Visits Trend</h5>
                        <canvas id="visitChart"></canvas> <!-- Grafik kunjungan pasien -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Common Diseases Distribution</h5>
                        <canvas id="diseaseChart"></canvas> <!-- Grafik distribusi penyakit -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?> <!-- Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/custom.js"></script> <!-- Custom JS -->
    <script>
        // Grafik Kunjungan Pasien
        const visitCtx = document.getElementById('visitChart').getContext('2d');
        new Chart(visitCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], // Label bulan
                datasets: [{
                    label: 'Monthly Patient Visits',
                    data: [65, 59, 80, 81, 56, 85], // Data dummy kunjungan
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '2025 Patient Visits'
                    }
                }
            }
        });

        // Grafik Distribusi Penyakit
        const diseaseCtx = document.getElementById('diseaseChart').getContext('2d');
        new Chart(diseaseCtx, {
            type: 'doughnut',
            data: {
                labels: ['Respiratory', 'Cardiovascular', 'Orthopedic', 'Neurological', 'Others'], // Label penyakit
                datasets: [{
                    data: [30, 25, 15, 20, 10], // Data dummy distribusi penyakit
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Animasi angka statistik
        document.addEventListener('DOMContentLoaded', () => {
            const counterElements = document.querySelectorAll('.stats-number');

            counterElements.forEach(counter => {
                const target = +counter.getAttribute('data-target'); // Target angka
                const duration = 1000; // Durasi animasi (ms)
                const step = target / (duration / 16); // Langkah per frame

                let current = 0;
                const updateCounter = () => {
                    current += step;
                    if (current > target) current = target;
                    counter.textContent = Math.round(current);

                    if (current < target) {
                        requestAnimationFrame(updateCounter); // Lanjut animasi
                    }
                };

                updateCounter();
            });
        });
    </script>
</body>
</html>
