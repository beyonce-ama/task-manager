<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    
    $status = "In Progress"; 
    $query = $conn->prepare("INSERT INTO tasks (user_id, title, description, priority, due_date, status) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param('isssss', $user_id, $title, $description, $priority, $due_date, $status);

    if ($query->execute()) {

        $_SESSION['message'] = "New task added successfully!";
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?task_added=true');
        exit;
    } else {
        $error = "Failed to add task!";
    }
}
?>