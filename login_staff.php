<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND user_type = 'staff' AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = 'staff';
        $_SESSION['username'] = $username;
        header("Location: dashboard_staff.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials'); window.location='index.php';</script>";
    }
}
?>