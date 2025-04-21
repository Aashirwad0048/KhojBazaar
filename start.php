<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KhojBazaar - Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Quintessential&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
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
            z-index: -1;
            object-fit: cover;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }
        .hero-section {
            position: relative;
            z-index: 1;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .content-wrapper {
            width: 100%;
            text-align: center;
            padding: 2rem;
            background: transparent;
            backdrop-filter: none;
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }
        .letter {
            display: inline-block;
            opacity: 0;
            filter: blur(10px);
            transform: translateX(-20px);
            transition: all 1.5s ease;
            white-space: nowrap;
        }
        .letter.visible {
            opacity: 1;
            filter: blur(0);
            transform: translateX(0);
        }
        .space {
            display: inline-block;
            width: 2rem;
        }
        .lets-go-btn {
            position: relative;
            display: inline-block;
            margin-top: 2rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        .lets-go-btn.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .lets-go-btn button {
            background: linear-gradient(45deg, #4b5563, #6b7280, #9ca3af);
            color: white;
            padding: 1rem 2rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-radius: 1rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        .lets-go-btn button:hover {
            transform: translateY(-8px) scale(1.1);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.4);
            animation: shake 0.5s ease infinite;
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
        .lets-go-btn button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(45deg);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .lets-go-btn button:hover::after {
            opacity: 1;
            animation: shine 1.5s ease infinite;
        }
        @keyframes shine {
            0% {
                transform: rotate(45deg) translateX(-100%);
            }
            100% {
                transform: rotate(45deg) translateX(100%);
            }
        }
        .lets-go-btn button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 0.875rem;
            z-index: 10;
        }
        .fade-in {
            animation: fadeIn 3s ease-in-out;
        }
        .tagline {
            font-size: 1.5rem;
            color: white;
            margin: 2rem 0;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        .tagline.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .quintessential-regular {
            font-family: "Quintessential", serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <video class="video-background" autoplay muted loop playsinline>
            <source src="indianstreet.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="overlay"></div>
        <div class="content-wrapper">
            <div class="text-container">
                <h1 id="welcome" class="text-7xl font-bold mb-4"></h1>
                <h1 id="to" class="text-7xl font-bold mb-4"></h1>
                <h1 id="town-trade" class="text-7xl font-bold mb-4"></h1>
                <p class="tagline">Shop smart. Shop local. Save big.</p>
                <a href="index.php" class="lets-go-btn">
                    <button type="button">
                        Let's Go
                    </button>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const words = {
                welcome: 'WELCOME',
                to: 'TO',
                'town-trade': 'KHOJBAZAAR'
            };

            function createLetters(word, element) {
                const letters = word.split('');
                letters.forEach(letter => {
                    const span = document.createElement('span');
                    span.className = 'letter';
                    span.textContent = letter;
                    element.appendChild(span);
                });
            }

            Object.entries(words).forEach(([id, word]) => {
                const element = document.getElementById(id);
                createLetters(word, element);
            });

            function animateLetters(element, delay) {
                const letters = element.querySelectorAll('.letter');
                letters.forEach((letter, index) => {
                    setTimeout(() => {
                        letter.classList.add('visible');
                    }, delay + (index * 300));
                });
            }

            // Start text animations immediately
            animateLetters(document.getElementById('welcome'), 0);
            animateLetters(document.getElementById('to'), 1000);
            animateLetters(document.getElementById('town-trade'), 2000);

            // Show the tagline after text animations
            setTimeout(() => {
                document.querySelector('.tagline').classList.add('visible');
            }, 2500);

            // Show the button after tagline
            setTimeout(() => {
                document.querySelector('.lets-go-btn').classList.add('visible');
            }, 3000);
        });
    </script>
</body>
</html> 