<?php require_once 'components/header.php'; ?>
<?php requirePermission($pdo, 'manage_bookings'); ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Đặt Lịch</h2>
        <p class="text-gray-600">Quản lý các lịch đặt chụp ảnh.</p>
    </div>
</div>

<?php
$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = '';
$params = [];
if ($search !== '') {
    $whereClause = "WHERE o.customer_name LIKE ? OR o.customer_email LIKE ? OR o.customer_phone LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM order_bookings ob
    JOIN orders o ON ob.order_id = o.id
    JOIN services s ON ob.service_id = s.id
    $whereClause
");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

$stmt = $pdo->prepare("
    SELECT ob.*, o.customer_name, o.customer_email, o.customer_phone, s.name as service_name 
    FROM order_bookings ob
    JOIN orders o ON ob.order_id = o.id
    JOIN services s ON ob.service_id = s.id
    $whereClause
    ORDER BY ob.booking_time ASC
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
?>

<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm tên khách, email, sđt..." class="border border-gray-300 rounded-md p-2 text-sm w-64 focus:ring-indigo-500 focus:border-indigo-500">
    <button type="submit" class="bg-indigo-600 border border-transparent text-white rounded-md px-4 py-2 text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Tìm kiếm</button>
    <?php if($search): ?>
        <a href="bookings.php" class="text-sm text-gray-500 self-center hover:text-gray-700 underline">Xóa bộ lọc</a>
    <?php endif; ?>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dịch Vụ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời Gian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa Điểm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($stmt->rowCount() > 0): ?>
                <?php while ($booking = $stmt->fetch()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #<?php echo $booking['id']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($booking['customer_name']); ?></div>
                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['customer_email']); ?></div>
                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['customer_phone']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo htmlspecialchars($booking['service_name']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo date('M d, Y H:i', strtotime($booking['booking_time'])); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($booking['location'] ?? 'Studio'); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php 
                        $statusColors = [
                            'scheduled' => 'bg-blue-100 text-blue-800',
                            'shooting' => 'bg-yellow-100 text-yellow-800',
                            'editing' => 'bg-purple-100 text-purple-800',
                            'delivered_files' => 'bg-indigo-100 text-indigo-800',
                            'finished' => 'bg-green-100 text-green-800'
                        ];
                        $statusLabels = [
                            'scheduled' => 'Đã lên lịch',
                            'shooting' => 'Đang chụp',
                            'editing' => 'Đang chỉnh sửa',
                            'delivered_files' => 'Đã giao file',
                            'finished' => 'Hoàn thành'
                        ];
                        $colorClass = $statusColors[$booking['status']] ?? 'bg-gray-100 text-gray-800';
                        $label = $statusLabels[$booking['status']] ?? ucfirst(str_replace('_', ' ', $booking['status']));
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $colorClass; ?>">
                            <?php echo $label; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex gap-2">
                        <?php if ($booking['status'] === 'scheduled'): ?>
                            <form action="../controllers/admin_update_status.php" method="POST" onsubmit="return confirm('Xác nhận lịch đặt này?');">
                                <input type="hidden" name="type" value="booking">
                                <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="text-green-600 hover:text-green-900 font-medium">Xác nhận</button>
                            </form>
                            <form action="../controllers/admin_update_status.php" method="POST" onsubmit="return confirm('Hủy lịch đặt này?');">
                                <input type="hidden" name="type" value="booking">
                                <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hủy</button>
                            </form>
                        <?php elseif ($booking['status'] === 'confirmed'): ?>
                             <form action="../controllers/admin_update_status.php" method="POST">
                                <input type="hidden" name="type" value="booking">
                                <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                <input type="hidden" name="action" value="start">
                                <button type="submit" class="text-blue-600 hover:text-blue-900 font-medium">Bắt đầu chụp</button>
                            </form>
                        <?php elseif ($booking['status'] === 'shooting'): ?>
                             <form action="../controllers/admin_update_status.php" method="POST">
                                <input type="hidden" name="type" value="booking">
                                <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                <input type="hidden" name="action" value="complete">
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-medium">Hoàn thành</button>
                            </form>
                        <?php else: ?>
                            <span class="text-gray-400">Không có thao tác</span>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Không tìm thấy lịch đặt chụp nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <nav class="flex items-center justify-between">
            <div class="hidden sm:block">
                <p class="text-sm text-gray-700">
                    Hiển thị trang <span class="font-medium"><?php echo $page; ?></span> trên <span class="font-medium"><?php echo $totalPages; ?></span>
                </p>
            </div>
            <div class="flex-1 flex justify-between sm:justify-end">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trang trước
                    </a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trang sau
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'components/footer.php'; ?>
