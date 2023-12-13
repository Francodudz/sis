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

// Function to add a new department to the database
function addNewDepartment($conn, $nameOrCode, $description, $status) {
    $query = "INSERT INTO department_list (name, description, status) VALUES ('$nameOrCode', '$description', '$status')";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get department details from the form
    $nameOrCode = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Add the new department to the database
    if (addNewDepartment($conn, $nameOrCode, $description, $status)) {
        echo "Department added successfully!";
    } else {
        echo "Error adding department: " . $conn->error;
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
    <title>Add New Department - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Add New Department</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name or Code:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="description">Department Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select><br>

        <input type="submit" value="Add Department">
        <input type="button" value="Cancel" onclick="window.location.href='department_list.php'">
    </form>

</body>
</html>
