<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - KhojBazaar</title>
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
        .privacy-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            padding: 2rem;
        }
        .privacy-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.9);
        }
        .privacy-card:hover h3,
        .privacy-card:hover p,
        .privacy-card:hover li {
            color: white;
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
                        <a href="about.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">About</a>
                        <a href="contact.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Contact</a>
                        <!-- <a href="privacy.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Privacy Policy</a> -->
                        <a href="login.php" class="text-white hover:text-sky-300 transition-colors duration-300 text-xl">Login</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Privacy Policy Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-6xl font-bold text-center mb-12">Privacy Policy</h1>
            
            <div class="privacy-card rounded-lg space-y-8">
                <section>
                    <h2 class="text-3xl font-semibold mb-6">Information We Collect</h2>
                    <p class="text-gray-200 text-lg">
                        We collect information that you provide directly to us, including:
                    </p>
                    <ul class="list-disc list-inside text-gray-200 space-y-3 text-lg mt-4">
                        <li>Name and contact information</li>
                        <li>Business details and location</li>
                        <li>Account credentials</li>
                        <li>Communication preferences</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-3xl font-semibold mb-6">How We Use Your Information</h2>
                    <p class="text-gray-200 text-lg">
                        We use the collected information to:
                    </p>
                    <ul class="list-disc list-inside text-gray-200 space-y-3 text-lg mt-4">
                        <li>Provide and maintain our services</li>
                        <li>Improve user experience</li>
                        <li>Send important updates and notifications</li>
                        <li>Respond to your inquiries</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-3xl font-semibold mb-6">Data Security</h2>
                    <p class="text-gray-200 text-lg">
                        We implement appropriate security measures to protect your personal information 
                        from unauthorized access, alteration, disclosure, or destruction.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-semibold mb-6">Your Rights</h2>
                    <p class="text-gray-200 text-lg">
                        You have the right to:
                    </p>
                    <ul class="list-disc list-inside text-gray-200 space-y-3 text-lg mt-4">
                        <li>Access your personal information</li>
                        <li>Request correction of your data</li>
                        <li>Request deletion of your data</li>
                        <li>Opt-out of marketing communications</li>
                    </ul>
                </section>
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
