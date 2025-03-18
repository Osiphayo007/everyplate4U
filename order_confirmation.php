<?php
// Start session
session_start();
include 'config.php';

// Check if we have order information
$order_id = $_GET['order_id'] ?? '';
$has_order = isset($_SESSION['current_order_id']) && isset($_SESSION['order_total']);

// Clear cart after successful payment
if ($has_order && $order_id === $_SESSION['current_order_id']) {
    $order_data = [
        'order_id' => $_SESSION['current_order_id'],
        'amount' => $_SESSION['order_total'],
        'status' => 'COMPLETED',
        'date' => date('Y-m-d H:i:s')
    ];

    // In a real application, you would save this to your database

    // Clear cart after successful payment
    $_SESSION['completed_order'] = $order_data;
    unset($_SESSION['cart']);
    unset($_SESSION['current_order_id']);
    unset($_SESSION['order_total']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - everyplate4U</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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
            text-decoration: none;
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

        .confirmation-box {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 80px auto;
            max-width: 600px;
            text-align: center;
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

        .confirmation-box i {
            color: #2E7D32;
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .confirmation-box h2 {
            margin-bottom: 20px;
            color: #333;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
        }

        .confirmation-box p {
            margin-bottom: 15px;
            color: #666;
        }

        .order-details {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        .order-details p {
            margin-bottom: 10px;
        }

        .order-details strong {
            color: #2E7D32;
        }

        .cta-button {
            display: inline-block;
            background-color: #2E7D32;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
            border: 2px solid #2E7D32;
        }

        .cta-button:hover {
            background-color: #1B5E20;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.3);
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
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

            .confirmation-box {
                padding: 30px 20px;
                margin: 60px auto;
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

    <div class="container">
        <div class="confirmation-box">
            <?php if ($has_order && isset($_SESSION['completed_order'])): ?>
                <i class="fas fa-check-circle"></i>
                <h2>Payment Successful!</h2>
                <p>Thank you for your order. Your payment has been processed successfully.</p>

                <div class="order-details">
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($_SESSION['completed_order']['order_id']); ?></p>
                    <p><strong>Amount:</strong> R<?php echo number_format($_SESSION['completed_order']['amount'], 2); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($_SESSION['completed_order']['date']); ?></p>
                </div>

                <p>We'll send you an email confirmation with order details shortly.</p>
                <a href="catalogue.php" class="cta-button">Continue Shopping</a>
            <?php else: ?>
                <i class="fas fa-exclamation-circle" style="color: #2E7D32;"></i>
                <h2>Order Information Unavailable</h2>
                <p>We couldn't find your order information. If you've completed a payment, please contact customer support.</p>
                <a href="catalogue.php" class="cta-button">Return to Shop</a>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 everyplate4U. All rights reserved.</p>
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
    </script>
</body>

</html>