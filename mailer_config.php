<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'izzsyaheerah@gmail.com';      // GANTIKAN dengan email anda
    $mail->Password   = 'rmep hhvi ouxx julm';   // GANTIKAN dengan app password Gmail anda
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('izzsyaheerah@gmail.com', 'CondoCare Support'); // Nama pengirim
    $mail->isHTML(false); // Set true jika nak guna HTML
} catch (Exception $e) {
    echo "Mailer Config Error: {$mail->ErrorInfo}";
}
?>
