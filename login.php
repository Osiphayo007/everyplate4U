<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platedbox - Login</title>
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

        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: var(--primary-green);
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        }
    </style>
</head>

<body class="font-poppins">
    <header class="bg-white/90 backdrop-blur-sm shadow-sm fixed w-full z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="font-playfair text-3xl font-bold text-[#2E7D32]">PlatedBox</h1>
            </div>
        </div>
    </header>

    <main class="pt-20">
        <div class="container mx-auto px-4">
            <section class="section-tile">
                <div class="max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h2 class="font-playfair text-4xl text-gray-800 mb-4">Welcome Back</h2>
                        <p class="text-gray-600">Sign in to your account to continue</p>
                    </div>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="authenticate.php" method="POST" class="space-y-6">
                        <div>
                            <label for="email" class="block text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required class="form-input">
                        </div>
                        <div>
                            <label for="password" class="block text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password" required class="form-input">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-[#2E7D32] border-gray-300 rounded">
                                <label for="remember" class="ml-2 text-gray-700">Remember me</label>
                            </div>
                            <a href="#" class="text-[#2E7D32] hover:text-[#1B5E20]">Forgot password?</a>
                        </div>
                        <button type="submit" class="submit-btn w-full py-3 text-white font-medium">
                            Sign In
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-gray-600">Don't have an account? 
                            <a href="register.php" class="text-[#2E7D32] hover:text-[#1B5E20] font-medium">Sign up</a>
                        </p>
                </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="bg-gray-900/90 backdrop-blur-sm text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">&copy; 2024 PlatedBox. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>