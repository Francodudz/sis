<?php
// Assuming you have a session started after successful login
session_start();

$_SESSION['type'];

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

// Map status codes to their corresponding string values
$statusMap = [
    1 => 'Active',
    0 => 'Inactive'
];

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

// Example usage
$departments = getDepartmentList($conn);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Departments - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>List of Departments</h2>

    <div>
        <a href="add_department.php">Add New Department</a>
        <input type="text" id="search" name="search" placeholder="Search...">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date Created</th>
                <th>Name or Code</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
    foreach ($departments as $department) {
        echo "<tr>";
        echo "<td>{$department['id']}</td>";
        echo "<td>{$department['date_created']}</td>";
        echo "<td>{$department['name']}</td>";
        echo "<td>{$department['description']}</td>";
        echo "<td>{$statusMap[$department['status']]}</td>"; // Use the status map here
        if (isset($_SESSION['type']) && $_SESSION['type'] == 1) {
            echo "<td><a href='edit_department.php?id={$department['id']}'>Edit</a> | <a href='delete_department.php?id={$department['id']}'>Delete</a></td>";
        } else {
            echo "<td>Edit | Delete</td>";
        }
        echo "</tr>";
    }
?>
        </tbody>
    </table>
    <a href="dashboard.php" class="return-button">Return to Dashboard</a>
</body>
</html>

<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #333;
}

div {
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

a {
    color: #333;
    text-decoration: none;
}

a:hover {
    color: #f44336;
}

.return-button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        color: #fff;
        background-color: #333;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .return-button:hover {
        background-color: #f44336;
    }
</style>