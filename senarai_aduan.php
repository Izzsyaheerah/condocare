<?php
session_start();
include 'database.php';

// Semak jika pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['user_id']; // Ambil id pengguna dari sesi login

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Dapatkan semua aduan pengguna
try {
    $stmt = $conn->prepare("SELECT * FROM tbl_aduan WHERE fld_id_pengguna = ? ORDER BY tarikh_aduan DESC");
    $stmt->execute([$id_pengguna]);
    $aduan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Aduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h3 class="mb-4">Senarai Aduan</h3>
            <div class="text-end mb-3">
                <a href="aduan.php" class="btn btn-tambah">Tambah Aduan</a>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr class="table-dark text-center">
                        <th>No</th>
                        <th>No Unit</th>
                        <th>Nama Pengadu</th>
                        <th>No Tel</th>
                        <th>Tajuk</th>
                        <th>Penerangan</th>
                        <th>Lampiran</th>
                        <th>Status Tindakan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($aduan) > 0): ?>
                        <?php $i = 1; foreach ($aduan as $row): ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['fld_no_unit']) ?></td>
                                <td><?= htmlspecialchars($row['nama_pengadu']) ?></td>
                                <td><?= htmlspecialchars($row['no_tel']) ?></td>
                                <td><?= htmlspecialchars($row['tajuk']) ?></td>
                                <td><?= htmlspecialchars($row['penerangan']) ?></td>
                                <td class="text-center">
                                    <a href="#" 
                                       class="btn btn-sm btn-outline-primary lihat-gambar" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#modalGambar" 
                                       data-src="uploads/<?= htmlspecialchars($row['lampiran']) ?>">
                                       Lihat Gambar
                                    </a></td>
                                <td><?= htmlspecialchars($row['status_tindakan']) ?></td> 
                                <td><a href="padam_aduan.php?id=<?= $row['id_aduan'] ?>" onclick="return confirm('Adakah anda pasti ingin batalkan aduan ini?')" class="text-danger ms-2">
        <i class="fas fa-trash-alt"></i>
    </a>
</td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tiada aduan ditemui.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- POPUP GAMBAR -->
    <div class="modal fade" id="modalGambar" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="gambarLampiran" class="img-fluid rounded" alt="Lampiran Gambar">
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const gambarLinks = document.querySelectorAll('.lihat-gambar');
        const modalGambar = document.getElementById('gambarLampiran');

        gambarLinks.forEach(link => {
            link.addEventListener('click', function () {
                const imageUrl = this.getAttribute('data-src');
                modalGambar.src = imageUrl;
            });
        });
    });
    </script>

</body>
</html>
