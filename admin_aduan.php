<?php
session_start();
include 'database.php';

// Semak jika pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Mendapatkan nilai search dari parameter URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Dapatkan semua data dalam tbl_aduan berdasarkan kata kunci pencarian
try {
    $query = "SELECT * FROM tbl_aduan WHERE tajuk LIKE :search OR penerangan LIKE :search ORDER BY tarikh_aduan DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', "%$search%");
    $stmt->execute();
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
    <title>Admin - Senarai Aduan</title>
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

        .btn-kemaskini {
            background-color: #D27D2C;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-kemaskini:hover {
            background-color: #A65F20;
        }

        .search-bar {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .search-bar input {
            flex-grow: 1;
            padding: 10px;
            border-radius: 25px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }

        .search-bar button {
            background-color: #D27D2C;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #A65F20;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="container">
            <h3 class="mb-4">Senarai Aduan</h3>

            <!-- Form Pencarian -->
            <form method="GET" class="search-bar">
                <input type="text" class="form-control" name="search" placeholder="Cari Aduan, No Unit..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                <button type="submit">Cari</button>
            </form>

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
                                    <?php if (!empty($row['lampiran'])): ?>
                                        <a href="#" class="btn btn-sm btn-outline-primary lihat-gambar" data-bs-toggle="modal" data-bs-target="#modalGambar" data-src="uploads/<?= htmlspecialchars($row['lampiran']) ?>">Lihat Gambar</a>
                                    <?php else: ?>
                                        Tiada lampiran
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="admin_aduan_status.php" method="POST">
                                        <input type="hidden" name="id_aduan" value="<?= $row['id_aduan'] ?>">
                                        <select name="status_tindakan" class="form-control">
                                            <option value="Sedang Diproses" <?= $row['status_tindakan'] == 'Sedang Diproses' ? 'selected' : '' ?>>Sedang Diproses</option>
                                            <option value="Selesai" <?= $row['status_tindakan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                        <button type="submit" class="btn-kemaskini mt-2">Kemaskini</button>
                                    </form>
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

    <!-- MODAL POPUP GAMBAR -->
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

