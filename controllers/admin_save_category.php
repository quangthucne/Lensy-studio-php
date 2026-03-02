<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!hasPermission($pdo, 'manage_products')) {
    setFlashMessage('error', 'Chỉ có quản trị viên mới được phép thực hiện thao tác này.');
    redirect('../admin/categories.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $type = $_POST['type'] ?? 'service';
    $icon = trim($_POST['icon'] ?? '📷');

    if (empty($name) || empty($slug)) {
        setFlashMessage('error', 'Vui lòng nhập tên và slug.');
        if ($id) {
            redirect("../admin/category_form.php?id=$id");
        } else {
            redirect("../admin/category_form.php");
        }
    }

    try {
        // Kiểm tra slug trùng lặp
        $checkStmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
        $checkStmt->execute([$slug, $id ?? 0]);
        if ($checkStmt->fetch()) {
            setFlashMessage('error', 'Slug này đã tồn tại, vui lòng chọn slug khác.');
            if ($id) {
                redirect("../admin/category_form.php?id=$id");
            } else {
                redirect("../admin/category_form.php");
            }
        }

        if ($id) {
            // Update
            $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, type=?, icon=? WHERE id=?");
            $stmt->execute([$name, $slug, $type, $icon, $id]);
            setFlashMessage('success', 'Danh mục đã được cập nhật thành công.');
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, type, icon) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $type, $icon]);
            setFlashMessage('success', 'Danh mục mới đã được tạo thành công.');
        }

    } catch (PDOException $e) {
        setFlashMessage('error', 'Lỗi dữ liệu: ' . $e->getMessage());
        if ($id) {
            redirect("../admin/category_form.php?id=$id");
        } else {
            redirect("../admin/category_form.php");
        }
    }
}

redirect('../admin/categories.php');
