<?php
// Start the session if you need to use session variables
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Tonyron Cross</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 50px;
        }
        h1 {
            color: #333;
            font-size: 48px;
        }
        .buttons {
            margin-top: 30px;
        }
        .button {
            padding: 15px 30px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
            text-decoration: none;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>WELCOME TO TONYRON CROSS</h1>

        <div class="buttons">
            <a href="motoshus.php" class="button">Motor Shop</a>
            <a href="#" class="button">Parts Shop</a>
        </div>
    </div>

</body>
</html>
