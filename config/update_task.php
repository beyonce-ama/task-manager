<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['id'];
    $newDueDate = $_POST['due_date'];

    // Update task due date
    $stmt = $conn->prepare("UPDATE tasks SET due_date = ? WHERE id = ?");
    $stmt->bind_param("si", $newDueDate, $taskId);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error";
    }

    $stmt->close();
    $conn->close();
}
?>
