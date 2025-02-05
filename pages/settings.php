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
                echo "<p>Email updated successfully!</p>";
            } else {
                echo "<p>Error updating email.</p>";
            }
        } else {
            echo "<p>Email is already in use.</p>";
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
                echo "<p>Username updated successfully!</p>";
            } else {
                echo "<p>Error updating username.</p>";
            }
        } else {
            echo "<p>Username is already taken.</p>";
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
                echo "<p>Password updated successfully!</p>";
            } else {
                echo "<p>Error updating password.</p>";
            }
        } else {
            echo "<p>Current password is incorrect.</p>";
        }
    }

    if (isset($_POST['delete_account'])) {
        // Delete account
        $delete_account = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_account->bind_param("i", $user_id);
        if ($delete_account->execute()) {
            session_destroy();
            echo "<p>Your account has been deleted.</p>";
            header("Location: register.php");
            exit;
        } else {
            echo "<p>Error deleting account.</p>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Settings</h2>

        <!-- Change Email Form -->
        <form method="POST">
            <h4>Change Email</h4>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
            <button type="submit" name="update_email" class="btn btn-primary mt-3">Update Email</button>
        </form>

        <!-- Change Username Form -->
        <form method="POST" class="mt-4">
            <h4>Change Username</h4>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="form-control" required>
            <button type="submit" name="update_username" class="btn btn-primary mt-3">Update Username</button>
        </form>

        <!-- Change Password Form -->
        <form method="POST" class="mt-4">
            <h4>Change Password</h4>
            <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
            <input type="password" name="new_password" class="form-control mt-3" placeholder="New Password" required>
            <button type="submit" name="update_password" class="btn btn-primary mt-3">Update Password</button>
        </form>

        <!-- Delete Account Form -->
        <form method="POST" class="mt-4">
            <h4>Delete Account</h4>
            <button type="submit" name="delete_account" class="btn btn-danger mt-3">Delete My Account</button>
        </form>
    </div>
</body>
</html>
