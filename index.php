<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KhojBazaar - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&family=Noto+Sans+Devanagari:wght@400;700&family=Amita:wght@400;700&display=swap" rel="stylesheet">
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
        .amita-regular {
            font-family: "Amita", serif;
            font-weight: 400;
            font-style: normal;
        }
        .amita-bold {
            font-family: "Amita", serif;
            font-weight: 700;
            font-style: normal;
        }
        .main-container {
            min-height: 100vh;
            position: relative;
            color: white;
        }
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
            z-index: -1;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://media.istockphoto.com/id/95522458/photo/small-town-u-s-a.webp?a=1&b=1&s=612x612&w=0&k=20&c=WSLjW3pWAf2Xon7c8ZaFAA7aZXw6U-1xL5W99tXpErA=');
            background-size: cover;
            background-position: center;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .feature-card {
            transition: all 0.5s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transform-origin: center;
            transform: scale(1);
        }
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
            border-color: rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.85);
        }
        .feature-card:hover .text-4xl {
            transform: scale(1.2);
            transition: transform 0.5s ease;
        }
        .feature-card:hover h3 {
            color: #ffffff;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
        .feature-card:hover p {
            color: #e5e7eb;
            transition: color 0.3s ease;
        }
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .title-char {
            display: inline-block;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.5s ease;
        }
        .title-char.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s ease forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .btn-hover-effect {
            position: relative;
            overflow: visible;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background-size: 200% 200%;
            background-position: 0% 50%;
            transform-origin: center;
        }
        .btn-hover-effect.login-btn {
            background: linear-gradient(45deg, #3b82f6, #2563eb, #1d4ed8, #3b82f6);
            animation: gradientMove 4s ease infinite;
        }
        .btn-hover-effect.signup-btn {
            background: linear-gradient(45deg, #ffffff, #f3f4f6, #e5e7eb, #ffffff);
            animation: gradientMove 4s ease infinite;
        }
        .btn-hover-effect:hover {
            transform: translateY(-8px) scale(1.1);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.4);
            animation: gradientMove 2s ease infinite, shake 0.5s ease infinite;
            background: #000000 !important;
            color: white !important;
        }
        @keyframes shake {
            0% { transform: translateY(-8px) scale(1.1) rotate(0deg); }
            25% { transform: translateY(-8px) scale(1.1) rotate(-2deg); }
            50% { transform: translateY(-8px) scale(1.1) rotate(2deg); }
            75% { transform: translateY(-8px) scale(1.1) rotate(-2deg); }
            100% { transform: translateY(-8px) scale(1.1) rotate(0deg); }
        }
        .btn-hover-effect::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
            transform: translate(-50%, -50%) scale(1);
            transition: all 0.6s ease-out;
            z-index: 0;
            pointer-events: none;
        }
        .btn-hover-effect:hover::before {
            transform: translate(-50%, -50%) scale(1.5);
            border-color: rgba(255, 255, 255, 0.1);
            width: 150%;
            height: 150%;
        }
        .btn-hover-effect::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            transform: translate(-50%, -50%) scale(1);
            transition: all 0.6s ease-out 0.2s;
            z-index: 0;
            pointer-events: none;
        }
        .btn-hover-effect:hover::after {
            transform: translate(-50%, -50%) scale(1.8);
            border-color: rgba(255, 255, 255, 0.05);
            width: 150%;
            height: 150%;
        }
        .btn-hover-effect span {
            position: relative;
            z-index: 1;
        }
        nav {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-link {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .nav-link::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px 16px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            border-radius: 4px;
            font-size: 0.875rem;
            white-space: nowrap;
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1000;
        }
        .nav-link:hover::after {
            opacity: 1;
            bottom: -40px;
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
        .funnel-display-regular {
            font-family: "Funnel Display", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }
        .funnel-display-bold {
            font-family: "Funnel Display", sans-serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <video class="video-background" autoplay muted loop>
            <source src="indexstreet.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <!-- Navigation -->
        <nav>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-24">
                    <div class="flex items-center">
                        <span class="text-6xl text-white satisfy-regular jumping-text" style="font-weight: 300;">KhojBazaar</span>
                    </div>
                    <div class="flex space-x-6">
                        <a href="about.php" class="nav-link text-white hover:text-sky-300 transition-colors duration-300 text-xl funnel-display-regular" data-tooltip="Learn more about KhojBazaar">About</a>
                        <a href="contact.php" class="nav-link text-white hover:text-sky-300 transition-colors duration-300 text-xl funnel-display-regular" data-tooltip="Get in touch with us">Contact</a>
                        <a href="privacy.php" class="nav-link text-white hover:text-sky-300 transition-colors duration-300 text-xl funnel-display-regular" data-tooltip="Read our privacy policy">Privacy Policy</a>
                        <a href="login.php" class="nav-link text-white hover:text-sky-300 transition-colors duration-300 text-xl funnel-display-regular" data-tooltip="Access your account">Login</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-8 fade-in-up funnel-display-bold" style="animation-delay: 0.5s">Welcome to <span class="satisfy-regular jumping-text">KhojBazaar</span></h1>
                <p class="text-xl mb-12 fade-in-up funnel-display-regular" style="animation-delay: 1s"></p>
                <div class="fade-in-up" style="animation-delay: 1.5s">
                    <div class="flex space-x-8 justify-center">
                        <a href="login.php" class="btn-hover-effect login-btn text-white px-12 py-6 rounded-2xl flex items-center justify-center font-semibold transition-colors duration-300 text-lg funnel-display-regular" data-tooltip="Access your account">
                            <span>Login</span>
                        </a>
                        <a href="register.php" class="btn-hover-effect signup-btn text-sky-500 px-12 py-6 rounded-2xl flex items-center justify-center font-semibold transition-colors duration-300 text-lg funnel-display-regular" data-tooltip="Create a new account">
                            <span>Sign Up</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center mb-12 fade-in">Our Features</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="feature-card p-6 rounded-lg">
                        <div class="text-white text-4xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold mb-2 text-white">Easy Search</h3>
                        <p class="text-gray-200">Discover amazing local deals around you.<br>
                        From Restaurants , groceries ,toolkits , sports to fashion — explore, save, and support nearby businesses effortlessly.</p>
                    </div>
                    <div class="feature-card p-6 rounded-lg">
                        <div class="text-white text-4xl mb-4">💼</div>
                        <h3 class="text-xl font-semibold mb-2 text-white">Business Profiles</h3>
                        <p class="text-gray-200">Grow your reach by showcasing your offers to nearby customers.<br>
                        Easily promote your business, boost footfall, and get noticed in your local area.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-transparent py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <span class="text-2xl text-white satisfy-regular" style="font-weight: 300;">KhojBazaar</span>
                        <p class="text-gray-300 mt-2">Connecting local businesses with their community</p>
                    </div>
                </div>
                <div class="mt-8 text-center text-gray-300">
                    <p>&copy; 2025 KhojBazaar. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate fade-in elements
            const fadeElements = document.querySelectorAll('.fade-in');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            fadeElements.forEach(element => {
                observer.observe(element);
            });

            // Animate feature cards
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach((card, index) => {
                card.style.transitionDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
                observer.observe(card);
            });
        });
    </script>
</body>
</html> 