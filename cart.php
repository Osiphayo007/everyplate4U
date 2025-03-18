<?php
session_start();

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle increase, decrease, remove, and empty cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'empty_cart') {
            // Empty the entire cart
            $_SESSION['cart'] = [];
        } else if (isset($_POST['product_id'])) {
            $product_id = $_POST['product_id'];

            // Find the product in the cart
            $found = false;
            foreach ($_SESSION['cart'] as $key => &$item) {
                // Convert both values to the same type (string) for comparison
                if ((string)$item['id'] === (string)$product_id) {
                    $found = true;
                    switch ($action) {
                        case 'increase':
                            $item['quantity'] += 1;
                            break;
                        case 'decrease':
                            if ($item['quantity'] > 1) {
                                $item['quantity'] -= 1;
                            }
                            break;
                        case 'remove':
                            // Remove item directly
                            unset($_SESSION['cart'][$key]);
                            break;
                    }
                    break;
                }
            }

            // Only reindex the array if we made changes
            if ($found && $action !== 'increase' && $action !== 'decrease') {
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
        }
    }

    // Handle delivery details submission
    if (isset($_POST['save_delivery_details'])) {
        require_once 'config.php';
        
        $user_id = $_SESSION['user_id'];
        $building_name = $_POST['building_name'];
        $delivery_address = $_POST['delivery_address'];
        $cellphone = $_POST['cellphone'];

        // Check if delivery details already exist
        $stmt = $conn->prepare("SELECT id FROM delivery_details WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update existing details
            $stmt = $conn->prepare("UPDATE delivery_details SET building_name = ?, delivery_address = ?, cellphone = ? WHERE user_id = ?");
            $stmt->execute([$building_name, $delivery_address, $cellphone, $user_id]);
        } else {
            // Insert new details
            $stmt = $conn->prepare("INSERT INTO delivery_details (user_id, building_name, delivery_address, cellphone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $building_name, $delivery_address, $cellphone]);
        }

        $_SESSION['delivery_details_saved'] = true;
    }

    // Redirect back to the cart page to prevent form resubmission
    header('Location: cart.php');
    exit();
}

