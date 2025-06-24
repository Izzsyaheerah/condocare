<?php
session_start();
include 'database.php';

// Import PHPMailer config (guna fail awak yang sedia ada)
require 'mailer_config.php';

// Pastikan pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data dari borang
$fld_id_pengguna = $_SESSION['user_id'];
$kemudahan = $_POST['kemudahan'];
$fld_no_unit = $_POST['no_unit'];
$nama_penempah = $_POST['nama_penempah'];
$no_tel = $_POST['no_tel']; 
if (!preg_match('/^\d{1,11}$/', $no_tel)) {
    echo "<script>alert('Sila masukkan nombor telefon yang sah.'); window.history.back();</script>";
    exit();
}
$masa_slot = $_POST['masa_tempahan']; 
$tarikh_tempahan = $_POST['tarikh_tempahan'];

$current_date = date('Y-m-d'); 

if ($tarikh_tempahan < $current_date) {
    echo "<script>alert('Tarikh tempahan telah lepas. Sila pilih tarikh yang akan datang.'); window.history.back();</script>";
    exit();
}

list($masa_mula, $masa_tamat) = explode('-', $masa_slot);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Semak pertindihan
    $check = $conn->prepare("SELECT * FROM tbl_tempahan 
                             WHERE kemudahan = ? 
                             AND tarikh_tempahan = ? 
                             AND (
                                 (masa_mula <= ? AND masa_tamat > ?) OR
                                 (masa_mula < ? AND masa_tamat >= ?)
                             )");
    $check->execute([
        $kemudahan,
        $tarikh_tempahan,
        $masa_mula, $masa_mula,
        $masa_tamat, $masa_tamat
    ]);

    if ($check->rowCount() > 0) {
        echo "<script>alert('Tempahan gagal! Slot masa ini telah ditempah.'); window.history.back();</script>";
        exit();
    }

    // Simpan tempahan
    $stmt = $conn->prepare("INSERT INTO tbl_tempahan 
         (fld_id_pengguna, kemudahan, fld_no_unit, nama_penempah, no_tel, masa_mula, masa_tamat, tarikh_tempahan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $fld_id_pengguna,
        $kemudahan,
        $fld_no_unit,
        $nama_penempah,
        $no_tel,
        $masa_mula,
        $masa_tamat,
        $tarikh_tempahan
    ]);

    // HANTAR EMEL
    // Ambil email penghuni
    $stmt_user = $conn->prepare("SELECT fld_emel FROM tbl_pengguna WHERE fld_id_pengguna = ?");
    $stmt_user->execute([$fld_id_pengguna]);
    $user = $stmt_user->fetch();
    $emel = $user['fld_emel'];

    // Email config (guna yang dari mailer_config.php)
    $mail->addAddress($emel);
    $mail->isHTML(true);
    $mail->Subject = "Tempahan Berjaya!";
    $mail->Body = "
       <p>Assalamualaikum $nama_penempah,</p>
    <p>Dimaklumkan bahawa tempahan anda bagi kemudahan berikut telah berjaya direkodkan:</p>
    <ul>
        <li><b>Kemudahan:</b> $kemudahan</li>
        <li><b>Tarikh:</b> $tarikh_tempahan</li>
        <li><b>Masa:</b> $masa_mula - $masa_tamat</li>
    </ul>
    <p><b>Perhatian dan Peringatan:</b></p>
    <ul>
        <li>Pengguna <b>dikehendaki menggunakan kemudahan ini dengan sebaik mungkin</b> dan menjaga kebersihan serta keselamatan sepanjang penggunaan.</li>
        <li><b>Segala kerosakan, kehilangan atau penyalahgunaan kemudahan akan dikenakan tindakan tata tertib</b> dan bayaran ganti rugi mengikut ketetapan pihak pengurusan.</li>
    </ul>
   <p>Sebarang pertanyaan lanjut, sila hubungi pihak pengurusan atau terus ke Pejabat Pengurusan.</p>

     <p><b>Waktu Operasi Pejabat Pengurusan:</b></p>
    <ul>
        <li>Isnin hingga Jumaat: 9.00 pagi - 5.00 petang</li>
       <li>Sabtu: 9.00 pagi - 1.00 petang</li>
        <li>Ahad & Cuti Umum: Tutup</li>
    </ul>

    <p>Sekian, terima kasih atas kerjasama anda.</p>
";

    $mail->send();

    echo "<script>alert('Tempahan berjaya dihantar!'); window.location.href='senarai_tempahan.php';</script>";

} catch (Exception $e) {
    echo "<script>alert('Tempahan berjaya, tetapi email gagal dihantar: {$mail->ErrorInfo}'); window.location.href='senarai_tempahan.php';</script>";
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>