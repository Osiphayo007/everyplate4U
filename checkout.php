<?php
// Start session at the very beginning
session_start();

// Include config file after session start
include 'config.php';

// Check if user is logged in (if you want to restrict checkout to logged in users)
// if (!isset($_SESSION['username'])) {
//     header('Location: login.php');
//     exit();
// }

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platedbox - Checkout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Base styles and reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
            background-color: #f9f9f9;
        }

        .container {
            width: 85%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2E7D32;
        }

        /* Header styles */
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            position: relative;
            z-index: 100;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #2E7D32;
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }

        nav {
            display: flex;
            gap: 20px;
        }

        nav a {
            color: #333;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        nav a:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #2E7D32;
            transition: width 0.3s ease;
        }

        nav a:hover:before {
            width: 100%;
        }

        /* Checkout section styles */
        .checkout-section {
            padding: 80px 0;
            min-height: calc(100vh - 130px);
            display: flex;
            align-items: center;
        }

        .checkout-section .container {
            max-width: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.8s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .checkout-section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
            color: #333;
            position: relative;
            font-family: 'Playfair Display', serif;
        }

        .checkout-section h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: #2E7D32;
        }

        .order-summary {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .order-summary h3 {
            margin-bottom: 15px;
            color: #444;
            font-family: 'Playfair Display', serif;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
        }

        .payment-details {
            margin-bottom: 25px;
        }

        .payment-details h3 {
            margin-bottom: 15px;
            color: #444;
            font-family: 'Playfair Display', serif;
        }

        .payment-method {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .payment-method img {
            height: 30px;
            margin-left: 10px;
        }

        .cta-button {
            display: inline-block;
            background-color: #2E7D32;
            color: #fff;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 30px;
            transition: all 0.3s ease;
            border: 2px solid #2E7D32;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .cta-button:hover {
            background-color: #1B5E20;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.3);
        }

        .cta-button:disabled {
            background-color: #ccc;
            border-color: #ccc;
            color: #666;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .checkout-section p {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .checkout-section p a {
            color: #2E7D32;
            font-weight: 600;
        }

        .checkout-section p a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        /* Mobile menu */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #333;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Payment processing overlay */
        .payment-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .payment-modal {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .payment-modal h3 {
            margin-bottom: 20px;
        }

        .spinner {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 5px solid rgba(46, 125, 50, 0.3);
            border-radius: 50%;
            border-top-color: #2E7D32;
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                background-color: #fff;
                flex-direction: column;
                gap: 0;
                padding-top: 80px;
                transition: right 0.3s ease;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            }

            nav.active {
                right: 0;
            }

            nav a {
                display: block;
                padding: 15px;
                border-radius: 0;
                border-bottom: 1px solid #eee;
            }

            nav a:before {
                display: none;
            }

            .checkout-section .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <h1>everyplate4U</h1>
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
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

    <section class="checkout-section">
        <div class="container">
            <h2>Checkout</h2>

            <!-- Order Summary Section -->
            <div class="order-summary">
                <h3>Order Summary</h3>
                <?php
                // Check if cart session exists and has items
                if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                    echo '<div class="order-item">Your cart is empty</div>';
                    echo '<div class="order-total"><div>Total Amount:</div><div>R0.00</div></div>';
                    $total = 0;
                } else {
                    $total = 0;

                    // Loop through cart items from session
                    foreach ($_SESSION['cart'] as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                ?>
                        <div class="order-item">
                            <div><?php echo htmlspecialchars($item['name']); ?> (x<?php echo htmlspecialchars($item['quantity']); ?>)</div>
                            <div>R<?php echo number_format($itemTotal, 2); ?></div>
                        </div>
                <?php
                    endforeach;

                    // Display total
                    echo '<div class="order-total">
                            <div>Total Amount:</div>
                            <div>R' . number_format($total, 2) . '</div>
                        </div>';
                }
                ?>
            </div>

            <!-- Payment Details Section -->
            <div class="payment-details">
                <h3>Payment Method</h3>
                <div class="payment-method">
                    <label>
                        <input type="radio" name="payment_method" value="ikhokha" checked>
                        iKhokha Payment
                        <img src="images/ikhokha-logo.png" alt="iKhokha">
                    </label>
                </div>
                <p>You'll be redirected to iKhokha secure payment page to complete your payment.</p>
            </div>

            <!-- Payment Button -->
            <button id="pay-button" class="cta-button" <?php echo ($total <= 0) ? 'disabled' : ''; ?>>
                Pay Now
            </button>

            <p>By proceeding, you agree to our <a href="terms.php">Terms and Conditions</a></p>
        </div>
    </section>

    <!-- Payment Processing Overlay -->
    <div class="payment-overlay" id="payment-overlay">
        <div class="payment-modal">
            <div class="spinner"></div>
            <h3>Processing Payment</h3>
            <p>Please do not close this window...</p>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Platedbox. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const nav = document.querySelector('nav');

        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
            if (nav.classList.contains('active')) {
                menuToggle.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });

        // Close menu when clicking on a link
        const navLinks = document.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (nav.classList.contains('active')) {
                    nav.classList.remove('active');
                    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        });
        // iKhokha Payment Integration
        document.getElementById('pay-button').addEventListener('click', function() {
            // Check if the button is disabled
            if (this.hasAttribute('disabled')) {
                alert('Your cart is empty. Please add items to proceed with checkout.');
                return;
            }

            // Show payment processing overlay
            document.getElementById('payment-overlay').style.display = 'flex';

            // Get the base URL by removing "checkout.php" from the current URL
            const currentPath = window.location.pathname;
            const basePath = currentPath.substring(0, currentPath.lastIndexOf('/') + 1);

            // Prepare payment data with corrected URLs
            const paymentData = {
                amount: <?php echo isset($total) ? $total : 0; ?>,
                reference: 'ORDER-' + Math.floor(Math.random() * 1000000),
                redirect_url: window.location.origin + basePath + 'order_confirmation.php',
                cancel_url: window.location.origin + basePath + 'cart.php'
            };

            console.log('Payment Data:', paymentData); // For debugging

            // Make AJAX request to your backend to initiate iKhokha payment
            fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(paymentData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response:', data); // For debugging

                    if (data.success && data.redirect_url) {
                        // Redirect to payment page
                        window.location.href = data.redirect_url;
                    } else {
                        alert('Payment initialization failed: ' + data.message);
                        document.getElementById('payment-overlay').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your payment. Please try again.');
                    document.getElementById('payment-overlay').style.display = 'none';
                });
        });
    </script>
</body>

</html>