// Fetch delivery details if they exist
$delivery_details = null;
if (isset($_SESSION['user_id'])) {
    require_once 'config.php';
    $stmt = $conn->prepare("SELECT * FROM delivery_details WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $delivery_details = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>everyplate4U - Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap');

        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }

        :root {
            --primary-green: #2E7D32;
            --light-green: #4CAF50;
            --dark-green: #1B5E20;
            --accent-green: #81C784;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('hero-bg3.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .section-tile {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-btn {
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-btn {
            background: white;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }

        .cart-btn:hover {
            background: var(--primary-green);
            color: white;
        }

        .logout-btn {
            background: var(--primary-green);
            color: white;
        }

        .logout-btn:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
        }

        .cart-count {
            background: var(--primary-green);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .cart-item {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(46, 125, 50, 0.1);
        }

        .remove-btn {
            color: #dc2626;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            color: #b91c1c;
            transform: scale(1.1);
        }

        .checkout-btn {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }

        .empty-cart {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="font-poppins">
    <header class="bg-white/90 backdrop-blur-sm shadow-sm fixed w-full z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="font-playfair text-3xl font-bold text-[#2E7D32]">everyplate4U</h1>
                <div class="flex items-center gap-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="cart.php" class="header-btn cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                            Cart
                            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="logout.php" class="header-btn logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-20">
        <section class="section-tile">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="font-playfair text-4xl text-gray-800 mb-4">Your Cart</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Review and manage your selected meal packages.</p>
                </div>

                <?php if(empty($_SESSION['cart'])): ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
                        <h3 class="font-playfair text-2xl text-gray-800 mb-4">Your cart is empty</h3>
                        <p class="text-gray-600 mb-8">Browse our menu to add delicious meals to your cart.</p>
                        <a href="catalogue.php" class="checkout-btn inline-block">
                            View Menu
                        </a>
                </div>
            <?php else: ?>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                    <?php
                            require_once 'config.php';
                    $total = 0;
                            foreach($_SESSION['cart'] as $item):
                                $product_id = $item['id'];
                                $quantity = $item['quantity'];
                                
                                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                                $stmt->execute([$product_id]);
                                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                // Skip if product not found
                                if (!$product) {
                                    continue;
                                }
                                
                                $subtotal = floatval($product['price']) * intval($quantity);
                        $total += $subtotal;
                    ?>
                        <div class="cart-item">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-playfair text-2xl text-gray-800 mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center gap-2">
                                                    <form action="cart.php" method="POST" class="inline">
                                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="hidden" name="action" value="decrease">
                                                        <button type="submit" class="text-gray-600 hover:text-[#2E7D32]">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                </form>
                                                    <span class="text-gray-600"><?php echo $quantity; ?></span>
                                                    <form action="cart.php" method="POST" class="inline">
                                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="hidden" name="action" value="increase">
                                                        <button type="submit" class="text-gray-600 hover:text-[#2E7D32]">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                </form>
                                                </div>
                                                <span class="text-2xl font-bold text-[#2E7D32]">R<?php echo number_format($subtotal, 2); ?></span>
                                            </div>
                                        </div>
                                        <form action="cart.php" method="POST" class="flex items-center">
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="hidden" name="action" value="remove">
                                            <button type="submit" class="remove-btn text-xl">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Delivery Details Form -->
                            <div class="bg-white rounded-xl p-6 shadow-lg mt-8">
                                <h3 class="font-playfair text-2xl text-gray-800 mb-4">Delivery Details</h3>
                                <form action="cart.php" method="POST" class="space-y-4">
                                    <div>
                                        <label class="block text-gray-700 mb-2">Building Name</label>
                                        <input type="text" name="building_name" required
                                            value="<?php echo htmlspecialchars($delivery_details['building_name'] ?? ''); ?>"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#2E7D32]">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 mb-2">Delivery Address</label>
                                        <textarea name="delivery_address" required rows="3"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#2E7D32]"><?php echo htmlspecialchars($delivery_details['delivery_address'] ?? ''); ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 mb-2">Cellphone Number</label>
                                        <input type="tel" name="cellphone" required
                                            value="<?php echo htmlspecialchars($delivery_details['cellphone'] ?? ''); ?>"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#2E7D32]">
                                    </div>
                                    <button type="submit" name="save_delivery_details" class="checkout-btn w-full">
                                        Save Delivery Details
                                    </button>
                                </form>
                            </div>
                            </div>

                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-xl p-6 shadow-lg">
                                <h3 class="font-playfair text-2xl text-gray-800 mb-4">Order Summary</h3>
                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal</span>
                                        <span>R<?php echo number_format($total, 2); ?></span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Delivery Fee</span>
                                        <span>R0.00</span>
                                    </div>
                                    <div class="border-t pt-4">
                                        <div class="flex justify-between text-xl font-bold text-[#2E7D32]">
                                            <span>Total</span>
                                            <span>R<?php echo number_format($total, 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($delivery_details): ?>
                                    <form action="checkout.php" method="POST">
                                        <button type="submit" class="checkout-btn w-full">
                                            Proceed to Checkout
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="text-center text-gray-600">
                                        Please fill in your delivery details to proceed to checkout.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900/90 backdrop-blur-sm text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div>
                    <h3 class="font-playfair text-2xl text-[#4CAF50] mb-6">About Us</h3>
                    <p class="text-gray-400">everyplate4U is your go-to solution for delicious, nutritious meals delivered right to your doorstep.</p>
                </div>
                <div>
                    <h3 class="font-playfair text-2xl text-[#4CAF50] mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="landingpage.php" class="text-gray-400 hover:text-[#4CAF50] transition-colors">Home</a></li>
                        <li><a href="catalogue.php" class="text-gray-400 hover:text-[#4CAF50] transition-colors">Menu</a></li>
                        <li><a href="cart.php" class="text-gray-400 hover:text-[#4CAF50] transition-colors">Cart</a></li>
                        <li><a href="contact.php" class="text-gray-400 hover:text-[#4CAF50] transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-playfair text-2xl text-[#4CAF50] mb-6">Contact Us</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3"></i>
                            +27 123 456 789
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            info@everyplate4u.com
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            Cape Town, South Africa
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-playfair text-2xl text-[#4CAF50] mb-6">Follow Us</h3>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-[#4CAF50] transition-colors text-2xl">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#4CAF50] transition-colors text-2xl">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#4CAF50] transition-colors text-2xl">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
        </div>
        </div>
    </footer>
</body>

</html>