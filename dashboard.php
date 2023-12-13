<?php
// Assuming you have a session started after successful login
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Assuming you have a database connection
// Replace the placeholder values with your actual database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "sis_db";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total count from a table
function getTotalCount($conn, $table) {
    $query = "SELECT COUNT(*) AS total FROM $table";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}

// Get total counts
$totalDepartments = getTotalCount($conn, 'department_list');
$totalCourses = getTotalCount($conn, 'course_list');
$totalStudents = getTotalCount($conn, 'student_list');
$totalAcademics = getTotalCount($conn, 'academic_history');

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

<div id="navbar">
    <ul>
        <li><a href="add_student.php">New Student</a></li>
        <li><a href="student_list.php">Student List</a></li>
        <li><a href="department_list.php">Department List</a></li>
        <li><a href="course_list.php">Course List</a></li>
        <li><a href="user_list.php">User List</a></li>
        <li style="float: right;"><a href="logout.php">Logout</a></li>
    </ul>
</div>

    <div id="dashboard">
        <h2>Welcome to Student Information System - Admin Panel</h2>

        <p>Total Departments: <?php echo $totalDepartments; ?></p>
        <p>Total Courses: <?php echo $totalCourses; ?></p>
        <p>Total Students: <?php echo $totalStudents; ?></p>
        <p>Total Student Academics: <?php echo $totalAcademics; ?></p>
    </div>

</body>


<style>
    
    #dashboard h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
}

#dashboard p {
    border: 1px solid #333;
    padding: 10px;
    margin: 10px 0;
    font-size: 18px;
    text-align: center;
}
body {
    font-family: Arial, sans-serif;
}

#navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    padding: 10px 0;
}

#navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

#navbar li {
    float: left;
}

#navbar li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

#navbar li a:hover {
    background-color: #111;
}

#dashboard {
    margin-top: 100px; /* Adjust this value according to the height of your navbar */
}
</style>
</html>


