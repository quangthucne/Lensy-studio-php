<?php
require_once 'config/config.php';
require_once 'helpers/mailer.php';

$toEmail = 'thucbq.goldenbee@gmail.com'; 
$customerName = 'Thuc Test';
$orderCode = 'TEST-1234';
$amount = 999000;

echo "Sending test email to $toEmail...\n";
$result = sendOrderConfirmationEmail($toEmail, $customerName, $orderCode, $amount);

if ($result) {
    echo "SUCCESS: Email sent successfully!\n";
} else {
    echo "FAILED: Email could not be sent. Check error logs.\n";
}
?>
