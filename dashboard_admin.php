<?php
session_start();
include 'database.php';

// Halang akses jika bukan admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'Pengurusan') {
    echo "<script>alert('Akses tidak dibenarkan!'); window.location.href='login.php';</script>";
    exit();
}


?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin - CondoCare</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: rgb(222, 218, 200);
        margin: 0;
        padding: 0;
        display: flex;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
        flex: 1;
        width: 100%;
    }

    .navbar {
        background: linear-gradient(90deg, #D27D2C 0%, #a85f1b 100%);
        padding: 25px 40px;
        color: white;
        font-size: 18px;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .navbar img {
        height: 40px;
        margin-right: 10px;
    }

    .carousel-caption {
        background-color: rgba(255,255,255,0.8);
        border-radius: 10px;
        padding: 15px;
    }

    .carousel-inner img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 15px;
    }

    .carousel-caption {
        bottom: 20px;
        background-color: rgba(0,0,0,0.5);
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
        max-width: 70%;
        margin: auto;
        text-align: center;
    }

    @media (max-width: 768px) {
        .carousel-inner img {
            height: 250px;
        }
        .carousel-caption h5 {
            font-size: 16px;
        }
        .carousel-caption p {
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .dashboard-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
        }
    }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content">
    <!-- Navbar -->
    <div class="navbar shadow-sm">
        <div class="d-flex align-items-center"></div>
        <div class="d-flex align-items-center">
            <span>Selamat Datang, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna'); ?></strong></span>
        </div>
    </div>

    <!-- Hero Carousel Section -->
    <div id="heroCarousel" class="carousel slide my-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded-3 shadow">
            <div class="carousel-item active">
                <img src="gambar/condo1.jpeg" class="d-block w-100" alt="Condo Image 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Selamat Datang ke CondoCare</h5>
                    <p>Mudah, cepat dan efisien untuk komuniti anda.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="gambar/condoo2.png" class="d-block w-100" alt="Condo Image 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Urusan Harian Lebih Tersusun</h5>
                    <p>Jejak tempahan, aduan, pelawat dan bayaran lebih mudah.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
        </button>
    </div>

    <div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header text-white d-flex align-items-center" style="background-color: #D27D2C;">
            <i class="fas fa-bullhorn me-2"></i>
            <strong>Hantar Notis Kepada Pengguna</strong>
        </div>
        <div class="card-body">
            <form action="proses_notis.php" method="POST">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tajuk Notis</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="tajuk" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kandungan</label>
                    </div>
                    <div class="col-md-8">
                        <textarea name="kandungan" class="form-control" rows="5" placeholder="Masukkan butiran notis di sini..." required></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn text-white" style="background-color: #D27D2C;">
                        <i class="fas fa-paper-plane me-1"></i> Hantar Notis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php if (isset($_SESSION['notis_status'])): ?>
    <script>
        alert("<?= $_SESSION['notis_status']; ?>");
    </script>
    <?php unset($_SESSION['notis_status']); ?>
<?php endif; ?>
</div>
</div>


<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
