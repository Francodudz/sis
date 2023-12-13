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

// Get student details from the database based on the provided ID
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $query = "SELECT * FROM student_list WHERE id = $studentId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
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

// Function to get the academic history of a student
function getAcademicHistory($conn, $studentId) {
    $query = "SELECT academic_history.id, department_list.name as department, course_list.name as course, academic_history.semester, academic_history.year, academic_history.status as beginning_semester_status, academic_history.end_status as end_semester_status
              FROM academic_history
              JOIN course_list ON academic_history.course_id = course_list.id
              JOIN department_list ON course_list.department_id = department_list.id
              WHERE academic_history.student_id = $studentId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Map status codes to their corresponding string values
        $statusMap = [
            1 => 'New',
            2 => 'Regular',
            3 => 'Returnee',
            4 => 'Transferee'
        ];
        $endStatusMap = [
            0 => 'Pending',
            1 => 'Completed',
            2 => 'Dropped',
            3 => 'Failed',
            4 => 'Transferred-out',
            5 => 'Graduated'
        ];

        $records = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($records as $index => $record) {
            $records[$index]['beginning_semester_status'] = isset($statusMap[$record['beginning_semester_status']]) ? $statusMap[$record['beginning_semester_status']] : 'Unknown';
            $records[$index]['end_semester_status'] = isset($endStatusMap[$record['end_semester_status']]) ? $endStatusMap[$record['end_semester_status']] : 'Unknown';
        }
        return $records;
    } else {
        return [];
    }
}

// Get academic history and store it in a variable
$academicHistory = getAcademicHistory($conn, $studentId);

// Map status codes to their corresponding string values
$statusMap = [
    1 => 'Active',
    0 => 'Inactive'
];

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - Student Information System</title>
    <!-- Add your CSS stylesheets here -->
</head>
<body>

    <h2>Student Details</h2>

    <div>
    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="button">Edit</a>
<a href="delete_student.php?id=<?php echo $student['id']; ?>" class="button">Delete</a>
<a href="add_academic_record.php?id=<?php echo $student['id']; ?>" class="button">Add Academic</a>
<a href="student_list.php" class="button">Back to List</a>
    </div>

    <h3>Details</h3>
    <p>Roll: <?php echo $student['roll']; ?></p>
    <p>Name: <?php echo "{$student['firstname']} {$student['middlename']} {$student['lastname']}"; ?></p>
    <p>Gender: <?php echo $student['gender']; ?></p>
    <p>Date of Birth: <?php echo $student['dob']; ?></p>
    <p>Contact #: <?php echo $student['contact']; ?></p>
    <p>Present Address: <?php echo $student['present_address']; ?></p>
    <p>Permanent Address: <?php echo $student['permanent_address']; ?></p>
    <p>Status: <?php echo $statusMap[$student['status']]; ?></p>

    <!-- Academic History Section -->
    <h3>Academic History</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Department/Courses</th>
                <th>Semester/School Year</th>
                <th>Year</th>
                <th>Beginning of Semester Status</th>
                <th>End of Semester Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
foreach ($academicHistory as $record) {
    echo "<tr>";
    echo "<td>{$record['id']}</td>";
    echo "<td>{$record['department']}/{$record['course']}</td>";
    echo "<td>{$record['semester']}</td>";
    echo "<td>{$record['year']}</td>";
    echo "<td>{$record['beginning_semester_status']}</td>";
    echo "<td>{$record['end_semester_status']}</td>";
    echo "<td><a href='edit_academic_record.php?id={$record['id']}'>Edit</a></td>";
    echo "<td><a href='delete_academic_record.php?id={$record['id']}'>Delete</a></td>";
    echo "</tr>";
}
?>
        </tbody>
    </table>

</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }

    h2, h3 {
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

    a.button {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px 2px;
        color: #fff;
        background-color: #333;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    a.button:hover {
        background-color: #f44336;
    }

    /* Style for student details */
    p {
        font-size: 18px;
        color: #333;
        line-height: 1.6;
    }

</style>