<?php
session_start();
include 'database.php';

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Dapatkan semua bayaran pengguna
try {
    $user_id = $_SESSION['user_id']; // ID pengguna sekarang
$stmt = $conn->prepare("SELECT * FROM tbl_bayaran WHERE fld_id_pengguna = ? ORDER BY tarikh_bayaran DESC");
$stmt->execute([$user_id]);
    $bayaran = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Bayaran - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(222, 218, 200);
            display: flex;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
            width: 100%;
        }

        .container {
            margin-top: 50px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
    font-size: 0.85em;
    padding: 3px 10px;
}

        .btn-tambah:hover {
            background-color: #A65F20;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h3 class="mb-4">Senarai Bayaran</h3>

            <table class="table table-bordered">
                <thead>
                    <tr class="table-dark text-center">
                        <th>No</th>
                        <th>Jenis Bayaran</th>
                        <th>Jumlah</th>
                        <th>Bulan Bayaran</th>
                        <th>Status Bayaran</th>
                        <th>Resit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bayaran) > 0): ?>
                        <?php $i = 1; foreach ($bayaran as $row): ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['jenis_bayaran']) ?></td>
                                <td>RM <?= number_format($row['jumlah'], 2) ?></td>
                                <td>
<?php 
    $date = new DateTime($row['tarikh_bayaran']);
    echo $date->format('F Y'); // Contoh: July 2025
?>
</td>
                                <td>
    <?= htmlspecialchars($row['status_bayaran']) ?></td>
    <td><?php if ($row['status_bayaran'] === 'Selesai'): ?>
        <a href="resit.php?id=<?= $row['id_bayaran'] ?>" class="btn btn-sm btn-success ms-2">Resit</a>
    <?php endif; ?>
</td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tiada bayaran ditemui.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>