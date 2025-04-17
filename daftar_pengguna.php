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

    // Tetapkan Kata Laluan Sementara (Admin boleh tukar manual nanti)
    $password = "Password123"; 

    // **Validasi Email - Pastikan ada '@' dan '.'**
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Sila masukkan emel yang sah!'); window.location.href='daftar_pengguna.php';</script>";
        exit();
    }

    try {
        // Semak jika email sudah wujud
        $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Emel sudah digunakan!');</script>";
        } else {
            // Masukkan pengguna baru dengan password sementara
            $stmt = $conn->prepare("INSERT INTO tbl_pengguna (fld_id_pengguna, fld_nama, fld_no_unit, fld_emel, fld_kata_laluan, fld_userlevel) 
                                    VALUES (UUID(), ?, ?, ?, ?, 'Pengguna')");
            $stmt->execute([$nama, $no_unit, $email, $password]);

            // ✅ Paparkan pop-up "Pengguna berjaya didaftarkan!" sahaja
            echo "<script>alert('Pengguna berjaya didaftarkan!'); window.location.href='dashboard_user.php';</script>";
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

        // Validasi emel
        if (!filter_var($emel, FILTER_VALIDATE_EMAIL)) continue;

        // Cek emel
        $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = ?");
        $stmt->execute([$emel]);
        if ($stmt->rowCount() > 0) continue;

        // Masukkan ke DB
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
        background-color: #073B3A;
        font-family: 'Poppins', sans-serif;
    }
    .header-bar {
        background-color: #D27D2C;
        padding: 15px;
        color: white;
        font-size: 20px;
        font-weight: bold;
        text-align: left;
    }
    .register-container {
        max-width: 400px;
        margin: auto;
        margin-top: 100px;
        padding: 20px;
        background: #FFFFFF;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-custom {
        background: #1D1D1D;
        color: #fff;
        font-weight: bold;
    }
    .btn-custom:hover {
        background: #000;
    }
    .form-control {
        border: none;
        border-bottom: 2px solid #000;
        border-radius: 0;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #FF6600;
    }
    .login-link {
        color: #FF6600;
        text-decoration: none;
    }
    .login-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="header-bar">CONDO CARE</div>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="register-container">
            <h4 class="text-center fw-bold">Daftar Pengguna</h4>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"> <?php echo $error; ?> </div>
            <?php } ?>
           <!-- Borang daftar individu -->
<form method="post">
    <div class="mb-3">
        <label for="nama" class="form-label fw-bold">Nama</label>
        <input type="text" id="nama" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="no_unit" class="form-label fw-bold">No Unit</label>
        <input type="text" id="no_unit" name="no_unit" class="form-control" placeholder="A-##-##" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label fw-bold">Emel</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label fw-bold">Kata Laluan</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <div class="text-center">
        <button type="submit" name="register" class="btn btn-custom w-100">Daftar Masuk</button>
    </div>
</form>

<hr class="my-4">

<!-- Borang daftar pukal (CSV) -->
<h5 class="text-center fw-bold mt-4">Daftar Pengguna </h5>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="csv_file" class="form-label">Muat Naik Fail .csv</label>
        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
    </div>
    <div class="text-center">
        <button type="submit" name="import_csv" class="btn btn-custom w-100">Muat Naik dan Daftar</button>
    </div>
    <!-- <p class="text-muted mt-2 text-center">Format CSV: Nama, No Unit, Emel, Kata Laluan</p> -->
</form>
        </div>
    </div>
</body>
</html>
