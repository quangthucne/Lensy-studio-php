<?php
require_once '../config/db.php';
require_once '../helpers/functions.php';
require_once '../config/config.php';
require_once '../helpers/mailer.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
$sessionId = session_id();

$input = json_decode(file_get_contents('php://input'), true);

$firstName = $input['firstName'] ?? '';
$lastName = $input['lastName'] ?? '';
$email = $input['email'] ?? '';
$phone = $input['phone'] ?? '';
$eventDate = $input['eventDate'] ?? '';
$eventType = $input['eventType'] ?? '';

if (empty($firstName) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
    exit;
}

// Fetch Cart from DB securely
if ($userId) {
    $stmtCart = $pdo->prepare("
        SELECT c.id as cart_id, p.id, p.name, p.rental_price_per_day as price, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmtCart->execute([$userId]);
} else {
    $stmtCart = $pdo->prepare("
        SELECT c.id as cart_id, p.id, p.name, p.rental_price_per_day as price, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.session_id = ? AND c.user_id IS NULL
    ");
    $stmtCart->execute([$sessionId]);
}
$cart = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart)) {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm!']);
    exit;
}

// Calculate Total Securely
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$totalAmount = $subtotal + ($subtotal * 0.1); // Add 10% tax

try {
    $pdo->beginTransaction();

    // 1. Create Order
    $code = 'ORD-' . strtoupper(uniqid());
    $fullName = trim("$firstName $lastName");
    $paymentMethod = $input['paymentMethod'] ?? 'cash';
    
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, code, total_amount, status, payment_status, payment_method) VALUES (?, ?, ?, ?, ?, ?, 'pending', 'unpaid', ?)");
    $stmt->execute([$userId, $fullName, $email, $phone, $code, $totalAmount, $paymentMethod]);
    $orderId = $pdo->lastInsertId();

    // 2. Process Cart Items
    foreach ($cart as $item) {
        $stmtRent = $pdo->prepare("INSERT INTO order_rentals (order_id, product_id, quantity, start_time, end_time, price_per_day) VALUES (?, ?, ?, ?, ?, ?)");
        $startTime = date('Y-m-d 00:00:00', strtotime($eventDate));
        $endTime = date('Y-m-d 23:59:59', strtotime($eventDate . ' + 1 day'));

        $stmtRent->execute([$orderId, $item['id'], $item['quantity'], $startTime, $endTime, $item['price']]);
    }
    
    // 3. Clear DB Cart
    if ($userId) {
        $stmtClear = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmtClear->execute([$userId]);
    } else {
        $stmtClear = $pdo->prepare("DELETE FROM cart WHERE session_id = ? AND user_id IS NULL");
        $stmtClear->execute([$sessionId]);
    }

    $pdo->commit();
    
    if ($paymentMethod === 'cash') {
        // Gửi email xác nhận
        sendOrderConfirmationEmail($email, $fullName, $code, $totalAmount);
        echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công', 'orderCode' => $code]);
    } else if ($paymentMethod === 'vnpay') {
        require_once '../helpers/vnpay_helper.php';
        $vnpayUrl = generateVNPayUrl($code, $totalAmount, "Thanh toan don hang $code tai Lensy Studio");
        echo json_encode(['success' => true, 'redirect' => $vnpayUrl, 'orderCode' => $code]);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage()]);
}
?>
