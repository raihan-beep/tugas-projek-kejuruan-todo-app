<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['description'])) {
    $description = $_POST['description'];
    $type = $_POST['type'];
    $deadline = $_POST['deadline'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, description, type, deadline, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $description, $type, $deadline]);
}

// Fetch tasks (admin sees all, users see their own)
if ($is_admin) {
    $stmt = $pdo->prepare("SELECT tasks.*, users.username FROM tasks JOIN users ON tasks.user_id = users.id");
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
    $stmt->execute([$user_id]);
}
$stmt->execute();
$tasks = $stmt->fetchAll();

// Fetch users (admin only)
if ($is_admin) {
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE id != ?");
    $stmt->execute([$user_id]);
    $users = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('paper.jpeg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: black;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
        }
        .btn { margin-top: 10px; }
        .header-img {
            width: 100%;
            max-width: 500px;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <img src="todo.png" alt="To-Do List" class="header-img">

    <!-- Task Form -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="description" class="form-label">Task</label>
            <input type="text" class="form-control" name="description" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Task Type</label>
            <select name="type" class="form-select" required>
                <option value="Work">Work</option>
                <option value="Personal">Personal</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="datetime-local" class="form-control" name="deadline" required>
        </div>
        <button type="submit" class="btn btn-success">Add Task</button>
    </form>

    <!-- Task List -->
    <h2 class="mt-4">Tasks</h2>
    <ul class="list-group">
        <?php foreach ($tasks as $task): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($task['description']); ?></strong> 
                <small>(<?php echo htmlspecialchars($task['type']); ?>)</small>
                <br>
                <small>Deadline: <?php echo htmlspecialchars($task['deadline']); ?></small>
                <br>
                <small>Status: <?php echo htmlspecialchars($task['status']); ?></small>

                <?php if ($is_admin): ?>
                    <br><small>User: <?php echo htmlspecialchars($task['username']); ?></small>
                <?php endif; ?>

                <a href="update_task.php?id=<?php echo $task['task_id']; ?>" class="btn btn-warning btn-sm float-end ms-2">Edit</a>
                <a href="delete_task.php?id=<?php echo $task['task_id']; ?>" class="btn btn-danger btn-sm float-end">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Admin: User Management -->
    <?php if ($is_admin): ?>
        <h2 class="mt-5">User Management</h2>
        <ul class="list-group">
            <?php foreach ($users as $user): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                    <a href="reset_password.php?id=<?php echo $user['id']; ?>" class="btn btn-secondary btn-sm float-end ms-2">Reset Password</a>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm float-end">Delete User</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
</div>

</body>
</html>