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

// Check if the delete button has been clicked
if (isset($_POST['confirm'])) {
    $id = $_GET['id'];

    // Delete the user
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirect to the user list
    header("Location: user_list.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>
    <h2>Delete User</h2>

    <p>Are you sure you want to delete this user?</p>

    <form method="post">
        <input type="submit" name="confirm" value="Yes, delete this user">
    </form>

    <a href="user_list.php">No, return to the user list</a>
</body>
</html>