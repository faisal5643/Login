<?php
session_start();
include 'dbConnect.php';

if (isset($_POST['send'])) {

    $email = $_POST['email'];

    $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);

    if ($query->rowCount() == 0) {
        $_SESSION['errors']['email'] = "Email tidak terdaftar!";
        header("Location: forgot_password.php");
        exit();
    }

    $token = bin2hex(random_bytes(50));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $insert = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $insert->execute([$email, $token, $expires]);

    $reset_link = "http://localhost/reset_password.php?token=" . $token;

    include 'sendMail.php';

    sendResetEmail($email, $token);



    $_SESSION['success'] = "Link reset sudah dikirim ke email Anda.";
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Lupa Password</h2>

<?php
if (!empty($_SESSION['errors']['email'])) {
    echo "<p class='error'>".$_SESSION['errors']['email']."</p>";
    unset($_SESSION['errors']['email']);
}

if (!empty($_SESSION['success'])) {
    echo "<p class='success'>".$_SESSION['success']."</p>";
    unset($_SESSION['success']);
}
?>

<form method="POST">
    <div class="input-group">
        <input type="email" name="email" placeholder="Masukkan Email Anda" required>
    </div>
    <button type="submit" name="send" class="btn">Kirim Link Reset</button>
</form>
</div>
</body>
</html>
