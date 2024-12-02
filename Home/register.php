<?php
session_start();
require 'config.php';  // Database connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $profile_photo = $_FILES['profile_photo'];

    // Validate the fields
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Upload the profile photo
        if (!empty($profile_photo['name'])) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = time() . "_" . basename($profile_photo['name']);
            $file_path = $upload_dir . $file_name;
            if (move_uploaded_file($profile_photo['tmp_name'], $file_path)) {
                // Hash the password before saving
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the database
                $sql = "INSERT INTO userss (email, username, password, profile_photo) VALUES (:email, :username, :password, :profile_photo)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':profile_photo', $file_path);

                if ($stmt->execute()) {
                    $message = "Registration successful!";
                } else {
                    $message = "Registration failed.";
                }
            } else {
                $message = "Failed to upload profile photo.";
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
    <title>Register - TonyRon Cross</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
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
    </style>
</head>
<body>

<div class="register-container">
    <h2 class="text-center mb-4">Register</h2>

    <!-- Display error message if exists -->
    <?php if ($message): ?>
        <div class="alert alert-danger message"><?= $message ?></div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="register.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" name="role" id="role">
                <option value="user" selected>User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="profile_photo" class="form-label">Profile Photo</label>
            <input type="file" class="form-control" name="profile_photo" id="profile_photo" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 text-center">
        Already have an account? <a href="login.php" class="text-white">Login here</a>
    </p>
</div>

</body>
</html>
