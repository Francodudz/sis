<?php
// Assuming you have a session started after successful login
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Assuming you have a database connection
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
    $departmentId = $_GET['id'];
} else {
    // Redirect to the department list page if no ID is provided
    header("Location: department_list.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the department from the database
    $query = "DELETE FROM department_list WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $departmentId);
    
    if ($stmt->execute()) {
        // Redirect to the department_list.php page
        header("Location: department_list.php");
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
    <title>Delete Department</title>
</head>
<body>
    <h2>Delete Department</h2>
    <p>Are you sure you want to delete this department?</p>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $departmentId; ?>">
        <input type="submit" value="Yes, delete it">
        <button type="button" onclick="window.location.href='department_list.php'">No, take me back</button>
    </form>
</body>
</html>