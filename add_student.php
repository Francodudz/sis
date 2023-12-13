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

// Function to add a new student to the database
function addNewStudent($conn, $roll, $firstname, $middlename, $lastname, $gender, $dob, $contact, $present_address, $permanent_address) {
    $query = "INSERT INTO student_list (roll, firstname, middlename, lastname, gender, dob, contact, present_address, permanent_address) VALUES ('$roll', '$firstname', '$middlename', '$lastname', '$gender', '$dob', '$contact', '$present_address', '$permanent_address')";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get student details from the form
    $roll = $_POST['roll'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $present_address = $_POST['present_address'];
    $permanent_address = $_POST['permanent_address'];

    // Add the new student to the database
    if (addNewStudent($conn, $roll, $firstname, $middlename, $lastname, $gender, $dob, $contact, $present_address, $permanent_address)) {
        echo "Student added successfully!";
    } else {
        echo "Error adding student: " . $conn->error;
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
    <title>Add New Student - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Add New Student</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="roll">Student Roll:</label>
        <input type="text" id="roll" name="roll" required><br>

        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="middlename">Middle Name:</label>
        <input type="text" id="middlename" name="middlename"><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br>

        <label for="contact">Contact #:</label>
        <input type="text" id="contact" name="contact" required><br>

        <label for="present_address">Present Address:</label>
        <textarea id="present_address" name="present_address" rows="4" required></textarea><br>

        <label for="permanent_address">Permanent Address:</label>
        <textarea id="permanent_address" name="permanent_address" rows="4" required></textarea><br>

        <input type="submit" value="Save Student Details">
        <input type="button" value="Cancel" onclick="window.location.href='dashboard.php'">
    </form>

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

form {
    margin: 0 auto;
    width: 50%;
}

form label {
    font-weight: bold;
    display: block;
    margin-top: 20px;
}

form input[type="text"], form input[type="date"], form textarea, form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

form input[type="submit"], form input[type="button"] {
    margin-top: 20px;
    padding: 10px 20px;
    border: none;
    color: #fff;
    background-color: #333;
    cursor: pointer;
}

form input[type="button"] {
    background-color: #f44336;
}

form input[type="submit"]:hover, form input[type="button"]:hover {
    opacity: 0.9;
}
</style>
