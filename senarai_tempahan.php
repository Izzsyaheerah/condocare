<?php
session_start();
include 'database.php';

// Semak jika pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['user_id'];

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Dapatkan semua tempahan pengguna
$stmt = $conn->prepare("SELECT * FROM tbl_tempahan WHERE fld_id_pengguna = ? ORDER BY tarikh_tempahan DESC");
$stmt->execute([$id_pengguna]);
$tempahan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Kemudahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(222, 218, 200);
            display: flex;
        }

        /* Main Content */
        .content {
            margin-left: 270px; /* To avoid the sidebar overlapping with the content */
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

        .btn-tambah {
            background-color: #D27D2C;
            color: white;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background-color: #A65F20;
        }
    </style>
</head>
<body>

    <!-- Panggil Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
    <div class="container">
        <h3 class="mb-4">Senarai Tempahan</h3>
        <div class="text-end mb-3">
            <a href="tempahan_kemudahan.php" class="btn btn-tambah">Tambah Tempahan</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr class="table-dark text-center">
                    <th>No</th>
                    <th>Kemudahan</th>
                    <th>No Unit</th>
                    <th>Nama Penempah</th>
                    <th>No Tel</th>
                    <th>Masa Mula</th>
                    <th>Masa Tamat</th>
                    <th>Tarikh Tempahan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tempahan) > 0): ?>
                    <?php $i = 1; foreach ($tempahan as $row): ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['kemudahan']) ?></td>
                            <td><?= htmlspecialchars($row['fld_no_unit']) ?></td>
                            <td><?= htmlspecialchars($row['nama_penempah']) ?></td>
                            <td><?= htmlspecialchars($row['no_tel']) ?></td>
                            <td><?= date('h:i A', strtotime($row['masa_mula'])) ?></td>
                            <td><?= date('h:i A', strtotime($row['masa_tamat'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tarikh_tempahan'])) ?></td>
                            <td><a href="padam_tempahan.php?id=<?= $row['id_tempahan'] ?>" onclick="return confirm('Adakah anda pasti ingin batalkan tempahan ini?')" class="text-danger ms-2">
        <i class="fas fa-trash-alt"></i>
    </a>
</td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tiada tempahan ditemui.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</body>
</html>