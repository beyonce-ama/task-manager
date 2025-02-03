<?php
include '../includes/db.php';

$message = '';
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $new_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $query = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $query->bind_param("s", $token);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        $query->bind_result($user_id);
        $query->fetch();

        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $update->bind_param("si", $new_password, $user_id);
        
        if ($update->execute()) {
            $message = "<div class='alert alert-success'>Password reset successful. <a href='login.php'>Login here</a></div>";
        } else {
            $message = "<div class='alert alert-danger'>Error updating password.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Invalid or expired token.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .reset-container h2 {
            color: black;
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 20px;
            padding: 18px;
        }
        .btn-primary {
            color: black;
            background-color: #1cc88a;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 25px;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            color: black;
            background-color: #17a673;
        }
        .toggle-password {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: gray;
        }
        .toggle-password:hover {
            color: black;
        }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Reset Password</h2>
    <p>Enter your new password below.</p>
    
    <?php echo $message; ?>

    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>

    <p class="mt-3"><a href="login.php">Back to Login</a></p>
</div>
    
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        let passwordInput = document.getElementById("password");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        }
    });
</script>

</body>
</html>
