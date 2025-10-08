<?php
include 'config.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'staff') {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    
    // Fetch all courses for the student
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $courses = $stmt->fetchAll();
    
    $passing_grades = ['A+', 'A', 'B+', 'B', 'C+', 'C'];
    $is_cleared = true;
    $reason = '';
    
    foreach ($courses as $course) {
        if (!in_array($course['grade'], $passing_grades)) {
            $is_cleared = false;
            $reason .= "Failing grade '{$course['grade']}' in {$course['course_name']} (Semester {$course['semester']}). Must repeat. ";
        }
    }
    
    // Check if all semesters are covered (simplified: assume if any failing, block)
    if (empty($courses)) {
        $is_cleared = false;
        $reason = 'No course records found.';
    }
    
    // Update clearance status
    $update_stmt = $pdo->prepare("UPDATE clearance_status SET academic_cleared = ?, academic_reason = ? WHERE student_id = ?");
    $update_stmt->execute([$is_cleared ? 'yes' : 'no', $reason ?: NULL, $student_id]);
    
    $message = $is_cleared ? "Student cleared academically!" : "Student not cleared: $reason";
    echo "<script>alert('$message'); window.location='academic_check.php';</script>";
}

// Fetch all students for dropdown
$students_stmt = $pdo->query("SELECT student_id, name FROM students");
$students = $students_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="custom.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Academic Check</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Academic Secretary - Grade Clearance</h2>
        <a href="dashboard_staff.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <a href="logout.php" class="btn btn-danger mb-3 float-end">Logout</a>
        
        <form method="POST">
            <div class="mb-3">
                <label for="student_id" class="form-label">Select Student</label>
                <select name="student_id" id="student_id" class="form-select" required>
                    <option value="">Choose...</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>"><?php echo $student['name'] . ' (' . $student['student_id'] . ')'; ?></option>
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
                    <h5>Current Academic Status: <span class="badge <?php echo $current_status['academic_cleared'] == 'yes' ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $current_status['academic_cleared'] == 'yes' ? 'Cleared' : 'Pending'; ?>
                    </span></h5>
                    <?php if ($current_status['academic_reason']): ?>
                        <p>Reason: <?php echo $current_status['academic_reason']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>