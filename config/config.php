<?php
// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'lensy-studio');
define('DB_USER', 'root');
define('DB_PASS', ''); 

// App configuration
define('BASE_URL', '/'); 

// SMTP Configuration (for PHPMailer)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); 
define('SMTP_USER', 'thucbq.goldenbee@gmail.com'); 
define('SMTP_PASS', 'vkwhthezfgxscgyt'); 
define('SMTP_FROM_EMAIL', 'no-reply@studiolensy.vn');
define('SMTP_FROM_NAME', 'Lensy Studio');

// VNPay Configuration (Sandbox)
define('VNP_TMN_CODE', 'VKYZVJII'); 
define('VNP_HASH_SECRET', 'GKMAD0X2VPC5C3DOWQHOOAV1JWL34041'); 
define('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'); 
define('VNP_RETURN_URL', 'https://lensy-studio-php.test/vnpay_return.php'); 

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
