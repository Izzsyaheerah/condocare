<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_pelawat = $_GET['id']; // Ambil ID pelawat dari URL

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Dapatkan data pelawat berdasarkan id_pelawat
try {
    $stmt = $conn->prepare("SELECT * FROM tbl_pelawat WHERE id_pelawat = ?");
    $stmt->execute([$id_pelawat]);
    $pelawat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pelawat) {
        echo "Pelawat tidak ditemui!";
        exit();
    }
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pelawat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(222, 218, 200);
        }

        .container {
            max-width: 800px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .btn-custom {
            background: #D27D2C;
            color: #fff;
            font-weight: 600;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .btn-custom:hover {
            background: #A65F20;
        }
    </style>
</head>
<body>

<!-- Panggil Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h3 class="mb-4 text-center">Maklumat Pelawat</h3>

        <!-- Paparkan Maklumat Pelawat -->
        <div class="mb-3">
            <label for="id_pelawat" class="form-label">ID Pelawat</label>
            <input type="text" class="form-control" id="id_pelawat" value="<?= htmlspecialchars($pelawat['id_pelawat']) ?>" disabled>
        </div>
        
        <div class="mb-3">
            <label for="tujuan_pelawat" class="form-label">Tujuan Pelawat</label>
            <input type="text" class="form-control" id="tujuan_pelawat" value="<?= htmlspecialchars($pelawat['tujuan_pelawat']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="nama_pelawat" class="form-label">Nama Pelawat</label>
            <input type="text" class="form-control" id="nama_pelawat" value="<?= htmlspecialchars($pelawat['nama_pelawat']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="no_tel_pelawat" class="form-label">No Tel Pelawat</label>
            <input type="text" class="form-control" id="no_tel_pelawat" value="<?= htmlspecialchars($pelawat['no_tel_pelawat']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="no_kenderaan" class="form-label">No Kenderaan</label>
            <input type="text" class="form-control" id="no_kenderaan" value="<?= htmlspecialchars($pelawat['no_kenderaan']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="tarikh_lawatan" class="form-label">Tarikh Lawatan</label>
            <input type="date" class="form-control" id="tarikh_lawatan" value="<?= htmlspecialchars($pelawat['tarikh_lawatan']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="kategori_pelawat" class="form-label">Kategori Pelawat</label>
            <input type="text" class="form-control" id="kategori_pelawat" value="<?= htmlspecialchars($pelawat['kategori_pelawat']) ?>" disabled>
        </div>

        <a href="senarai_pelawat.php" class="btn btn-custom">Kembali ke Senarai Pelawat</a>
    </div>
</div>

</body>
</html>