<?php
// seed_orders.php
require_once 'config/config.php';
require_once 'config/db.php';

echo "Bắt đầu tạo dữ liệu Seed cho Đơn hàng và Booking (30 ngày qua)...\n";

try {
    $pdo->beginTransaction();

    // Lấy một số user, product, service, asset có sẵn
    $users = $pdo->query("SELECT id FROM users WHERE status = 'active' LIMIT 10")->fetchAll(PDO::FETCH_COLUMN);
    $products = $pdo->query("SELECT id, rental_price_per_day, deposit_fee FROM products WHERE is_active = 1 LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
    $services = $pdo->query("SELECT id, base_price FROM services WHERE is_active = 1 LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($products) && empty($services)) {
        die("Lỗi: Không có product hoặc service nào trong Database. Vui lòng chạy seed.sql trước.\n");
    }

    $userIds = empty($users) ? [null] : $users;

    $now = time();
    $thirtyDaysAgo = strtotime('-30 days');

    $statuses = ['pending', 'confirmed', 'processing', 'completed', 'completed', 'completed', 'completed', 'cancelled']; // Tăng tỷ lệ completed
    $paymentStatuses = ['unpaid', 'paid', 'paid', 'paid']; // Tăng tỷ lệ paid
    $paymentMethods = ['cash', 'vnpay', 'bank_transfer'];

    $totalOrders = 80; // Tạo 80 đơn hàng

    for ($i = 0; $i < $totalOrders; $i++) {
        // Random ngày tạo trong 30 ngày qua
        $randomTime = rand($thirtyDaysAgo, $now);
        $createdAt = date('Y-m-d H:i:s', $randomTime);

        $userId = $userIds[array_rand($userIds)];
        $code = 'ORD-SEED-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $status = $statuses[array_rand($statuses)];
        $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
        $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

        $totalAmount = 0;

        // INSERT ORDER TEMP (sẽ update total_amount sau)
        $stmtOrder = $pdo->prepare("
            INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, code, total_amount, status, payment_status, payment_method, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtOrder->execute([
            $userId, 
            "Khách Hàng " . rand(100, 999), 
            "khach" . rand(100, 999) . "@example.com", 
            "09" . rand(10000000, 99999999), 
            $code, 
            0, 
            $status, 
            $paymentStatus, 
            $paymentMethod, 
            $createdAt
        ]);
        $orderId = $pdo->lastInsertId();

        // Random có thuê đồ hay không
        $hasRentals = rand(0, 1) && !empty($products);
        $hasBookings = !$hasRentals && !empty($services); // Đảm bảo có ít nhất 1 loại
        if (rand(0, 10) > 8 && !empty($products) && !empty($services)) {
            $hasRentals = true;
            $hasBookings = true;
        }

        if ($hasRentals) {
            $numItems = rand(1, 3);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 2);
                $days = rand(1, 5);
                
                $pricePerDay = $product['rental_price_per_day'];
                $itemTotal = $pricePerDay * $quantity * $days;
                $totalAmount += $itemTotal;

                $startTime = date('Y-m-d H:i:s', strtotime("+1 day", $randomTime));
                $endTime = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($startTime)));

                $rentalStatus = ($status == 'completed') ? 'returned' : 'picked_up';

                $stmtRental = $pdo->prepare("
                    INSERT INTO order_rentals (order_id, product_id, quantity, start_time, end_time, price_per_day, deposit_amount, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmtRental->execute([
                    $orderId, 
                    $product['id'], 
                    $quantity, 
                    $startTime, 
                    $endTime, 
                    $pricePerDay, 
                    $product['deposit_fee'] * $quantity,
                    $rentalStatus
                ]);
            }
        }

        if ($hasBookings) {
            $service = $services[array_rand($services)];
            $bookingPrice = $service['base_price'] * (rand(10, 20) / 10); // Random multiplier for variation
            $totalAmount += $bookingPrice;

            $bookingTime = date('Y-m-d H:i:s', strtotime("+" . rand(1, 14) . " days", $randomTime));
            $bookingStatus = ($status == 'completed') ? 'finished' : 'scheduled';

            $stmtBooking = $pdo->prepare("
                INSERT INTO order_bookings (order_id, service_id, booking_time, location, price, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmtBooking->execute([
                $orderId,
                $service['id'],
                $bookingTime,
                'Studio Lensy ' . rand(1, 3),
                $bookingPrice,
                $bookingStatus
            ]);
        }

        // Cập nhật lại tổng tiền đơn hàng (Cộng thêm VAT 10% như thực tế)
        $totalAmount = $totalAmount * 1.1;
        $stmtUpdateOrder = $pdo->prepare("UPDATE orders SET total_amount = ? WHERE id = ?");
        $stmtUpdateOrder->execute([$totalAmount, $orderId]);
    }

    $pdo->commit();
    echo "Đã tạo thành công $totalOrders đơn hàng mẫu cho 30 ngày qua!\n";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Lỗi khi Seed: " . $e->getMessage() . "\n";
}
