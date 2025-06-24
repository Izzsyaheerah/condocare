<?php
session_start();
include_once 'database.php';

// Halang akses jika bukan Admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'Pengurusan') {
    echo "<script>alert('Anda tidak mempunyai akses ke halaman ini!'); window.location.href='dashboard_admin.php';</script>";
    exit();
}

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $no_unit = $_POST['no_unit'];
    $email = $_POST['email'];
    $password = "Password123";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Sila masukkan emel yang sah!'); window.location.href='daftar_pengguna.php';</script>";
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Emel sudah digunakan!');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO tbl_pengguna (fld_id_pengguna, fld_nama, fld_no_unit, fld_emel, fld_kata_laluan, fld_userlevel) 
                                    VALUES (UUID(), ?, ?, ?, ?, 'Pengguna')");
            $stmt->execute([$nama, $no_unit, $email, $password]);
            echo "<script>alert('Pengguna berjaya didaftarkan!'); window.location.href='dashboard_admin.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "Ralat: " . $e->getMessage();
    }
}

if (isset($_POST['import_csv']) && isset($_FILES['csv_file'])) {
    $csv = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($csv, "r");
    $firstRow = true;
    $count = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($firstRow) { $firstRow = false; continue; }

        list($nama, $no_unit, $emel, $password) = $data;

        if (!filter_var($emel, FILTER_VALIDATE_EMAIL)) continue;

        $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = ?");
        $stmt->execute([$emel]);
        if ($stmt->rowCount() > 0) continue;

        $stmt = $conn->prepare("INSERT INTO tbl_pengguna (fld_id_pengguna, fld_nama, fld_no_unit, fld_emel, fld_kata_laluan, fld_userlevel) 
                                VALUES (UUID(), ?, ?, ?, ?, 'Pengguna')");
        $stmt->execute([$nama, $no_unit, $emel, $password]);
        $count++;
    }

    echo "<script>alert('Berjaya daftar $count pengguna melalui CSV!'); window.location.href='daftar_pengguna.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Pengguna - CondoCare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: rgb(222, 218, 200);
    }

    .container {
        max-width: 600px;
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
        width: 100%;
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    .btn-custom:hover {
        background: #A65F20;
    }

    .form-label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 5px;
        padding: 10px;
        border: 1px solid #ddd;
    }
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h3 class="mb-4 text-center">Daftar Pengguna Baharu</h3>

        <form method="post">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <div class="mb-3">
                <label for="no_unit" class="form-label">No Unit:</label>
                <input type="text" class="form-control" id="no_unit" name="no_unit" placeholder="A-##-##" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Emel:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Laluan Sementara:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" name="register" class="btn-custom">Daftar</button>
        </form>

        <hr class="my-4">

        <h5 class="text-center fw-bold mt-4">Daftar Pengguna melalui Fail CSV</h5>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="csv_file" class="form-label">Muat Naik Fail .csv</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
            </div>
            <button type="submit" name="import_csv" class="btn-custom">Muat Naik & Daftar</button>
        </form>
    </div>
</div>

</body>
</html>
