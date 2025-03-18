<?php
session_start();

// Include config file
include 'config.php';

// Ensure this script is only accessible via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// Get JSON POST data instead of form data
$json_data = file_get_contents('php://input');
$payment_request = json_decode($json_data, true);

// Check if we received valid JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit();
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit();
}

// Calculate total from session cart to verify against request
$cart_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_total += $item['price'] * $item['quantity'];
}

// Verify the amount matches what's in the cart
if (!isset($payment_request['amount']) || $payment_request['amount'] != $cart_total) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Amount mismatch']);
    exit();
}

// Extract values from the payment request
$amount = $payment_request['amount'];
$reference = $payment_request['reference'] ?? 'ORDER-' . time();

// Define your domain for URLs
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/platedbox';

// Set up redirect URLs for success and cancellation
$redirect_url = $payment_request['redirect_url'] ?? $base_url . '/order_confirmation.php?order_id=' . $reference;
$cancel_url = $payment_request['cancel_url'] ?? $base_url . '/order_cancelled.php?order_id=' . $reference;

// Store order information in session for later verification
$_SESSION['current_order_id'] = $reference;
$_SESSION['order_total'] = $amount;

// iKhokha API Configuration
$merchant_key = ''; // Replace with your actual iKhokha merchant key when going live
$merchant_secret = ''; // Replace with your actual iKhokha merchant secret when going live
$api_url = 'https://pay.ikhokha.com/api/v1/payment-requests'; // iKhokha API endpoint

try {
    // Prepare payment data for iKhokha API
    $payment_data = [
        'merchantId' => $merchant_key,
        'merchantReference' => $reference,
        'amount' => $amount * 100, // Convert to cents
        'currency' => 'ZAR',
        'description' => 'Platedbox Order #' . $reference,
        'redirectUrl' => $redirect_url,
        'cancelUrl' => $cancel_url,
        'paymentMethod' => 'REDIRECT', // Changed to REDIRECT for web checkout
    ];

    // Calculate signature for API request
    $signature = hash_hmac('sha256', json_encode($payment_data), $merchant_secret);

    // Set up cURL request to iKhokha API
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $merchant_key,
        'X-Signature: ' . $signature
    ]);

    // For development environment - simulate successful payment response
    if (strpos($base_url, 'localhost') !== false || strpos($base_url, '127.0.0.1') !== false) {
        // We're in a development environment
        $_SESSION['payment_initiated'] = true;

        // Create a completed order in the session to simulate successful payment
        $order_data = [
            'order_id' => $_SESSION['current_order_id'],
            'amount' => $_SESSION['order_total'],
            'status' => 'COMPLETED',
            'date' => date('Y-m-d H:i:s')
        ];
        $_SESSION['completed_order'] = $order_data;

        echo json_encode([
            'success' => true,
            'message' => 'Payment request created (Development mode)',
            'redirect_url' => $redirect_url
        ]);
        exit();
    } else {
        // PRODUCTION MODE - Use real iKhokha API
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $result = json_decode($response, true);

            if ($result && isset($result['redirectUrl'])) {
                // Return the redirect URL to the client
                echo json_encode([
                    'success' => true,
                    'message' => 'Payment request created',
                    'redirect_url' => $result['redirectUrl']
                ]);
            } else {
                // Payment initialization failed
                $error_message = $result['message'] ?? 'Payment initialization failed';
                echo json_encode(['success' => false, 'message' => $error_message]);
            }
        } else {
            // API call failed
            echo json_encode(['success' => false, 'message' => 'Payment gateway error: ' . $http_code]);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error: ' . $e->getMessage()]);
}
