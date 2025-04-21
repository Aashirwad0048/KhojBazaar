<?php
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

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data and sanitize
    $userType = $_POST['userType'] ?? '';
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate input
    $errors = [];

    if (empty($userType)) {
        $errors[] = "Please select user type";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one capital letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Password must contain at least one special character";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        // Check if email already exists in appropriate database
        if ($userType === 'admin') {
            $checkStmt = $adminsConn->prepare("SELECT email FROM admins WHERE email = ?");
        } else {
            $checkStmt = $usersConn->prepare("SELECT email FROM users WHERE email = ?");
        }
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        if ($result->num_rows > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'account_exists',
                'email' => $email
            ]);
            exit;
        }
        $checkStmt->close();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Save to appropriate database based on user type
        if ($userType === 'admin') {
            $stmt = $adminsConn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
        } else {
            $stmt = $usersConn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        }
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Registration successful! Please login.',
                'redirect' => 'login.php'
            ]);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $stmt->error
            ]);
            exit;
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
    <title>KhojBazaar - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&family=Noto+Sans+Devanagari:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
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
            max-width: 800px;
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
        .user-type-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .user-type-option:hover {
            transform: translateY(-5px);
        }
        .user-type-option.selected {
            border-color: #0ea5e9;
            background-color: rgba(14, 165, 233, 0.1);
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
            <h1 class="text-4xl font-bold mb-8">Register</h1>
            <div class="flex justify-center mb-8">
                <span class="text-6xl text-black satisfy-regular jumping-text" style="font-weight: 300;">KhojBazaar</span>
            </div>
            <div id="accountExistsMessage" class="hidden mb-4 p-4 bg-blue-100 text-blue-700 rounded-lg">
                <p class="mb-2">An account with this email already exists.</p>
                <div class="flex flex-col items-center space-y-2">
                    <p class="text-sm text-gray-600">What would you like to do?</p>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <button type="button" id="useDifferentEmailBtn" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors duration-300">
                            Use a different email
                        </button>
                        <a href="login.php" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-300 text-center">
                            Login to existing account
                        </a>
                        <a href="forgot_password.php" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 text-center">
                            Reset forgotten password
                        </a>
                    </div>
                </div>
            </div>
            <form id="registerForm" class="space-y-4">
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
                <div id="registrationForm" class="hidden space-y-4">
                    <div>
                        <input type="email" id="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>
                        <div id="passwordRequirements" class="mt-2 bg-white border border-red-200 rounded-lg shadow-lg p-4 hidden">
                            <div class="text-sm font-semibold text-red-600 mb-2">Password Requirements:</div>
                            <ul class="list-none space-y-1">
                                <li id="lengthReq" class="text-red-500 flex items-center">
                                    <span class="mr-2">•</span>
                                    At least 8 characters
                                </li>
                                <li id="capitalReq" class="text-red-500 flex items-center">
                                    <span class="mr-2">•</span>
                                    One capital letter
                                </li>
                                <li id="numberReq" class="text-red-500 flex items-center">
                                    <span class="mr-2">•</span>
                                    One number
                                </li>
                                <li id="specialReq" class="text-red-500 flex items-center">
                                    <span class="mr-2">•</span>
                                    One special character
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <div class="relative">
                            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500">
                        </div>
                        <div id="passwordMismatchError" class="hidden text-sm text-red-500 mt-1">Passwords do not match</div>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg shadow-md hover:bg-gray-800 transition-colors duration-300">
                        Register
                    </button>
                </div>
            </form>
            <p class="mt-4">
                Already have an account? <a href="login.php" class="text-sky-500 hover:text-sky-600">Login</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeOptions = document.querySelectorAll('.user-type-option');
            const userTypeInput = document.getElementById('userTypeInput');
            const userTypeSelection = document.getElementById('userTypeSelection');
            const registrationForm = document.getElementById('registrationForm');
            const passwordInput = document.getElementById('password');
            const emailInput = document.getElementById('email');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const registerButton = document.querySelector('button[type="submit"]');
            const passwordRequirements = document.getElementById('passwordRequirements');
            const passwordMismatchError = document.getElementById('passwordMismatchError');
            const accountExistsMessage = document.getElementById('accountExistsMessage');
            const useDifferentEmailBtn = document.getElementById('useDifferentEmailBtn');

            const requirements = {
                length: document.getElementById('lengthReq'),
                capital: document.getElementById('capitalReq'),
                number: document.getElementById('numberReq'),
                special: document.getElementById('specialReq')
            };

            // User type selection
            userTypeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    userTypeOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    userTypeInput.value = this.dataset.type;
                    userTypeSelection.classList.add('hidden');
                    registrationForm.classList.remove('hidden');
                });
            });

            function checkAllRequirementsMet() {
                return passwordInput.value.length >= 8 &&
                       /[A-Z]/.test(passwordInput.value) &&
                       /[0-9]/.test(passwordInput.value) &&
                       /[^A-Za-z0-9]/.test(passwordInput.value);
            }

            function updatePasswordValidation() {
                const password = passwordInput.value;
                
                // Check length
                if (password.length >= 8) {
                    requirements.length.classList.remove('text-red-500');
                    requirements.length.classList.add('text-green-500');
                } else {
                    requirements.length.classList.remove('text-green-500');
                    requirements.length.classList.add('text-red-500');
                }
                
                // Check capital letter
                if (/[A-Z]/.test(password)) {
                    requirements.capital.classList.remove('text-red-500');
                    requirements.capital.classList.add('text-green-500');
                } else {
                    requirements.capital.classList.remove('text-green-500');
                    requirements.capital.classList.add('text-red-500');
                }
                
                // Check number
                if (/[0-9]/.test(password)) {
                    requirements.number.classList.remove('text-red-500');
                    requirements.number.classList.add('text-green-500');
                } else {
                    requirements.number.classList.remove('text-green-500');
                    requirements.number.classList.add('text-red-500');
                }
                
                // Check special character
                if (/[^A-Za-z0-9]/.test(password)) {
                    requirements.special.classList.remove('text-red-500');
                    requirements.special.classList.add('text-green-500');
                } else {
                    requirements.special.classList.remove('text-green-500');
                    requirements.special.classList.add('text-red-500');
                }

                // Show/hide requirements based on whether all are met
                if (checkAllRequirementsMet()) {
                    passwordRequirements.classList.add('hidden');
                } else {
                    passwordRequirements.classList.remove('hidden');
                }

                // Update confirm password validation
                if (confirmPasswordInput.value) {
                    if (confirmPasswordInput.value === password) {
                        confirmPasswordInput.classList.remove('border-red-500');
                        confirmPasswordInput.classList.add('border-green-500');
                        passwordMismatchError.classList.add('hidden');
                    } else {
                        confirmPasswordInput.classList.remove('border-green-500');
                        confirmPasswordInput.classList.add('border-red-500');
                        passwordMismatchError.classList.remove('hidden');
                    }
                } else {
                    confirmPasswordInput.classList.remove('border-red-500');
                    confirmPasswordInput.classList.remove('border-green-500');
                    passwordMismatchError.classList.add('hidden');
                }
            }

            // Show requirements and make password visible when password field is focused
            passwordInput.addEventListener('focus', function() {
                if (!checkAllRequirementsMet()) {
                    passwordRequirements.classList.remove('hidden');
                }
                this.setAttribute('type', 'text');
            });

            // Hide requirements and make password hidden when password field loses focus
            passwordInput.addEventListener('blur', function() {
                this.setAttribute('type', 'password');
            });

            // Show password when confirm password field is focused
            confirmPasswordInput.addEventListener('focus', function() {
                this.setAttribute('type', 'text');
            });

            // Hide password when confirm password field loses focus
            confirmPasswordInput.addEventListener('blur', function() {
                this.setAttribute('type', 'password');
            });

            // Update validation on input
            passwordInput.addEventListener('input', updatePasswordValidation);
            confirmPasswordInput.addEventListener('input', updatePasswordValidation);

            // Add click handler for use different email button
            useDifferentEmailBtn.addEventListener('click', function() {
                // Hide the account exists message
                accountExistsMessage.classList.add('hidden');
                
                // Clear all form fields
                emailInput.value = '';
                passwordInput.value = '';
                confirmPasswordInput.value = '';
                
                // Reset password requirements display
                passwordRequirements.classList.add('hidden');
                
                // Reset password validation states
                Object.values(requirements).forEach(req => {
                    req.classList.remove('text-green-500');
                    req.classList.add('text-red-500');
                });
                
                // Reset password mismatch error
                passwordMismatchError.classList.add('hidden');
                confirmPasswordInput.classList.remove('border-red-500', 'border-green-500');
                
                // Focus on email field
                emailInput.focus();
            });

            // Form submission
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!userTypeInput.value) {
                    alert('Please select a user type');
                    return;
                }

                if (!checkAllRequirementsMet()) {
                    alert('Please meet all password requirements');
                    return;
                }

                if (passwordInput.value !== confirmPasswordInput.value) {
                    alert('Passwords do not match');
                    return;
                }

                const formData = new FormData(this);
                
                fetch('register.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = data.redirect;
                    } else {
                        if (data.message === 'account_exists') {
                            accountExistsMessage.classList.remove('hidden');
                            emailInput.focus();
                        } else {
                            alert(data.message);
                        }
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