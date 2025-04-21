<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - KhojBazaar</title>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&family=Noto+Sans+Devanagari:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Noto Sans Devanagari', sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }
        h1, h2, h3, p {
            font-family: 'Noto Sans Devanagari', sans-serif;
        }
        .hindi-text {
            font-family: 'Noto Sans Devanagari', 'Mangal', 'Devanagari', sans-serif;
        }
        .satisfy-regular {
            font-family: "Satisfy", cursive;
            font-weight: 400;
            font-style: normal;
        }
        .main-container {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('imgae.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }
        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            padding: 2rem;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.9);
        }
        .contact-card:hover h3,
        .contact-card:hover p,
        .contact-card:hover a {
            color: white;
        }
        .email-link {
            color: #3b82f6;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .email-link:hover {
            color: #60a5fa;
            text-decoration: underline;
        }
        @keyframes jump {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        .jumping-text {
            animation: jump 2s ease-in-out infinite;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Navigation -->
        <nav class="bg-transparent py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-6xl text-white satisfy-regular jumping-text" style="font-weight: 300;">KhojBazaar</span>
                    </div>
                    <div class="flex space-x-6">
                        <a href="index.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Home</a>
                        <a href="about.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">About</a>
                        <a href="privacy.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Privacy Policy</a>
                        <a href="login.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Login</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contact Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-6xl font-bold text-center mb-12">Creators</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="contact-card rounded-lg">
                    <h2 class="text-3xl font-semibold mb-4">Hardik Saini</h2>
                    <p class="text-gray-200 text-lg mb-2">K23RK 12321767</p>
                    <a href="mailto:sainihardik371@gmail.com" class="email-link text-xl">
                        sainihardik371@gmail.com
                    </a>
                </div>

                <div class="contact-card rounded-lg">
                    <h2 class="text-3xl font-semibold mb-4">Vaibhav Kumar</h2>
                    <p class="text-gray-200 text-lg mb-2">K23RK 12310016</p>
                    <a href="mailto:vaibjais123456@gmail.com" class="email-link text-xl">
                        vaibjais123456@gmail.com
                    </a>
                </div>

                <div class="contact-card rounded-lg">
                    <h2 class="text-3xl font-semibold mb-4">Swayam Mehta</h2>
                    <p class="text-gray-200 text-lg mb-2">K23RK 12313788</p>
                    <a href="mailto:swayamehta@gmail.com" class="email-link text-xl">
                        swayamehta@gmail.com
                    </a>
                </div>

                <div class="contact-card rounded-lg">
                    <h2 class="text-3xl font-semibold mb-4">Aashirwad Singh</h2>
                    <p class="text-gray-200 text-lg mb-2">K23RK 12304534</p>
                    <a href="mailto:anonymous.hello6096@gmail.com" class="email-link text-xl">
                    anonymous.hello6096@gmail.com
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-transparent py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-300 text-xl">
                    <p>&copy; 2025 KhojBazaar. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
