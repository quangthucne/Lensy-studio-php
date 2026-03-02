<?php
session_start();
require_once '../config/config.php';
require_once '../config/db.php';
require_once '../helpers/functions.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!hasPermission($pdo, 'manage_products')) {
    setFlashMessage('error', 'Access Denied.');
    redirect('../admin/products.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $categoryId = $_POST['category_id'] ?? '';
    $rentalPrice = $_POST['rental_price'] ?? 0;
    $depositFee = $_POST['deposit_fee'] ?? 0;
    $insuranceFee = $_POST['insurance_fee'] ?? 0;
    $totalStock = $_POST['total_stock'] ?? 1;
    $description = $_POST['description'] ?? '';

    // Simple validation
    if (!$name || !$categoryId || !$rentalPrice) {
        setFlashMessage('error', 'Vui lòng nhập đầy đủ thông tin.');
        if ($id) {
            redirect("../admin/product_form.php?id=$id");
        } else {
            redirect("../admin/product_form.php");
        }
    }

    // Handle Image Upload
    $imageUrl = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        try {
            $imageUrl = uploadImage($_FILES['image'], 'assets/uploads/');
        } catch (Exception $e) {
            setFlashMessage('error', 'Lỗi khi tải ảnh: ' . $e->getMessage());
            if ($id) {
                redirect("../admin/product_form.php?id=$id");
            } else {
                redirect("../admin/product_form.php");
            }
        }
    }

    try {
        if ($id) {
            // Update
            $sql = "UPDATE products SET category_id=?, name=?, rental_price_per_day=?, deposit_fee=?, insurance_fee=?, description=?, total_stock_quantity=?";
            $params = [$categoryId, $name, $rentalPrice, $depositFee, $insuranceFee, $description, $totalStock];
            
            if ($imageUrl) {
                $sql .= ", image_url=?";
                $params[] = $imageUrl;
            }
            
            $sql .= " WHERE id=?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            setFlashMessage('success', 'Sản phẩm đã được cập nhật.');

        } else {
            // Insert
            // If no image uploaded for new product, use placeholder
            if (!$imageUrl) {
                $imageUrl = 'assets/placeholder.jpg'; 
            }

            $sql = "INSERT INTO products (category_id, name, rental_price_per_day, deposit_fee, insurance_fee, description, total_stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$categoryId, $name, $rentalPrice, $depositFee, $insuranceFee, $description, $totalStock, $imageUrl]);
            setFlashMessage('success', 'Sản phẩm đã được tạo.');
        }

    } catch (PDOException $e) {
        setFlashMessage('error', 'Lỗi khi lưu sản phẩm: ' . $e->getMessage());
        if ($id) {
            redirect("../admin/product_form.php?id=$id");
        } else {
            redirect("../admin/product_form.php");
        }
    }
}

redirect('../admin/products.php');
