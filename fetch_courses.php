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

$sql = "SELECT course_name, Grade FROM courses";
$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    // Fetch all courses
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();

echo json_encode($courses);
?>
