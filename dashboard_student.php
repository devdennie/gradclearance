<?php
include 'config.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'student') {
    header("Location: index.php");
    exit();
}
$student_id = $_SESSION['student_id'];  // This is now the 9-digit ID from login

// Fetch student name for display
$stmt_name = $pdo->prepare("SELECT name FROM students WHERE student_id = ?");
$stmt_name->execute([$student_id]);
$student_name = $stmt_name->fetchColumn() ?: 'Unknown';

$stmt = $pdo->prepare("SELECT * FROM clearance_status WHERE student_id = ?");
$stmt->execute([$student_id]);
$status = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link href="custom.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Clearance Status for <?php echo htmlspecialchars($student_name); ?> (ID: <?php echo htmlspecialchars($student_id); ?>)</h2>
        <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
        <div class="card mt-4">
            <div class="card-body">
                <h5>Overall Status: <span class="badge <?php echo $status['overall_cleared'] == 'yes' ? 'bg-success' : 'bg-danger'; ?>">
                    <?php echo $status['overall_cleared'] == 'yes' ? 'Cleared for Graduation' : 'Not Cleared'; ?>
                </span></h5>
                <?php if ($status['overall_cleared'] == 'no'): ?>
                    <h6>Reasons for Pending Clearance:</h6>
                    <ul class="list-group">
                        <?php if ($status['academic_cleared'] == 'no'): ?>
                            <li class="list-group-item list-group-item-warning">Academic: <?php echo $status['academic_reason'] ?: 'Grades not verified - failing courses detected (D, D+, F).'; ?></li>
                        <?php endif; ?>
                        <?php if ($status['payment_cleared'] == 'no'): ?>
                            <li class="list-group-item list-group-item-warning">Payments: <?php echo $status['payment_reason'] ?: 'Pending fees across semesters.'; ?></li>
                        <?php endif; ?>
                        <?php if ($status['library_cleared'] == 'no'): ?>
                            <li class="list-group-item list-group-item-warning">Library: <?php echo $status['library_reason'] ?: 'Overdue books or unpaid fines.'; ?></li>
                        <?php endif; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-success">Congratulations! You are cleared for graduation.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>