<?php
include 'config.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'staff') {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    
    // Fetch all uncleared books for the student and calculate overdue/fines dynamically
    $stmt = $pdo->prepare("
        SELECT *, 
               CASE 
                   WHEN return_date IS NULL THEN DATEDIFF(CURDATE(), due_date)
                   ELSE DATEDIFF(return_date, due_date)
               END AS overdue_days,
               CASE 
                   WHEN (CASE WHEN return_date IS NULL THEN DATEDIFF(CURDATE(), due_date) ELSE DATEDIFF(return_date, due_date) END) > 7 
                   THEN GREATEST(0, (CASE WHEN return_date IS NULL THEN DATEDIFF(CURDATE(), due_date) ELSE DATEDIFF(return_date, due_date) END) - 7) * 10 
                   ELSE 0 
               END AS fine
        FROM books 
        WHERE student_id = ? AND cleared = 'no'
    ");
    $stmt->execute([$student_id]);
    $overdue_books = $stmt->fetchAll();
    
    $is_cleared = empty($overdue_books);
    $reason = '';
    $total_fine = 0;
    
    if (!$is_cleared) {
        foreach ($overdue_books as $book) {
            $fine = $book['fine'];
            $total_fine += $fine;
            $status = $book['return_date'] ? 'Returned late' : 'Still borrowed';
            $overdue = max(0, $book['overdue_days']);
            $reason .= "{$book['book_name']}: {$status} (Overdue: {$overdue} days, Fine: K" . number_format($fine, 2) . "). ";
        }
        $reason .= "Total fine: K" . number_format($total_fine, 2) . ". Grace period: 7 days; K10/day overdue. Return/pay to clear.";
    }
    
    // Update clearance status
    $update_stmt = $pdo->prepare("UPDATE clearance_status SET library_cleared = ?, library_reason = ? WHERE student_id = ?");
    $update_stmt->execute([$is_cleared ? 'yes' : 'no', $reason ?: NULL, $student_id]);
    
    $message = $is_cleared ? "Student cleared for library!" : "Student not cleared: Overdue books/fines detected.";
    echo "<script>alert('$message'); window.location='library_check.php';</script>";
}

// Fetch all students for dropdown (now shows 9-digit IDs)
$students_stmt = $pdo->query("SELECT student_id, name, program FROM students ORDER BY student_id");
$students = $students_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Check</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link href="custom.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Librarian - Library Clearance</h2>
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
            // Display current status after check
            $check_stmt = $pdo->prepare("SELECT * FROM clearance_status WHERE student_id = ?");
            $check_stmt->execute([$_POST['student_id']]);
            $current_status = $check_stmt->fetch();
            ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Current Library Status: <span class="badge <?php echo $current_status['library_cleared'] == 'yes' ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $current_status['library_cleared'] == 'yes' ? 'Cleared' : 'Pending'; ?>
                    </span></h5>
                    <?php if ($current_status['library_reason']): ?>
                        <p>Reason: <?php echo htmlspecialchars($current_status['library_reason']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>