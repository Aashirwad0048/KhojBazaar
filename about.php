<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - KhojBazaar</title>
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
        .main-container {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('imgae.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            padding: 2rem;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.9);
        }
        .feature-card:hover h3,
        .feature-card:hover p {
            color: white;
        }
        .beau-rivage-regular {
            font-family: "Beau Rivage", cursive;
            font-weight: 400;
            font-style: normal;
        }
        .amita-regular {
            font-family: "Amita", serif;
            font-weight: 400;
            font-style: normal;
        }
        .above-beyond {
            font-family: "Above the Beyond Script", cursive;
            font-weight: 400;
            font-style: normal;
        }
        .satisfy-regular {
            font-family: "Satisfy", cursive;
            font-weight: 400;
            font-style: normal;
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
                        <!-- <a href="about.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">About</a> -->
                        <a href="contact.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Contact</a>
                        <a href="privacy.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Privacy Policy</a>
                        <a href="login.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Login</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- About Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-6xl font-bold text-center mb-12">About KhojBazaar</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-16">
                <div class="feature-card rounded-lg">
                    <h2 class="text-3xl font-semibold mb-6">Our Mission</h2>
                    <p class="text-gray-200 text-lg">
                        KhojBazaar is dedicated to connecting local businesses with their communities. 
                        We provide a platform where businesses can showcase their services and customers 
                        can easily find what they need in their local area.
                    </p>
                </div>
                
                <div class="feature-card rounded-lg">
                    <h2 class="text-3xl font-semibold mb-6">Key Features</h2>
                    <ul class="list-disc list-inside text-gray-200 space-y-3 text-lg">
                        <li>Easy business registration and profile management</li>
                        <li>Advanced search functionality</li>
                        <li>Secure user authentication</li>
                        <li>Real-time business updates</li>
                        <li>Interactive business listings</li>
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="feature-card rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4">For Businesses</h3>
                    <p class="text-gray-200 text-lg">
                        Create your business profile, manage your information, 
                        and connect with potential customers in your area.
                    </p>
                </div>

                <div class="feature-card rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4">For Customers</h3>
                    <p class="text-gray-200 text-lg">
                        Find local businesses easily, read reviews, 
                        and get the services you need in your community.
                    </p>
                </div>

                <div class="feature-card rounded-lg">
                    <h3 class="text-2xl font-semibold mb-4">For Communities</h3>
                    <p class="text-gray-200 text-lg">
                        Support local businesses and help your community 
                        thrive through our platform.
                    </p>
                </div>
            </div>

            <div class="mt-12">
                <div class="mb-4 md:mb-0">
                    <span class="text-2xl text-white satisfy-regular" style="font-weight: 300;">KhojBazaar</span>
                    <p class="text-gray-300 mt-2">Connecting local businesses with their community</p>
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
