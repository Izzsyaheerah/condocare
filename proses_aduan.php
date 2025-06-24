<?php
session_start();
include 'database.php';

// Import PHPMailer config
require 'mailer_config.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Dapatkan data dari borang
$fld_no_unit = $_POST['no_unit'];
$nama_pengadu = $_POST['nama_pengadu'];
$no_tel = $_POST['no_tel'];  
if (!preg_match('/^\d{1,11}$/', $no_tel)) {
    echo "<script>alert('Sila masukkan nombor telefon yang sah.'); window.history.back();</script>";
    exit();
}

$tajuk = $_POST['tajuk'];
$penerangan = $_POST['penerangan'];
$lampiran = '';
if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK) {
    $lampiran = $_FILES['lampiran']['name']; 
    move_uploaded_file($_FILES['lampiran']['tmp_name'], "uploads/".$lampiran);
}

$fld_id_pengguna = $_SESSION['user_id'];

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO tbl_aduan (fld_no_unit, nama_pengadu, no_tel, tajuk, penerangan, lampiran, fld_id_pengguna) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fld_no_unit, $nama_pengadu, $no_tel, $tajuk, $penerangan, $lampiran, $fld_id_pengguna]);

    // Hantar Email Notifikasi
    $stmt_user = $conn->prepare("SELECT fld_emel FROM tbl_pengguna WHERE fld_id_pengguna = ?");
    $stmt_user->execute([$fld_id_pengguna]);
    $user_data = $stmt_user->fetch();
    $emel = $user_data['fld_emel'];

    // Set emel penerima
    $mail->addAddress($emel);
    $mail->isHTML(true);
    $mail->Subject = "Aduan Berjaya Dihantar!";
    $mail->Body = "
        <p>Assalamualaikum $nama_pengadu,</p>
        <p>Aduan anda telah berjaya dihantar!</p>
        <ul>
            <li><b>Tajuk Aduan:</b> $tajuk</li>
            <li><b>No Unit:</b> $fld_no_unit</li>
            <li><b>No Telefon:</b> $no_tel</li>
            <li><b>Tarikh Hantar:</b> " . date('d/m/Y') . "</li>
        </ul>
        <p>Pihak pengurusan akan memproses aduan anda secepat mungkin.</p>

         <p>Sekian, terima kasih atas kerjasama anda.</p>
";

    $mail->send();

    echo "<script>alert('Aduan berjaya dihantar!'); window.location.href='senarai_aduan.php';</script>";

} catch (Exception $e) {
    echo "<script>alert('Aduan berjaya dihantar tetapi email gagal dihantar: {$mail->ErrorInfo}'); window.location.href='senarai_aduan.php';</script>";
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>