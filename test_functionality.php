<?php
require_once 'config.php';

echo "<h2>PlatedBox System Functionality Test</h2>";

try {
    // 1. Test User Registration
    echo "<h3>1. Testing User Registration</h3>";
    $testUsername = "testuser_" . time();
    $testEmail = "test" . time() . "@example.com";
    $testPassword = password_hash("Test123!", PASSWORD_DEFAULT);
    $testCampus = "Test Campus";
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, campus) VALUES (?, ?, ?, ?)");
    $stmt->execute([$testUsername, $testEmail, $testPassword, $testCampus]);
    echo "<p style='color: green;'>✓ Test user created successfully</p>";
    
    // Get the created user's ID
    $userId = $conn->lastInsertId();
    
    // 2. Test Product Selection
    echo "<h3>2. Testing Product Selection</h3>";
    $products = $conn->query("SELECT * FROM products WHERE active = 1")->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>Available products:</p>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>{$product['name']} - R{$product['price']}</li>";
    }
    echo "</ul>";
    
    // 3. Test Order Creation
    echo "<h3>3. Testing Order Creation</h3>";
    $orderRef = "TEST-" . time();
    $totalAmount = $products[0]['price']; // Using first product's price
    
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_reference, total_amount, status, delivery_campus) VALUES (?, ?, ?, 'PENDING', ?)");
    $stmt->execute([$userId, $orderRef, $totalAmount, $testCampus]);
    $orderId = $conn->lastInsertId();
    echo "<p style='color: green;'>✓ Test order created successfully</p>";
    
    // 4. Test Order Items
    echo "<h3>4. Testing Order Items</h3>";
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES (?, ?, 1, ?)");
    $stmt->execute([$orderId, $products[0]['id'], $products[0]['price']]);
    echo "<p style='color: green;'>✓ Order item added successfully</p>";
    
    // 5. Test Payment Creation
    echo "<h3>5. Testing Payment Creation</h3>";
    $paymentRef = "PAY-" . time();
    $stmt = $conn->prepare("INSERT INTO payments (order_id, amount, payment_reference, payment_method, status) VALUES (?, ?, ?, 'TEST', 'PENDING')");
    $stmt->execute([$orderId, $totalAmount, $paymentRef]);
    echo "<p style='color: green;'>✓ Test payment created successfully</p>";
    
    // 6. Test Delivery Schedule
    echo "<h3>6. Testing Delivery Schedule</h3>";
    $deliveryDate = date('Y-m-d', strtotime('+1 day'));
    $stmt = $conn->prepare("INSERT INTO delivery_schedules (order_id, delivery_date, delivery_time_slot, status) VALUES (?, ?, '16:00-18:00', 'SCHEDULED')");
    $stmt->execute([$orderId, $deliveryDate]);
    echo "<p style='color: green;'>✓ Delivery schedule created successfully</p>";
    
    // 7. Display Test Results
    echo "<h3>7. Test Results Summary</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Component</th><th>Status</th><th>Details</th></tr>";
    
    // User details
    $user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch(PDO::FETCH_ASSOC);
    echo "<tr><td>User</td><td>Created</td><td>Username: {$user['username']}</td></tr>";
    
    // Order details
    $order = $conn->query("SELECT * FROM orders WHERE id = $orderId")->fetch(PDO::FETCH_ASSOC);
    echo "<tr><td>Order</td><td>Created</td><td>Reference: {$order['order_reference']}</td></tr>";
    
    // Payment details
    $payment = $conn->query("SELECT * FROM payments WHERE order_id = $orderId")->fetch(PDO::FETCH_ASSOC);
    echo "<tr><td>Payment</td><td>Created</td><td>Reference: {$payment['payment_reference']}</td></tr>";
    
    // Delivery schedule
    $schedule = $conn->query("SELECT * FROM delivery_schedules WHERE order_id = $orderId")->fetch(PDO::FETCH_ASSOC);
    echo "<tr><td>Delivery</td><td>Scheduled</td><td>Date: {$schedule['delivery_date']}</td></tr>";
    
    echo "</table>";
    
    // 8. Cleanup Test Data
    echo "<h3>8. Cleaning Up Test Data</h3>";
    $conn->exec("DELETE FROM delivery_schedules WHERE order_id = $orderId");
    $conn->exec("DELETE FROM payments WHERE order_id = $orderId");
    $conn->exec("DELETE FROM order_items WHERE order_id = $orderId");
    $conn->exec("DELETE FROM orders WHERE id = $orderId");
    $conn->exec("DELETE FROM users WHERE id = $userId");
    echo "<p style='color: green;'>✓ Test data cleaned up successfully</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?> 