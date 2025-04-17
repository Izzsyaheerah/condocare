<?php
session_start();

// Semak sama ada pengguna telah login
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
    <title>Dashboard Pengguna - CondoCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(222, 218, 200);
            margin: 0;
            padding: 0;
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

        .sidebar h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
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

        /* Content */
        .content {
            margin-left: 260px;
            padding: 20px;
            flex: 1;
            width: 100%;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #D27D2C;
            padding: 15px;
            color: white;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar img {
            height: 40px;
            margin-right: 10px;
        }

        /* Logout Button */
        .logout-btn {
            background: #D27D2C;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: darkred;
        }

    </style>
</head>
<body>

   <!-- Panggil Sidebar -->
   <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <?php if ($_SESSION['user_level'] == 'Pengguna') { ?>
            <!-- <div>
                <p>Selamat datang Pengguna, <strong><?php echo $_SESSION['user_name']; ?></strong>!</p>
            </div>-->

            <?php include('./pengguna/dashboard_pengguna.php'); ?>
        <?php } ?>



        <?php if ($_SESSION['user_level'] == 'Pengurusan') { ?>
            <!-- <div>
                <p>Selamat datang Pengurusan, <strong><?php echo $_SESSION['user_name']; ?></strong>!</p>
            </div> -->

            <?php include('./admin/dashboard_admin.php'); ?>
        <?php } ?>
        
      

        <!-- Tambah Kandungan Dashboard di sini -->
        <div class="card p-3 shadow-sm">
            <h4>Notis Terbaru</h4>
            <p>Tiada notis buat masa ini.</p>
        </div>
    </div>

</body>
</html>

