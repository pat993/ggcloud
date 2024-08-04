<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Smtp_test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Load Composer's autoloader
        require 'vendor/autoload.php'; // Pastikan path ini sesuai dengan struktur proyek Anda
    }

    public function index()
    {
        // PHPMailer object
        $mail = new PHPMailer();

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = 'iix1533.idcloudhost.com'; // Gunakan IP address server SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@ggcloud.id'; // User email
        $mail->Password = '@Patra007'; // Password email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Atau 'ssl' jika menggunakan port 465
        $mail->Port     = 587; // Port untuk TLS. Gunakan 465 jika SSL.

        // Aktifkan debugging
        $mail->SMTPDebug = 2; // 2: Debug output verbose
        $mail->Debugoutput = 'html'; // Output debugging dalam format HTML

        // Pengaturan email
        $mail->setFrom('noreply@ggcloud.id', 'GGCLOUD');
        $mail->addAddress('patra.brave@gmail.com'); // Ganti dengan email tujuan yang valid
        $mail->Subject = 'Test Email';
        $mail->isHTML(true);
        $mail->Body = '<h1>This is a test email</h1><p>If you see this email, SMTP is working.</p>';

        // Kirim email
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent.';
        }
    }
}
