
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradebook";

// connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];
$userType = $_POST['userType'];

// Determine the table based on user type
if ($userType === 'student') {
    $table = 'students';
} elseif ($userType === 'faculty') {
    $table = 'faculty';
} elseif ($userType === 'admin') {
    $table = 'admins';
} else {
    die("Invalid user type selected");
}

// Query the database
$sql = "SELECT * FROM $table WHERE email='$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    if (password_verify($password, $userData['password'])) {
        // session variables
        $_SESSION['userType'] = $userType;
        $_SESSION['userData'] = $userData;
        $_SESSION['studentId'] = $userData['studentId']; 

        // Redirect to the respective dashboard
        if ($userType === 'student') {
            header("Location: studentdashboard.php");
        } elseif ($userType === 'faculty') {
            header("Location: facultydashboard.php");
        } elseif ($userType === 'admin') {
            header("Location: admindashboard.php");
        }
        exit();
    } else {
        echo "Invalid email or password";
    }
} else {
    echo "Invalid email or password";
}

$conn->close();
?>
