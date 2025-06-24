<?php
session_start();
include 'database.php';

// Hanya admin/pengurusan sahaja dibenarkan
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'Pengurusan') {
    echo "<script>alert('Akses tidak dibenarkan!'); window.location.href='login.php';</script>";
    exit();
}

// Sambung ke DB
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Dapatkan semua bayaran bersama maklumat pengguna
try {
    $stmt = $conn->prepare("
        SELECT b.*, p.fld_nama, p.fld_no_unit 
        FROM tbl_bayaran b 
        JOIN tbl_pengguna p ON b.fld_id_pengguna = p.fld_id_pengguna 
        ORDER BY b.tarikh_bayaran DESC
    ");
    $stmt->execute();
    $bayaran = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Senarai Bayaran - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #dedac8;
            display: flex;
        }
        .content {
            margin-left: 270px;
            padding: 30px;
            width: 100%;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-tambah {
            background-color: #D27D2C;
            color: white;
            font-weight: bold;
        }
        .btn-tambah:hover {
            background-color: #a65f20;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h3 class="mb-4">Senarai Bayaran Penghuni</h3>

        <div class="text-end mb-3">
            <a href="admin_tambah_bayaran.php" class="btn btn-tambah">Tambah Bayaran</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Penghuni</th>
                    <th>No Unit</th>
                    <th>Jenis Bayaran</th>
                    <th>Jumlah (RM)</th>
                    <th>Bulan Bayaran</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bayaran): ?>
                    <?php $i = 1; foreach ($bayaran as $row): ?>
                        <tr class="align-middle">
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['fld_nama']) ?></td>
                            <td><?= htmlspecialchars($row['fld_no_unit']) ?></td>
                            <td><?= htmlspecialchars($row['jenis_bayaran']) ?></td>
                            <td class="text-center">RM <?= number_format($row['jumlah'], 2) ?></td>
                            <td class="text-center">
    <?= date('F Y', strtotime($row['tarikh_bayaran'])) ?>
</td>
                            <td class="text-center">
                                <form action="admin_status_bayaran.php" method="post" class="d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="id_bayaran" value="<?= $row['id_bayaran'] ?>">
                                    <select name="status_bayaran" class="form-select form-select-sm me-2">
                                        <option value="Belum Dibayar" <?= $row['status_bayaran'] == 'Belum Dibayar' ? 'selected' : '' ?>>Belum Dibayar</option>
                                        <option value="Selesai" <?= $row['status_bayaran'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success">Kemaskini</button>
                                </form>
                            </td>
                            <td class="text-center">
                <a href="padam_bayaran.php?id=<?= $row['id_bayaran'] ?>" 
                   onclick="return confirm('Adakah anda pasti ingin padam bayaran ini?')" 
                   class="text-danger ms-2">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Tiada rekod bayaran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
