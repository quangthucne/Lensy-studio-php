<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

function sendOrderConfirmationEmail($toEmail, $customerName, $orderCode, $amount) {
    if (empty(SMTP_USER) || SMTP_USER === 'your_email@gmail.com') {
        // Skip sending email if not configured
        error_log("Email sending skipped: SMTP not configured. Order Code: $orderCode");
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0; 
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        //Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($toEmail, $customerName);

        // Content
        $formattedAmount = number_format($amount, 0, ',', '.') . ' ₫';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        $mail->isHTML(true);
        $mail->Subject = "Xác nhận đặt lịch/đơn hàng thành công tại Lensy Studio. Mã đơn: $orderCode";
        
        $mail->Body = "
            <h2>Xin chào, $customerName!</h2>
            <p>Cảm ơn bạn đã sử dụng dịch vụ tại Lensy Studio. Yêu cầu của bạn đã được ghi nhận.</p>
            <p>Dưới đây là thông tin chi tiết:</p>
            <ul>
                <li><strong>Mã Đơn Hàng:</strong> <span style='color: #e1ad01; font-weight: bold; font-size: 1.1em;'>$orderCode</span></li>
                <li><strong>Tổng Thanh Toán:</strong> $formattedAmount</li>
            </ul>
            <p>Vui lòng ghi nhớ hoặc lưu lại <strong>Mã Đơn Hàng</strong> này.</p>
            <p>Bạn có thể sử dụng tính năng <a href='" . (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$host/track-order.php'>Tra Cứu Đơn Hàng</a> trên trang web của chúng tôi cùng với email này để theo dõi trạng thái lịch hẹn/đơn hàng bất kỳ lúc nào.</p>
            <br>
            <p>Trân trọng,<br>Lensy Studio Team</p>
        ";
        
        $mail->AltBody = "Xin chào, $customerName!\n\nCảm ơn bạn đã sử dụng dịch vụ tại Lensy Studio. Yêu cầu của bạn đã được ghi nhận.\n\nMã Đơn Hàng: $orderCode\nTổng Thanh Toán: $formattedAmount\n\nVui lòng ghi nhớ Mã Đơn Hàng này để Tra cứu trên website bất cứ lúc nào.\n\nTrân trọng,\nLensy Studio Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
