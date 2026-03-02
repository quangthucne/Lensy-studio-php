<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../login.php');
}

// Ensure permission
if (!hasPermission($pdo, 'manage_bookings')) {
    setFlashMessage('error', 'Bạn không có quyền quản lý đơn hàng.');
    redirect('../admin/orders.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? '';

    $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];

    if (!$id || !in_array($status, $validStatuses)) {
        setFlashMessage('error', 'Yêu cầu cập nhật không hợp lệ.');
        redirect('../admin/orders.php');
    }

    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        $statusLabels = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        $viStatus = $statusLabels[$status];

        setFlashMessage('success', "Đã cập nhật trạng thái đơn hàng #$id thành: $viStatus.");
    } catch (PDOException $e) {
        setFlashMessage('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
    }
}

redirect('../admin/orders.php');
