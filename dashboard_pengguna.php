<?php
session_start();

include 'database.php';

// Semak sama ada pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$jumlah_tertunggak = 0;

$user_id = $_SESSION['user_id']; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Jumlah tempahan
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_tempahan WHERE fld_id_pengguna = ?");
    $stmt->execute([$user_id]);
    $count_tempahan = $stmt->fetchColumn();

    // Jumlah aduan
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_aduan WHERE fld_id_pengguna = ?");
    $stmt->execute([$user_id]);
    $count_aduan = $stmt->fetchColumn();

    // // Jumlah pelawat hari ini
    // $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_pelawat WHERE fld_id_pengguna = ?");
    // $stmt->execute([$user_id]);
    // $count_pelawat = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT SUM(jumlah) FROM tbl_bayaran WHERE status_bayaran = 'Belum Dibayar' AND fld_id_pengguna = ?");
$stmt->execute([$user_id]);
$total_bayaran = $stmt->fetchColumn();

// Ambil data user untuk nama & gambar
$stmt = $conn->prepare("SELECT fld_nama, fld_gambar FROM tbl_pengguna WHERE fld_id_pengguna = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$user_name = $user['fld_nama'] ?? 'Pengguna';
$user_gambar = (!empty($user['fld_gambar'])) ? 'uploads/' . $user['fld_gambar'] : 'uploads/default.jpg';



    // $stmt = $conn->prepare("SELECT SUM(jumlah) FROM tbl_bayaran WHERE status_bayaran = 'Belum Dibayar'");
    // $stmt->execute();
    // $total_bayaran = $stmt->fetchColumn();


    // Jumlah pelawat hari ini
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_pelawat WHERE DATE(tarikh_lawatan) = CURDATE()");
    $stmt->execute();
    $count_pelawat = $stmt->fetchColumn();

    // Jumlah pembayaran tertunggak
    // Uncomment jika anda ada data ini
    // $stmt = $conn->prepare("SELECT IFNULL(SUM(jumlah),0) FROM tbl_pembayaran WHERE status = 'Tertunggak'");
    // $stmt->execute();
    // $jumlah_tertunggak = $stmt->fetchColumn();

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Pengguna - CondoCare</title>
    
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

    .dashboard-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 kotak sama lebar */
    gap: 20px;
    margin-top: 20px;
}

    .dashboard-box {
    background: white;
    border-radius: 15px;
    padding: 30px 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    text-align: center;
    transition: 0.3s;
}

    .dashboard-box:hover {
        transform: scale(1.03);
        background-color: #f9f9f9;
    }

    .dashboard-box i {
        font-size: 35px;
        margin-bottom: 10px;
        color: #D27D2C;
    }

    .dashboard-box h4 {
        margin-bottom: 10px;
        font-weight: 600;
        color: #333;
    }

    .dashboard-number {
        font-size: 22px;
        font-weight: 700;
        color: #555;
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
.profile-pic-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    margin-left: 15px;
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
    <div></div>
    <div class="d-flex align-items-center">
        <span>Selamat Datang, <strong><?php echo htmlspecialchars($user_name); ?></strong></span>
       <a href="kemaskini_profil.php">
    <img src="<?= $user_gambar ?>" alt="Gambar Profil" class="profile-pic-small" style="cursor: pointer;">
</a>
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

     <!-- Dashboard Boxes -->
    <div class="dashboard-container-wrapper d-flex justify-content-center">
    <div class="dashboard-container">
        <div class="dashboard-box">
            <i class="fas fa-calendar-alt"></i>
            <h4>Jadual Tempahan</h4>
            <div class="dashboard-number"><?php echo $count_tempahan; ?> tempahan</div>
        </div>

        <div class="dashboard-box">
            <i class="fas fa-tools"></i>
            <h4>Aduan</h4>
            <div class="dashboard-number"><?php echo $count_aduan; ?> aduan</div>
        </div>

        <div class="dashboard-box">
            <i class="fas fa-user-friends"></i>
            <h4>Pelawat Hari Ini</h4>
            <div class="dashboard-number"><?php echo $count_pelawat; ?> pelawat</div>
        </div>

        <div class="dashboard-box">
    <i class="fas fa-credit-card"></i>
    <h4>Pembayaran Tertunggak</h4>
    <div class="dashboard-number">
        RM <?php echo number_format($total_bayaran ?? 0, 2); ?>
    </div>
</div>

<!-- Notis Daripada Admin -->
<div class="container bg-white p-4 rounded shadow mt-3" style="max-width:700px;">
    <h5 class="mb-3">Notis Penting</h5>
    <?php
    $stmt = $conn->query("SELECT * FROM tbl_notis ORDER BY tarikh_hantar DESC LIMIT 3");
    $notis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($notis):
        foreach ($notis as $n): ?>
            <div class="border-start border-4 border-warning ps-3 mb-3">
                <h6><?= htmlspecialchars($n['tajuk']) ?></h6>
                <p><?= nl2br(htmlspecialchars($n['kandungan'])) ?></p>
                <small class="text-muted">Dihantar pada: <?= date('d/m/Y H:i', strtotime($n['tarikh_hantar'])) ?></small>
            </div>
    <?php endforeach; else: ?>
        <p>Tiada notis buat masa ini.</p>
    <?php endif; ?>
</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>