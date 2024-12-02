<?php
session_start();
// Assuming you have some sort of session or authentication for admins
if ($_SESSION['role'] != 'Admin') {
    header("Location: index.php"); // Redirect if not an admin
    exit();
}

include('config.php'); // Database connection

// Add Item
if (isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    $query = "INSERT INTO items (name, description, image, price) VALUES ('$name', '$description', '$image', '$price')";
    mysqli_query($conn, $query);
}

// Delete Item
if (isset($_GET['delete_item'])) {
    $id = $_GET['delete_item'];
    $query = "DELETE FROM items WHERE id = $id";
    mysqli_query($conn, $query);
}

// Edit Item
if (isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    $query = "UPDATE items SET name = '$name', description = '$description', image = '$image', price = '$price' WHERE id = $id";
    mysqli_query($conn, $query);
}

// Fetch Items
$query = "SELECT * FROM items";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Items</title>
</head>
<body>

<h2>Manage Items</h2>

<!-- Add Item Form -->
<form method="POST" action="motoshad.php">
    <h3>Add Item</h3>
    Name: <input type="text" name="name" required><br>
    Description: <textarea name="description" required></textarea><br>
    Image URL: <input type="text" name="image"><br>
    Price: <input type="number" name="price" step="0.01" required><br>
    <button type="submit" name="add_item">Add Item</button>
</form>

<hr>

<!-- Edit Item Form -->
<?php if (isset($_GET['edit_item'])): ?>
    <?php
    $id = $_GET['edit_item'];
    $item_query = "SELECT * FROM items WHERE id = $id";
    $item_result = mysqli_query($conn, $item_query);
    $item = mysqli_fetch_assoc($item_result);
    ?>
    <form method="POST" action="motoshad.php">
        <h3>Edit Item</h3>
        <input type="hidden" name="id" value="<?= $item['id'] ?>"><br>
        Name: <input type="text" name="name" value="<?= $item['name'] ?>" required><br>
        Description: <textarea name="description" required><?= $item['description'] ?></textarea><br>
        Image URL: <input type="text" name="image" value="<?= $item['image'] ?>"><br>
        Price: <input type="number" name="price" value="<?= $item['price'] ?>" step="0.01" required><br>
        <button type="submit" name="edit_item">Save Changes</button>
    </form>
<?php endif; ?>

<hr>

<h3>Items List</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Image</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php while ($item = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['name'] ?></td>
            <td><?= $item['description'] ?></td>
            <td><img src="<?= $item['image'] ?>" width="50" alt="Item Image"></td>
            <td><?= $item['price'] ?></td>
            <td>
                <a href="motoshad.php?edit_item=<?= $item['id'] ?>">Edit</a> |
                <a href="motoshad.php?delete_item=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
