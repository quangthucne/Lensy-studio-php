<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($fullName)) {
        setFlashMessage('error', 'Tên đầy đủ là bắt buộc.');
        redirect('../profile.php');
    }

    try {
        // Handle Avatar Upload
        $avatarUrl = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            try {
                // Uploads to assets/uploads/
                $avatarUrl = uploadImage($_FILES['avatar'], 'assets/uploads/');
            } catch (Exception $e) {
                setFlashMessage('error', 'Lỗi khi tải ảnh đại diện: ' . $e->getMessage());
                redirect('../profile.php');
            }
        }

        // Build Update Query
        $sql = "UPDATE users SET full_name = ?, phone = ?";
        $params = [$fullName, $phone];

        // Password Update
        if (!empty($password)) {
            if ($password !== $confirmPassword) {
                setFlashMessage('error', 'Mật khẩu không khớp.');
                redirect('../profile.php');
            }
            $sql .= ", password_hash = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Avatar Update
        if ($avatarUrl) {
            $sql .= ", avatar_url = ?";
            $params[] = $avatarUrl;
        }

        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Update Session Name if changed
        $_SESSION['user_name'] = $fullName;

        setFlashMessage('success', 'Cập nhật thông tin thành công.');
        redirect('../profile.php');

    } catch (PDOException $e) {
        setFlashMessage('error', 'Lỗi khi cập nhật thông tin: ' . $e->getMessage());
        redirect('../profile.php');
    }
}

redirect('../profile.php');
