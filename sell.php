<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';

// Create connection to admin database
$conn = new mysqli($host, $user, $pass, 'busweb_admins');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create uploads directory if it doesn't exist
if (!file_exists("uploads")) {
    mkdir("uploads");
}

// Initialize variables
$notification = "";
$editMode = false;
$editId = null;

// Fetch admin data
$adminId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Load all submissions
$submissions = [];
$result = $conn->query("SELECT * FROM submissions ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
}

// Handle delete
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    
    // Get image path before deletion
    $stmt = $conn->prepare("SELECT image_path FROM submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['image_path']) && file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
    }
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM submissions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Handle edit request
if (isset($_GET["edit"])) {
    $editId = (int)$_GET["edit"];
    $stmt = $conn->prepare("SELECT * FROM submissions WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $editMode = true;
        $editData = $result->fetch_assoc();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    $required = ['seller_name', 'address', 'latitude', 'longitude', 'phone', 'product', 'category'];
    $errors = [];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Field " . ucfirst(str_replace('_', ' ', $field)) . " is required!";
        }
    }
    
    if (!empty($errors)) {
        $notification = implode("<br>", $errors);
    } else {
        // Sanitize inputs
        $seller_name = $conn->real_escape_string($_POST['seller_name']);
        $address = $conn->real_escape_string($_POST['address']);
        $latitude = (float)$_POST['latitude'];
        $longitude = (float)$_POST['longitude'];
        $phone = $conn->real_escape_string($_POST['phone']);
        $product = $conn->real_escape_string($_POST['product']);
        $offer = $conn->real_escape_string($_POST['offer'] ?? '');
        $description = $conn->real_escape_string($_POST['description'] ?? '');
        $category = $conn->real_escape_string($_POST['category']);
        $imagePath = "";

        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $imagePath = "uploads/" . uniqid() . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $imagePath);
        }

        try {
            if (isset($_POST['edit_id'])) {
                // Update existing record
                $id = (int)$_POST['edit_id'];
                
                // Keep existing image if no new upload
                if (empty($imagePath)) {
                    $stmt = $conn->prepare("SELECT image_path FROM submissions WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $imagePath = $row['image_path'];
                    }
                }

                $stmt = $conn->prepare("UPDATE submissions SET 
                    seller_name = ?, 
                    address = ?, 
                    latitude = ?, 
                    longitude = ?, 
                    phone = ?, 
                    product = ?, 
                    offer = ?, 
                    description = ?, 
                    image_path = ?, 
                    category = ? 
                    WHERE id = ?");
                
                $stmt->bind_param("ssddssssssi", 
                    $seller_name,
                    $address,
                    $latitude,
                    $longitude,
                    $phone,
                    $product,
                    $offer,
                    $description,
                    $imagePath,
                    $category,
                    $id
                );
                
                $notification = "✏️ Listing updated successfully!";
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO submissions (
                    seller_name, 
                    address, 
                    latitude, 
                    longitude, 
                    phone, 
                    product, 
                    offer, 
                    description, 
                    image_path,
                    category
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $stmt->bind_param("ssddssssss", 
                    $seller_name,
                    $address,
                    $latitude,
                    $longitude,
                    $phone,
                    $product,
                    $offer,
                    $description,
                    $imagePath,
                    $category
                );
                
                $notification = "✅ Thank you, $seller_name! Your product has been listed.";
            }
            
            $stmt->execute();
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
            exit;
            
        } catch (mysqli_sql_exception $e) {
            $notification = "⚠️ Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KhojBazaar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
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
    </style>
</head>
<body>
    <div class="main-container">
        <div class="background-image">
            <img src="imgae.png" alt="Background">
        </div>
        <div class="content-wrapper">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-4xl font-bold">Admin Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-lg">Welcome, <?php echo htmlspecialchars($admin['email']); ?></span>
                    <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Logout</a>
                </div>
            </div>

            <?php if (!empty($notification)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-3 mb-6 rounded-lg shadow">
                    <?= $notification ?>
                </div>
            <?php endif; ?>

            <!-- Seller Form -->
            <form method="POST" enctype="multipart/form-data" class="bg-white bg-opacity-90 p-8 rounded-xl shadow-md mb-10">
                <h2 class="text-2xl font-bold text-blue-600 mb-6">
                    <?= $editMode ? "✏️ Edit Your Listing" : "🛍️ Sell Your Product" ?>
                </h2>
                
                <?php if ($editMode): ?>
                    <input type="hidden" name="edit_id" value="<?= $editId ?>">
                <?php endif; ?>

                <div class="grid md:grid-cols-2 gap-4">
                    <input type="text" name="seller_name" 
                           value="<?= htmlspecialchars($editData['seller_name'] ?? '') ?>" 
                           placeholder="Your Shop Name" required 
                           class="p-3 border rounded-md">
                    <input type="tel" name="phone" 
                           value="<?= htmlspecialchars($editData['phone'] ?? '') ?>" 
                           placeholder="Phone Number" required 
                           class="p-3 border rounded-md">
                    
                    <input type="text" name="address" 
                           value="<?= htmlspecialchars($editData['address'] ?? '') ?>" 
                           placeholder="Business Address" required 
                           class="p-3 border rounded-md">
                    
                    <input type="text" name="product" 
                           value="<?= htmlspecialchars($editData['product'] ?? '') ?>" 
                           placeholder="What are you selling?" required 
                           class="p-3 border rounded-md">
                    
                    <input type="text" id="latitude" name="latitude" 
                           value="<?= htmlspecialchars($editData['latitude'] ?? '') ?>" 
                           placeholder="Latitude" required 
                           class="p-3 border rounded-md">
                    
                    <input type="text" id="longitude" name="longitude" 
                           value="<?= htmlspecialchars($editData['longitude'] ?? '') ?>" 
                           placeholder="Longitude" required 
                           class="p-3 border rounded-md">
                    
                    <input type="text" name="offer" 
                           value="<?= htmlspecialchars($editData['offer'] ?? '') ?>" 
                           placeholder="Offer (e.g. 20% off)" 
                           class="p-3 border rounded-md">
                    
                    <textarea name="description" 
                              placeholder="Product/Offer Description" 
                              rows="3" 
                              class="p-3 border rounded-md md:col-span-2"><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>

                    <!-- Category Dropdown -->
                    <select name="category" required class="p-3 border rounded-md">
                        <option value="">Select Category</option>
                        <option value="restaurants" <?= isset($editData['category']) && $editData['category'] == 'restaurants' ? 'selected' : '' ?>>Restaurants</option>
                        <option value="retail" <?= isset($editData['category']) && $editData['category'] == 'retail' ? 'selected' : '' ?>>Retail & Shopping</option>
                        <option value="wellness" <?= isset($editData['category']) && $editData['category'] == 'wellness' ? 'selected' : '' ?>>Personal Care & Wellness</option>
                        <option value="services" <?= isset($editData['category']) && $editData['category'] == 'services' ? 'selected' : '' ?>>Services</option>
                        <option value="kids" <?= isset($editData['category']) && $editData['category'] == 'kids' ? 'selected' : '' ?>>Kids & Education</option>
                        <option value="automotive" <?= isset($editData['category']) && $editData['category'] == 'automotive' ? 'selected' : '' ?>>Automotive</option>
                        <option value="entertainment" <?= isset($editData['category']) && $editData['category'] == 'entertainment' ? 'selected' : '' ?>>Entertainment & Lifestyle</option>
                        <option value="health" <?= isset($editData['category']) && $editData['category'] == 'health' ? 'selected' : '' ?>>Health & Medical</option>
                    </select>
                    
                    <input type="file" name="photo" accept="image/*" class="md:col-span-2">
                </div>

                <div class="my-6">
                    <label class="block font-semibold mb-2 text-blue-700">
                        📍 Your Location on Map (search your city or area):
                    </label>
                    <div id="map" class="w-full h-64 rounded-md shadow"></div>
                </div>

                <button type="submit" class="mt-6 bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition">
                    <?= $editMode ? "Update Listing" : "Save & Notify" ?>
                </button>
            </form>

            <!-- Display Table -->
            <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-blue-700">📋 Submitted Products</h3>
                <div class="overflow-auto">
                    <table class="min-w-full table-auto border border-collapse border-gray-300">
                        <thead>
                            <tr class="bg-blue-200 text-left">
                                <th class="p-3 border">Photo</th>
                                <th class="p-3 border">Name</th>
                                <th class="p-3 border">Phone</th>
                                <th class="p-3 border">Address</th>
                                <th class="p-3 border">Product</th>
                                <th class="p-3 border">Offer</th>
                                <th class="p-3 border">Lat, Long</th>
                                <th class="p-3 border">Category</th>
                                <th class="p-3 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td class="p-3 border">
                                        <?php if ($submission['image_path']): ?>
                                            <img src="<?= $submission['image_path'] ?>" alt="Product Photo" class="w-16 h-16 object-cover">
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['seller_name']) ?></td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['phone']) ?></td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['address']) ?></td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['product']) ?></td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['offer']) ?></td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['latitude']) ?>, <?= htmlspecialchars($submission['longitude']) ?></td>
                                    <td class="p-3 border"><?= htmlspecialchars($submission['category']) ?></td>
                                    <td class="p-3 border">
                                        <a href="?edit=<?= $submission['id'] ?>" class="text-yellow-600 hover:text-yellow-800 mr-3">✏️ Edit</a>
                                        <a href="?delete=<?= $submission['id'] ?>" class="text-red-600 hover:text-red-800">🗑️ Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Map integration (Leaflet)
        var map = L.map('map').setView([<?= $editMode ? $editData['latitude'] : '28.6139' ?>, <?= $editMode ? $editData['longitude'] : '77.2090' ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Add search bar
        var geocoder = L.Control.Geocoder.nominatim();

        var marker;

        // Add geocode search functionality
        var searchControl = new L.Control.Geocoder({
            geocoder: geocoder,
            collapsed: false
        }).addTo(map);

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        // Geocode to search for location
        geocoder.on('markgeocode', function(event) {
            var lat = event.geocode.center.lat;
            var lng = event.geocode.center.lng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Adding search bar to the map
        L.Control.geocoder().addTo(map);
    </script>
</body>
</html>