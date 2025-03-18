<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platedbox - Our Menu</title>
    <script src="https://unpkg.com/framer-motion@10.16.4/dist/framer-motion.js"></script>
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

        .menu-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            display: flex;
            flex-direction: column;
        }

        .menu-item-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-item-footer {
            margin-top: auto;
            padding-top: 20px;
        }

        .menu-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(46, 125, 50, 0.1);
        }

        .category-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .category-btn:hover {
            transform: translateY(-2px);
        }

        .category-btn.active {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
            color: white;
        }

        .menu-item-image {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item:hover .menu-item-image {
            transform: scale(1.05);
        }

        .add-to-cart-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-green);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .weekly-schedule {
            background: linear-gradient(135deg, rgba(46, 125, 50, 0.1), rgba(76, 175, 80, 0.1));
        }

        .day-card {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .day-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.1);
        }

        .meal-images {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
        }

        .meal-image {
            height: 200px;
            object-fit: cover;
        }

        .best-value-banner {
            position: absolute;
            top: 20px;
            right: -35px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            padding: 8px 40px;
            transform: rotate(45deg);
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 1;
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
        <!-- Weekly Schedule Section -->
        <section class="section-tile">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="font-playfair text-4xl text-gray-800 mb-4">Weekly Meal Schedule</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Discover our carefully planned weekly menu, featuring fresh and nutritious meals for every day of the week.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    $days = [
                        'Monday' => [
                            'lunch' => 'Grilled Chicken with Roasted Vegetables',
                            'dinner' => 'Beef Tenderloin with Mashed Potatoes',
                            'next_lunch' => 'Mediterranean Grilled Fish',
                            'next_dinner' => 'Lamb Chops with Mint Sauce'
                        ],
                        'Tuesday' => [
                            'lunch' => 'Salmon with Quinoa and Asparagus',
                            'dinner' => 'Lamb Shank with Mediterranean Vegetables',
                            'next_lunch' => 'Chicken Caesar Salad',
                            'next_dinner' => 'Beef Wellington'
                        ],
                        'Wednesday' => [
                            'lunch' => 'Beef Stir-Fry with Brown Rice',
                            'dinner' => 'Grilled Sea Bass with Lemon Butter Sauce',
                            'next_lunch' => 'Vegetable Curry with Rice',
                            'next_dinner' => 'Duck Breast with Orange Sauce'
                        ],
                        'Thursday' => [
                            'lunch' => 'Vegetarian Buddha Bowl',
                            'dinner' => 'Chicken Marsala with Pasta',
                            'next_lunch' => 'Tuna Nicoise Salad',
                            'next_dinner' => 'Pork Tenderloin with Apple Sauce'
                        ],
                        'Friday' => [
                            'lunch' => 'Pasta Primavera with Grilled Shrimp',
                            'dinner' => 'Rack of Lamb with Rosemary Potatoes',
                            'next_lunch' => 'Thai Green Curry',
                            'next_dinner' => 'Grilled Lobster Thermidor'
                        ],
                        'Saturday' => [
                            'lunch' => 'Sunday Roast with Mashed Potatoes',
                            'dinner' => 'Grilled Lobster with Garlic Butter',
                            'next_lunch' => 'Beef Tenderloin Salad',
                            'next_dinner' => 'Seafood Paella'
                        ]
                    ];

                    foreach($days as $day => $meals):
                    ?>
                    <div class="day-card rounded-xl overflow-hidden shadow-md">
                        <div class="meal-images">
                            <img src="images/<?php echo strtolower($day); ?>-week1.jpg" 
                                 alt="<?php echo $meals['lunch']; ?>" 
                                 class="meal-image">
                            <img src="images/<?php echo strtolower($day); ?>-week2.jpg" 
                                 alt="<?php echo $meals['next_lunch']; ?>" 
                                 class="meal-image">
                        </div>
                        <div class="p-6">
                            <h3 class="font-playfair text-2xl text-[#2E7D32] mb-4"><?php echo $day; ?></h3>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">This Week</h4>
                                    <p class="text-gray-600">Lunch: <?php echo $meals['lunch']; ?></p>
                                    <p class="text-gray-600">Dinner: <?php echo $meals['dinner']; ?></p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Next Week</h4>
                                    <p class="text-gray-600">Lunch: <?php echo $meals['next_lunch']; ?></p>
                                    <p class="text-gray-600">Dinner: <?php echo $meals['next_dinner']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Menu Section -->
        <section class="section-tile">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="font-playfair text-4xl text-gray-800 mb-4">Our Meal Packages</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Choose from our selection of carefully prepared meals, each designed to provide the perfect balance of nutrition and taste.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    require_once 'config.php';
                    $stmt = $conn->query("SELECT * FROM products WHERE active = 1 LIMIT 3");
                    while($product = $stmt->fetch(PDO::FETCH_ASSOC)):
                        $features = json_decode($product['features'], true);
                        if($product['name'] === 'Monthly Package') {
                            $features = [
                                '20 carefully curated meals (5 meals per week)',
                                'Fresh ingredients',
                                'Regular delivery',
                                'Standard menu access',
                                'Basic meal planning'
                            ];
                        } elseif($product['name'] === 'Monthly VIP Package') {
                            $features = [
                                '40 carefully curated meals (10 meals per week)',
                                'Premium ingredients',
                                'Priority delivery',
                                'Exclusive menu access',
                                'Personalized meal planning',
                                'Nutritional consultation'
                            ];
                        }
                    ?>
                    <div class="menu-item rounded-2xl overflow-hidden shadow-lg">
                        <?php if($product['name'] === 'Monthly Package'): ?>
                            <div class="best-value-banner">Best Value</div>
                        <?php endif; ?>
                        <div class="overflow-hidden">
                            <img src="images/<?php echo strtolower(str_replace(' ', '-', $product['name'])); ?>.jpg" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="menu-item-image w-full h-64 object-cover">
                        </div>
                        <div class="menu-item-content p-6">
                            <h3 class="font-playfair text-2xl text-gray-800 mb-3"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="text-2xl font-bold text-[#2E7D32] mb-4">R<?php echo number_format($product['price'], 2); ?></div>
                            <ul class="space-y-2 mb-6">
                                <?php foreach($features as $feature): ?>
                                <li class="flex items-center text-gray-600">
                                    <i class="fas fa-check text-[#2E7D32] mr-2"></i>
                                    <?php echo htmlspecialchars($feature); ?>
                                </li>
                                <?php endforeach; ?>
                    </ul>
                            <div class="menu-item-footer">
                    <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="add-to-cart-btn w-full py-3 rounded-full text-white font-medium">
                                        Add to Cart
                        </button>
                    </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-gray-900/90 backdrop-blur-sm text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div>
                    <h3 class="font-playfair text-2xl text-[#4CAF50] mb-6">About Us</h3>
                    <p class="text-gray-400">PlatedBox is your go-to solution for delicious, nutritious meals delivered right to your doorstep.</p>
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
                            info@platedbox.com
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

    <script>
        // Framer Motion animations
        const menuItems = document.querySelectorAll('.menu-item, .day-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        menuItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            observer.observe(item);
        });
    </script>
</body>

</html>