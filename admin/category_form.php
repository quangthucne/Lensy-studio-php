<?php require_once 'includes/header.php'; ?>
<?php requirePermission($pdo, 'manage_products'); ?>

<?php
$id = $_GET['id'] ?? null;
$category = null;
$isEdit = false;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ? AND is_active = 1");
    $stmt->execute([$id]);
    $category = $stmt->fetch();
    
    if (!$category) {
        setFlashMessage('error', 'Không tìm thấy danh mục.');
        redirect('categories.php');
    }
    $isEdit = true;
}
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo $isEdit ? 'Sửa Danh mục' : 'Thêm Danh mục Mới'; ?></h2>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
    <form action="../controllers/admin_save_category.php" method="POST" class="space-y-6">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tên danh mục</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" 
                   value="<?php echo $isEdit ? htmlspecialchars($category['name']) : ''; ?>">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Slug (URL)</label>
            <input type="text" name="slug" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" 
                   value="<?php echo $isEdit ? htmlspecialchars($category['slug']) : ''; ?>">
            <p class="text-xs text-gray-500 mt-1">Ví dụ: rental-cameras, service-photography</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Loại (Type)</label>
            <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                <option value="service" <?php echo ($isEdit && $category['type'] == 'service') ? 'selected' : ''; ?>>Dịch vụ (Service)</option>
                <option value="rental_gear" <?php echo ($isEdit && $category['type'] == 'rental_gear') ? 'selected' : ''; ?>>Thiết bị thuê (Rental Gear)</option>
                <option value="rental_fashion" <?php echo ($isEdit && $category['type'] == 'rental_fashion') ? 'selected' : ''; ?>>Trang phục thuê (Rental Fashion)</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Icon (Emoji hoặc Lucide Icon name)</label>
            <input type="text" name="icon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" 
                   value="<?php echo $isEdit ? htmlspecialchars($category['icon']) : '📷'; ?>">
        </div>

        <div class="flex justify-end gap-3">
            <a href="categories.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Hủy</a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                <?php echo $isEdit ? 'Lưu Thay đổi' : 'Lưu Danh mục'; ?>
            </button>
        </div>
    </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
