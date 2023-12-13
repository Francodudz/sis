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

// Initialize variables to hold course details
$courseId = '';
$name = '';
$description = '';
$status = '';

// Check if we have an ID and fetch the course details
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];
    $query = "SELECT * FROM course_list WHERE id = ? AND delete_flag = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
        $name = $course['name'];
        $description = $course['description'];
        $status = $course['status'];
    } else {
        // Redirect to the course list page if the course ID is invalid
        header("Location: course_list.php");
        exit();
    }
} else {
    // Redirect to the course list page if no ID is provided
    header("Location: course_list.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Update the course details in the database
    $query = "UPDATE course_list SET name = ?, description = ?, status = ? WHERE id = ? AND delete_flag = 0";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $name, $description, $status, $courseId);
    
    if ($stmt->execute()) {
        // Redirect to the course_list.php page with updated details
        header("Location: course_list.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
</head>
<body>
    <h2>Edit Course</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $courseId; ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?php echo $description; ?></textarea><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status" required>
            <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Active</option>
            <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Inactive</option>
        </select><br>

        <input type="submit" value="Update">
        <input type="button" value="Cancel" onclick="window.location.href='course_list.php'">
    </form>
</body>
</html>