<?php require_once 'components/header.php'; ?>
<?php requirePermission($pdo, 'manage_products'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Quản lý sản phẩm</h2>
        <p class="text-gray-600">Quản lý thiết bị và trang phục.</p>
    </div>
    <a href="product_form.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium flex items-center">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Thêm sản phẩm
    </a>
</div>

<?php
$search = $_GET['search'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = '';
$params = [];
if ($search !== '') {
    $whereClause = "WHERE p.name LIKE ?";
    $params[] = "%$search%";
}

// Count total
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM products p $whereClause");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Fetch data
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    $whereClause
    ORDER BY p.id DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
?>

<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm tên sản phẩm..." class="border border-gray-300 rounded-md p-2 text-sm w-64 focus:ring-indigo-500 focus:border-indigo-500">
    <button type="submit" class="bg-indigo-600 border border-transparent text-white rounded-md px-4 py-2 text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Tìm kiếm</button>
    <?php if($search): ?>
        <a href="products.php" class="text-sm text-gray-500 self-center hover:text-gray-700 underline">Xóa bộ lọc</a>
    <?php endif; ?>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá/Ngày</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($stmt->rowCount() > 0): ?>
                <?php while ($product = $stmt->fetch()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="h-10 w-10 rounded object-cover">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($product['category_name']); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <?php echo number_format($product['rental_price_per_day'], 0, ',', '.'); ?> VND
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="product_form.php?id=<?php echo $product['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Chỉnh sửa</a>
                        <form action="../controllers/admin_delete_product.php" method="POST" class="inline" onsubmit="return confirm('Xóa sản phẩm này?');">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Không tìm thấy sản phẩm nào.</td>
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
