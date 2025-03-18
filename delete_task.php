<?php
include 'db.php';  // Include the database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];  // Get task ID from URL

// Prepare the SQL query to delete the task
$stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
$stmt->execute([$task_id, $user_id]);

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>