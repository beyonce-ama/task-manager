<?php
include '../includes/db.php';
include '../config/email.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    
    $query = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $query->store_result();
    
    if ($query->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $update->bind_param("sss", $token, $expiry, $email);
        if ($update->execute()) {
   
            $reset_link = "https://taskmanager.fun/pages/reset_password.php?token=$token";
            $subject = "Password Reset Request - Task Manager";

            $message = "
            <html>
            <head>
                <title>Password Reset Request</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                     h2{
                        color: #4e73df;
                    }
                    .container {
                        max-width: 500px;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                        text-align: center;
                    }
                    .btn {
                            background-color: #4e73df;
                            color: white;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            font-weight: bold;
                        }
                        .btn:hover {
                            background-color: #1cc88a;
                        }
                    .footer {
                        font-size: 12px;
                        color: #666;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Password Reset Request</h2>
                    <p>You recently requested to reset your password for your Task Manager account. Click the button below to reset it.</p>
                    <a href='$reset_link' class='btn'>Reset Password</a>
                    <p>If you did not request this, please ignore this email. This link will expire in 1 hour.</p>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " Task Manager. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@taskmanager.fun" . "\r\n";
            
            sendEmail($email, $subject, $message, $headers);            
            
            $message = "<div class='alert alert-success'>Check your email for the reset link.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error generating reset link.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Email not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        .reset-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .reset-container h2 {
            color:rgb(23, 24, 26);
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-primary {
            color: black;
            background-color: #1cc88a;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-primary:hover {
            color: black;
            background-color: #17a673;
        }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Forgot Password</h2>
    <p>Enter your email address below and we'll send you a password reset link.</p>
    
    <?php echo $message; ?>

    <form method="POST">
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>

    <p class="mt-3"><a href="login.php">Back to Login</a></p>
</div>

</body>
</html>
