<?php
session_start();
include 'database.php';

// Pastikan pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pastikan ID bayaran diterima
if (!isset($_GET['id'])) {
    echo "Resit tidak dijumpai.";
    exit();
}

$id_bayaran = $_GET['id'];

try {
    // Sambung ke DB
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Dapatkan maklumat bayaran dan sahkan milik pengguna
    $stmt = $conn->prepare("SELECT b.*, p.fld_nama, p.fld_no_unit 
                            FROM tbl_bayaran b
                            JOIN tbl_pengguna p ON b.fld_id_pengguna = p.fld_id_pengguna
                            WHERE b.id_bayaran = ? AND b.fld_id_pengguna = ?");
    $stmt->execute([$id_bayaran, $user_id]);
    $bayaran = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bayaran) {
        echo "Resit tidak wujud atau anda tidak dibenarkan melihatnya.";
        exit();
    }

} catch (PDOException $e) {
    die("Ralat pangkalan data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Resit Bayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 40px;
        }
        .resit-box {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
        }
        h4 {
            margin-bottom: 30px;
            color: #D27D2C;
        }
        .btn-print {
            margin-top: 20px;
        }
        .table th {
            width: 40%;
        }
    </style>
</head>
<body>

<div class="resit-box border p-4 bg-white">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h5 class="fw-bold">CondoCare Management</h5>
            <p class="mb-1">Blok A, Kondominium Desaminium<br>
                Bandar Putra Permai, 43300 Seri Kembangan<br>
                Selangor, Malaysia</p>
        </div>
        <div class="text-end">
            <h6 class="text-muted">RESIT #<?= str_pad($bayaran['id_bayaran'], 6, '0', STR_PAD_LEFT) ?></h6>
            <p class="mb-1"><strong>Tarikh:</strong> <?= date('d/m/Y') ?></p>
            <p class="mb-1"><strong>Status:</strong> <?= htmlspecialchars($bayaran['status_bayaran']) ?></p>
        </div>
    </div>

    <hr>

    <div class="mb-4">
        <p class="mb-1"><strong>Nama Penghuni:</strong> <?= htmlspecialchars($bayaran['fld_nama']) ?></p>
        <p class="mb-1"><strong>No Unit:</strong> <?= htmlspecialchars($bayaran['fld_no_unit']) ?></p>
        <p class="mb-1"><strong>Jenis Bayaran:</strong> <?= htmlspecialchars($bayaran['jenis_bayaran']) ?></p>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Keterangan</th>
                <th>Nilai (RM)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($bayaran['jenis_bayaran']) ?> - Bulan <?= date('F Y', strtotime($bayaran['tarikh_bayaran'])) ?></td>
                <td>RM <?= number_format($bayaran['jumlah'], 2) ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end">Jumlah Bayaran</th>
                <th>RM <?= number_format($bayaran['jumlah'], 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-warning">Cetak Resit</button>
    </div>
</div>

</body>
</html>
