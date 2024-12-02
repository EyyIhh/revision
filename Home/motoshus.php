<?php
session_start();
include('config.php');

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // Check if item already in cart
    $exists = false;
    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['id'] == $item_id) {
            $_SESSION['cart'][$index]['quantity'] += $quantity; // Update quantity
            $exists = true;
            break;
        }
    }

    // If not exists, add new item to cart
    if (!$exists) {
        $query = "SELECT * FROM items WHERE id = $item_id";
        $result = mysqli_query($conn, $query);
        $item = mysqli_fetch_assoc($result);

        $_SESSION['cart'][] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $quantity,
        ];
    }
}

// Remove from Cart
if (isset($_GET['remove_item'])) {
    $item_id = $_GET['remove_item'];

    // Remove the item from the cart
    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['id'] == $item_id) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }
}

// Edit Cart Item Quantity
if (isset($_POST['edit_cart'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];

    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['id'] == $item_id) {
            $_SESSION['cart'][$index]['quantity'] = $new_quantity;
            break;
        }
    }
}

// Fetch Items for Display
$query = "SELECT * FROM items";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>

<h2>Items Available</h2>

<?php while ($item = mysqli_fetch_assoc($result)): ?>
    <div>
        <h3><?= $item['name'] ?></h3>
        <p><?= $item['description'] ?></p>
        <p>Price: $<?= $item['price'] ?></p>
        <form method="POST" action="motoshus.php">
            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
            Quantity: <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </div>
<?php endwhile; ?>

<hr>

<h3>Your Cart</h3>
<table>
    <tr>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($_SESSION['cart'] as $cart_item): ?>
        <tr>
            <td><?= $cart_item['name'] ?></td>
            <td>
                <form method="POST" action="motoshus.php">
                    <input type="hidden" name="item_id" value="<?= $cart_item['id'] ?>">
                    <input type="number" name="quantity" value="<?= $cart_item['quantity'] ?>" min="1">
                    <button type="submit" name="edit_cart">Update</button>
                </form>
            </td>
            <td>$<?= $cart_item['price'] * $cart_item['quantity'] ?></td>
            <td>
                <a href="motoshus.php?remove_item=<?= $cart_item['id'] ?>" onclick="return confirm('Remove from cart?')">Remove</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- View Cart -->
<a href="viewcart.php">View Cart</a>

</body>
</html>
