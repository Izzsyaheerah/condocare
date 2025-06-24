<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Menangani form pencarian
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Dapatkan semua data dalam tbl_tempahan berdasarkan pencarian
try {
    $stmt = $conn->prepare("SELECT * FROM tbl_tempahan WHERE kemudahan LIKE ? OR fld_no_unit LIKE ? OR no_tel LIKE ? ORDER BY tarikh_tempahan DESC");
    $stmt->execute(["%$search_query%", "%$search_query%", "%$search_query%"]);
    $tempahan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Senarai Tempahan</title>
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

        .btn-tambah {
            background-color: #D27D2C;
            color: white;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background-color: #A65F20;
        }

        .search-bar {
    display: flex;
    align-items: center;
    width: 100%; /* Lebar penuh */
    margin-bottom: 20px;
}

.search-bar input {
    flex-grow: 1; /* Membuat input mengisi ruang yang tinggal */
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

    <!-- Panggil Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h3 class="mb-4">Senarai Tempahan</h3>

            <!-- Form Pencarian -->
            <form method="POST" class="search-bar">
                <input type="text" name="search" class="form-control" placeholder="Cari Kemudahan, No Unit, No Tel..." value="<?= htmlspecialchars($search_query) ?>" />
                <button type="submit" class="btn btn-primary mt-2">Cari</button>
            </form>

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
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tiada tempahan ditemui.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>