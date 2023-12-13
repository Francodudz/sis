<?php

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

// Function to get student list
function getStudentList($conn) {
    $query = "SELECT * FROM student_list";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Get student list and store it in a variable
$students = getStudentList($conn);

// Map status codes to their corresponding string values
$statusMap = [
    1 => 'Active',
    0 => 'Inactive'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Students - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>List of Students</h2>

    <div>
        <a href="add_student.php">Add Student</a>
        <input type="text" id="search" name="search" placeholder="Search...">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date Created</th>
                <th>Roll</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($students as $student) {
                $status = isset($statusMap[$student['status']]) ? $statusMap[$student['status']] : 'Unknown';
                echo "<tr>";
                echo "<td>{$student['id']}</td>";
                echo "<td>{$student['date_created']}</td>";
                echo "<td>{$student['roll']}</td>";
                echo "<td>{$student['firstname']} {$student['middlename']} {$student['lastname']}</td>";
                echo "<td><a href='view_student.php?id={$student['id']}'>View</a> | <a href='add_academic_record.php?student_id={$student['id']}'>Add Academic Record</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="return-button">Return to Dashboard</a>
</body>
</html>

<!-- Your CSS styles go here -->
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