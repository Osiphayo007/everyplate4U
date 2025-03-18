<?php
// Start session
session_start();
include 'config.php';

// Check if we have order information
$order_id = $_GET['order_id'] ?? '';
$has_order = isset($_SESSION['current_order_id']) && isset($_SESSION['order_total']);

// Record cancelled order
if ($has_order && $order_id === $_SESSION['current_order_id']) {
    $cancelled_order = [
        'order_id' => $_SESSION['current_order_id'],
        'amount' => $_SESSION['order_total'],
        'status' => 'CANCELLED',
        'date' => date('Y-m-d H:i:s')
    ];

    // In a real application, you would save this to your database

    // Store cancelled order data and keep cart active
    $_SESSION['cancelled_order'] = $cancelled_order;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled - Platedbox</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Copy the relevant styles from your checkout.php */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        .container {
            width: 85%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #ff6b6b;
            font-size: 2rem;
            font-weight: 700;
        }

        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            color: #333;
            font-weight: 600;
            padding: 8px 16px;
            text-decoration: none;
        }

        .cancellation-box {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 80px auto;
            max-width: 600px;
            text-align: center;
        }

        .cancellation-box i {
            color: #ff6b6b;
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .cancellation-box h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .cancellation-box p {
            margin-bottom: 15px;
            color: #666;
        }

        .order-details {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            text-align: left;
        }

        .cta-button {
            display: inline-block;
            background-color: #ff6b6b;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
        }

        .secondary-button {
            display: inline-block;
            background-color: #666;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1>Platedbox</h1>
            <nav>
                <a href="landingpage.php">Home</a>
                <a href="catalogue.php">Catalogue</a>
                <a href="cart.php">Cart</a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="account.php">My Account</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="cancellation-box">
            <?php if ($has_order && isset($_SESSION['cancelled_order'])): ?>
                <i class="fas fa-times-circle"></i>
                <h2>Payment Cancelled</h2>
                <p>Your order has been cancelled and no payment has been processed.</p>

                <div class="order-details">
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($_SESSION['cancelled_order']['order_id']); ?></p>
                    <p><strong>Amount:</strong> R<?php echo number_format($_SESSION['cancelled_order']['amount'], 2); ?></p>
                    <p><strong>Status:</strong> Cancelled</p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($_SESSION['cancelled_order']['date']); ?></p>
                </div>

                <p>Your items are still in your cart if you wish to complete your purchase.</p>
                <a href="cart.php" class="cta-button">Return to Cart</a>
                <a href="catalogue.php" class="secondary-button">Continue Shopping</a>
            <?php else: ?>
                <i class="fas fa-exclamation-circle"></i>
                <h2>Order Cancellation</h2>
                <p>Your payment process was cancelled or interrupted.</p>
                <p>No charges have been made to your account.</p>
                <a href="cart.php" class="cta-button">Return to Cart</a>
                <a href="catalogue.php" class="secondary-button">Continue Shopping</a>
            <?php endif; ?>
        </div>
    </div>

    <footer style="background-color: #333; color: white; text-align: center; padding: 20px 0; margin-top: 40px;">
        <div class="container">
            <p>&copy; 2025 Platedbox. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>