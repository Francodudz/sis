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

// Get student details from the database based on the provided ID
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $query = "SELECT * FROM student_list WHERE id = $studentId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        // Redirect to the student list page if the student ID is invalid
        header("Location: student_list.php");
        exit();
    }
} else {
    // Redirect to the student list page if no ID is provided
    header("Location: student_list.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the student from the database
    $query = "DELETE FROM student_list WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $studentId);
    
    if ($stmt->execute()) {
        // Redirect to the student_list.php page after deletion
        header("Location: student_list.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Delete Student</h2>

    <p>Are you sure you want to delete the following student?</p>
    <p>Name: <?php echo "{$student['firstname']} {$student['middlename']} {$student['lastname']}"; ?></p>
    <p>Roll: <?php echo $student['roll']; ?></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $studentId; ?>">
        <input type="submit" value="Delete">
        <input type="button" value="Cancel" onclick="window.location.href='student_list.php'">
    </form>

</body>
</html>