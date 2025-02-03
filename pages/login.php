<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input']; 
    $password = $_POST['password'];

    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    } else {
        $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    }

    $query->bind_param('s', $input);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; 
            header('Location: dashboard.php'); 
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
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
    <title>Task Manager - Login</title> 
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
        .forgot-pass {
            margin-top: 10px;
            font-size: 14px;
        }
        .forgot-pass a {
            color: #4e73df;
            font-weight: bold;
            text-decoration: none;
        }
        .forgot-pass a:hover {
            text-decoration: underline;
        }
        .icon {
            font-size: 30px;
            color: black;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="title">Welcome Back!</div>
    <div class="tagline">Stay Organized. Stay Productive. 🚀</div>

    <div class="register-container">
        <i class="fas fa-user icon"></i>
        <h2>Log In</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3 d-flex flex-column text-start">
                <label for="input" class="form-label fw-semibold">Username or Email:</label>
                <input type="text" class="form-control" id="input" name="input" placeholder="Enter Username or Email" required>
            </div>
            <div class="mb-2 d-flex flex-column text-start">
                <label for="password" class="form-label fw-semibold">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
            </div>
            <div class="forgot-pass text-end">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-custom mt-3">Log In</button>
        </form>
        <p class="login-link">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

</body>
</html>