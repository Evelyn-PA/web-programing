<?php
// Start the session
session_start();

// Include the database connection file
include("db.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the email input
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Get the password from the form
    $password = $_POST["password"];

    // Prepare the SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the email parameter to the SQL statement
    $stmt->bind_param("s", $email);

    // Execute the statement
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Store the result
    $stmt->store_result();

    // Bind the result variables
    $stmt->bind_result($id, $hashed_password);

    // Fetch the result
    $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // Set session variables
        $_SESSION["user_id"] = $id;
        $_SESSION["loggedin"] = true;
        $_SESSION["LAST_ACTIVITY"] = time(); // Track last activity time

        // Redirect to the homepage or dashboard
        header("Location: header.php");
        exit();
    } else {
        // Display an error message if login fails
        echo "Invalid email or password.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wander Whimsy</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>