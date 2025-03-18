<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $user_id]);

    $_SESSION['success'] = "Password berhasil diperbarui!";
    header("Location: dashboard.php");
    exit();
}

$user_id = $_GET['id'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('paper.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
        }
        .btn {
            margin-top: 10px;
        }
        .form-control {
            text-align: center;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <h2 class="mb-4">Ganti Password</h2>
        <form method="POST">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
            <div class="mb-3">
                <input type="password" name="new_password" class="form-control" placeholder="Password Baru" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ganti Password</button>
            <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
        </form>
    </div>
</body>
</html>