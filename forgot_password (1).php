<?php
session_start();

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';

// Create connections
$usersConn = new mysqli($host, $user, $pass, 'busweb_users');
$adminsConn = new mysqli($host, $user, $pass, 'busweb_admins');

if ($usersConn->connect_error || $adminsConn->connect_error) {
    die("Connection failed: " . ($usersConn->connect_error ?? $adminsConn->connect_error));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $otp = $_POST['otp'] ?? null;
    $newPassword = $_POST['newPassword'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;
    $userType = $_POST['userType'] ?? '';

    // Validate email
    if (empty($email)) {
        $error = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // Check if email exists in appropriate database
        if ($userType === 'admin') {
            $stmt = $adminsConn->prepare("SELECT id FROM admins WHERE email = ?");
        } else {
            $stmt = $usersConn->prepare("SELECT id FROM users WHERE email = ?");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "Email not found";
        } else {
            // If OTP is not set, send OTP
            if (!isset($otp)) {
                // Generate 6-digit OTP
                $otp = rand(100000, 999999);
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_user_type'] = $userType;
                $_SESSION['otp_expiry'] = time() + 300; // 5 minutes expiry

                // Send OTP via email
                $headers = "From: sainihardik371@gmail.com\r\n";
                $headers .= "Reply-To: sainihardik371@gmail.com\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                $message = "Your password reset OTP is: $otp\nThis OTP will expire in 5 minutes.";
                
                // Enable error reporting for mail function
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                
                // Try to send email
                $mailSent = mail($email, "Password Reset OTP", $message, $headers);
                
                if ($mailSent) {
                    $success = "OTP has been sent to your email";
                } else {
                    // Get the last error
                    $error = "Failed to send OTP. Error: " . error_get_last()['message'];
                    
                    // For testing purposes, you can use this to see the OTP
                    $success = "OTP: $otp (Email sending failed, but here's the OTP for testing)";
                }
            } 
            // If OTP is set, verify it and update password
            else {
                if ($otp == $_SESSION['reset_otp'] && $email == $_SESSION['reset_email'] && $userType == $_SESSION['reset_user_type'] && time() < $_SESSION['otp_expiry']) {
                    if (empty($newPassword)) {
                        $error = "New password is required";
                    } elseif (strlen($newPassword) < 8) {
                        $error = "Password must be at least 8 characters long";
                    } elseif (!preg_match('/[A-Z]/', $newPassword)) {
                        $error = "Password must contain at least one capital letter";
                    } elseif (!preg_match('/[0-9]/', $newPassword)) {
                        $error = "Password must contain at least one number";
                    } elseif (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
                        $error = "Password must contain at least one special character";
                    } elseif ($newPassword !== $confirmPassword) {
                        $error = "Passwords do not match";
                    } else {
                        // Update password in appropriate database
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                        if ($userType === 'admin') {
                            $updateStmt = $adminsConn->prepare("UPDATE admins SET password = ? WHERE email = ?");
                        } else {
                            $updateStmt = $usersConn->prepare("UPDATE users SET password = ? WHERE email = ?");
                        }
                        $updateStmt->bind_param("ss", $hashedPassword, $email);
                        
                        if ($updateStmt->execute()) {
                            // Clear session variables
                            unset($_SESSION['reset_otp']);
                            unset($_SESSION['reset_email']);
                            unset($_SESSION['reset_user_type']);
                            unset($_SESSION['otp_expiry']);
                            
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true,
                                'message' => 'Password updated successfully!',
                                'redirect' => 'login.php'
                            ]);
                            exit;
                        } else {
                            $error = "Failed to update password";
                        }
                    }
                } else {
                    $error = "Invalid or expired OTP";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            background: rgba(128, 128, 128, 0.2);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
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
            <h1 class="text-4xl font-bold mb-8">Reset Password</h1>
            <?php if (isset($error)): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            <div class="flex justify-center mb-8">
                <span class="text-6xl text-black satisfy-regular jumping-text" style="font-weight: 300;">KhojBazaar</span>
            </div>
            <form id="resetForm" class="space-y-4">
                <div>
                    <input type="email" id="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                </div>
                <div id="loadingMessage" class="hidden text-center text-blue-600 mb-4">
                    Sending OTP to your email...
                </div>
                <button type="button" id="sendOtpBtn" class="w-full px-6 py-3 bg-sky-500 text-white font-semibold rounded-lg shadow-md hover:bg-sky-600 transition-colors duration-300">
                    Send OTP
                </button>
                <div id="otpSection" class="hidden">
                    <div>
                        <input type="text" id="otp" name="otp" placeholder="Enter OTP" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                    </div>
                    <div>
                        <input type="password" id="newPassword" name="newPassword" placeholder="New Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                    </div>
                    <div>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg shadow-md hover:bg-gray-800 transition-colors duration-300">
                        Reset Password
                    </button>
                </div>
            </form>
            <p class="mt-4">
                Remember your password? <a href="login.php" class="text-sky-500 hover:text-sky-600">Login</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetForm');
            const emailInput = document.getElementById('email');
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const otpSection = document.getElementById('otpSection');
            const otpInput = document.getElementById('otp');
            const newPasswordInput = document.getElementById('newPassword');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const loadingMessage = document.getElementById('loadingMessage');

            // Handle Send OTP button click
            sendOtpBtn.addEventListener('click', function() {
                const email = emailInput.value;
                
                if (!email) {
                    alert('Please enter your email address');
                    return;
                }

                // Show loading message and disable button
                loadingMessage.classList.remove('hidden');
                sendOtpBtn.disabled = true;
                sendOtpBtn.classList.add('opacity-50');

                // Send OTP request
                fetch('forgot_password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(response => response.text())
                .then(html => {
                    // Hide loading message
                    loadingMessage.classList.add('hidden');
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.classList.remove('opacity-50');

                    // Check if the response contains success message
                    if (html.includes('OTP has been sent')) {
                        // Show OTP section immediately
                        otpSection.classList.remove('hidden');
                        sendOtpBtn.classList.add('hidden');
                        alert('OTP has been sent to your email');
                    } else {
                        // Show error message
                        const errorMatch = html.match(/class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">(.*?)<\/div>/);
                        if (errorMatch) {
                            alert(errorMatch[1]);
                        } else {
                            alert('Failed to send OTP. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    loadingMessage.classList.add('hidden');
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.classList.remove('opacity-50');
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });

            // Handle form submission for password reset
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!otpInput.value) {
                    alert('Please enter the OTP');
                    return;
                }

                if (!newPasswordInput.value) {
                    alert('Please enter a new password');
                    return;
                }

                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    alert('Passwords do not match');
                    return;
                }

                const formData = new FormData(this);
                
                fetch('forgot_password.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
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