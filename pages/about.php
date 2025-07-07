<?php
require_once '../config/config.php'; // Memuat konfigurasi dan koneksi database
checkLogin(); // Cek apakah user sudah login

// Prosedur 1
function showHospitalHistory() { // Menampilkan sejarah rumah sakit
    echo "<div class='about-section'>";
    echo "<h4><i class='bi bi-clock-history'></i> A Brief History of the Hospital</h4>";
    echo "<p>Sentosa Hospital was established in 1900 with a commitment to provide the best health services in Indonesia</p>";
    echo "</div>";
}

// Prosedur 2
function showDirectorProfile() { // Menampilkan profil direktur/manajemen
    echo "<div class='about-section'>";
    echo "<h4><i class='bi bi-person-badge'></i> Management Profile</h4>";
    echo "<p>Led by Dr. Andreas Rio Christian, Sp.B, our hospital continues to grow with a holistic approach and cutting-edge technology in patient care</p>";
    echo "</div>";
}

// Prosedur 3
function showAccreditationCertification() { // Menampilkan akreditasi dan sertifikasi
    echo "<div class='about-section'>";
    echo "<h4><i class='bi bi-award'></i> Certification and Accreditation</h4>";
    echo "<p>Completely accredited by KARS in 2023, and become a referral hospital for BPJS and national private insurance</p>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Hospital System</title>
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
        /* Bentuk Tampilan Kalkulator Kesehatan */
        .health-calculator {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .health-score {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            border-radius: 12px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        .health-score.show {
            opacity: 1;
            transform: translateY(0);
        }
        .score-good { 
            background: linear-gradient(145deg, #d4edda, #c3e6cb);
            color: #155724;
            box-shadow: 0 4px 15px rgba(21,87,36,0.1);
        }
        .score-average { 
            background: linear-gradient(145deg, #fff3cd, #ffeeba);
            color: #856404;
            box-shadow: 0 4px 15px rgba(133,100,4,0.1);
        }
        .score-poor { 
            background: linear-gradient(145deg, #f8d7da, #f5c6cb);
            color: #721c24;
            box-shadow: 0 4px 15px rgba(114,28,36,0.1);
        }
        .score-value {
            font-size: 48px;
            display: block;
            margin: 10px 0;
        }
        .score-message {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 10px;
        }

        .about-section {
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #20B2AA;
            background: #f8f9fa;
            border-radius: 0 8px 8px 0;
        }
        .about-section h4 {
            color: #20B2AA;
            margin-bottom: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .about-section p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .values-list, .facilities-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .values-list li, .facilities-list li {
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .icon-wrapper {
            color: #20B2AA;
            width: 24px;
            text-align: center;
        }
        .section-divider {
            height: 2px;
            background: linear-gradient(to right, #20B2AA, transparent);
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-5 main-content">
        <h2 class="mb-4 text-primary text-center">About Our Hospital</h2>
        
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <?php
                        showHospitalHistory();
                        showDirectorProfile();
                        showAccreditationCertification();
                        ?>
                        
                        <div class="section-divider"></div>

                        <div class="about-section">
                            <h4><i class="bi bi-flag"></i> Our Mission</h4>
                            <p>To provide exceptional healthcare services with compassion and commitment to our community.</p>
                        </div>

                        <div class="about-section">
                            <h4><i class="bi bi-eye"></i> Our Vision</h4>
                            <p>To be the leading healthcare provider known for excellence in patient care, medical education, and innovative research.</p>
                        </div>

                        <div class="about-section">
                            <h4><i class="bi bi-star"></i> Our Values</h4>
                            <ul class="values-list">
                                <li><span class="icon-wrapper"><i class="bi bi-check2-circle"></i></span> Excellence in Healthcare</li>
                                <li><span class="icon-wrapper"><i class="bi bi-heart"></i></span> Patient-Centered Care</li>
                                <li><span class="icon-wrapper"><i class="bi bi-shield-check"></i></span> Integrity and Ethics</li>
                                <li><span class="icon-wrapper"><i class="bi bi-lightning"></i></span> Innovation and Research</li>
                                <li><span class="icon-wrapper"><i class="bi bi-people"></i></span> Teamwork and Collaboration</li>
                            </ul>
                        </div>

                        <div class="about-section">
                            <h4><i class="bi bi-building"></i> Our Facilities</h4>
                            <p>Our hospital is equipped with state-of-the-art medical facilities and staffed by highly qualified healthcare professionals. We offer:</p>
                            <ul class="facilities-list">
                                <li><span class="icon-wrapper"><i class="bi bi-hospital"></i></span> 24/7 Emergency Services</li>
                                <li><span class="icon-wrapper"><i class="bi bi-clipboard2-pulse"></i></span> Modern Operation Theaters</li>
                                <li><span class="icon-wrapper"><i class="bi bi-microscope"></i></span> Advanced Diagnostic Center</li>
                                <li><span class="icon-wrapper"><i class="bi bi-bandaid"></i></span> Specialized Care Units</li>
                                <li><span class="icon-wrapper"><i class="bi bi-activity"></i></span> Rehabilitation Center</li>
                            </ul>
                        </div>
                        
                        <!-- Bagian Kalkulator Kesehatan -->
                        <div class="health-calculator mt-5">
                            <h4 class="text-center mb-4">Your Healthy Footprint Calculator</h4>
                            <form id="healthForm" class="mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Do you smoke?</label>
                                    <select class="form-select" id="smoking">
                                        <option value="0">No</option>
                                        <option value="-30">Yes</option>
                                        <option value="-15">Occasionally</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Exercise Frequency (per week)</label>
                                    <select class="form-select" id="exercise">
                                        <option value="30">4+ times</option>
                                        <option value="20">2-3 times</option>
                                        <option value="10">1 time</option>
                                        <option value="0">Never</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Average Sleep Per Night</label>
                                    <select class="form-select" id="sleep">
                                        <option value="30">7-9 hours</option>
                                        <option value="15">6-7 hours</option>
                                        <option value="0">Less than 6 hours</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Calculate My Score</button>
                            </form>
                            <div id="healthScore" class="health-score" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/custom.js"></script>
    <script>
        document.getElementById('healthForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const smoking = parseInt(document.getElementById('smoking').value);
            const exercise = parseInt(document.getElementById('exercise').value);
            const sleep = parseInt(document.getElementById('sleep').value);
            
            const score = 40 + smoking + exercise + sleep;
            
            const scoreDiv = document.getElementById('healthScore');
            scoreDiv.style.display = 'block';
            
            let scoreClass = '';
            let message = '';
            let icon = '';
            
            if (score >= 80) {
                scoreClass = 'score-good';
                message = 'Excellent! Your health habits are great!';
                icon = 'bi-emoji-smile-fill';
            } else if (score >= 50) {
                scoreClass = 'score-average';
                message = 'Good, but there\'s room for improvement.';
                icon = 'bi-emoji-neutral-fill';
            } else {
                scoreClass = 'score-poor';
                message = 'Consider making some lifestyle changes.';
                icon = 'bi-emoji-frown-fill';
            }
            
            scoreDiv.className = 'health-score ' + scoreClass;
            scoreDiv.innerHTML = `
                <i class="bi ${icon} fs-1"></i>
                <span class="score-value">${score}</span>
                <div class="score-message">${message}</div>
            `;
            
            // Menampilkan Animasi Score
            setTimeout(() => {
                scoreDiv.classList.add('show');
            }, 10);
        });
    </script>
</body>
</html>
