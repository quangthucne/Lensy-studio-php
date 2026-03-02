<?php
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'helpers/vnpay_helper.php';
require_once 'helpers/mailer.php';

$isValid = false;
$isSuccess = false;
$message = "Thao tác thanh toán bị hủy hoặc không thành công.";

if (isset($_GET['vnp_SecureHash'])) {
    $isValid = validateVNPayHash($_GET);
}

if ($isValid) {
    if ($_GET['vnp_ResponseCode'] == '00') {
        $orderCode = $_GET['vnp_TxnRef'];
        
        try {
            // Check if order exists and is unpaid
            $stmt = $pdo->prepare("SELECT id, customer_email, customer_name, total_amount, payment_status FROM orders WHERE code = ?");
            $stmt->execute([$orderCode]);
            $order = $stmt->fetch();
            
            if ($order) {
                if ($order['payment_status'] !== 'paid') {
                    // Update to paid
                    $updateStmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = ?");
                    $updateStmt->execute([$order['id']]);
                    
                    // Send Email Confirmation now that it's paid
                    sendOrderConfirmationEmail($order['customer_email'], $order['customer_name'], $orderCode, $order['total_amount']);
                }
                $isSuccess = true;
                $message = "Giao dịch thanh toán thành công!";
            } else {
                $message = "Không tìm thấy đơn hàng trong hệ thống.";
            }
        } catch (Exception $e) {
            $message = "Lỗi cập nhật hệ thống: " . $e->getMessage();
        }
    } else {
        $message = "Giao dịch lỗi. Mã lỗi: " . $_GET['vnp_ResponseCode'];
    }
} else {
    $message = "Chữ ký bảo mật không hợp lệ (Invalid Signature).";
}
?>
<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<main class="w-full bg-background text-foreground min-h-screen flex items-center justify-center py-20">
    <div class="max-w-md w-full bg-card border border-border rounded-lg p-8 text-center space-y-6">
        <?php if ($isSuccess): ?>
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-10 h-10"></i>
            </div>
            <h1 class="text-3xl font-bold text-green-600">Thanh Toán Thành Công</h1>
            <p class="text-muted-foreground"><?php echo $message; ?></p>
            <div class="bg-secondary/10 p-4 rounded-md text-left text-sm mt-4 space-y-2">
                <p><strong>Mã Đơn Hàng:</strong> <span class="text-primary font-bold"><?php echo htmlspecialchars($_GET['vnp_TxnRef']); ?></span></p>
                <p><strong>Số Tiền:</strong> <?php echo number_format($_GET['vnp_Amount'] / 100, 0, ',', '.'); ?> VND</p>
                <p><strong>Mã GD VNPay:</strong> <?php echo htmlspecialchars($_GET['vnp_TransactionNo']); ?></p>
                <p><strong>Thông báo:</strong> Đã gửi biên lai về email của bạn.</p>
            </div>
        <?php else: ?>
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="x-circle" class="w-10 h-10"></i>
            </div>
            <h1 class="text-3xl font-bold text-red-600">Thanh Toán Thất Bại</h1>
            <p class="text-muted-foreground"><?php echo $message; ?></p>
            <p class="text-sm mt-4">Vui lòng thử lại hoặc liên hệ hỗ trợ nếu bạn đã bị trừ tiền.</p>
        <?php endif; ?>
        
        <div class="pt-6">
            <a href="index.php" class="inline-block bg-primary text-primary-foreground px-6 py-3 rounded-md font-semibold hover:bg-primary/90 transition-colors">
                Quay Lại Trang Chủ
            </a>
        </div>
    </div>
</main>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include 'components/footer.php'; ?>
