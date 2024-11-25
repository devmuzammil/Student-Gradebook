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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userType = $_POST['userType'];
    $userId = $_POST['userId'];
    $courseId = $_POST['courseId'];

    if ($userType === 'student') {
        $stmt = $conn->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("si", $userId, $courseId);
            if ($stmt->execute()) {
                $message = "Course assigned successfully!";
            } else {
                $message = "Error assigning course: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid user type selected.";
        }
    } elseif ($userType === 'faculty') {
        $stmt = $conn->prepare("INSERT INTO faculty_courses (faculty_id, course_id) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ii", $userId, $courseId);
            if ($stmt->execute()) {
                $message = "Course assigned successfully!";
            } else {
                $message = "Error assigning course: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid user type selected.";
        }
    }


}

// Fetch courses
$sql = "SELECT course_Id, course_name FROM courses";
$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Fetch students
$sql = "SELECT studentId, fullName FROM students";
$result = $conn->query($sql);

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch faculty
$sql = "SELECT id, fullName FROM faculty";
$result = $conn->query($sql);

$faculty = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faculty[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Courses</title>
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
        <h2>Assign Courses</h2>
        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        <form action="assign-courses.php" method="POST">
            <div class="form-group">
                <label for="userType">User Type</label>
                <select class="form-control" id="userType" name="userType" required onchange="updateUserDropdown()">
                    <option value="">Select User Type</option>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                </select>
            </div>
            <div class="form-group">
                <label for="userId">User ID</label>
                <select class="form-control" id="userId" name="userId" required>
                    <option value="">Select User ID</option>
                </select>
            </div>
            <div class="form-group">
                <label for="courseId">Course</label>
                <select class="form-control" id="courseId" name="courseId" required>
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $course) { ?>
                        <option value="<?php echo $course['course_Id']; ?>"><?php echo $course['course_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Course</button>
        </form>
    </div>
</div>

    <script>
        const students = <?php echo json_encode($students); ?>;
        const faculty = <?php echo json_encode($faculty); ?>;

        function updateUserDropdown() {
            const userType = document.getElementById('userType').value;
            const userIdDropdown = document.getElementById('userId');
            userIdDropdown.innerHTML = '<option value="">Select User ID</option>';

            if (userType === 'student') {
                students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.studentId;
                    option.textContent = `${student.studentId} - ${student.fullName}`;
                    userIdDropdown.appendChild(option);
                });
            } else if (userType === 'faculty') {
                faculty.forEach(facultyMember => {
                    const option = document.createElement('option');
                    option.value = facultyMember.id;
                    option.textContent = `${facultyMember.id} - ${facultyMember.fullName}`;
                    userIdDropdown.appendChild(option);
                });
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
