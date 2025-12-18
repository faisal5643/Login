<?php
session_start();
include __DIR__ . '/dbConnect.php';

if (!isset($_GET['token'])) {
    die("Token tidak ditemukan");
}

$token = $_GET['token'];

// Cek token
$query = $conn->prepare("SELECT * FROM users WHERE verify_token = ?");
$query->execute([$token]);

if ($query->rowCount() == 0) {
    die("Token tidak valid!");
}

$user = $query->fetch(PDO::FETCH_ASSOC);

// Update status verifikasi
$update = $conn->prepare("
    UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = ?
");
$update->execute([$user['id']]);

echo "<h2>Email berhasil diverifikasi! Anda sudah bisa login.</h2>";
echo "<a href='login.php'>Login Sekarang</a>";
?>
