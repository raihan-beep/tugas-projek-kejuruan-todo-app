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

// Handle the POST request to update the task
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $type = $_POST['type'];
    $deadline = $_POST['deadline'];

    // Prepare the SQL query to update the task
    $stmt = $pdo->prepare("UPDATE tasks SET status = ?, type = ?, deadline = ? WHERE task_id = ? AND user_id = ?");
    $stmt->execute([$status, $type, $deadline, $task_id, $user_id]);

    // Redirect to the dashboard after updating the task
    header("Location: dashboard.php");
    exit();
}

// Prepare the SQL query to get the task details
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_id = ? AND user_id = ?");
$stmt->execute([$task_id, $user_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// Check deadline status
$deadline_time = strtotime($task['deadline']);
$current_time = time();
$time_diff = $deadline_time - $current_time;

// If the deadline is in the past, display a notification
$deadline_notification = '';
if ($time_diff < 0) {
    $deadline_notification = 'The deadline for this task has passed!';
} elseif ($time_diff <= 86400) {  // 24 hours (86400 seconds)
    $deadline_notification = 'The deadline for this task is approaching within the next 24 hours!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Set the background image */
        body {
            background-image: url('paper.jpeg'); /* Ensure this path is correct */
            background-size: cover;  /* Cover the entire page with the image */
            background-position: center; /* Center the image */
            background-attachment: fixed; /* Make the background fixed while scrolling */
            color: white; /* Set text color to white */
            font-family: Arial, sans-serif; /* Font family for readability */
        }

        /* Container style */
        .container {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            border-radius: 8px; /* Rounded corners */
            padding: 30px;
            margin-top: 50px;
            max-width: 600px; /* Limit container width for better appearance */
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            font-size: 2rem;
            color: white; /* Set header text color to white */
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%; /* Full-width button */
        }

        .form-control, .btn {
            border-radius: 6px; /* Rounded form controls and button */
            color: black; /* Text color inside form controls (input fields) */
        }

        .form-select {
            color: black; /* Text color inside the select dropdown */
            background-color: white; /* Set the background color to white */
        }

        /* Optional: Add some padding to the body */
        body {
            padding: 0;
            margin: 0;
        }

        .alert {
            margin-top: 20px;
        }

        .form-label {
            color: white; /* Ensure form label text is white */
        }

    </style>
</head>
<body>

<div class="container">
    <h1>Update Task</h1>
    
    <!-- Display deadline notification if applicable -->
    <?php if (!empty($deadline_notification)): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $deadline_notification; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">

        <!-- Task Status -->
        <div class="mb-3">
            <label for="status" class="form-label">Task Status</label>
            <select name="status" class="form-select" required>
                <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>

        <!-- Task Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Task Type</label>
            <select name="type" class="form-select" required>
                <option value="Work" <?php if ($task['type'] == 'Work') echo 'selected'; ?>>Work</option>
                <option value="Personal" <?php if ($task['type'] == 'Personal') echo 'selected'; ?>>Personal</option>
                <option value="Other" <?php if ($task['type'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>

        <!-- Task Deadline -->
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="datetime-local" class="form-control" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($task['deadline'])); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>
</div>

</body>
</html>
