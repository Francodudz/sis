<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "sis_db";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if we have an ID
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];
} else {
    // Redirect to the course list page if no ID is provided
    header("Location: course_list.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the course from the database
    $query = "UPDATE course_list SET delete_flag = 1 WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $courseId);
    
    if ($stmt->execute()) {
        // Redirect to the course_list.php page
        header("Location: course_list.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Course</title>
</head>
<body>
    <h2>Delete Course</h2>
    <p>Are you sure you want to delete this course?</p>
    <form method="POST" action="">
        <input type="submit" value="Yes, delete it">
        <button type="button" onclick="window.location.href='course_list.php'">No, take me back</button>
    </form>
</body>
</html>