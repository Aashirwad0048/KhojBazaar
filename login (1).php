<?php
session_start();

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';

// Create connections
$usersConn = new mysqli($host, $user, $pass, 'busweb_users');
$adminsConn = new mysqli($host, $user, $pass, 'busweb_admins');

if ($usersConn->connect_error) {
    die("Users database connection failed: " . $usersConn->connect_error);
}
if ($adminsConn->connect_error) {
    die("Admins database connection failed: " . $adminsConn->connect_error);
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $userType = $_POST['userType'] ?? '';

    // Validate input
    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($userType)) {
        $errors[] = "Please select user type";
    }

    if (empty($errors)) {
        try {
            // Check if user exists in appropriate database
            if ($userType === 'admin') {
                $stmt = $adminsConn->prepare("SELECT id, password FROM admins WHERE email = ?");
            } else {
                $stmt = $usersConn->prepare("SELECT id, password FROM users WHERE email = ?");
            }
            
            if (!$stmt) {
                throw new Exception("Database error: " . ($userType === 'admin' ? $adminsConn->error : $usersConn->error));
            }
            
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Login successful
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $userType;
                    
                    // Set redirect based on user type
                    $redirect = $userType === 'admin' ? 'sell.php' : 'dashboard.php';
                    
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Login successful!',
                        'redirect' => $redirect
                    ]);
                    exit;
                } else {
                    $errors[] = "Invalid password";
                }
            } else {
                $errors[] = "Email not found in " . ($userType === 'admin' ? 'admin' : 'user') . " database";
            }
            $stmt->close();
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KhojBazaar - Login</title>
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

    .satisfy-regular {
        font-family: "Satisfy", cursive;
        font-weight: 400;
        font-style: normal;
    }

    .main-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .background-image video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.9;
    }

    .content-wrapper {
        width: 100%;
        max-width: 600px;
        min-height: 600px;
        text-align: center;
        padding: 4rem;
        background: rgba(128, 128, 128, 0.3);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .user-type-option {
        transition: all 0.3s ease-in-out;
        cursor: pointer;
        background-color: #f3f4f6; /* Tailwind's gray-100 */
        color: #1f2937; /* Tailwind's gray-800 */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transform: scale(1);
        border: 2px solid transparent;
    }

    .user-type-option:hover {
        transform: scale(1.05);
        background-color: #000;
        color: #fff;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .user-type-option.selected {
        border-color: #0ea5e9; /* sky-500 */
        background-color: rgba(14, 165, 233, 0.1);
        transform: scale(1.05);
    }
</style>

</head>
<body>
    <div class="main-container">
        <div class="background-image">
            <video autoplay muted loop playsinline class="w-full h-full object-cover opacity-90">
                <source src="login.mp4" type="video/mp4">
            </video>
        </div>
        <div class="content-wrapper">
            <h1 class="text-4xl font-bold mb-8">Login</h1>
            <div class="flex justify-center mb-8">
                <span class="text-6xl text-black satisfy-regular jumping-text" style="font-weight: 300;">KhojBazaar</span>
            </div>
            <form id="loginForm" class="space-y-4">
                <div id="userTypeSelection" class="space-y-4">
                    <h2 class="text-xl font-semibold mb-4">Select User Type</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="user-type-option p-4 border-2 border-gray-300 rounded-lg" data-type="user">
                            <div class="text-4xl mb-2">👤</div>
                            <h3 class="font-semibold">User</h3>
                            <p class="text-sm text-gray-600">Regular user account</p>
                        </div>
                        <div class="user-type-option p-4 border-2 border-gray-300 rounded-lg" data-type="admin">
                            <div class="text-4xl mb-2">👨‍💼</div>
                            <h3 class="font-semibold">Admin</h3>
                            <p class="text-sm text-gray-600">Business administrator</p>
                        </div>
                    </div>
                    <input type="hidden" name="userType" id="userTypeInput">
                </div>
                <div id="loginFormFields" class="hidden space-y-4">
                    <div>
                        <input type="email" id="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>
                        <div class="text-right mt-2">
                            <a href="forgot_password.php" class="text-sm text-sky-500 hover:text-sky-600">Forgot Password?</a>
                        </div>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg shadow-md hover:bg-gray-800 transition-colors duration-300">
                        Login
                    </button>
                </div>
            </form>
            <p class="mt-4">
                Don't have an account? <a href="register.php" class="text-sky-500 hover:text-sky-600">Register</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeOptions = document.querySelectorAll('.user-type-option');
            const userTypeInput = document.getElementById('userTypeInput');
            const userTypeSelection = document.getElementById('userTypeSelection');
            const loginFormFields = document.getElementById('loginFormFields');
            const emailInput = document.getElementById('email');
            const loginForm = document.getElementById('loginForm');

            // User type selection
            userTypeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    userTypeOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    userTypeInput.value = this.dataset.type;
                    userTypeSelection.classList.add('hidden');
                    loginFormFields.classList.remove('hidden');
                });
            });

            // Form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!userTypeInput.value) {
                    alert('Please select a user type');
                    return;
                }

                const formData = new FormData(this);
                fetch('login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    </script>
</body>
</html> 