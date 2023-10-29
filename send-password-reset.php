<?php
// menambahakan 2 kolom table database
//1. reset_token varchar 64 length  default:NULL null centang INDEX:UNIQUE
//2. reset_token_expired_at datetime default:NULL null centang


$email = $_POST["email"];
$token = bin2hex(random_bytes(16)); //generate random token values (16) itu length ..... bin2hex conver menjadi hexadecimal string

$token_hash = hash("sha256", $token); //menyimpan token dalam bentuk hash

//token dijadikan expired biar ga di brute force nebaknya
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);  //token valid hanya 30 min

//store ke db
$mysqli = require __DIR__ . "/database.php"; //konek db
$sql = "UPDATE user 
        SET reset_token_hash = ?,
            reset_token_expired_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();


// jika di klik send, di database terisi reset_token_hash dan reset_token_expired_at

//langkah selanjutnya
// instal library phpmailer
// sama akses ke SMTP Server
// jika sudah terinstal akan ada folder bernama vendor


if ($mysqli->affected_rows) {  //jika email was found dan row updated

    $mail = require __DIR__ . "mailer.php";
    //set properties buat message yg akan dikirim

    $mail->setFrom("noreply@example.com"); //set email address pengirim
    $mail->addAddress($email); //set penerima email
    $email->Subject = "Password Reset"; //set subject email
    $email->Body = <<<END

    isi eemail nya... contoh
    Click <a href="http://example.com/reset-password.php?token=$token">Here</a> to reset your password.

    END;
    try {
        $mail->send(); //send email
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}
