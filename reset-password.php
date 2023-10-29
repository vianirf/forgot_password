<?php

//get token from query string

$token = $_GET["token"];
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
//display form

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <h1>Reset Password</h1>

    <form action="process-reset-password.php" method="post">
        <!-- token values -->
        <input type="hidden" name="token" value="<?php htmlspecialchars($token) ?>">


        <label for="password">New Password</label>
        <input type="password" id="password" name="password">

        <label for="password_konfirm">Confirmation Password</label>
        <input type="password" id="password_konfirm" name="password_konfirm">

        <button>Send</button>
    </form>
</body>

</html>