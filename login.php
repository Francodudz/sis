<?php
// Start the session
session_start();

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

// Constants for login attempts and lockout time
$maxLoginAttempts = 3;
$lockoutTime = 180; // 3 minutes in seconds

// Check if the form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get login details from the form
    $loginUsername = $_POST['username'];
    $loginPassword = $_POST['password'];

    // Check if the user is currently locked out
    if (isset($_SESSION['lockout_time']) && time() - $_SESSION['lockout_time'] < $lockoutTime) {
        $remainingLockoutTime = $lockoutTime - (time() - $_SESSION['lockout_time']);
        $error_message = 'Account is locked. Please try again later. Time remaining: <span id="countdown">' . $remainingLockoutTime . '</span> seconds.';
    } else {
       // Fetch the user from the database
// Fetch the user from the database
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $loginUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user is currently locked out
if ($user['lockout_time'] && time() - strtotime($user['lockout_time']) < $lockoutTime) {
    $remainingLockoutTime = $lockoutTime - (time() - strtotime($user['lockout_time']));
    $error_message = 'Account is locked. Please try again later. Time remaining: <span id="countdown">' . $remainingLockoutTime . '</span> seconds.';
} else {
    // Verify the password
    if ($user && password_verify($loginPassword, $user['password'])) {
        // If the password is correct, reset login attempts and lockout time, store the user type and redirect to dashboard
        $query = "UPDATE users SET login_attempts = 0, lockout_time = NULL WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $loginUsername);
        $stmt->execute();

        $_SESSION['username'] = $loginUsername;
        $_SESSION['type'] = $user['type'];
        header('Location: dashboard.php'); // replace 'dashboard.php' with the path to your dashboard page
        exit;
    } else {
        // Incorrect password, update login attempts
        $query = "UPDATE users SET login_attempts = login_attempts + 1 WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $loginUsername);
        $stmt->execute();

        // Check if the maximum login attempts have been reached
        if ($user['login_attempts'] + 1 >= $maxLoginAttempts) {
            $query = "UPDATE users SET lockout_time = NOW() WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $loginUsername);
            $stmt->execute();

            $error_message = 'Maximum login attempts reached. Account is locked for 3 minutes.';
        } else {
            $attemptsLeft = $maxLoginAttempts - ($user['login_attempts'] + 1);
            $error_message = 'Incorrect username or password! ' . $attemptsLeft . ' attempts remaining.';
        }
    }
}
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
    <title>Login Page</title>
</head>

<body>
<center>
    <h2>Login</h2>

    <?php
    // Display error message if authentication failed
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <br><br><input type="submit" value="Login">

        <!-- Add the "Register" button linking to the registration page -->
        <br><br><br><a href="register.php"><button type="button">Register</button></a>
    </form>

</body>
</html>

<script>
    // JavaScript to update the countdown in real-time
    var countdownElement = document.getElementById('countdown');
    var countdown = <?php echo isset($remainingLockoutTime) ? $remainingLockoutTime : 0; ?>;

    function updateCountdown() {
        countdownElement.textContent = countdown + ' seconds';
        countdown -= 1;
        if (countdown >= 0) {
            setTimeout(updateCountdown, 1000);
        } else {
            location.reload(); // Reload the page when the countdown ends
        }
    }

    if (countdown > 0) {
        updateCountdown();
    }
</script>