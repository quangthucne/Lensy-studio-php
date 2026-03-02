<?php
require_once '../config/config.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
    exit;
}

$code = trim($_POST['code'] ?? '');
$email = trim($_POST['email'] ?? '');

if (empty($code) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin Mã Đơn Hàng hoặc Email']);
    exit;
}

try {
    // Look up the order to verify ownership and status
    $stmt = $pdo->prepare("SELECT id, status FROM orders WHERE code = ? AND customer_email = ?");
    $stmt->execute([$code, $email]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng khớp với thông tin cung cấp.']);
        exit;
    }

    if ($order['status'] !== 'pending') {
        echo json_encode(['success' => false, 'message' => 'Chỉ có thể hủy những đơn hàng đang ở trạng thái Chờ Xử Lý.']);
        exit;
    }

    // Process termination
    $pdo->beginTransaction();

    $stmtUpdate = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
    $stmtUpdate->execute([$order['id']]);

    // Optional: Log action or send email notification of cancellation

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Hủy đơn hàng thành công.']);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>
