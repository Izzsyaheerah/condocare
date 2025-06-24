<?php
include 'database.php';
session_start();

date_default_timezone_set('Asia/Kuala_Lumpur');

// Include PHPMailer manually
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emel = $_POST['emel'];

    

    // Validate email format
    if (!filter_var($emel, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Emel tidak sah!'); window.location.href='lupa_katalaluan.php';</script>";
        exit();
    }

    // Semak jika email wujud dalam database
    $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE fld_emel = ?");
    $stmt->execute([$emel]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Cipta token unik
        $token = bin2hex(random_bytes(50)); 
        $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes")); // Luput dalam 30 minit

        // Simpan token dalam database
        $stmt = $conn->prepare("UPDATE tbl_pengguna SET reset_token=?, reset_expiry=? WHERE fld_emel=?");
        $stmt->execute([$token, $expiry, $emel]);

        // Hantar email reset password guna PHPMailer
        $reset_link = "http://localhost/condocare/reset_password.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gunakan SMTP Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'izzsyaheerah@gmail.com'; // Gantilah dengan email anda
            $mail->Password = 'rmep hhvi ouxx julm'; // ✅ **Gunakan APP PASSWORD Gmail**
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // $mail->isSMTP();
            // $mail->Host = 'sandbox.smtp.mailtrap.io'; // Gunakan SMTP Gmail
            // $mail->SMTPAuth = true;
            // $mail->Username = 'd0b4517e9f7984'; // Gantilah dengan email anda
            // $mail->Password = '5ef5032cdf5046'; // ✅ **Gunakan APP PASSWORD Gmail**
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $mail->Port = 2525;

            

            // Tetapan email
            $mail->setFrom('izzsyaheerah@gmail.com', 'CondoCare Support');
            $mail->addAddress($emel);
            $mail->isHTML(true);
            $mail->Subject = "Reset Kata Laluan CondoCare";
            $mail->Body = "
             <p>Assalamualaikum dan Salam Sejahtera,</p>
    <p>Anda telah menerima pautan untuk menetapkan semula kata laluan bagi akaun CondoCare anda.</p>
    <p><b>Nota Penting:</b></p>
    <ul>
        <li>Untuk pengguna pertama kali, anda <b>DIWAJIBKAN</b> menetapkan kata laluan baharu sebelum dapat mengakses sistem ini sepenuhnya.</li>
        <li>Sebarang permasalahan berkaitan akaun atau akses, sila hubungi pihak pengurusan melalui emel rasmi yang disediakan.</li>
    </ul>
    <p>Sila klik pautan di bawah untuk menetapkan semula kata laluan anda:</p>
            <p><a href='http://localhost/condocare/reset_password.php?token=$token' target='_blank' style='color: blue; text-decoration: underline;'>
                Reset Kata Laluan
            </a></p>
            <p>Terima kasih.</p>
        ";



            $mail->send();
            echo "<script>alert('Sila semak email anda untuk reset kata laluan!'); window.location.href='login.php';</script>";
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Email gagal dihantar: " . $mail->ErrorInfo . "');</script>";
        }
    } else {
        echo "<script>alert('Email tidak dijumpai dalam sistem!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Laluan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #073B3A;
            color: #000; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 450px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            color: #000; 
        }

        .form-control {
            border: 1px solid #000; 
            border-radius: 5px;
            width: 100%;
        }

        .btn-custom {
            background: #1D1D1D;
            color: #fff;
            font-weight: 600;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-custom:hover {
            background: #000;
        }

        .login-link {
            margin-top: 10px;
            display: block;
            color: #D27D2C;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <div class="container">
        <h2 class="mb-3 fw-bold">Lupa Kata Laluan</h2>
        <p class="text-dark">Masukkan emel anda untuk menerima pautan set semula kata laluan.</p>
        <form method="post">
            <div class="mb-3">
                <label for="emel" class="form-label">Emel Anda</label>
                <input type="email" id="emel" name="emel" class="form-control" placeholder="contoh@gmail.com" required>
            </div>
            <button type="submit" class="btn-custom">Hantar</button>
        </form>
        <a href="login.php" class="login-link">Kembali ke Log Masuk</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>