<?php
// Database configuration
$servername = "localhost";
$username = "root";  // MySQL username
$password = "1000Projects";  // MySQL password
$dbname = "platedbox";

// Create connection using PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
