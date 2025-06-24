<?php
session_start();
include 'database.php';

// Halang akses jika bukan Admin
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'Pengurusan') {
    echo "<script>alert('Anda tidak mempunyai akses ke halaman ini!'); window.location.href='dashboard_admin.php';</script>";
    exit();
}


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ralat sambungan: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #dedac8; display: flex; }
        .content { margin-left: 260px; padding: 20px; flex: 1; width: 100%; }
        .container { max-width: 600px; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-top: 20px; }
        .btn-custom { background: #D27D2C; color: #fff; font-weight: 600; padding: 10px; width: 100%; border-radius: 5px; border: none; }
        .btn-custom:hover { background: #A65F20; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="text-center mb-3">Tambah Bayaran</h2>
        <form action="proses_bayaran.php" method="post">
            <div class="mb-3">
                <label for="jenis_bayaran" class="form-label">Jenis Bayaran:</label>
                <input type="text" id="jenis_bayaran" name="jenis_bayaran" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah Bayaran (RM):</label>
                <input type="number" id="jumlah" name="jumlah" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="tarikh_bayaran" class="form-label">Bulan Bayaran:</label>
                <input type="month" id="tarikh_bayaran" name="tarikh_bayaran" class="form-control" required>
            </div>

            <div class="mb-3">
    <label for="fld_id_pengguna" class="form-label">Pilih Penghuni:</label>
    <select id="fld_id_pengguna" name="fld_id_pengguna" class="form-select" required>
        <option value="">-- Pilih Penghuni --</option>
        <option value="ALL">Semua Penghuni</option> <!-- Tambah option ini -->

        <?php
        $stmt = $conn->query("SELECT fld_id_pengguna, fld_nama, fld_no_unit FROM tbl_pengguna WHERE fld_userlevel = 'Pengguna'");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($row['fld_id_pengguna']) . "'>" .
                 htmlspecialchars($row['fld_nama']) . " (" . htmlspecialchars($row['fld_no_unit']) . ")" .
                 "</option>";
        }
        ?>
    </select>
</div>

            <button type="submit" class="btn-custom">Tambah Bayaran</button>
        </form>
    </div>
</div>

</body>
</html>
