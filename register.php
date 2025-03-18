<?php
session_start();
require 'db.php'; // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = isset($_POST['role']) ? $_POST['role'] : 'user'; // Default role is 'user'

    // Ensure only admins can create admin accounts
    if ($role === 'admin' && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
        die("Only admins can create admin accounts.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'role' => $role]);
        echo "Registration successful!";
    } catch (PDOException $e) {
        echo "Error: " . ($e->getCode() == 23000 ? "Username already exists." : $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Set background image */
        body {
            background-image: url('paper.jpeg'); /* Replace with your image file */
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
        }
        .btn {
            margin-top: 10px;
        }
        .link-blue {
            color: #00bfff;
        }
        .link-blue:hover {
            text-decoration: underline; 
        }
        .logo-img {
            width: 750px;
            height: 104px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="d-flex full-height">
    <div class="container centered mt-5">
        <img src="todologo.png" alt="Logo" class="logo-img">
        <h1 class="mb-5">Register</h1> 
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <!-- Show role selection only if an admin is logged in -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div class="mb-3">
                    <label class="form-label">Role:</label><br>
                    <input type="radio" id="user" name="role" value="user" checked>
                    <label for="user">User</label>
                    <input type="radio" id="admin" name="role" value="admin">
                    <label for="admin">Admin</label>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="mt-3">
            <p>Already have an account? <a href="login.php" class="link-blue">Login here</a></p>
        </div>
    </div>
</body>
</html>