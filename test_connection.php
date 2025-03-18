<?php
require_once 'config.php';

try {
    // Test the connection
    echo "<h2>Database Connection Test</h2>";
    echo "<p style='color: green;'>✓ Successfully connected to the database!</p>";
    
    // Get database name
    $dbname = $conn->query("SELECT DATABASE()")->fetchColumn();
    echo "<p>Connected to database: <strong>" . $dbname . "</strong></p>";
    
    // Get MySQL version
    $version = $conn->query("SELECT VERSION()")->fetchColumn();
    echo "<p>MySQL Version: <strong>" . $version . "</strong></p>";
    
    // Get list of tables
    echo "<h3>Tables in database:</h3>";
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table . "</li>";
    }
    echo "</ul>";
    
    // Test query on products table
    echo "<h3>Testing products table:</h3>";
    $products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    echo "<p>Number of products in database: <strong>" . $products . "</strong></p>";
    
    // Display first product if exists
    $firstProduct = $conn->query("SELECT * FROM products LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($firstProduct) {
        echo "<p>Sample product:</p>";
        echo "<pre>";
        print_r($firstProduct);
        echo "</pre>";
    }
    
} catch(PDOException $e) {
    echo "<h2>Connection Error</h2>";
    echo "<p style='color: red;'>✗ Connection failed: " . $e->getMessage() . "</p>";
}
?> 