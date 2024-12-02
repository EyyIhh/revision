<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$users = $pdo->query("SELECT * FROM userss")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deactivate'])) {
        $user_id = $_POST['user_id'];
        $pdo->query("UPDATE userss SET status = 'inactive' WHERE id = $user_id");
    }
    if (isset($_POST['update_role'])) {
        $user_id = $_POST['user_id'];
        $new_role = $_POST['role'];
        $pdo->query("UPDATE userss SET role = '$new_role' WHERE id = $user_id");
    }
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TonyRon Cross</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Custom Background Gradient */
        body {
            background: linear-gradient(to right, #32a852, #ffffff); /* Green to white gradient */
            color: white;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar style */
        .navbar {
            background: transparent;
        }

        .navbar-brand img {
            width: 50px;  /* Adjust logo size */
            height: auto;
        }

        .navbar-brand {
            font-family: 'Cursive', sans-serif;  /* Fancy font for website name */
            font-size: 30px;
            color: white;
            margin-left: 15px;
        }

        /* Container for the Admin Panel */
        .container {
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Cursive', sans-serif;
            font-size: 35px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: center;
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #333;
        }

        td {
            background-color: rgba(0, 0, 0, 0.6);
        }

        .btn {
            background-color: #32a852;
            border: none;
            padding: 8px 12px;
            margin: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #28a745;
        }

        .alert {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar with Clickable Logo and Website Name -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="home.php">
            <img src="img/Logo.png" alt="Logo">
            TonyRon Cross
        </a>
    </nav>

    <div class="container">
        <h1>Admin Panel</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['status']) ?></td>
                        <td>
                            <form action="admin.php" method="post" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="deactivate" class="btn btn-danger">Deactivate</button>
                            </form>
                            <form action="admin.php" method="post" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="role" class="form-select d-inline w-auto">
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                </select>
                                <button type="submit" name="update_role" class="btn btn-primary">Update Role</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
