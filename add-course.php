<?php
session_start();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'admin') {
    header("Location: signin.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradebook";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = $_POST['courseId'];
    $courseName = $_POST['courseName'];
    $creditHrs = $_POST['creditHrs'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO courses (course_id, course_name, Credit_Hrs) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isi", $courseId, $courseName, $creditHrs);
        if ($stmt->execute()) {
            $message = "Course added successfully!";
        } else {
            $message = "Error adding course: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Failed to prepare the SQL statement.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            padding-top: 50px;
            color: white;
            z-index: 1;
        }
        .sidebar-item {
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 70px;
            /* Adjust padding top to make space for the header */
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 2;
        }
    </style>
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-item">
        <a class="nav-link" href="admindashboard.php">Dashboard</a>
    </div>
    <div class="sidebar-item">
        <a class="nav-link" href="add-user.html">Add User </a>
    </div>
    <div class="sidebar-item">
        <a class="nav-link" href="./users.html">Users</a>
    </div>
    <div class="sidebar-item">
        <a class="nav-link" href="add-course.php">Add Courses</a>
    </div>
    <div class="sidebar-item">
        <a class="nav-link" href="assign-courses.php">Assign Courses</a>
    </div>

</div>
<div class="main-content">
    <div class="container mt-5">
        <h2>Add Course</h2>
        <?php if ($message) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        <form action="add-course.php" method="POST">
            <div class="form-group">
                <label for="courseId">Course ID</label>
                <input type="number" class="form-control" id="courseId" name="courseId" required>
            </div>
            <div class="form-group">
                <label for="courseName">Course Name</label>
                <input type="text" class="form-control" id="courseName" name="courseName" required>
            </div>
            <div class="form-group">
                <label for="creditHrs">Credit Hours</label>
                <input type="number" class="form-control" id="creditHrs" name="creditHrs" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
