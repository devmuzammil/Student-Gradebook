
<?php
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

// Get form data
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$userType = $_POST['userType'];

// Determine the user type and insert the data into the appropriate table
if ($userType === 'student') {
    $studentId = $_POST['studentId'];
    $major = $_POST['major'];
    $sql = "INSERT INTO students (fullName, email, password, studentId, major) VALUES ('$fullName', '$email', '$password', '$studentId', '$major')";
} elseif ($userType === 'faculty') {
    $facultyId = $_POST['facultyId'];
    $department = $_POST['department'];
    $contact = $_POST['Contact_No'];
    $sql = "INSERT INTO faculty (fullName, email, password, facultyId, department, Contact_No) VALUES ('$fullName', '$email', '$password', '$facultyId', '$department', '$contact')";
} elseif ($userType === 'admin') {
    $adminCode = $_POST['adminCode'];
    $sql = "INSERT INTO admins (fullName, email, password, adminCode) VALUES ('$fullName', '$email', '$password', '$adminCode')";
} else {
    die("Invalid user type selected");
}

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location: signin.html");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>