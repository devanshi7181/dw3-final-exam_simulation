<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Validate image upload
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (in_array($image['type'], $allowed_types) && $image['size'] <= $max_size) {
        $image_name = time() . '-' . basename($image['name']);
        $image_path = 'uploads/' . $image_name;

        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            $stmt = $pdo->prepare("INSERT INTO products (user_id, name, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $name, $description, $price, $image_path]);
            header('Location: dashboard.php');
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Invalid image file.";
    }
}
?>

<!-- Form for adding product -->
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description" required></textarea>
    <input type="number" name="price" placeholder="Price" required>
    <input type="file" name="image" accept="image/jpeg, image/png" required>
    <button type="submit">Add Product</button>
</form>
