<?php
include '../includes/db.php';
include '../config/email.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email']; 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $verification_code = md5(rand());

    $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkQuery->bind_param('s', $username);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo "Username already exists!";
    } else {
        $query = $conn->prepare("INSERT INTO users (username, email, password, verification_code, is_verified) VALUES (?, ?, ?, ?, 0)");
        $query->bind_param('ssss', $username, $email, $password, $verification_code);

        if ($query->execute()) {
            $verification_link = "https://taskmanager.fun/pages/verify.php?code=$verification_code";
                $subject = "Activate Your Task Manager Account";

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
                            <h1>Welcome to Task Manager!</h1>
                        </div>
                        <div class='email-body'>
                            <p>Hi $username,</p>
                            <p>Thank you for registering with Task Manager! We are excited to have you onboard and help you stay organized and productive.</p>
                            <p>To complete your registration and activate your account, please click the button below to verify your email address:</p>
                            <p style='text-align: center;'>
                                <a href='$verification_link' class='btn'>Verify Email</a>
                            </p>
                            <p>If you did not sign up for Task Manager, please ignore this email.</p>
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
                
            if (sendEmail($email, $subject, $message)) {
                echo '<div class="mt-4 alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Verification email sent! Please check your inbox.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } else {
                echo '<div class="mt-4 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Failed to send verification email.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        } else {
            echo "Registration failed!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Task Manager - Register</title>
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
        .title {
            color: white;
            font-size: 36px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.3);
        }
        .tagline {
            color: white;
            font-size: 16px;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        .register-container {
            background-color: #f4f4f9;
            padding: 30px;
            width: 450px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .register-container h2 {
            color: black;
            margin-bottom: 20px;
        }
        .form-control {
            background-color: rgb(203, 203, 206);
            border: none;
            border-radius: 5px;
            color: black;
            padding: 15px;
            margin-top: 10px;
        }
        .form-control::placeholder {
            color: black;
        }
        .btn-custom {
            background-color: #1cc88a;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: #17a673;
        }
        .login-link {
            color: black;
            margin-top: 15px;
        }
        .login-link a {
            color: #ffeb3b;
            font-weight: bold;
        }
        .icon {
            font-size: 30px;
            color: black;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="title">Task Manager</div>
    <div class="tagline">Stay Organized. Stay Productive. 🚀</div>

    <div class="register-container">
        <i class="fas fa-user-plus icon"></i>
        <h2>Create an Account</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
        <div class="mb-3 d-flex flex-column text-start">
            <label for="username"  class="form-label fw-semibold">Username:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
        </div>
        <div class="mb-3 d-flex flex-column text-start">
            <label for="email"  class="form-label fw-semibold">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
        </div>
        <div class="mb-4 d-flex flex-column text-start">
            <label for="password" class="form-label fw-semibold">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
        </div>
            <button type="submit" class="btn btn-custom">Sign Up</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
