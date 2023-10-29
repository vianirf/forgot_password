<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

require __DIR__ . "vendor/autoload.php";

$mail = new PHPMailer(true);

//jika ada issu sending email (optional)
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isStMTP();
$mail->SMTPAuth = true;


//setting SMTP
$mail->Host = "smtp.example.com";
$mail->SMTPSecure = PHPMAILER::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "your-username@example.com";
$mail->Pasword = "your-password";

//enable html content in emails
$mail->isHtml(true);

return $mail;


//balike ke send-password-reset