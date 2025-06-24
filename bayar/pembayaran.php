<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode_pembayaran'];
    $id_pelawat = $_SESSION['user_id'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO tbl_pembayaran (id_pelawat, jumlah, tarikh_pembayaran, status_pembayaran, metode_pembayaran) 
                                VALUES (?, ?, NOW(), 'Belum Dibayar', ?)");
        $stmt->execute([$id_pelawat, $jumlah, $metode]);

        echo "<script>alert('Pembayaran berjaya dihantar!'); window.location.href='pembayaran.php';</script>";
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
    <title>Pembayaran</title>
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
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .btn-custom {
            background-color: #D27D2C;
            color: white;
            font-weight: bold;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-custom:hover {
            background-color: #A65F20;
        }

        /* Form Customization */
        .form-label {
            font-weight: bold;
        }

        input, select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            width: 100%;
        }

        .input-group {
            margin-bottom: 20px;
        }

    </style>
</head>
<body>

    <!-- Panggil Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h3 class="mb-4">Pembayaran</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Pembayaran (RM):</label>
                    <input type="number" class="form-control" name="jumlah" required>
                </div>

                <div class="mb-3">
                    <label for="metode_pembayaran" class="form-label">Kaedah Pembayaran:</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <option value="Kad Kredit">Kad Kredit</option>
                        <option value="Perbankan Dalam Talian">Perbankan Dalam Talian</option>
                        <option value="Tunai">Tunai</option>
                    </select>
                </div>

                <button type="submit" class="btn-custom">Hantar Pembayaran</button>
            </form>
        </div>
    </div>

</body>
</html>