<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the product and delete image from server
    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $product = $stmt->fetch();

    if ($product) {
        unlink($product['image_path']);
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: dashboard.php');
    }
}
?>
