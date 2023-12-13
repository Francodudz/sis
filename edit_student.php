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

// Initialize variables to hold student details
$studentId = '';
$roll = '';
$firstname = '';
$middlename = '';
$lastname = '';
$gender = '';
$contact = '';
$present_address = '';
$permanent_address = '';
$dob = '';
$status = '';

// Check if we have an ID and fetch the student details
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $query = "SELECT * FROM student_list WHERE id = $studentId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $roll = $student['roll'];
        $firstname = $student['firstname'];
        $middlename = $student['middlename'];
        $lastname = $student['lastname'];
        $gender = $student['gender'];
        $contact = $student['contact'];
        $present_address = $student['present_address'];
        $permanent_address = $student['permanent_address'];
        $dob = $student['dob'];
        $status = $student['status'];
    } else {
        // Redirect to the student list page if the student ID is invalid
        header("Location: student_list.php");
        exit();
    }
} else {
    // Redirect to the student list page if no ID is provided
    header("Location: student_list.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $roll = $_POST['roll'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $present_address = $_POST['present_address'];
    $permanent_address = $_POST['permanent_address'];
    $dob = $_POST['dob'];
    $status = intval($_POST['status']); // Convert the status to an integer

    // Update the student details in the database
    $query = "UPDATE student_list SET roll = ?, firstname = ?, middlename = ?, lastname = ?, gender = ?, contact = ?, present_address = ?, permanent_address = ?, dob = ?, status = ? WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssiii", $roll, $firstname, $middlename, $lastname, $gender, $contact, $present_address, $permanent_address, $dob, $status, $studentId);

    if ($stmt->execute()) {
        // Redirect to the view_student.php page with updated details
        header("Location: view_student.php?id=$studentId");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
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
    <title>Edit Student Details - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Edit Student Details</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $studentId; ?>">
        <label for="roll">Roll:</label>
        <input type="text" id="roll" name="roll" value="<?php echo $roll; ?>" required><br>

        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required><br>

        <label for="middlename">Middle Name:</label>
        <input type="text" id="middlename" name="middlename" value="<?php echo $middlename; ?>"><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo $gender == 'Other' ? 'selected' : ''; ?>>Other</option>
        </select><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required><br>

        <label for="contact">Contact #:</label>
        <input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" required><br>

        <label for="present_address">Present Address:</label>
        <input type="text" id="present_address" name="present_address" value="<?php echo $present_address; ?>" required><br>

        <label for="permanent_address">Permanent Address:</label>
        <input type="text" id="permanent_address" name="permanent_address" value="<?php echo $permanent_address; ?>" required><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Active</option>
            <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Inactive</option>
        </select><br>

        <input type="submit" value="Update">
        <input type="button" value="Cancel" onclick="window.location.href='student_list.php'">
    </form>

</body>
</html>