<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để xem thông tin này.']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn hàng.']);
    exit;
}

$orderId = (int)$_GET['id'];
$currentUser = getCurrentUser($pdo);
$isAdminOrManager = hasRole($pdo, ['admin', 'manager']);

try {
    // Look up the order
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
        exit;
    }

    // Check permission: Admin/Manager can view all. Customers can only view their own (match email)
    if (!$isAdminOrManager && $order['customer_email'] !== $currentUser['email']) {
        echo json_encode(['success' => false, 'message' => 'Bạn không có quyền xem đơn hàng này.']);
        exit;
    }

    // Format data
    $order['total_amount_formatted'] = number_format($order['total_amount'], 0, ',', '.') . ' ₫';
    $order['created_at_formatted'] = date('d/m/Y H:i', strtotime($order['created_at']));
    
    // Check if it has an associated booking
    $stmtObj = $pdo->prepare("
        SELECT ob.*, s.name as service_name 
        FROM order_bookings ob 
        JOIN services s ON ob.service_id = s.id 
        WHERE ob.order_id = ?
    ");
    $stmtObj->execute([$order['id']]);
    $booking = $stmtObj->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        $bookingSubStatuses = [
            'scheduled' => 'Đã Lên Lịch',
            'shooting' => 'Đang Chụp',
            'editing' => 'Đang Chỉnh Sửa',
            'delivered_files' => 'Đã Bàn Giao File',
            'finished' => 'Hoàn Tất'
        ];
        $booking['booking_time_formatted'] = date('d/m/Y H:i', strtotime($booking['booking_time']));
        $booking['status_formatted'] = $bookingSubStatuses[$booking['status']] ?? ucfirst($booking['status']);
        $order['booking'] = $booking;
    }

    // Fetch rental items
    $stmtRentals = $pdo->prepare("
        SELECT or_r.*, p.name as product_name, p.image_url as product_image 
        FROM order_rentals or_r
        JOIN products p ON or_r.product_id = p.id
        WHERE or_r.order_id = ?
    ");
    $stmtRentals->execute([$order['id']]);
    $rentals = $stmtRentals->fetchAll(PDO::FETCH_ASSOC);

    if (count($rentals) > 0) {
        foreach ($rentals as &$rental) {
            $rental['price_formatted'] = number_format($rental['price_per_day'], 0, ',', '.') . ' ₫';
            $rental['total_formatted'] = number_format($rental['price_per_day'] * $rental['quantity'], 0, ',', '.') . ' ₫';
        }
        $order['rentals'] = $rentals;
    }

    echo json_encode(['success' => true, 'data' => $order]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>
