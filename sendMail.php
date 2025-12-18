<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendResetEmail($email, $token) {

    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'syukurisal@gmail.com';
        $mail->Password   = 'xbyt mdbf qodf gdee'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email pengirim & penerima
        $mail->setFrom('syukurisal@gmail.com', 'Admin Auth Password');
        $mail->addAddress($email);

        //  link reset password ( token!)
        $resetLink = "http://localhost/Login_Auth/reset_password.php?token=$token";

        // Isi email
        $mail->isHTML(true);
        $mail->Subject = 'Reset Password Anda';
        $mail->Body = "
            Klik link berikut untuk reset password Anda:<br><br>
            <a href='$resetLink' style='padding:10px 15px;background:#4CAF50;color:white;text-decoration:none;border-radius:5px;'>
                Reset Password
            </a>
            <br><br>
            Jika tombol tidak berfungsi, buka link ini:<br>
            <b>$resetLink</b>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
