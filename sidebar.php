<div class="sidebar">
    <div>
        <img src="image.jpg" alt="CondoCare Logo" style="width: 100%; height: auto; display: block; margin-bottom: 20px;">
        <hr>

        <?php if ($_SESSION['user_level'] == 'Pengguna') { ?>
            <a href="dashboard_user.php">Halaman Utama</a>
            <a href="senarai_tempahan.php">Tempahan Kemudahan</a>
            <a href="senarai_aduan.php">Aduan</a>
            <a href="#">Pelawat</a>
            <a href="#">Pelawat Hari Ini</a>
            <a href="#">Pembayaran</a>
        <?php } ?>

        <!-- Hanya Pengurusan boleh lihat -->
        <?php if ($_SESSION['user_level'] === 'Pengurusan') { ?>
            <a href="#">Halaman Utama</a>
            <a href="#">Tempahan Kemudahan</a>
            <a href="#">Aduan</a>
            <a href="#">Pelawat</a>
            <a href="#">Pelawat Hari Ini</a>
            <a href="#">Pembayaran</a>
            <a href="daftar_pengguna.php">Daftar Pengguna</a>
        <?php } ?>
    </div>

    <a href="logout.php" class="logout-btn">Log Keluar</a>
</div>