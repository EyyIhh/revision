<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

require 'config.php';  // Database connection

// Fetch the user's current information (like profile photo) from the database
$sql = "SELECT * FROM userss WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Display the profile image or default
$profile_photo = !empty($user['profile_photo']) ? $user['profile_photo'] : 'default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - TonyRon Cross</title>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* User Profile Styles */
        .user-profile {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
}

.user-profile img {
    width: 80px;  /* Increased size */
    height: 80px;  /* Increased size */
    border-radius: 50%;
    margin-right: 10px;
    border: 3px solid #fff;  /* Slightly thicker border */
}

        .user-profile a {
            color: #fff;
            font-family: 'Roboto', sans-serif;
            font-size: 18px;
            text-decoration: none;
        }

        .user-profile a:hover {
            text-decoration: underline;
        }

        /* Additional styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, black, #28a745);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
            position: relative;
            overflow: auto;
            align-items: center;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            max-width: 200px;
            height: auto;
            z-index: 10;
        }

        .website-name {
            position: absolute;
            top: 20px;
            left: 250px;
            font-family: 'Pacifico', cursive;
            font-size: 48px;
            color: #fff;
            z-index: 10;
        }

        .navbar {
            display: flex;
            justify-content: center;
            position: absolute;
            top: 130px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9;
        }

        .navbar a {
            color: #fff;
            font-family: 'Roboto', sans-serif;
            font-size: 22px;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: block;
            text-align: center;
        }

        .navbar a:not(:last-child)::after {
            content: '|';
            margin-left: 20px;
            color: #fff;
        }

        .navbar a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .container {
            background-color: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1200px;
            height: auto;
            position: relative;
            margin-top: 200px;
            z-index: 1;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 30px;
        }

        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 16px;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .big-image {
            width: 100%;
            max-width: 1200px;
            height: auto;
            display: block;
            margin: 40px auto 20px;
            border-radius: 10px;
        }

        .container p {
            font-family: 'Roboto', sans-serif;
            font-size: 24px;
            color: #333;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .extra-text p {
            font-family: 'Roboto', sans-serif;
            font-size: 20px;
            color: #333;
            line-height: 1.8;
            text-align: center;
            margin-top: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }

        .extra-text strong {
            font-weight: 700;
            color: #28a745;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
            width: 100%;
            position: relative;
            bottom: 0;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <img src="img/Logo.png" alt="Logo" class="logo" />
    <div class="website-name">TonyRon Cross</div>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="logout.php">Logout</a>
        <a href="admin.php">Admin</a>
    </div>

    <!-- User Profile Section -->
    <div class="user-profile">
        <img src="<?= htmlspecialchars($profile_photo) ?>" alt="User Profile Photo" />
        <a href="update_profile.php">Edit Profile</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>Welcome to TonyRon Cross</h2>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <img src="img/centerimage.png" alt="Big Image" class="big-image" />

        <!-- Additional Content / Paragraph -->
        <div class="extra-text">
            <p><strong>Welcome to TonyRon Cross – Your Ultimate Online Motorcycle Store!</strong></p>
            <p>At <strong>TonyRon Cross</strong>, we are passionate about everything related to motorcycles, from high-performance parts to stylish accessories and gear. Whether you're a seasoned rider or just starting out, our goal is to provide you with everything you need to make your motorcycle experience unforgettable.</p>
            <p>With a vast selection of top-quality bikes, parts, riding apparel, and custom accessories, we’re your one-stop shop for all things motorcycling. We source only the best products from trusted brands to ensure your safety, style, and performance are always at the forefront.</p>
            <p>Explore our user-friendly online store, where you can easily browse, compare, and find the perfect products for your ride. Whether you're looking to upgrade your bike, gear up with the latest apparel, or find replacement parts, <strong>TonyRon Cross</strong> has you covered.</p>
            <p><strong>Ride smart. Ride with style. Ride with TonyRon Cross!</strong></p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 TonyRon Cross. All Rights Reserved. Designed by <strong>Richard Anthony Iddurut</strong> & <strong>Aeron Canta</strong>.
    </footer>
</body>
</html>
