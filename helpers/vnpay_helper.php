<?php
require_once dirname(__DIR__) . '/config/config.php';

/**
 * Helper file for VNPay Integration
 */

function generateVNPayUrl($orderId, $amount, $orderDesc) {
    $vnp_TmnCode = VNP_TMN_CODE;
    $vnp_HashSecret = VNP_HASH_SECRET;
    $vnp_Url = VNP_URL;
    $vnp_Returnurl = VNP_RETURN_URL;
    
    // VNPay requires amount in VND * 100
    $vnp_Amount = $amount * 100;
    $vnp_Locale = 'vn';
    $vnp_BankCode = 'NCB'; 
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    $vnp_TxnRef = $orderId;

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $orderDesc,
        "vnp_OrderType" => "other",
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    );

    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }

    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";

    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }

    return $vnp_Url;
}

function validateVNPayHash($inputData) {
    $vnp_SecureHash = $inputData['vnp_SecureHash'];
    $vnp_HashSecret = VNP_HASH_SECRET;

    unset($inputData['vnp_SecureHashType']);
    unset($inputData['vnp_SecureHash']);

    ksort($inputData);
    $i = 0;
    $hashData = "";
    foreach ($inputData as $key => $value) {
        // VNPay only signs parameters that start with "vnp_"
        if (substr($key, 0, 4) === "vnp_") {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
    }

    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    return $secureHash === $vnp_SecureHash;
}
?>
