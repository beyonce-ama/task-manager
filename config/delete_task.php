<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

if (!isset($_GET['id'])) {
    header('Location: ../pages/dashboard.php');
    exit;
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$query->bind_param('ii', $task_id, $user_id);

if ($query->execute()) {
    $_SESSION['message'] = "Task deleted successfully!";
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?task_deleted=true');
    exit;
} else {
    $error = "Failed to delete task!";
}
?>