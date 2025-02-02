<?php
include $_SERVER['DOCUMENT_ROOT'] . '/config/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/config/email.php';

$date_today = date('Y-m-d');

$query = $conn->prepare("SELECT users.email, tasks.task_name 
                         FROM tasks 
                         INNER JOIN users ON tasks.user_id = users.id 
                         WHERE tasks.due_date = ?");
$query->bind_param('s', $date_today);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $task_name = $row['task_name'];

    $subject = "Reminder: Task Due Today!";
    $message = "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                <h1>Task Due Today</h1>
            </div>
            <div class='email-body'>
                <p>Hi $username,</p>
                <p>This is a friendly reminder that your task <b>$title</b> is due today!</p>
                <p>We encourage you to complete it on time to stay on track with your goals.</p>
                <p style='text-align: center;'>
                    <a href='#' class='btn'>Go to Task</a> <!-- Link to the task or task manager -->
                </p>
                <p>If you need any assistance or have questions, feel free to reach out to us.</p>
            </div>
        </div>
    </body>
    </html>";

    sendEmail($email, $subject, $message);
}

echo "Reminders sent!";
?>
