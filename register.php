<?php
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

// Check if the form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get registration details from the form
    $regUsername = $_POST['regUsername'];
    $regPassword = $_POST['regPassword'];
    $regUserType = $_POST['regUserType'];
    $regFirstname = $_POST['regFirstname'];
    $regLastname = $_POST['regLastname'];   

   
    // Password validation
    if (!(preg_match('/[A-Z]/', $regPassword) && preg_match('/[a-z]/', $regPassword) && preg_match('/[^a-zA-Z0-9]/', $regPassword) && strlen($regPassword) >= 8)) {
        $error_message = 'Password must have at least 8 characters, including upper and lower case letters and at least one special character.';
    } else {
        // Hash the password
        $hashedPassword = password_hash($regPassword, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insertQuery = "INSERT INTO users (firstname, lastname, username, password, type) VALUES ('$regFirstname', '$regLastname', '$regUsername', '$hashedPassword', '$regUserType')";
        if ($conn->query($insertQuery) === TRUE) {
            $success_message = "Registration successful! You can now log in.";
        } else {
            $error_message = "Error in registration: " . $conn->error;
        }
    }

}

// Close the database connection
$conn->close();
?>

<!-- Rest of your HTML code -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script>
        function validatePassword() {
            var password = document.getElementById('regPassword').value;
            var passwordStrength = document.getElementById('password-strength');

            var hasUpperCase = /[A-Z]/.test(password);
            var hasLowerCase = /[a-z]/.test(password);
            var hasSpecialCharacter = /[^a-zA-Z0-9]/.test(password);
            var isLengthValid = password.length >= 8;

            if (hasUpperCase && hasLowerCase && hasSpecialCharacter && isLengthValid) {
                passwordStrength.innerHTML = 'Password strength: Strong';
                passwordStrength.style.color = 'green';
            } else {
                passwordStrength.innerHTML = 'Password must have at least 8 characters, including upper and lower case letters, and at least one special character.';
                passwordStrength.style.color = 'red';
            }
        }
    </script>
</head>
<body>

    <h2>Register</h2>

    <?php
    // Display error or success message
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    } elseif (isset($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="regFirstname">First Name:</label>
<input type="text" id="regFirstname" name="regFirstname" required><br>

<label for="regLastname">Last Name:</label>
<input type="text" id="regLastname" name="regLastname" required><br>
        <label for="regUsername">Username:</label>
        <input type="text" id="regUsername" name="regUsername" required><br>

        <label for="regPassword">Password:</label>
        <input type="password" id="regPassword" name="regPassword" required onkeyup="validatePassword()">
        <span id="password-strength"></span><br>

        <label for="regUserType">User Type:</label>
        <select id="regUserType" name="regUserType" required>
    <option value="1">Admin</option>
    <option value="2">Staff</option>
</select><br>

        <input type="submit" value="Register">
    </form>

    <!-- Add the "Login" button linking to the login page -->
    <a href="login.php"><button type="button">Login</button></a>

</body>
</html>