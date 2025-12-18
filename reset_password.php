<?php
date_default_timezone_set('Asia/Jakarta');

session_start();
include 'dbConnect.php';

if (!isset($_GET['token'])) {
    die("Token tidak ditemukan");
}

$token = $_GET['token'];

$query = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
$query->execute([$token]);


if ($query->rowCount() === 0) {
    die("Token tidak valid atau sudah expired.");
}

$data = $query->fetch(PDO::FETCH_ASSOC);
$email = $data['email'];

if (isset($_POST['reset'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->execute([$password, $email]);

    $delete = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
    $delete->execute([$email]);

    $_SESSION['success'] = "Password berhasil direset, silakan login.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <div class="container">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Reset Password</h2>

<form method="POST">
    <div class="input-group">
        <input type="password" name="password" placeholder="Password Baru" required>
    </div>

    <button type="submit" name="reset" class="btn">Reset Password</button>
</form>
</div>
</body>
</html>
