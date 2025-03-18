<?php
// Include the config file with database connection
include 'config.php';

// Check if the connection is established
if (!isset($conn) || $conn === null) {
    // If $conn is not defined in config.php, create the connection here
    $servername = "localhost";
    $username = "root"; // Default XAMPP username
    $password = ""; // Default XAMPP password
    $dbname = "platedbox"; // Your database name

    // Create connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $campus = $_POST['campus'];

    try {
        // Prepare SQL statement to insert user data
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, campus) VALUES (?, ?, ?, ?)");

        // Execute the statement with the form data
        $stmt->execute([$username, $email, $password, $campus]);

        // Redirect to login page after successful registration
        header("Location: login.php?register=success");
        exit();
    } catch (PDOException $e) {
        // Check if the error is due to duplicate username or email
        if ($e->getCode() == 23000) { // Integrity constraint violation
            header("Location: register.php?error=duplicate");
        } else {
            header("Location: register.php?error=database&message=" . urlencode($e->getMessage()));
        }
        exit();
    }
} else {
    // If not a POST request, redirect to the registration form
    header("Location: register.php");
    exit();
}
