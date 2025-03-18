<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Simpan role dalam session
        $_SESSION['user_id'] = $user['id'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Set background image */
        body {
            background-image: url('paper.jpeg'); /* Replace with your image file */
            background-size: cover;  /* Cover the entire background */
            background-position: center; /* Center the background */
            background-attachment: fixed; /* Make the background fixed while scrolling */
            color: white; /* Set text color to white for all text */
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background for contrast */
            padding: 20px;
            border-radius: 8px; /* Rounded corners */
            text-align: center; /* Center-align content */
        }
        .btn {
            margin-top: 10px;
        }
        .link-blue {
            color: #00bfff; /* Light blue for links */
        }
        .link-blue:hover {
            text-decoration: underline; /* Add underline on hover for better visibility */
        }
        /* Style for the logo */
        .logo-img {
            width: 750px;  /* Set the width to the original size */
            height: 104px; /* Set the height to the original size */
            margin-bottom: 20px; /* Space between logo and text */
        }
    </style>
</head>
<body class="d-flex full-height">
    <div class="container centered mt-5">
        <!-- Logo above the login text -->
        <img src="todologo.png" alt="Logo" class="logo-img">
        <h1 class="mb-5">Login</h1> <!-- Added margin-bottom to the title -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="register.php" class="link-blue">Sign up</a></p>
    </div>
</body>
</html>