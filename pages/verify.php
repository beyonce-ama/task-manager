<?php
include '../includes/db.php';

$message = '';  

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    $query = $conn->prepare("SELECT id FROM users WHERE verification_code = ? AND is_verified = 0");
    $query->bind_param('s', $verification_code);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        $updateQuery = $conn->prepare("UPDATE users SET is_verified = 1 WHERE verification_code = ?");
        $updateQuery->bind_param('s', $verification_code);
        $updateQuery->execute();

        $message = "Email verified successfully! You can now <a href='login.php'>login</a>.";
    } else {
        $message = "Invalid or expired verification link.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: Arial, sans-serif;
        }
        .verification-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .verification-container h2 {
            color: #4e73df;
        }
        .verification-container p {
            color: #555;
        }
        .verification-container a {
            text-decoration: none;
            font-weight: bold;
            color: #4e73df;
        }
        .verification-container a:hover {
            color: #1cc88a;
        }
    </style>
</head>
<body>

<div class="verification-container">
    <h2>Email Verification</h2>
    <p>
        <?php echo $message; ?>
    </p>
</div>

</body>
</html>
