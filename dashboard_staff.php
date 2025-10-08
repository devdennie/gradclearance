<?php
include 'config.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'staff') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link href="custom.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Staff Dashboard - <?php echo $_SESSION['username']; ?></h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="academic_check.php" class="btn btn-primary w-100">Academic Secretary Check</a>
            </div>
            <div class="col-md-4">
                <a href="payment_check.php" class="btn btn-success w-100">Accountant Check</a>
            </div>
            <div class="col-md-4">
                <a href="library_check.php" class="btn btn-info w-100">Librarian Check</a>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>