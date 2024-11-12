<?php
session_start();

// Include database connection
require_once 'db_connection.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row was found
    if ($result->num_rows == 1) {
        // Authentication successful
        $_SESSION['username'] = $username;
        header('Location: login.php'); // Redirect to admin dashboard
        exit();
    } else {
        // Authentication failed
        $login_error = "Invalid username or password.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link rel="stylesheet" href="index.css">
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form id="loginForm" action="" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <?php
    if (isset($login_error)) {
        echo '<div class="error">' . $login_error . '</div>';
    }
    ?>
</div>

</body>
</html>


