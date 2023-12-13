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

// Function to get the course list
function getCourseList($conn) {
    $query = "SELECT * FROM course_list";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Function to add a new academic record to the database
function addAcademicRecord($conn, $studentId, $semester, $schoolYear, $course, $year, $beginningSemesterStatus, $endSemesterStatus) {
    $studentId = mysqli_real_escape_string($conn, $studentId);
    $semester = mysqli_real_escape_string($conn, $semester);
    $schoolYear = mysqli_real_escape_string($conn, $schoolYear);
    $course = mysqli_real_escape_string($conn, $course);
    $year = mysqli_real_escape_string($conn, $year);
    $beginningSemesterStatus = mysqli_real_escape_string($conn, $beginningSemesterStatus);
    $endSemesterStatus = mysqli_real_escape_string($conn, $endSemesterStatus);

    $query = "INSERT INTO academic_history 
              (student_id, semester, school_year, course_id, year, status, end_status) 
              VALUES 
              ('$studentId', '$semester', '$schoolYear', '$course', '$year', '$beginningSemesterStatus', '$endSemesterStatus')";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        return false;
    } else {
        return true;
    }
}

// Get course list and store it in a variable
$courses = getCourseList($conn);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get academic record details from the form
    $studentId = $_POST['student_id'];
    $semester = $_POST['semester'];
    $schoolYear = $_POST['school_year'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $beginningSemesterStatus = $_POST['beginning_semester_status'];
    $endSemesterStatus = $_POST['end_semester_status'];

    // Add the new academic record to the database
    if (addAcademicRecord($conn, $studentId, $semester, $schoolYear, $course, $year, $beginningSemesterStatus, $endSemesterStatus)) {
        echo "Academic record added successfully!";
    } else {
        echo "Error adding academic record: " . $conn->error;
    }
}
?>

<h2>Add Academic Record</h2>

<form method="post" action="add_academic_record.php">
    <input type="hidden" name="student_id" value="<?php echo $_GET['id']; ?>">

    <label for="semester">Semester:</label>
<select id="semester" name="semester" required>
    <option value="First Semester">First Semester</option>
    <option value="Second Semester">Second Semester</option>
    <option value="Third Semester">Third Semester</option>
</select><br>

<label for="school_year">School Year:</label>
    <input type="text" id="school_year" name="school_year" required><br>

    <label for="course">Course:</label>
<select id="course" name="course" required>
    <?php foreach ($courses as $course): ?>
        <option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
    <?php endforeach; ?>
</select><br>

    <label for="year">Year:</label>
<select id="year" name="year" required>
    <option value="1st Year">1st Year</option>
    <option value="2nd Year">2nd Year</option>
    <option value="3rd Year">3rd Year</option>
    <option value="4th Year">4th Year</option>
</select><br>

<label for="beginning_semester_status">Beginning of Semester Status:</label>
    <select id="beginning_semester_status" name="beginning_semester_status" required>
        <option value="1">New</option>
        <option value="2">Regular</option>
        <option value="3">Returnee</option>
        <option value="4">Transferee</option>
    </select><br>

    <label for="end_semester_status">End of Semester Status:</label>
    <select id="end_semester_status" name="end_semester_status" required>
        <option value="0">Pending</option>
        <option value="1">Complete</option>
        <option value="2">Dropout</option>
        <option value="3">Failed</option>
        <option value="4">Transferred Out</option>
        <option value="5">Graduated</option>
    </select><br>

    <input type="submit" value="Add Academic Record">
    <input type="button" value="Cancel" onclick="window.history.back();">
</form>