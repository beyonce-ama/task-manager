<?php
include '../includes/db.php';

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

        echo "Email verified successfully! You can now <a href='login.php'>login</a>.";
    } else {
        echo "Invalid or expired verification link.";
    }
}
?>
