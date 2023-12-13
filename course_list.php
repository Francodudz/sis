<?php
// Assuming you have a session started after successful login
session_start();

echo "User type: " . $_SESSION['type'];

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

// Function to get course list
function getCourseList($conn) {
    $query = "SELECT course_list.*, department_list.name as department_name FROM course_list JOIN department_list ON course_list.department_id = department_list.id WHERE course_list.delete_flag = 0";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $statusMap = [
            1 => 'Active',
            0 => 'Inactive'
        ];

        $courses = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($courses as $index => $course) {
            $courses[$index]['status'] = isset($statusMap[$course['status']]) ? $statusMap[$course['status']] : 'Unknown';
        }
        return $courses;
    } else {
        return [];
    }
}

// Example usage
$courses = getCourseList($conn);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Courses - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>List of Courses</h2>

    <div>
        <a href="add_course.php">Add New Course</a>
        <input type="text" id="search" name="search" placeholder="Search...">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date Created</th>
                <th>Department</th>
                <th>Course/Code</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
    // Map status codes to their corresponding string values
    $statusMap = [
        'Active' => 'Active',
        'Inactive' => 'Inactive'
    ];

    foreach ($courses as $course) {
        echo "<tr>";
        echo "<td>{$course['id']}</td>";
        echo "<td>{$course['date_created']}</td>";
        echo "<td>{$course['department_name']}</td>";
        echo "<td>{$course['name']}</td>";
        echo "<td>{$course['description']}</td>";
        echo "<td>{$statusMap[$course['status']]}</td>"; // Use the status map here
        
        
        if (isset($_SESSION['type']) && $_SESSION['type'] == 1) {
            echo "<td><a href='edit_course.php?id={$course['id']}'>Edit</a> | <a href='delete_course.php?id={$course['id']}'>Delete</a></td>";
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
