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
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

<!-- Main content -->
<div class="main-content">
    <section class="container mt-5">
        <h2>Welcome, Admin</h2>
        <p class="lead">Here's an overview of your profile:</p>
        <hr />

        
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Admin Code</th >
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td><?php echo htmlspecialchars($_SESSION['userData']['fullName']); ?></td>
                            <td><?php echo htmlspecialchars($_SESSION['userData']['email']); ?></td>
                            <td><?php echo htmlspecialchars($_SESSION['userData']['adminCode']); ?></td >
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>