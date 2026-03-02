<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<?php
// Fetch categories
$stmt = $pdo->prepare("SELECT * FROM categories WHERE type = 'rental_gear'");
$stmt->execute();
$dbCategories = $stmt->fetchAll();

$categories = [];
foreach ($dbCategories as $cat) {
    if ($cat['slug'] === 'rental-cameras') $id = 'cameras';
    elseif ($cat['slug'] === 'rental-lenses') $id = 'lenses';
    elseif ($cat['slug'] === 'rental-lighting') $id = 'lighting';
    else $id = 'accessories'; // Simplified mapping

    $categories[] = [
        "id" => $id, 
        "name" => $cat['name'], 
        "icon" => $cat['icon']
    ];
}

$dateRanges = [
    ["id" => "1day", "label" => "1 Ngày", "multiplier" => 1],
    ["id" => "3days", "label" => "3 Ngày", "multiplier" => 0.9],
    ["id" => "7days", "label" => "1 Tuần", "multiplier" => 2.5],
    ["id" => "30days", "label" => "1 Tháng", "multiplier" => 7],
    ["id" => "90days", "label" => "3 Tháng", "multiplier" => 18],
];

// Fetch equipment
$stmt = $pdo->prepare("
    SELECT p.*, c.slug as category_slug 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    WHERE c.type = 'rental_gear' AND p.is_active = 1
");
$stmt->execute();
$dbEquipment = $stmt->fetchAll();

$equipment = [];
foreach ($dbEquipment as $item) {
    $catSlug = $item['category_slug'];
    if ($catSlug === 'rental-cameras') $catId = 'cameras';
    elseif ($catSlug === 'rental-lenses') $catId = 'lenses';
    elseif ($catSlug === 'rental-lighting') $catId = 'lighting';
    else $catId = 'accessories';

    $specs = json_decode($item['specifications'], true);
    $specStr = $specs['specs'] ?? $item['description'];
    
    // Parse features if stored in JSON or just use description
    $features = isset($specs['features']) ? $specs['features'] : explode(',', $item['description']);

    $equipment[] = [
        "id" => $item['id'],
        "category" => $catId,
        "name" => $item['name'],
        "basePrice" => $item['rental_price_per_day'],
        "specs" => $specStr,
        "image" => $item['image_url'],
        "description" => $item['description'],
        "features" => array_slice($features, 0, 3), // Limit features
        "insurance" => $item['insurance_fee'],
        "deposit" => $item['deposit_fee']
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
                    Cho Thuê Máy Ảnh & Phụ Kiện Chuyên Nghiệp
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Bộ sưu tập đầy đủ máy ảnh, ống kính, đèn, thiết bị âm thanh và phụ kiện cao cấp
                </p>
            </div>
        </div>
    </section>

    <!-- Controls -->
    <section class="py-4 bg-background sticky top-16 z-50 border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <!-- Date Range Selector -->
            <div class="bg-card border border-border rounded-lg p-4">
                 <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    Chọn Thời Hạn Cho Thuê (Giá Sẽ Tự Động Tính Toán)
                </h3>
                <div class="flex flex-wrap gap-2" id="date-filters">
                    <?php foreach ($dateRanges as $range): ?>
                        <button 
                            data-id="<?php echo $range['id']; ?>"
                            data-multiplier="<?php echo $range['multiplier']; ?>"
                            class="date-btn px-4 py-1 rounded-full text-sm font-medium transition-all <?php echo $range['id'] === '3days' ? 'bg-primary text-primary-foreground' : 'bg-muted text-foreground hover:bg-muted/80 border border-border'; ?>"
                        >
                            <?php echo $range['label']; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Categories -->
            <div class="flex flex-wrap gap-3 justify-center" id="category-filters">
                <?php foreach ($categories as $cat): ?>
                    <button 
                        data-id="<?php echo $cat['id']; ?>"
                        class="cat-btn px-6 py-2 rounded-full text-sm font-medium transition-all flex items-center gap-2 <?php echo $cat['id'] === 'cameras' ? 'bg-primary text-primary-foreground' : 'bg-muted text-foreground hover:bg-muted/80 border border-border'; ?>"
                    >
                        <span><?php echo $cat['icon']; ?></span>
                        <?php echo $cat['name']; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Equipment Grid -->
    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="equipment-grid">
                <?php foreach ($equipment as $item): ?>
                    <div 
                        class="equipment-item bg-card border border-border rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col"
                        data-category="<?php echo $item['category']; ?>"
                        data-baseprice="<?php echo $item['basePrice']; ?>"
                    >
                        <div class="relative h-72 bg-muted overflow-hidden">
                            <img 
                                src="<?php echo $item['image']; ?>" 
                                alt="<?php echo $item['name']; ?>" 
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                            >
                            <button class="absolute top-4 right-4 p-2 bg-white/90 rounded-full hover:bg-white transition">
                                <i data-lucide="heart" class="w-5 h-5 text-gray-400"></i>
                            </button>
                        </div>

                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-bold mb-1"><?php echo $item['name']; ?></h3>
                            <p class="text-sm text-muted-foreground mb-4 line-clamp-1"><?php echo $item['specs']; ?></p>
                            <p class="text-sm text-muted-foreground mb-4 line-clamp-2"><?php echo $item['description']; ?></p>

                            <ul class="space-y-2 mb-6 text-sm">
                                <?php foreach ($item['features'] as $feature): ?>
                                    <li class="flex items-center gap-2">
                                        <i data-lucide="zap" class="w-4 h-4 text-secondary"></i>
                                        <span><?php echo $feature; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="border-t border-border pt-4 mt-auto space-y-3">
                                <div>
                                    <p class="text-xs text-muted-foreground mb-1">
                                        Giá Cho Thuê (<span class="current-duration-label">3 Ngày</span>)
                                    </p>
                                    <p class="text-2xl font-bold text-primary">
                                        <span class="price-display"></span> 
                                        <span class="text-xs text-muted-foreground">VND</span>
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="bg-muted rounded p-2">
                                        <p class="text-muted-foreground">Bảo Hiểm</p>
                                        <p class="font-semibold"><?php echo number_format($item['insurance']); ?> VND</p>
                                    </div>
                                    <div class="bg-muted rounded p-2">
                                        <p class="text-muted-foreground">Tiền Cọc</p>
                                        <p class="font-semibold"><?php echo number_format($item['deposit']); ?> VND</p>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button 
                                        onclick="addRentalToCart(this, <?php echo $item['basePrice']; ?>, <?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>', '<?php echo $item['image']; ?>')"
                                        class="w-full bg-foreground text-background hover:bg-foreground/90 font-semibold py-4 rounded-md flex items-center justify-center gap-2 transition-colors">
                                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                        Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Category Filters
        const catBtns = document.querySelectorAll('.cat-btn');
        const items = document.querySelectorAll('.equipment-item');
        
        // Initial filter
        const initialCategory = 'cameras';
        filterItems(initialCategory);

        catBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.getAttribute('data-id');
                
                catBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-primary-foreground');
                    b.classList.add('bg-muted', 'text-foreground', 'hover:bg-muted/80');
                });
                btn.classList.remove('bg-muted', 'text-foreground', 'hover:bg-muted/80');
                btn.classList.add('bg-primary', 'text-primary-foreground');

                filterItems(filter);
            });
        });

        function filterItems(category) {
            items.forEach(item => {
                if (item.getAttribute('data-category') === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        // Date Duration / Price Calculation
        const dateBtns = document.querySelectorAll('.date-btn');
        const priceDisplays = document.querySelectorAll('.price-display');
        const durationLabels = document.querySelectorAll('.current-duration-label');
        
        let currentMultiplier = 0.9; // Default 3days

        function updatePrices() {
            items.forEach(item => {
                const basePrice = parseFloat(item.getAttribute('data-baseprice'));
                const finalPrice = Math.round(basePrice * currentMultiplier);
                const priceDisplay = item.querySelector('.price-display');
                if (priceDisplay) {
                    priceDisplay.textContent = finalPrice.toLocaleString('vi-VN');
                }
            });
        }
        
        // Initial calculation
        updatePrices();

        // Wrapper for dynamic price cart addition
        window.addRentalToCart = function(btn, basePrice, id, name, image) {
            const finalPrice = Math.round(basePrice * currentMultiplier);
            const item = {
                id: id,
                name: name,
                price: finalPrice.toString(), // Cart expects string
                image: image,
                quantity: 1
            };
            window.addToCart(item, btn); // Call global handler with button ref
        };

        dateBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const multiplier = parseFloat(btn.getAttribute('data-multiplier'));
                const label = btn.innerText;

                // Update active state
                dateBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-primary-foreground');
                    b.classList.add('bg-muted', 'text-foreground', 'hover:bg-muted/80');
                });
                btn.classList.remove('bg-muted', 'text-foreground', 'hover:bg-muted/80');
                btn.classList.add('bg-primary', 'text-primary-foreground');

                // Update logic
                currentMultiplier = multiplier;
                durationLabels.forEach(l => l.innerText = label);
                updatePrices();
            });
        });
    });
</script>

<?php include 'components/footer.php'; ?>
