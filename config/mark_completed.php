<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    $updateQuery = $conn->prepare("UPDATE tasks SET status = 'Completed' WHERE id = ? AND user_id = ?");
    if (!$updateQuery) {
        die('Error in query preparation: ' . $conn->error);
    }
    $updateQuery->bind_param('ii', $task_id, $_SESSION['user_id']);
    $updateQuery->execute();
    
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?task_updated=true');
   
}
?>
