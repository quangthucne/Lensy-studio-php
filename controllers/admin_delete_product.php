<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!hasPermission($pdo, 'manage_products')) {
    setFlashMessage('error', 'Access Denied.');
    redirect('../admin/products.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    
    if ($id) {
        // Soft delete the product by setting is_active to 0
        $stmt = $pdo->prepare("UPDATE products SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Sản phẩm đã tạm ngưng hoạt động.');
    } else {
        setFlashMessage('error', 'Không tìm thấy sản phẩm.');
    }
}

redirect('../admin/products.php');
