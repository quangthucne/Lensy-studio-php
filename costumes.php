<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<?php
$categories = [
    ["id" => "all", "name" => "Tất Cả"],
    ["id" => "ao-dai", "name" => "Áo Dài"],
    ["id" => "wedding", "name" => "Váy Cưới"],
    ["id" => "vintage", "name" => "Đồ Cổ Điển"],
    ["id" => "accessories", "name" => "Phụ Kiện"],
];


// Fetch categories for filter
$stmt = $pdo->prepare("SELECT * FROM categories WHERE type = 'rental_fashion'");
$stmt->execute();
$dbCategories = $stmt->fetchAll();

$categories = [["id" => "all", "name" => "Tất Cả"]];
foreach ($dbCategories as $cat) {
    if ($cat['slug'] === 'fashion-ao-dai') $id = 'ao-dai';
    elseif ($cat['slug'] === 'fashion-wedding') $id = 'wedding'; 
    elseif ($cat['slug'] === 'fashion-vintage') $id = 'vintage';
    else $id = $cat['slug']; // fallback

    $categories[] = ["id" => $id, "name" => $cat['name'], "db_id" => $cat['id']];
}
// Add manually others if missing or handle better mapping
if (count($categories) == 1) {
     $categories = [
        ["id" => "all", "name" => "Tất Cả"],
        ["id" => "ao-dai", "name" => "Áo Dài"],
        ["id" => "wedding", "name" => "Váy Cưới"],
        ["id" => "vintage", "name" => "Đồ Cổ Điển"],
        ["id" => "accessories", "name" => "Phụ Kiện"],
    ];
}

// Fetch products
$stmt = $pdo->prepare("
    SELECT p.*, c.slug as category_slug 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE c.type = 'rental_fashion' AND p.is_active = 1
");
$stmt->execute();
$dbProducts = $stmt->fetchAll();

$costumes = [];
foreach ($dbProducts as $prod) {
    // Map DB category slug to view filter id
    $catSlug = $prod['category_slug'];
    if ($catSlug === 'fashion-ao-dai') $catId = 'ao-dai';
    elseif ($catSlug === 'fashion-wedding') $catId = 'wedding';
    elseif ($catSlug === 'fashion-vintage') $catId = 'vintage';
    else $catId = $catSlug;

    $costumes[] = [
        "id" => $prod['id'],
        "category" => $catId,
        "name" => $prod['name'],
        "price" => number_format($prod['rental_price_per_day']),
        "rentalDays" => "3 ngày", // Default or fetch from somewhere
        "image" => $prod['image_url'],
        "description" => $prod['description'],
        "sizes" => $prod['sizes'] ?? "Standard",
        "featured" => (bool)$prod['is_featured'],
    ];
}
?>
?>

<main class="w-full bg-background text-foreground">
    <!-- Hero Section -->
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-5xl md:text-6xl font-bold text-balance">
                    Thuê Trang Phục & Đồ Cưới
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Hoàn thiện khoảnh khắc đặc biệt của bạn với những bộ trang phục tinh xảo từ bộ sưu tập của chúng tôi
                </p>
            </div>
        </div>
    </section>

    <!-- Filter Categories -->
    <section class="py-6 bg-background sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-3 justify-center" id="costume-filters">
                <?php foreach ($categories as $cat): ?>
                    <button 
                        data-id="<?php echo $cat['id']; ?>"
                        class="filter-btn px-6 py-2 rounded-full text-sm font-medium transition-all <?php echo $cat['id'] === 'all' ? 'bg-primary text-primary-foreground' : 'bg-muted text-foreground hover:bg-muted/80 border border-border'; ?>"
                    >
                        <?php echo $cat['name']; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="costume-grid">
                <?php foreach ($costumes as $costume): ?>
                    <div 
                        class="costume-item bg-card border border-border rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col"
                        data-category="<?php echo $costume['category']; ?>"
                    >
                        <!-- Image with overlay -->
                        <div class="relative h-80 bg-muted overflow-hidden">
                            <img 
                                src="<?php echo $costume['image']; ?>" 
                                alt="<?php echo $costume['name']; ?>" 
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                            >
                            <?php if ($costume['featured']): ?>
                                <div class="absolute top-4 right-4 bg-secondary text-foreground px-3 py-1 rounded-full text-xs font-bold">
                                    NỔI BẬT
                                </div>
                            <?php endif; ?>
                            <button class="absolute top-4 left-4 bg-white/90 hover:bg-white p-2 rounded-full transition-all">
                                <i data-lucide="heart" class="w-5 h-5 text-gray-400"></i>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold mb-2">
                                <?php echo $costume['name']; ?>
                            </h3>
                            <p class="text-muted-foreground text-sm mb-4">
                                <?php echo $costume['description']; ?>
                            </p>

                            <div class="space-y-3 mb-6 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">Các cỡ có sẵn:</span>
                                    <span class="font-semibold"><?php echo $costume['sizes']; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">Thời gian thuê:</span>
                                    <span class="font-semibold"><?php echo $costume['rentalDays']; ?></span>
                                </div>
                            </div>

                            <div class="border-t border-border pt-4 mt-auto">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-xs text-muted-foreground">Giá Thuê</p>
                                        <p class="text-2xl font-bold text-primary">
                                            <?php echo $costume['price']; ?> 
                                            <span class="text-xs text-muted-foreground">VND</span>
                                        </p>
                                    </div>
                                </div>
                                <button 
                                    onclick="addToCart({id: <?php echo $costume['id']; ?>, name: '<?php echo addslashes($costume['name']); ?>', price: '<?php echo str_replace(',', '', $costume['price']); ?>', image: '<?php echo $costume['image']; ?>', quantity: 1})"
                                    class="w-full bg-foreground text-background hover:bg-foreground/90 font-semibold py-6 flex items-center justify-center gap-2 rounded-md transition-colors">
                                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                    Thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Collections Highlights -->
    <section class="py-20 bg-secondary/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-3 text-balance">
                    Bộ Sưu Tập Của Chúng Tôi
                </h2>
                <p class="text-lg text-muted-foreground">
                    Khám phá các danh mục trang phục đặc biệt của chúng tôi
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <h3 class="text-3xl font-bold text-primary">
                        Áo Dài Truyền Thống
                    </h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Bộ sưu tập Áo Dài phong phú của chúng tôi có các thiết kế truyền thống với màu sắc rực rỡ hoàn hảo cho các lễ hội Tết. Mỗi sản phẩm được lựa chọn cẩn thận về chất lượng và phong cách Việt Nam đích thực.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold text-lg">&check;</span>
                            <span>Màu sắc và hoa văn truyền thống đích thực</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold text-lg">&check;</span>
                            <span>Chất liệu lụa và cotton cao cấp</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold text-lg">&check;</span>
                            <span>Có đủ các cỡ từ XS đến XXL</span>
                        </li>
                    </ul>
                </div>
                <div class="relative h-96 rounded-lg overflow-hidden shadow-xl">
                    <img src="assets/costume-collection-ao-dai.jpg" alt="Áo Dài collection" class="object-cover w-full h-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Combo Offers omitted for brevity, adding Logic Script -->

</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.costume-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.getAttribute('data-id');

                // Update active button state
                filterBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-primary-foreground');
                    b.classList.add('bg-muted', 'text-foreground', 'hover:bg-muted/80');
                });
                btn.classList.remove('bg-muted', 'text-foreground', 'hover:bg-muted/80');
                btn.classList.add('bg-primary', 'text-primary-foreground');

                // Filter items
                items.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
    });
</script>

<?php include 'components/footer.php'; ?>
