<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$search_query = ''; // Deklarasi pembolehubah untuk carian

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = $_POST['search']; // Dapatkan nilai carian dari form
}

try {
    // Sambung ke database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query untuk dapatkan data berdasarkan carian
    $stmt = $conn->prepare("SELECT * FROM tbl_pelawat WHERE id_pelawat LIKE ? OR tujuan_pelawat LIKE ? OR nama_pelawat LIKE ? OR kategori_pelawat LIKE ? ORDER BY tarikh_lawatan DESC");
    $stmt->execute([
        "%$search_query%", "%$search_query%", "%$search_query%", "%$search_query%"
    ]);
    $pelawat = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pelawat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(222, 218, 200);
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #073B3A;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar img {
            width: 100%;
            height: auto;
            display: block;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background 0.3s ease-in-out;
        }

        .sidebar a:hover {
            background-color: #D27D2C;
        }

        /* Main Content */
        .content {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
            width: 100%;
        }

        .container {
            max-width: 100%;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .btn-tambah {
            background-color: #D27D2C;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-tambah:hover {
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

<!-- Panggil Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h3 class="mb-4">Senarai Pelawat</h3>

        <!-- Form Pencarian -->
        <form method="POST" class="search-bar">
    <input type="text" name="search" class="form-control" placeholder="Cari ID Pelawat, Tujuan, Nama, Kategori Pelawat" value="<?= htmlspecialchars($search_query) ?>" />
    <button type="submit" class="btn btn-tambah">Cari</button>
</form>

        <!-- Table Senarai Pelawat -->
        <table class="table table-bordered">
    <thead>
        <tr class="table-dark text-center">
            <th>No</th>
            <th>ID Pelawat</th>
            <th>Nama Pelawat</th>
            <th>Tarikh Lawatan</th>
            <th>Tujuan Pelawat</th>
            <th>No Tel Pelawat</th>
            <th>No Kenderaan</th>
            <th>Kategori Pelawat</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($pelawat) > 0): ?>
            <?php $i = 1; foreach ($pelawat as $row): ?>
                <tr>
                    <td class="text-center"><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['id_pelawat']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pelawat']) ?></td>
                    <td><?= htmlspecialchars($row['tarikh_lawatan']) ?></td>
                    <td><?= htmlspecialchars($row['tujuan_pelawat']) ?></td>
                    <td><?= htmlspecialchars($row['no_tel_pelawat']) ?></td>
                    <td><?= htmlspecialchars($row['no_kenderaan']) ?></td>
                    <td><?= htmlspecialchars($row['kategori_pelawat']) ?></td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">Tiada pelawat ditemui.</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>
    </div>
</div>

</body>
</html>