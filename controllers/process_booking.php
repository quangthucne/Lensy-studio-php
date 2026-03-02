<?php
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';
require_once '../helpers/mailer.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;

// The form could submit via JSON or regular POST
if (empty($_POST) && $input = json_decode(file_get_contents('php://input'), true)) {
    $_POST = $input;
}

$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$fullName = trim("$firstName $lastName");
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$serviceType = $_POST['serviceType'] ?? 'photography';
$eventDate = $_POST['eventDate'] ?? date('Y-m-d');
$message = trim($_POST['message'] ?? '');

if (empty($firstName) || empty($email) || empty($eventDate)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Create Order
    $code = 'BKG-' . strtoupper(uniqid());
    
    // Check for package
    $packageId = $_POST['packageId'] ?? '';
    $packagePrice = null;
    $packageName = null;
    
    if ($packageId) {
        $packagesData = require '../config/packages_data.php';
        foreach ($packagesData as $cat) {
            foreach ($cat['tiers'] as $tier) {
                if ($tier['id'] === $packageId) {
                    $packagePrice = $tier['price_num'];
                    $packageName = $cat['category'] . ' - ' . $tier['name'];
                    break 2;
                }
            }
        }
    }

    $stmt = $pdo->query("SELECT id, base_price, name FROM services LIMIT 1");
    $service = $stmt->fetch();
    $serviceId = $service['id'] ?? 1;
    $price = $packagePrice !== null ? $packagePrice : ($service['base_price'] ?? 1500000);
    
    // Add package info to note
    if ($packageName) {
        if ($message) $message .= "\n";
        $message .= "Đã chọn gói dịch vụ: " . $packageName;
    }
    
    $paymentMethod = $_POST['paymentMethod'] ?? 'cash';

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, code, total_amount, status, payment_status, note, payment_method) VALUES (?, ?, ?, ?, ?, ?, 'pending', 'unpaid', ?, ?)");
    $stmt->execute([$userId, $fullName, $email, $phone, $code, $price, $message, $paymentMethod]);
    $orderId = $pdo->lastInsertId();

    // Create Booking
    $bookingTime = $eventDate . ' 09:00:00'; // Default time
    
    $stmt = $pdo->prepare("INSERT INTO order_bookings (order_id, service_id, booking_time, location, price, status) VALUES (?, ?, ?, ?, ?, 'scheduled')");
    $stmt->execute([$orderId, $serviceId, $bookingTime, 'Studio', $price]);

    $pdo->commit();
    
    if ($paymentMethod === 'cash') {
        sendOrderConfirmationEmail($email, $fullName, $code, $price);
        echo json_encode(['success' => true, 'message' => 'Đặt lịch thành công!', 'orderCode' => $code]);
    } else if ($paymentMethod === 'vnpay') {
        require_once '../helpers/vnpay_helper.php';
        $vnpayUrl = generateVNPayUrl($code, $price, "Thanh toan dat lich $code tai Lensy Studio");
        echo json_encode(['success' => true, 'redirect' => $vnpayUrl, 'orderCode' => $code]);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>
