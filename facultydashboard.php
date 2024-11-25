<?php
session_start();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'faculty') {
    header("Location: signin.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradebook";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$facultyId = $_SESSION['userData']['id'];

// Fetch courses
$sql = "SELECT c.course_name, c.course_id FROM courses c
        INNER JOIN faculty_courses fc ON c.course_id = fc.course_id
        WHERE fc.faculty_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $facultyId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$stmt->close();

$grades = [];
$course_id = '';

if (isset($_GET['course_id'])) {
    $course_id=$_GET['course_id'];
}

// Fetch grades
$sql = "SELECT fg.faculty_grade_id, fg.student_id, fg.course_id, fg.grade, c.course_name, s.fullName FROM faculty_grades fg
        INNER JOIN courses c ON c.course_id = fg.course_id
        INNER JOIN students s ON s.id = fg.student_id
        WHERE fg.faculty_id = ?";
if (!empty($course_id)) {
    $sql .= " AND fg.course_id = ?";
}

    $stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($course_id)) {
    $stmt->bind_param("ss", $facultyId, $course_id);
} else {
    $stmt->bind_param("s", $facultyId);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }
}
$stmt->close();

$updateMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['studentId'];
    $course_id = $_POST['courseId'];
    $grade = $_POST['grade'];
    $facultyGradeId = $_POST['facultyGradeId'];
    // Prepare the SQL statement
    $sql = "UPDATE faculty_grades SET grade = ? WHERE student_id = ? AND course_id = ? AND faculty_grade_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ssss", $grade, $student_id, $course_id, $facultyGradeId);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['updateMsg'] = "Grade updated successfully.";
    } else {
        $_SESSION['updateMsg'] = "Error updating grade: " . $stmt->error;
    }
    header("Location: facultydashboard.php?course_id=" . $course_id);
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       
        /* Custom styles for the sidebar */
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
        <a class="navbar-brand" href="#">Faculty Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signin.html">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-item">
            <a class="nav-link" href="facultydashboard.php">Dashboard</a>
    </div>
    <div class="sidebar-item">
        <a class="nav-link" href="#" id="view-courses">Courses</a>
    </div>
    <div class="sidebar-item">
        <a class="nav-link" href="#" id="view-grades">Grades</a>
    </div>
</div>

<!-- Main content -->
<div class="main-content">
    <section class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['userData']['fullName']); ?></h2>
                <p class="lead">Here's an overview of your profile:</p>
            </div>
        </div>
            <hr />
        
            <div class="row">
            <div class="col-12">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Contact No.</th>
                        <th>Faculty ID</th >
                    </tr>
                    </thead>
                    <tbody>
                    <tr><td><?php echo htmlspecialchars($_SESSION['userData']['email']); ?></td>
                    <td><?php echo htmlspecialchars($_SESSION['userData']['Contact_No']); ?></td>
                    <td><?php echo htmlspecialchars($_SESSION['userData']['facultyId']); ?></td >
                    </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <!-- Courses Section -->
        <div id="courses-section" style="display:none;">
            <h3>Your Courses</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course ID</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($course['course_id']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Grades Section -->
        <div id="grades-section" style="display:none;">
            <h3>Grades</h3>
            <div class="form-group">
                <label for="userType">Select Course</label>
                <select class="form-control" id="courseId" name="courseId" required>
                    <option value="">Select</option>
                    <?php foreach ($courses as $course): ?>
                        <option <?php echo $course_id == $course['course_id'] ? 'selected' : '' ?> value="<?php echo htmlspecialchars($course['course_id']); ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select a course.</div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($grade['fullName']); ?></td>
                        <td>
                            <form action="" method="post">
                                <div class="d-flex">
                                    <div class="form-group mr-3">
                                        <input type="text" class="form-control" id="grade" name="grade" placeholder="Enter Grade" value="<?php echo isset($_POST,$_POST['studentId'], $_POST['courseId'], $_POST['grade']) && $_POST['studentId']==$grade['student_id']&& $_POST['courseId']==$grade['course_id'] ? $_POST['grade'] : htmlspecialchars($grade['grade']); ?>">
                                        <input type="hidden" id="studentId" name="studentId" value="<?php echo htmlspecialchars($grade['student_id']); ?>">
                                        <input type="hidden" id="courseId" name="courseId" value="<?php echo htmlspecialchars($grade['course_id']); ?>">
                                        <input type="hidden" id="facultyGradeId" name="facultyGradeId" value="<?php echo htmlspecialchars($grade['faculty_grade_id']); ?>">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block" >Submit</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Student Section -->
        <div id="student-section" style="display:none;">
            <h3>Your Students</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>GPA</th>
                    <th>CGPA</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>

    </section>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#view-courses').click(function() {
            $('#courses-section').show();
            $('#grades-section').hide();
            $('#student-section').hide();
        });
        $('#view-grades').click(function() {
            $('#grades-section').show();
            $('#courses-section').hide();
            $('#student-section').hide();
        });
        const urlParams = new URLSearchParams(window.location.search);
        const course_id = urlParams.get('course_id');
        if (course_id) {
            $('#grades-section').show();
            $('#courses-section').hide();
            $('#student-section').hide();
        }
        $("#courseId").change(function () {
            var course = $('#courseId').val();
            window.location.href=( 'facultydashboard.php?course_id=' + course);
        });
    });

</script>
</body>
</html>
