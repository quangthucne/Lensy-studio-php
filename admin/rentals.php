<?php require_once 'components/header.php'; ?>
<?php requirePermission($pdo, 'manage_bookings'); ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Đồ Thuê</h2>
        <p class="text-gray-600">Theo dõi thiết bị và phục trang đang cho thuê.</p>
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
    $whereClause = "WHERE o.customer_name LIKE ? OR p.name LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM order_rentals or_
    JOIN orders o ON or_.order_id = o.id
    JOIN products p ON or_.product_id = p.id
    $whereClause
");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

$stmt = $pdo->prepare("
    SELECT or_.*, o.customer_name, p.name as product_name 
    FROM order_rentals or_
    JOIN orders o ON or_.order_id = o.id
    JOIN products p ON or_.product_id = p.id
    $whereClause
    ORDER BY or_.start_time DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
?>

<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm khách hàng, thiết bị..." class="border border-gray-300 rounded-md p-2 text-sm w-64 focus:ring-indigo-500 focus:border-indigo-500">
    <button type="submit" class="bg-indigo-600 border border-transparent text-white rounded-md px-4 py-2 text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Tìm kiếm</button>
    <?php if($search): ?>
        <a href="rentals.php" class="text-sm text-gray-500 self-center hover:text-gray-700 underline">Xóa bộ lọc</a>
    <?php endif; ?>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thiết Bị</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời Hạn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Trả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($stmt->rowCount() > 0): ?>
                <?php while ($rental = $stmt->fetch()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #<?php echo $rental['id']; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($rental['customer_name']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo htmlspecialchars($rental['product_name']); ?>
                        <span class="text-xs text-gray-500 block">Qty: <?php echo $rental['quantity']; ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo date('M d', strtotime($rental['start_time'])); ?> - 
                        <?php echo date('M d', strtotime($rental['end_time'])); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php 
                        $statusColors = [
                            'reserved' => 'bg-blue-100 text-blue-800',
                            'picked_up' => 'bg-yellow-100 text-yellow-800',
                            'returned' => 'bg-green-100 text-green-800',
                            'overdue' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'reserved' => 'Đã đặt',
                            'picked_up' => 'Đã lấy',
                            'returned' => 'Đã trả',
                            'overdue' => 'Quá hạn',
                        ];
                        $colorClass = $statusColors[$rental['status']] ?? 'bg-gray-100 text-gray-800';
                        $label = $statusLabels[$rental['status']] ?? ucfirst(str_replace('_', ' ', $rental['status']));
                        ?>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $colorClass; ?>">
                            <?php echo $label; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                         <?php echo date('M d, H:i', strtotime($rental['end_time'])); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex gap-2">
                        <?php if ($rental['status'] === 'reserved'): ?>
                            <form action="../controllers/admin_update_status.php" method="POST">
                                <input type="hidden" name="type" value="rental">
                                <input type="hidden" name="id" value="<?php echo $rental['id']; ?>">
                                <input type="hidden" name="action" value="pickup">
                                <button type="submit" class="text-blue-600 hover:text-blue-900 font-medium">Lấy đồ</button>
                            </form>
                             <form action="../controllers/admin_update_status.php" method="POST" onsubmit="return confirm('Hủy thuê đồ này?');">
                                <input type="hidden" name="type" value="rental">
                                <input type="hidden" name="id" value="<?php echo $rental['id']; ?>">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hủy</button>
                            </form>
                        <?php elseif ($rental['status'] === 'picked_up'): ?>
                            <form action="../controllers/admin_update_status.php" method="POST">
                                <input type="hidden" name="type" value="rental">
                                <input type="hidden" name="id" value="<?php echo $rental['id']; ?>">
                                <input type="hidden" name="action" value="return">
                                <button type="submit" class="text-green-600 hover:text-green-900 font-medium">Trả đồ</button>
                            </form>
                        <?php else: ?>
                            <span class="text-gray-400">Đã lưu trữ</span>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Không tìm thấy yêu cầu thuê đồ nào.</td>
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
