<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!hasPermission($pdo, 'manage_products')) {
    setFlashMessage('error', 'Chỉ có quản trị viên mới được phép thực hiện thao tác này.');
    redirect('../admin/categories.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    
    if ($id) {
        try {
            // Soft delete: is_active = 0
            $stmt = $pdo->prepare("UPDATE categories SET is_active = 0 WHERE id = ?");
            $stmt->execute([$id]);
            setFlashMessage('success', 'Danh mục đã được tạm ngưng (xóa mềm).');
        } catch (PDOException $e) {
            setFlashMessage('error', 'Lỗi khi xóa danh mục: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('error', 'ID danh mục không hợp lệ.');
    }
}

redirect('../admin/categories.php');
