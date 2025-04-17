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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(222, 218, 200);
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

        .logout-btn {
            background: #D27D2C;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: darkred;
        }

        /* Main Content */
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

    <!-- Panggil Sidebar -->
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
                        <th>Tajuk</th>
                        <th>Penerangan</th>
                        <th>Lampiran</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($aduan) > 0): ?>
                        <?php $i = 1; foreach ($aduan as $row): ?>
                            <tr>
                                <td class="text-center"><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['fld_no_unit']) ?></td>
                                <td><?= htmlspecialchars($row['tajuk']) ?></td>
                                <td><?= htmlspecialchars($row['penerangan']) ?></td>
                                <td><?= htmlspecialchars($row['lampiran']) ?></td>
                               
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tiada aduan ditemui.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>