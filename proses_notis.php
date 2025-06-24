<?php
session_start();
include 'database.php';

// Import PHPMailer config
require 'mailer_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Gagal sambung ke pangkalan data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tajuk = $_POST['tajuk'];
    $kandungan = $_POST['kandungan'];

    try {
        // Simpan notis ke database
        $stmt = $conn->prepare("INSERT INTO tbl_notis (tajuk, kandungan) VALUES (?, ?)");
        $stmt->execute([$tajuk, $kandungan]);

        // Hantar emel kepada semua penghuni
        $stmt_penghuni = $conn->query("SELECT fld_emel, fld_nama FROM tbl_pengguna WHERE fld_userlevel = 'Pengguna'");
        while ($penghuni = $stmt_penghuni->fetch(PDO::FETCH_ASSOC)) {
            $mail->clearAddresses();  // Reset sebelum hantar ke setiap penerima
            $mail->addAddress($penghuni['fld_emel'], $penghuni['fld_nama']);
            $mail->isHTML(true);
            $mail->Subject = "Notis Baru dari CondoCare";
            $mail->Body = "
                <p>Assalamualaikum " . htmlspecialchars($penghuni['fld_nama']) . ",</p>
                <p>Anda menerima notis baru daripada pihak pengurusan:</p>
                <h4>$tajuk</h4>
                <p>$kandungan</p>
                <p>Sekian, terima kasih.</p>";

            $mail->send();
        }

        $_SESSION['notis_status'] = "Notis berjaya dihantar dan email telah dihantar kepada semua penghuni!";
    } catch (Exception $e) {
        $_SESSION['notis_status'] = "Notis berjaya dihantar, tetapi emel gagal: " . $mail->ErrorInfo;
    } catch (PDOException $e) {
        $_SESSION['notis_status'] = "Ralat: " . $e->getMessage();
    }
}

header("Location: dashboard_admin.php");
exit();
?>