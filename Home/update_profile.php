<?php
session_start();
require 'config.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Fetch user data from the database
$sql = "SELECT * FROM userss WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";  // Initialize a message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $profile_photo = $_FILES['profile_photo'];  // File upload

    // Validate password
    if ($password && $password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Update password if provided
        if ($password) {
            $password = password_hash($password, PASSWORD_DEFAULT);  // Hash password
        } else {
            $password = $user['password'];  // Keep the old password if not updated
        }

        // Handle profile photo upload
        $new_profile_photo = $user['profile_photo'];  // Default to current photo
        if (!empty($profile_photo['name'])) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);  // Create directory if not exists
            }

            $file_name = time() . "_" . basename($profile_photo['name']);
            $file_path = $upload_dir . $file_name;

            // Validate file type and size (example: allow only images)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size = 5 * 1024 * 1024; // 5 MB max

            if (!in_array($profile_photo['type'], $allowed_types)) {
                $message = "Invalid file type! Only JPEG, PNG, and GIF are allowed.";
            } elseif ($profile_photo['size'] > $max_file_size) {
                $message = "File is too large. Maximum allowed size is 5 MB.";
            } else {
                if (move_uploaded_file($profile_photo['tmp_name'], $file_path)) {
                    $new_profile_photo = $file_path;  // Update with new photo
                } else {
                    $message = "Failed to upload profile photo!";
                }
            }
        }

        // Update user in the database
        if (empty($message)) { // Only proceed if there are no errors
            $sql = "UPDATE userss SET email = :email, username = :username, password = :password, profile_photo = :profile_photo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':profile_photo', $new_profile_photo);
            $stmt->bindParam(':id', $_SESSION['user_id']);

            if ($stmt->execute()) {
                $message = "Profile updated successfully!";
            } else {
                $message = "Error updating profile.";
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
    <title>Update Profile - TonyRon Cross</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        body {
            background: linear-gradient(to right, #32a852, #000000);
            background-attachment: fixed;
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Logo and Website Name Styling */
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 10px;
        }

        .navbar-brand img {
            width: 150px;
            height: 150px;
            margin-right: 10px;
        }

        .navbar-brand {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }

        .register-container {
            max-width: 500px;
            margin: 150px auto;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message {
            text-align: center;
            color: red;
        }

        .btn-primary {
            background-color: #32a852;
            border: none;
        }

        .btn-primary:hover {
            background-color: #28a745;
        }

        .alert {
            text-align: center;
            color: red;
            font-weight: bold;
        }

        /* Profile Image Styling */
        .profile-image {
            max-width: 250px;
            height: 250px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Navbar with Logo and Website Name -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
            <img src="img/Logo.png" alt="Logo"> TonyRon Cross
        </a>
    </nav>

    <div class="container register-container">
        <h2 class="text-center">Update Your Profile</h2>

        <!-- Display Profile Photo -->
        <div class="text-center mb-4">
            <img src="<?= !empty($user['profile_photo']) ? $user['profile_photo'] : 'uploads/default-profile.png' ?>" alt="Profile Photo" class="profile-image" />
        </div>

        <?php if ($message) : ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required />
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" />
            </div>

            <div class="mb-3">
                <label for="profile_photo" class="form-label">Profile Photo</label>
                <input type="file" name="profile_photo" class="form-control" />
            </div>

            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>

        <div class="text-center mt-3">
            <a href="home.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
