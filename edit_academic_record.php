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

// Get academic record details from the database based on the provided ID
if (isset($_GET['id'])) {
    $academicRecordId = $_GET['id'];
    $query = "SELECT * FROM academic_history WHERE id = $academicRecordId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $academicRecord = $result->fetch_assoc();
    } else {
        header("Location: student_list.php");
        exit();
    }
} else {
    header("Location: student_list.php");
    exit();
}

// Fetch all courses from the database
$query = "SELECT * FROM course_list";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $courses = $result->fetch_all(MYSQLI_ASSOC);
}

// Define your semesters, years, and statuses
$semesters = ['First Semester', 'Second Semester', 'Third Semester'];
$years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
$beginningSemesterStatuses = ['New', 'Regular', 'Returnee', 'Transferee'];
$endSemesterStatuses = ['Pending', 'Complete', 'Dropout', 'Failed', 'Transferred Out', 'Graduated'];

// Check if the form is submitted
// ... [Previous code remains unchanged]

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $academicRecordId = $_POST['id']; // Use the hidden input value
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $schoolYear = mysqli_real_escape_string($conn, $_POST['school_year']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $beginningSemesterStatus = mysqli_real_escape_string($conn, $_POST['beginning_semester_status']);
    $endSemesterStatus = mysqli_real_escape_string($conn, $_POST['end_semester_status']);

    // Update the academic record in the database
    $query = "UPDATE academic_history SET semester = '$semester', school_year = '$schoolYear', course_id = '$course', year = '$year', status = '$beginningSemesterStatus', end_status = '$endSemesterStatus' WHERE id = $academicRecordId";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } else {
        echo "Academic record updated successfully!";
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
    <title>Edit Academic Record - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Edit Academic Record</h2>

    <form method="post" action="edit_academic_record.php?id=<?php echo $academicRecord['id']; ?>">
    <input type="hidden" name="id" value="<?php echo $academicRecord['id']; ?>">
        <label for="semester">Semester:</label>
        <select id="semester" name="semester" required>
            <?php foreach ($semesters as $semester): ?>
            <option value="<?php echo $semester; ?>" <?php echo $academicRecord['semester'] == $semester ? 'selected' : ''; ?>><?php echo $semester; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="school_year">School Year:</label>
        <input type="text" id="school_year" name="school_year" value="<?php echo $academicRecord['school_year']; ?>" required><br>

        <label for="course">Course:</label>
        <select id="course" name="course" required>
            <?php foreach ($courses as $course): ?>
            <option value="<?php echo $course['id']; ?>" <?php echo $academicRecord['course_id'] == $course['id'] ? 'selected' : ''; ?>><?php echo $course['name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="year">Year:</label>
        <select id="year" name="year" required>
            <?php foreach ($years as $year): ?>
            <option value="<?php echo $year; ?>" <?php echo $academicRecord['year'] == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="beginning_semester_status">Beginning of Semester Status:</label>
<select id="beginning_semester_status" name="beginning_semester_status" required>
    <option value="1" <?php echo $academicRecord['status'] == 1 ? 'selected' : ''; ?>>New</option>
    <option value="2" <?php echo $academicRecord['status'] == 2 ? 'selected' : ''; ?>>Regular</option>
    <option value="3" <?php echo $academicRecord['status'] == 3 ? 'selected' : ''; ?>>Returnee</option>
    <option value="4" <?php echo $academicRecord['status'] == 4 ? 'selected' : ''; ?>>Transferee</option>
</select><br>

<label for="end_semester_status">End of Semester Status:</label>
<select id="end_semester_status" name="end_semester_status" required>
    <option value="0" <?php echo $academicRecord['end_status'] == 0 ? 'selected' : ''; ?>>Pending</option>
    <option value="1" <?php echo $academicRecord['end_status'] == 1 ? 'selected' : ''; ?>>Completed</option>
    <option value="2" <?php echo $academicRecord['end_status'] == 2 ? 'selected' : ''; ?>>Dropped</option>
    <option value="3" <?php echo $academicRecord['end_status'] == 3 ? 'selected' : ''; ?>>Failed</option>
    <option value="4" <?php echo $academicRecord['end_status'] == 4 ? 'selected' : ''; ?>>Transferred-out</option>
    <option value="5" <?php echo $academicRecord['end_status'] == 5 ? 'selected' : ''; ?>>Graduated</option>
</select><br>

        <input type="submit" value="Save">
        <input type="button" value="Cancel" onclick="window.location.href='view_student.php?id=<?php echo $academicRecord['student_id']; ?>'">
    </form>

</body>
</html>