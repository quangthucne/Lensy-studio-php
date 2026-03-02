<?php
require_once 'includes/header.php';
requirePermission($pdo, 'manage_products');

$product = null;
$isEdit = false;

// Fetch active categories for dropdown
$catStmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
$categories = $catStmt->fetchAll();

if (isset($_GET['id'])) {
    $isEdit = true;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
    
    if (!$product) {
        setFlashMessage('error', 'Product not found.');
        redirect('products.php');
    }
}
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo $isEdit ? 'Edit Product' : 'Add New Product'; ?></h2>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
    <form action="../controllers/admin_save_product.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2" 
                   value="<?php echo $isEdit ? htmlspecialchars($product['name']) : ''; ?>">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Danh mục</label>
            <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                <option value="">Chọn danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                        <?php echo ($isEdit && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Giá thuê (theo ngày)</label>
                <input type="number" name="rental_price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                       value="<?php echo $isEdit ? $product['rental_price_per_day'] : ''; ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phí đặt cọc</label>
                <input type="number" name="deposit_fee" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                       value="<?php echo $isEdit ? $product['deposit_fee'] : ''; ?>">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
             <div>
                <label class="block text-sm font-medium text-gray-700">Phí bảo hiểm</label>
                <input type="number" name="insurance_fee" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                       value="<?php echo $isEdit ? $product['insurance_fee'] : '0'; ?>">
            </div>
             <div>
                <label class="block text-sm font-medium text-gray-700">Tổng số lượng</label>
                <input type="number" name="total_stock" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"
                       value="<?php echo $isEdit ? $product['total_stock_quantity'] : '1'; ?>">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Mô tả</label>
            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2"><?php echo $isEdit ? htmlspecialchars($product['description']) : ''; ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Hình ảnh</label>
            <?php if ($isEdit && !empty($product['image_url'])): ?>
                <div class="mb-2">
                    <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="Current Image" class="h-20 w-20 object-cover rounded">
                </div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100
            ">
        </div>

        <div class="flex justify-end gap-3">
            <a href="products.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Hủy</a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Lưu Sản phẩm</button>
        </div>
    </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
