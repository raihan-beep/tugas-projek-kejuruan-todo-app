<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    echo "Akun berhasil dihapus!";
    header("Location: dashboard.php");
    exit();
}
?>