<style>
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
</style>


<div class="sidebar">
    <div>
        <img src="image.jpg" alt="CondoCare Logo" style="width: 100%; height: auto; display: block; margin-bottom: 20px;">
        <hr>

        <?php if ($_SESSION['user_level'] == 'Pengguna') { ?>
            <a href="dashboard_pengguna.php">Halaman Utama</a>
            <a href="senarai_tempahan.php">Tempahan Kemudahan</a>
            <a href="senarai_aduan.php">Aduan</a>
            <a href="senarai_pelawat.php">Pelawat</a>
            <a href="bayaran.php">Pembayaran</a>
        <?php } ?>

        <!-- Hanya Pengurusan boleh lihat -->
        <?php if ($_SESSION['user_level'] === 'Pengurusan') { ?>
            <a href="dashboard_admin.php">Halaman Utama</a>
            <a href="admin_tempahan.php">Tempahan Kemudahan</a>
            <a href="admin_aduan.php">Aduan</a>
            <a href="admin_pelawat.php">Pelawat</a>
            <a href="admin_lihat_bayaran.php">Pembayaran</a>
            <a href="daftar_pengguna.php">Daftar Pengguna</a>
        <?php } ?>
    </div>

    <a href="logout.php" class="logout-btn">Log Keluar</a>
</div>