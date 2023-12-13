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

// Function to get the department list
function getDepartmentList($conn) {
    $query = "SELECT * FROM department_list";
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        $departments = array(); // Initialize an array to store department data
        while ($row = $result->fetch_assoc()) {
            // Process each row of the result set
            $departments[] = $row; // Add the row to the array
        }
        return $departments; // Return the array
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return array(); // Return an empty array in case of an error
    }
}

// Fetch all departments from the database
$departments = getDepartmentList($conn);

// Function to add a new course to the database
function addNewCourse($conn, $department, $nameOrCode, $description, $status) {
    $query = "INSERT INTO course_list (department_id, name, description, status) VALUES ('$department', '$nameOrCode', '$description', '$status')";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get course details from the form
    $department = $_POST['department'];
    $nameOrCode = $_POST['name_or_code'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Add the new course to the database
    if (addNewCourse($conn, $department, $nameOrCode, $description, $status)) {
        echo "Course added successfully!";
    } else {
        echo "Error adding course: " . $conn->error;
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
    <title>Add New Course - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Add New Course</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="department">Department:</label>
        <select id="department" name="department" required>
            <?php foreach ($departments as $department): ?>
                <option value="<?php echo $department['id']; ?>"><?php echo $department['name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="name_or_code">Course/Code:</label>
        <input type="text" id="name_or_code" name="name_or_code" required><br>

        <label for="description">Course Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select><br>

        <input type="submit" value="Add Course">
        <input type="button" value="Cancel" onclick="window.location.href='course_list.php'">
    </form>

</body>
</html>