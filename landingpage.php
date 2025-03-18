<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platedbox - Home</title>
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
            margin: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--light-green);
        }

        .header-btn {
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn {
            background: white;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }

        .nav-btn:hover {
            background: var(--primary-green);
            color: white;
        }

        .cta-btn {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--accent-green);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border-color: var(--primary-green);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--light-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }

        header.transparent {
            background: transparent;
        }

        header.transparent .nav-btn {
            color: var(--primary-green);
            border-color: var(--primary-green);
        }

        header.transparent .nav-btn:hover {
            background: var(--primary-green);
            color: white;
        }

        header.transparent h1 {
            color: white;
        }
    </style>
</head>

<body class="font-poppins">
    <header class="bg-white/90 backdrop-blur-sm shadow-sm fixed w-full z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="font-playfair text-3xl font-bold text-[#2E7D32]">everyplate4U</h1>
                <div class="flex items-center gap-4">
                    <a href="catalogue.php" class="header-btn nav-btn">
                        <i class="fas fa-utensils"></i>
                        Menu
                    </a>
                    <a href="login.php" class="header-btn nav-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                    <a href="register.php" class="header-btn nav-btn">
                        <i class="fas fa-user-plus"></i>
                        Register
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-20">
        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center text-white text-center px-4">
            <div class="max-w-4xl">
                <h1 class="font-playfair text-5xl md:text-6xl mb-6">Delicious Meals Delivered to Your Door</h1>
                <p class="text-xl mb-8">Experience the convenience of fresh, healthy meals prepared by expert chefs and delivered right to your doorstep.</p>
                <a href="catalogue.php" class="cta-btn inline-block py-3 px-8 text-lg">
                    View Our Menu
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="font-playfair text-4xl text-white mb-4">Why Choose everyplate4U?</h2>
                    <p class="text-white text-lg">We make healthy eating convenient and delicious</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="font-playfair text-2xl text-gray-800 mb-4">Fresh Ingredients</h3>
                        <p class="text-gray-600">We use only the freshest, highest quality ingredients in every meal.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3 class="font-playfair text-2xl text-gray-800 mb-4">Fast Delivery</h3>
                        <p class="text-gray-600">Hot meals delivered to your doorstep within 30 minutes.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="font-playfair text-2xl text-gray-800 mb-4">Healthy Options</h3>
                        <p class="text-gray-600">Nutritious meals designed by professional nutritionists.</p>
                    </div>
        </div>
        </div>
    </section>

        <!-- CTA Section -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="section-tile text-center">
                    <h2 class="font-playfair text-4xl text-gray-800 mb-6">Ready to Start Your Culinary Journey?</h2>
                    <p class="text-gray-600 mb-8">Join thousands of satisfied customers enjoying delicious meals at their convenience.</p>
                    <div class="flex justify-center gap-4">
                        <a href="register.php" class="cta-btn py-3 px-8">
                            Get Started
                        </a>
                        <a href="catalogue.php" class="header-btn nav-btn py-3 px-8">
                            View Menu
                        </a>
                    </div>
                </div>
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
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.remove('transparent');
            } else {
                header.classList.add('transparent');
            }
        });
    </script>
</body>

</html>