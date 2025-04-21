<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';

// Create connection to admin database (where submissions are stored)
$conn = new mysqli($host, $user, $pass, 'busweb_admins');
    
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from users database
$usersConn = new mysqli($host, $user, $pass, 'busweb_users');
if ($usersConn->connect_error) {
    die("Connection failed: " . $usersConn->connect_error);
}

$userId = $_SESSION['user_id'];
$stmt = $usersConn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle search and filter
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// Build the query
$query = "SELECT * FROM submissions WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (seller_name LIKE ? OR product LIKE ? OR description LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
}

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

$query .= " ORDER BY created_at DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch all submissions
$submissions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - KhojBazaar</title>
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&family=Noto+Sans+Devanagari:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
  <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Noto Sans Devanagari', sans-serif;
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
            position: relative;
            width: 100vw;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .background-image {
            position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }
        .background-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.5;
        }
        .content-wrapper {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
            background: transparent;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .category-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
    }
  </style>
</head>
<body>
    <div class="main-container">
        <div class="background-image">
            <img src="imgae.png" alt="Background">
        </div>
        <div class="content-wrapper">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800"><span class="satisfy-regular jumping-text">KhojBazaar</span></h1>
                    <!-- <p class="text-gray-600 mt-2">Discover amazing local products and services</p> -->
            </div>
                <div class="flex items-center space-x-4">
                    <span class="text-lg text-gray-700">Hello, <?php echo htmlspecialchars($user['email']); ?></span>
                    <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300">Logout</a>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card p-6 mb-8">
                <form method="GET" action="" class="flex flex-col md:flex-row gap-4">
                    <input type="text" 
                           name="search" 
                           value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Search products..." 
                           class="flex-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <select name="category" class="p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">All Categories</option>
                        <option value="restaurants" <?= $category === 'restaurants' ? 'selected' : '' ?>>Restaurants</option>
                        <option value="retail" <?= $category === 'retail' ? 'selected' : '' ?>>Retail & Shopping</option>
                        <option value="wellness" <?= $category === 'wellness' ? 'selected' : '' ?>>Personal Care & Wellness</option>
                        <option value="services" <?= $category === 'services' ? 'selected' : '' ?>>Services</option>
                        <option value="kids" <?= $category === 'kids' ? 'selected' : '' ?>>Kids & Education</option>
                        <option value="automotive" <?= $category === 'automotive' ? 'selected' : '' ?>>Automotive</option>
                        <option value="entertainment" <?= $category === 'entertainment' ? 'selected' : '' ?>>Entertainment & Lifestyle</option>
                        <option value="health" <?= $category === 'health' ? 'selected' : '' ?>>Health & Medical</option>
      </select>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                        Search
                    </button>
                    <?php if (!empty($search) || !empty($category)): ?>
                        <a href="dashboard.php" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-300">
                            Clear Filters
                        </a>
          <?php endif; ?>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($submissions as $submission): ?>
                    <div class="card p-6 relative">
                        <span class="category-badge <?php 
                            echo $submission['category'] == 'food' ? 'bg-green-100 text-green-800' : 
                                ($submission['category'] == 'handmade' ? 'bg-yellow-100 text-yellow-800' : 
                                ($submission['category'] == 'services' ? 'bg-blue-100 text-blue-800' : 
                                'bg-purple-100 text-purple-800')); 
                        ?>">
                            <?php echo ucfirst($submission['category']); ?>
                        </span>
                        
                        <?php if ($submission['image_path']): ?>
                            <img src="<?= $submission['image_path'] ?>" alt="Product" class="product-image mb-4">
          <?php endif; ?>
                        
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($submission['product']) ?></h3>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars($submission['description']) ?></p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-lg font-bold text-blue-600">
                                <?= htmlspecialchars($submission['offer']) ?>
                            </span>
                            <span class="text-sm text-gray-500">
                                <?= date('M d, Y', strtotime($submission['created_at'])) ?>
            </span>
          </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex items-center space-x-2 text-gray-600 mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <a href="https://www.google.com/maps?q=<?= urlencode($submission['latitude'] . ',' . $submission['longitude']) ?>" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                    <?= htmlspecialchars($submission['seller_name']) ?>
              </a>
            </div>
                            <div class="flex items-center space-x-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span><?= htmlspecialchars($submission['phone']) ?></span>
          </div>
        </div>
      </div>
                <?php endforeach; ?>
    </div>
  </div>
    </div>
</body>
</html>
