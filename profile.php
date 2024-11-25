<?php
session_start();

if (!isset($_SESSION['userType'])) {
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

$facultyId = '';
$studentId = '';
if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'faculty') {
    $facultyId = $_SESSION['userData']['id'];
// Fetch faculty information
    $sql = "SELECT fullName, email, department, Contact_No FROM faculty WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $facultyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
} else if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'student') {
    $studentId = $_SESSION['studentId'];

// Fetch student information
    $sql = "SELECT fullName, email, studentId, Major FROM students WHERE studentId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}


$stmt->close();

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = '';
    $column = '';
    $value = '';
    if (isset($_POST['updateEmail'])) {
        $currentPassword = $_POST['currentPassword'];
        $newEmail = $_POST['newEmail'];
        if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'faculty') {
            $table = 'faculty';
            $column = 'id';
            $value = $facultyId;
        }
        else if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'student') {
            $table = 'student';
            $column = 'studentId';
            $value = $studentId;
        }
        // Verify current password
        $sql = "SELECT password FROM $table WHERE $column = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $value);

        $stmt->execute();
        $stmt->bind_result($storedPassword);
        $stmt->fetch();
        $stmt->close(); // Close the statement before proceeding

        if (password_verify($currentPassword, $storedPassword)) {
            // Update email
            $sql = "UPDATE $table SET email = ? WHERE $column = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newEmail, $value);
            $stmt->execute();
            $stmt->close(); // Close the statement after execution

            echo "Email updated successfully.";
        } else {
            echo "Incorrect current password.";
        }
    } elseif (isset($_POST['updatePassword'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);

        // Verify current password
        $sql = "SELECT password FROM $table WHERE $column = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->bind_result($storedPassword);
        $stmt->fetch();
        $stmt->close(); // Close the statement before proceeding

        if (password_verify($currentPassword, $storedPassword)) {
            // Update password
            $sql = "UPDATE $table SET password = ? WHERE $column = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newPassword, $value);
            $stmt->execute();
            $stmt->close(); // Close the statement after execution

            echo "Password updated successfully.";
        } else {
            echo "Incorrect current password.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Student Gradebook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#"> Dashboard</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <?php if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'faculty') { ?>
                            <a class="nav-link" href="facultydashboard.php">Dashboard</a>
                        <?php } if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'student') { ?>
                            <a class="nav-link" href="studentdashboard.php">Dashboard</a>
                        <?php } ?>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signin.html">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h3>Your Profile</h3>
        <table class="table">
            <thead>
            <?php if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'faculty') { ?>

                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Contact</th>
                </tr>
            <?php } if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'student') { ?>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Major</th>
                </tr>
            <?php } ?>

            </thead>
            <tbody>
                <?php if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'faculty') { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['fullName']); ?></td>
                        <td><?php echo htmlspecialchars($data['email']); ?></td>
                        <td><?php echo htmlspecialchars($data['department']); ?></td>
                        <td><?php echo htmlspecialchars($data['Contact_No']); ?></td>
                    </tr>
                <?php } if (isset($_SESSION['userType']) && $_SESSION['userType'] === 'student') { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['fullName']); ?></td>
                        <td><?php echo htmlspecialchars($data['email']); ?></td>
                        <td><?php echo htmlspecialchars($data['studentId']); ?></td>
                        <td><?php echo htmlspecialchars($data['Major']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Update Email</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newEmail">New Email</label>
                <input type="email" class="form-control" id="newEmail" name="newEmail" required>
            </div>
            <button type="submit" class="btn btn-primary" name="updateEmail">Update Email</button>
        </form>

        <h3>Update Password</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>
            <button type="submit" class="btn btn-primary" name="updatePassword">Update Password</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
