<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT id, username, email, password FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id); 
$user_query->execute();
$user_query->store_result();

if ($user_query->num_rows == 1) {
    $user_query->bind_result($id, $username, $email, $password); 
    $user_query->fetch();
} else {
    echo "User not found!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_email'])) {
        // Change email
        $new_email = $_POST['email'];
        $check_email_query = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email_query->bind_param("s", $new_email);
        $check_email_query->execute();
        $check_email_query->store_result();

        if ($check_email_query->num_rows == 0) {
            $update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_email->bind_param("si", $new_email, $user_id);
            if ($update_email->execute()) {
                $_SESSION['message'] = "Email updated successfully!";
            } else {
                $_SESSION['message'] = "Error updating email.";
            }
        } else {
            $_SESSION['message'] = "Email is already in use.";
        }
    }

    if (isset($_POST['update_username'])) {
        // Change username
        $new_username = $_POST['username'];
        $check_username_query = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_username_query->bind_param("s", $new_username);
        $check_username_query->execute();
        $check_username_query->store_result();

        if ($check_username_query->num_rows == 0) {
            $update_username = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $update_username->bind_param("si", $new_username, $user_id);
            if ($update_username->execute()) {
                $_SESSION['message'] = "Username updated successfully!";
            } else {
                $_SESSION['message'] = "Error updating username.";
            }
        } else {
            $_SESSION['message'] = "Username is already taken.";
        }
    }

    if (isset($_POST['update_password'])) {
        // Change password
        $current_password = $_POST['current_password'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        if (password_verify($current_password, $password)) {
            $update_password = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_password->bind_param("si", $new_password, $user_id);
            if ($update_password->execute()) {
                $_SESSION['message'] = "Password updated successfully!";
            } else {
                $_SESSION['message'] = "Error updating password.";
            }
        } else {
            $_SESSION['message'] = "Current password is incorrect.";
        }
    }

    if (isset($_POST['delete_account'])) {
        // Delete account
        $delete_account = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_account->bind_param("i", $user_id);
        if ($delete_account->execute()) {
            session_destroy();
            $_SESSION['message'] = "Your account has been deleted.";
            header("Location: register.php");
            exit;
        } else {
            $_SESSION['message'] = "Error deleting account.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link href="../assets/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
        }

        .form-section {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-section h4 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-section .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        .form-section .btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        .notification-item {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #notification {
            margin-top: 20px;
            position: relative;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            max-width: 600px;
        }

        .container-fluid {
            padding: 0 20px;
        }

        @media (max-width: 767px) {
            .form-section {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
    <a class="navbar-brand" href="#"  style="color: white; font-size: 28px; font-weight: bold; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);" >Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="calendar.php">Calendar</a></li>
                <li class="nav-item"><a class="nav-link" href="tasks_by_priority.php">Tasks by Priority</a></li>
                <li class="nav-item"><a class="nav-link" href="all_tasks.php">In Progress Tasks</a></li>
                <li class="nav-item"><a class="nav-link" href="completed_tasks.php">Completed Tasks</a></li>
                <li class="nav-item"><a class="nav-link active" href="settings.php">Settings</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="return confirmLogout();">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div id="notification" class="show"></div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="form-section">
                <h2>User Settings</h2>

                <!-- Change Email Form -->
                <form method="POST">
                    <h4>Change Email</h4>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
                    <button type="submit" name="update_email" class="btn btn-primary mt-3">Update Email</button>
                </form>
            </div>

            <div class="form-section">
                <!-- Change Username Form -->
                <form method="POST">
                    <h4>Change Username</h4>
                    <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="form-control" required>
                    <button type="submit" name="update_username" class="btn btn-primary mt-3">Update Username</button>
                </form>
            </div>

            <div class="form-section">
                <!-- Change Password Form -->
                <form method="POST">
                    <h4>Change Password</h4>
                    <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
                    <input type="password" name="new_password" class="form-control mt-3" placeholder="New Password" required>
                    <button type="submit" name="update_password" class="btn btn-primary mt-3">Update Password</button>
                </form>
            </div>

            <div class="form-section">
            <form method="POST" id="delete-account-form">
                <h4>Delete Account</h4>
                <button type="button" class="btn btn-danger mt-3" onclick="confirmDelete()">Delete My Account</button>
            </form>
        </div>
        </div>
    </div>
</div>



<script>
    function confirmDelete() {
        var confirmation = confirm("Are you sure you want to delete your account? This action cannot be undone.");
        if (confirmation) {
            document.getElementById('delete-account-form').submit(); // Submit the form if confirmed
        }
    }
</script>

<script>
    window.onload = function() {
        <?php if (isset($_SESSION['message'])): ?>
            var notification = document.getElementById('notification');
            var message = "<?php echo $_SESSION['message']; ?>";

            var messageDiv = document.createElement('div');
            messageDiv.innerText = message;
            messageDiv.classList.add('notification-item');
            notification.appendChild(messageDiv);

            setTimeout(function() {
                notification.removeChild(messageDiv);
            }, 5000);

            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    };
</script>

</body>
</html>
