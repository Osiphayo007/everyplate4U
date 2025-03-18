<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: register.php");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: register.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: register.php");
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email already exists";
            header("Location: register.php");
            exit();
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password]);

        // Set success message
        $_SESSION['success'] = "Registration successful! Please login.";
        
        // Redirect to login page
        header("Location: login.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: register.php");
    exit();
}
?> 