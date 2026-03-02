<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!hasPermission($pdo, 'manage_users')) {
    setFlashMessage('error', 'Access Denied.');
    redirect('../admin/users.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? 0;
    $roleId = $_POST['role_id'] ?? 0;

    if (!$userId || !$roleId) {
        setFlashMessage('error', 'Invalid data.');
        redirect('../admin/users.php');
    }

    // Prevent changing own role for safety (unless complex logic)
    if ($userId == $_SESSION['user_id']) {
        setFlashMessage('error', 'Bạn không thể thay đổi vai trò của mình.');
        redirect('../admin/users.php');
    }

    // Check if target user is an admin
    if (hasRole($pdo, 'admin', $userId)) { // Wait, hasRole checks session user by default. I need to check specific user.
        // hasRole only checks current session. I need to query.
    }
    
    // Let's write the query directly.
    $checkStmt = $pdo->prepare("
        SELECT 1 
        FROM user_roles ur 
        JOIN roles r ON ur.role_id = r.id 
        WHERE ur.user_id = ? AND r.name = 'admin'
    ");
    $checkStmt->execute([$userId]);
    if ($checkStmt->fetch()) {
        setFlashMessage('error', 'Không thể thay đổi vai trò của một tài khoản Quản trị viên.');
        redirect('../admin/users.php');
    }

    try {
        // 1. Remove existing roles (Assuming single role policy for UI simplicity)
        $stmt = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
        $stmt->execute([$userId]);

        // 2. Add new role
        $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$userId, $roleId]);

        setFlashMessage('success', 'Vai trò của người dùng đã được cập nhật.');

    } catch (PDOException $e) {
        setFlashMessage('error', 'Lỗi khi cập nhật vai trò: ' . $e->getMessage());
    }
}

redirect('../admin/users.php');
