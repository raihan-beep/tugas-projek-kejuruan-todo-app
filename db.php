<?php
// db.php: Create PDO connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo_app"; // Ensure this database exists

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>