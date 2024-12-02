<?php
session_start();
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM userss WHERE email = :email AND status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        header("Location: home.php");
        exit;
    } else {
        $message = "Invalid credentials or account inactive.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TonyRon Cross</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts for Fancy but Readable Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&display=swap" rel="stylesheet"> <!-- Custom font for fire effect -->

    <!-- Custom CSS for Gradient Background and Layout -->
    <style>
        /* Apply a gradient and fix the background */
        body {
            background: linear-gradient(to right, #32a852, #000000); /* Green to Black gradient */
            background-attachment: fixed;
            color: white;
            font-family: 'Poppins', sans-serif; /* Default font */
            margin: 0;
            padding: 0;
        }

        /* Adding the white lines as background */
        .background-lines {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(90deg, transparent, transparent 10px, rgba(255, 255, 255, 0.1) 10px, rgba(255, 255, 255, 0.1) 11px);
            z-index: -1; /* Behind content */
        }

        /* Styling the container for login */
        .login-container {
            max-width: 400px;
            margin: 150px auto;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.7); /* Transparent background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Logo and website name */
        .logo-container {
            display: flex;
            align-items: center;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1; /* Keep logo and website name on top */
        }

        .logo-container img {
            width: 150px; /* Logo size */
            margin-right: 10px;
        }

        .glowing-text {
    font-family: 'Rubik Mono One', sans-serif; /* Use the fancy font */
    font-size: 36px;
    font-weight: 600;
    color:#000000;
    text-shadow: 
        2px 2px 0px #000,   /* Black outline */
        -2px -2px 0px #000,
        2px -2px 0px #000,
        -2px 2px 0px #000,  /* Black outline */
        0 0 5px #32a852,    /* Glowing green effect */
        0 0 10px #32a852, 
        0 0 15px #32a852, 
        0 0 20px #32a852, 
        0 0 25px #32a852, 
        0 0 30px #32a852, 
        0 0 35px #32a852;   /* Glowing green effect */
    animation: glowing 1.5s ease-in-out infinite alternate;
}

        /* Animation for glowing effect */
        @keyframes glowing {
            0% {
                text-shadow: 
                    0 0 5px #32a852, 
                    0 0 10px #32a852, 
                    0 0 15px #32a852, 
                    0 0 20px #32a852, 
                    0 0 25px #32a852, 
                    0 0 30px #32a852, 
                    0 0 35px #32a852;
            }
            100% {
                text-shadow: 
                    0 0 10px #32a852, 
                    0 0 20px #32a852, 
                    0 0 30px #32a852, 
                    0 0 40px #32a852, 
                    0 0 50px #32a852, 
                    0 0 60px #32a852, 
                    0 0 70px #32a852;
            }
        }

        /* Message styling */
        .message {
            text-align: center;
            color: red;
        }

        /* Form input styles */
        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Styling the login button */
        .btn-primary {
            background-color: #32a852;
            border: none;
        }

        .btn-primary:hover {
            background-color: #28a745;
        }

        /* Link styling */
        .text-white {
            color: white;
            text-decoration: none;
        }

        .text-white:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Background lines -->
<div class="background-lines"></div>

<!-- Logo and Website Name Section -->
<div class="logo-container">
    <img src="img/Logo.png" alt="Logo"> <!-- Your Logo -->
    <h1 class="glowing-text">TonyRon Cross</h1> <!-- Website Name with Glowing Green Effect -->
</div>

<!-- Login Container -->
<div class="login-container">
    <h2 class="text-center mb-4">Login</h2>

    <!-- Display error message if exists -->
    <?php if ($message): ?>
        <div class="alert alert-danger message"><?= $message ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="login.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 text-center">
        Don't have an account? <a href="register.php" class="text-white">Sign up here</a>
    </p>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
