<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aduan</title>
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
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
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

    </style>
</head>
<body>

    <!-- Panggil Sidebar -->
   <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="mb-3 text-center">Tambah Aduan</h2>
            <form action="proses_aduan.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="no_unit" class="form-label">No Unit :</label>
                    <input type="text" id="no_unit" name="no_unit" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="hubungi" class="form-label">No Tel :</label>
                    <input type="text" id="hubungi" name="hubungi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="tajuk" class="form-label">Tajuk :</label>
                    <input type="text" id="tajuk" name="tajuk" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="penerangan" class="form-label">Penerangan :</label>
                    <textarea id="penerangan" name="penerangan" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran</label>
                    <input type="file" id="lampiran" name="lampiran" class="form-control">
                </div>
                <button type="submit" class="btn-custom">Hantar</button>
            </form>
        </div>
    </div>

</body>
</html>