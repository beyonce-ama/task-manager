<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status']; 

    $update = $conn->prepare("UPDATE tasks SET title = ?, description = ?, priority = ?, due_date = ?, status = ? WHERE id = ?");
    $update->bind_param('sssssi', $title, $description, $priority, $due_date, $status, $task_id);

    if ($update->execute()) {
        $_SESSION['message'] = "Task updated successfully!";
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?task_updated=true');
        exit;
    } else {
        $error = "Failed to update task!";
    }
}
?>
