<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('../login.php');
}

// Check permission. Both bookings and rentals fall under 'manage_bookings' for now.
if (!hasPermission($pdo, 'manage_bookings')) {
    setFlashMessage('error', 'Bạn không có quyền thực hiện hành động này.');
    redirect('../admin/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? ''; // 'booking' or 'rental'
    $id = $_POST['id'] ?? 0;
    $action = $_POST['action'] ?? '';

    if (!$id || !$action) {
        setFlashMessage('error', 'Yêu cầu không hợp lệ.');
        redirect('../admin/index.php');
    }

    try {
        if ($type === 'booking') {
            $status = '';
            switch ($action) {
                case 'approve':
                    $status = 'confirmed';
                    break;
                case 'start':
                    $status = 'shooting';
                    break;
                case 'complete':
                    $status = 'finished';
                    break;
                case 'cancel':
                    $status = 'cancelled';
                    break;
                default:
                    throw new Exception("Hành động không hợp lệ");
            }

            $stmt = $pdo->prepare("UPDATE order_bookings SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            setFlashMessage('success', "Đã cập nhật trạng thái của đơn đặt hàng #$id thành $status.");
            redirect('../admin/bookings.php');

        } elseif ($type === 'rental') {
            $status = '';
            $fullUpdate = false;
            switch ($action) {
                case 'pickup':
                    $status = 'picked_up';
                    break;
                case 'return':
                    $status = 'returned';
                    $fullUpdate = true; // Set actual_return_time
                    break;
                case 'cancel':
                    $status = 'cancelled';
                    break;
                default:
                    throw new Exception("Hành động không hợp lệ");
            }

            if ($fullUpdate && $status === 'returned') {
                $stmt = $pdo->prepare("UPDATE order_rentals SET status = ?, actual_return_time = NOW() WHERE id = ?");
                $stmt->execute([$status, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE order_rentals SET status = ? WHERE id = ?");
                $stmt->execute([$status, $id]);
            }
            
            setFlashMessage('success', "Đã cập nhật trạng thái của đơn thuê #$id thành $status.");
            redirect('../admin/rentals.php');

        } else {
            throw new Exception("Loại không hợp lệ.");
        }

    } catch (Exception $e) {
        setFlashMessage('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        redirect('../admin/index.php');
    }
} else {
    redirect('../admin/index.php');
}
