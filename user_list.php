<?php
// Assuming you have a session started after successful login
session_start();

// Debug line
echo "User type: " . $_SESSION['type'];

// Check if the user is logged in

// Assuming you have a session started after successful login


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

// Function to get user list
function getUserList($conn) {
    $query = "SELECT * FROM users";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Example usage
$users = getUserList($conn);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Users - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>List of Users</h2>

    <div>
        <input type="text" id="search" name="search" placeholder="Search...">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Map user types to their string equivalents
        $userTypeMap = [
            1 => 'Admin',
            2 => 'Staff'
            // Add more user types here if needed
        ];

        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['firstname']} {$user['lastname']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$userTypeMap[$user['type']]}</td>"; // Use the user type map here
            if (isset($_SESSION['type']) && $_SESSION['type'] == 1) {
                echo "<td> <a href='delete_user.php?id={$user['id']}'>Delete</a></td>";
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

<!-- Add your CSS styles here -->
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
