<?php
include 'config.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'staff') {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    
    // Fetch all payments for the student
    $stmt = $pdo->prepare("SELECT SUM(pending_amount) as total_pending FROM payments WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $result = $stmt->fetch();
    $total_pending = $result['total_pending'] ?? 0;
    
    $is_cleared = ($total_pending == 0);
    $reason = $is_cleared ? NULL : "Total pending fees: K" . number_format($total_pending, 2) . " across semesters. (Fee: K2500/course, max 4/semester; Degree: 8 semesters, Diploma: 6 semesters)";
    
    // Update clearance status
    $update_stmt = $pdo->prepare("UPDATE clearance_status SET payment_cleared = ?, payment_reason = ? WHERE student_id = ?");
    $update_stmt->execute([$is_cleared ? 'yes' : 'no', $reason, $student_id]);
    
    $message = $is_cleared ? "Student cleared for payments!" : "Student not cleared: Pending fees detected.";
    echo "<script>alert('$message'); window.location='payment_check.php';</script>";
}

// Fetch all students for dropdown
$students_stmt = $pdo->query("SELECT student_id, name, program FROM students");
$students = $students_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Check</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
	  <link href="custom.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Accountant - Payment Clearance</h2>
        <a href="dashboard_staff.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <a href="logout.php" class="btn btn-danger mb-3 float-end">Logout</a>
        
        <form method="POST">
            <div class="mb-3">
                <label for="student_id" class="form-label">Select Student</label>
                <select name="student_id" id="student_id" class="form-select" required>
                    <option value="">Choose...</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>">
                            <?php echo $student['name'] . ' (' . $student['student_id'] . ' - ' . ucfirst($student['program']) . ')'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Check and Update Clearance</button>
        </form>
        
        <?php if (isset($_POST['student_id'])): ?>
            <?php
            // Display current status
            $check_stmt = $pdo->prepare("SELECT * FROM clearance_status WHERE student_id = ?");
            $check_stmt->execute([$_POST['student_id']]);
            $current_status = $check_stmt->fetch();
            ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Current Payment Status: <span class="badge <?php echo $current_status['payment_cleared'] == 'yes' ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $current_status['payment_cleared'] == 'yes' ? 'Cleared' : 'Pending'; ?>
                    </span></h5>
                    <?php if ($current_status['payment_reason']): ?>
                        <p>Reason: <?php echo $current_status['payment_reason']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>