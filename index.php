<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Graduation Clearance System</title>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="custom.css" rel="stylesheet">  <!-- Must be here, after Bootstrap -->
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Student Graduation Clearance</h2>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Login</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="#" class="btn btn-primary w-100 mb-3" onclick="showLogin('staff')">Staff Login</a>
                                <form id="staffForm" style="display:none;" method="POST" action="login_staff.php">
                                    <input type="text" name="username" class="form-control mb-2" placeholder="Username (e.g., admin)" required>
                                    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                                    <button type="submit" class="btn btn-primary w-100">Login as Staff</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn btn-success w-100 mb-3" onclick="showLogin('student')">Student Login</a>
                                <form id="studentForm" style="display:none;" method="POST" action="login_student.php">
                                    <input type="text" name="username" class="form-control mb-2" placeholder="Student ID (e.g., 202300001)" required>
                                    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                                    <button type="submit" class="btn btn-success w-100">Login as Student</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        function showLogin(type) {
            // Hide both forms first
            document.getElementById('staffForm').style.display = 'none';
            document.getElementById('studentForm').style.display = 'none';
            // Show the selected one
            document.getElementById(type + 'Form').style.display = 'block';
        }
    </script>
</body>
</html>