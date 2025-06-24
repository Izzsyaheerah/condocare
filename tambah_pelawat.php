<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tujuan_pelawat = $_POST['tujuan_pelawat'];
    $nama_pelawat = $_POST['nama_pelawat'];
    $no_tel_pelawat = $_POST['no_tel_pelawat'];
    $no_kenderaan = $_POST['no_kenderaan'];
    $tarikh_lawatan = $_POST['tarikh_lawatan'];
    $kategori_pelawat = $_POST['kategori_pelawat'];
    $fld_id_pengguna = $_SESSION['user_id'];

    // Dapatkan ID pelawat baru
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id_pelawat FROM tbl_pelawat ORDER BY id_pelawat DESC LIMIT 1");
    $stmt->execute();
    $last_id = $stmt->fetchColumn(); // Mendapatkan ID terakhir

    // Tentukan ID pelawat baru
    if ($last_id) {
        $last_number = (int)substr($last_id, 2); // Ambil nombor selepas "VS"
        $new_id = 'VS' . ($last_number + 1); // Menjana ID baru
    } else {
        $new_id = 'VS1'; // Jika tiada pelawat, set ID pertama sebagai VS1
    }

    try {
        // Insert pelawat baru ke dalam database
        $stmt = $conn->prepare("INSERT INTO tbl_pelawat (id_pelawat, tujuan_pelawat, nama_pelawat, no_tel_pelawat, no_kenderaan, tarikh_lawatan, kategori_pelawat, fld_id_pengguna) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$new_id, $tujuan_pelawat, $nama_pelawat, $no_tel_pelawat, $no_kenderaan, $tarikh_lawatan, $kategori_pelawat, $fld_id_pengguna]);

        // Redirect ke halaman lihat pelawat dengan ID pelawat yang baru
        header("Location: lihat_pelawat.php?id=$new_id");
        exit();

    } catch (PDOException $e) {
        echo "Ralat: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelawat Baharu</title>
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

        .form-select {
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<!-- Panggil Sidebar -->
<?php include 'sidebar.php'; ?>


<!-- Main Content -->
<div class="content">
    <div class="container">
        <h3 class="mb-4 text-center">Tambah Pelawat Baru</h3>
        
        <!-- Form Tambah Pelawat -->
         
        <form action="proses_pelawat.php" method="post">
            <div class="mb-3">
                <label for="tujuan_pelawat" class="form-label">Tujuan Pelawat:</label>
                <select class="form-select" id="tujuan_pelawat" name="tujuan_pelawat" required>
                    <option value="">Pilih Tujuan Pelawat</option>
                    <option value="Tetamu">Tetamu</option>
                    <option value="Pekerja">Pekerja</option>
                    <option value="Penghantaran">Penghantaran</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="nama_pelawat" class="form-label">Nama Pelawat:</label>
                <input type="text" class="form-control" id="nama_pelawat" name="nama_pelawat" required>
            </div>

            <div class="mb-3">
                <label for="no_tel_pelawat" class="form-label">No Tel Pelawat:</label>
                <input type="text" class="form-control" id="no_tel_pelawat" name="no_tel_pelawat" maxlength="11" pattern="\d{10,11}" required>
            </div>

            <div class="mb-3">
                <label for="no_kenderaan" class="form-label">No Kenderaan:</label>
                <input type="text" class="form-control" id="no_kenderaan" name="no_kenderaan" required>
            </div>

            <div class="mb-3">
    <label for="tarikh_lawatan" class="form-label">Tarikh Lawatan</label>
    <input type="date" class="form-control" id="tarikh_lawatan" name="tarikh_lawatan" required>
</div>

<div class="mb-3">
    <label for="kategori_pelawat" class="form-label">Kategori Pelawat :</label>
    <select class="form-select" id="tujuan_pelawat" name="kategori_pelawat" required>
        <option value="">Pilih Kategori Pelawat</option>
        <option value="pelawat_harian">Pelawat Harian</option>
        <option value="pelawat_bermalam">Pelawat Bermalam</option>
        <option value="pelawat_sementara">Pelawat Sementara</option>
    </select>
</div>

            <button type="submit" class="btn-custom">Hantar</button>
        </form>
    </div>
</div>

</body>
</html>