<?php
include '../includes/db.php';
include '../config/email.php';

$date_today = date('Y-m-d');

$query = $conn->prepare("SELECT users.email, users.username, tasks.title 
                         FROM tasks 
                         INNER JOIN users ON tasks.user_id = users.id 
                         WHERE tasks.due_date = ?");
$query->bind_param('s', $date_today);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $title = $row['title'];
    $username = $row['username']; 

    $subject = "Reminder: Task Due Today!";

    $message = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .email-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .email-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .email-header h1 {
                color: #4e73df;
            }
            .email-body {
                font-size: 16px;
                line-height: 1.5;
                color: #555;
                margin-bottom: 20px;
            }
            .email-footer {
                font-size: 14px;
                color: #888;
                text-align: center;
                margin-top: 20px;
            }
            .btn {
                background-color: #4e73df;
                color: #fff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .btn:hover {
                background-color: #1cc88a;
            }
        </style>
    </head>
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
            <div class='email-footer'>
                <p>Best regards,</p>
                <p>The Task Manager Team</p>
                <p><a href='https://taskmanager.fun' style='color: #4e73df;'>Visit Task Manager</a></p>
            </div>
        </div>
    </body>
    </html>
    ";

    sendEmail($email, $subject, $message);
}

echo "Reminders sent!";
?>
