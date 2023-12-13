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

// Function to add a new user to the database
function addNewUser($conn, $firstname, $lastname, $username, $password, $type) {
    // Note: You should hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (firstname, lastname, username, password, type) VALUES ('$firstname', '$lastname', '$username', '$hashedPassword', '$type')";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user details from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    // Add the new user to the database
    if (addNewUser($conn, $firstname, $lastname, $username, $password, $type)) {
        echo "User added successfully!";
    } else {
        echo "Error adding user: " . $conn->error;
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
    <title>Create New User - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Create New User</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="type">User Type:</label>
        <select id="type" name="type" required>
            <option value="Administrator">Administrator</option>
            <option value="Staff">Staff</option>
        </select><br>

        <input type="submit" value="Create User">
        <input type="button" value="Cancel" onclick="window.location.href='user_list.php'">
    </form>

</body>
</html>
