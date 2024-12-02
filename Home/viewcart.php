<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Cart</title>
</head>
<body>

<h2>Your Cart</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $cart_item): ?>
            <tr>
                <td><?= $cart_item['name'] ?></td>
                <td><?= $cart_item['quantity'] ?></td>
                <td>$<?= $cart_item['price'] * $cart_item['quantity'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
