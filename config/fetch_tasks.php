<?php
include '../includes/db.php';
include '../config/auth.php';
checkSession();

$user_id = $_SESSION['user_id'];
$tasks = [];

$sql = "SELECT id, title, due_date, priority FROM tasks WHERE user_id = ? AND status != 'Completed'"; // Exclude completed tasks
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    $color = '#28a745'; 
    if ($row['priority'] == 'High') {
        $color = '#dc3545'; 
    } elseif ($row['priority'] == 'Medium') {
        $color = '#ffc107'; 
    }

    $tasks[] = [
        'id'    => $row['id'],
        'title' => $row['title'],
        'start' => $row['due_date'], 
        'color' => $color, 
        'priority' => $row['priority']
    ];
}

header('Content-Type: application/json');
echo json_encode($tasks);
?>