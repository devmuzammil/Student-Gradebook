<?php
session_start();

if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== 'student') {
    header("Location: signin.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradebook";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentId = $_SESSION['studentId'];

// Fetch courses
$sql = "SELECT c.course_name, c.course_id FROM courses c
        INNER JOIN student_courses sc ON c.course_id = sc.course_id
        WHERE sc.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$stmt->close();

// Fetch grades
$sql = "SELECT c.course_name, c.Credit_Hrs, fg.grade FROM faculty_grades fg
        INNER JOIN courses c ON fg.course_id = c.course_id
        INNER JOIN students s ON fg.student_id = s.id
        WHERE s.studentId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentId);

$stmt->execute();
$result = $stmt->get_result();

$grades = [];
$grade_points = [
    'A' => 4.00, 'A-' => 3.67, 'B+' => 3.33, 'B' => 3.00,
    'B-' => 2.67, 'C+' => 2.33, 'C' => 2.00, 'C-' => 1.67,
    'D' => 1.00, 'F' => 0.00
];
$total_credit_hours = 0;
$total_grade_points = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
        $credit_hours = $row['Credit_Hrs'];
        $grade = $row['grade'];
        if(!empty($grade)) {
            $total_credit_hours += $credit_hours;
            $total_grade_points += $grade_points[$grade] * $credit_hours;
        }
    }
}

$gpa = $total_credit_hours ? round($total_grade_points / $total_credit_hours, 2) : 0.00;
$cgpa = $gpa; // CGPA SAME AS GPA FOR NOW

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Student Gradebook</title>
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
            <a class="navbar-brand" href="#">Student Dashboard</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="sidebar">
        <div class="sidebar-item">
            <a class="nav-link" >Dashboard</a>
        </div>
        <div class="sidebar-item">
            <a class="nav-link"  id="view-courses">Courses</a>
        </div>
        <div class="sidebar-item">
            <a class="nav-link"  id="view-grades">Grades</a>
        </div>
        <div class="sidebar-item">
            <a class="nav-link"  id="view-gpa">GPA/CGPA</a>
        </div>
        
    </div>

    <div class="main-content">
        <section class="container mt-5">
            <div class="row">
                <div class="col-md-8">
                    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['userData']['fullName']); ?></h2>
                    <p class="lead">Here's an overview of your academic progress:</p>
                </div>
                
            </div>
            <hr>

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
                <h3>Your Grades</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- GPA/CGPA Section -->
            <div id="gpa-section" style="display:none;">
                <h3>Your GPA/CGPA</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>GPA</th>
                            <th>CGPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($gpa); ?></td>
                            <td><?php echo htmlspecialchars($cgpa); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#view-courses').click(function() {
                $('#courses-section').show();
                $('#grades-section').hide();
                $('#gpa-section').hide();
            });
            $('#view-grades').click(function() {
                $('#grades-section').show();
                $('#courses-section').hide();
                $('#gpa-section').hide();
            });
            $('#view-gpa').click(function() {
                $('#gpa-section').show();
                $('#grades-section').hide();
                $('#courses-section').hide();
            });
        });
    </script>
</body>
</html>
