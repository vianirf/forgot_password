<?php

//get token from query string

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

//conn db
$mysqli = require __DIR__ . "database.php";
$sql = "SELECT * FROM user WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();


$result = $stmt->get_result();
$user = $result->fetch_assoc();

//if no record was found
if ($user === null) {
    die("token not found");
}
//cek apakah token nya expired atau tidak
if (strtotime($user["reset_token_expired_at"]) <= time()) {
    die("token has expired");
}
echo "token is valid and hasn't expired";




// Content Validasi password
//setelah bikin content validasi password
//update sql
$sql = "UPDATE user SET passsword_hash = ?m
                        reset_token_hash = NULL,
                        reset_token_expired_at = NULL
                    WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $password_hash, $user["id"]);
$stmt->execute();
