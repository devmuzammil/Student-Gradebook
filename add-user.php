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

// Fetch courses
$sql = "SELECT * FROM courses";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Get form data
    $userType = $_POST['role'];
    
    

// Determine the user type and insert the data into the appropriate table
if ($userType === 'student') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $studentId = $_POST['studentId'];
    $major = $_POST['major'];
    $sql = "INSERT INTO students (fullName, email, password, studentId, major) VALUES ('$fullName', '$email', '$password', '$studentId', '$major')";
} elseif ($userType === 'faculty') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $facultyId = $_POST['facultyId'];
    $department = $_POST['department'];
    $contact = $_POST['Contact_No'];
    $sql = "INSERT INTO faculty (fullName, email, password, facultyId, department, Contact_No) VALUES ('$fullName', '$email', '$password', '$facultyId', '$department', '$contact')";
} elseif ($userType === 'admin') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $adminCode = $_POST['adminCode'];
    $sql = "INSERT INTO admins (fullName, email, password, adminCode) VALUES ('$fullName', '$email', '$password', '$adminCode')";
} else {
    die("Invalid user type selected");
}

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location: admindashboard.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

}

$conn->close();
?>

