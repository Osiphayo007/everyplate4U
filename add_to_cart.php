<?php
session_start();

// Hardcoded product details
$products = [
    1 => [
        'id' => 1,
        'name' => 'Weekly Package',
        'price' => 450,
    ],
    2 => [
        'id' => 2,
        'name' => 'Monthly Package',
        'price' => 1000,
    ],
    3 => [
        'id' => 3,
        'name' => 'Monthly VIP Package',
        'price' => 2000,
    ],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];

    // Check if the product exists in the hardcoded list
    if (isset($products[$product_id])) {
        $product = $products[$product_id];

        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $product['id']) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        // If the product is not in the cart, add it
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }

        // Redirect back to the catalogue page
        header('Location: catalogue.php');
        exit();
    } else {
        // Product not found
        echo "<script>alert('Product not found.'); window.location.href = 'catalogue.php';</script>";
    }
}
