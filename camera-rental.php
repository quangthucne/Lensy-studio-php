<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$categories = [
    ["id" => "cameras", "name" => "Máy Ảnh", "icon" => "📷"],
    ["id" => "lenses", "name" => "Ống Kính", "icon" => "🔍"],
    ["id" => "lighting", "name" => "Đèn Chiếu Sáng", "icon" => "💡"],
    ["id" => "stabilization", "name" => "Ổn Định Hình Ảnh", "icon" => "🎥"],
    ["id" => "audio", "name" => "Thiết Bị Âm Thanh", "icon" => "🎤"],
    ["id" => "accessories", "name" => "Phụ Kiện", "icon" => "⚙️"],
];

$dateRanges = [
    ["id" => "1day", "label" => "1 Ngày", "multiplier" => 1],
    ["id" => "3days", "label" => "3 Ngày", "multiplier" => 0.9],
    ["id" => "7days", "label" => "1 Tuần", "multiplier" => 2.5],
    ["id" => "30days", "label" => "1 Tháng", "multiplier" => 7],
    ["id" => "90days", "label" => "3 Tháng", "multiplier" => 18],
];

// Simplified equipment list for PHP - in real app, fetch from DB
$equipment = [
    [
        "id" => 1,
        "category" => "cameras",
        "name" => "Canon EOS R5C",
        "basePrice" => 2800000,
        "specs" => "Full Frame Mirrorless, 45MP",
        "image" => "assets/equipment-canon-r5.jpg", // Using local placeholder path
        "description" => "Máy ảnh mirrorless full frame chuyên nghiệp cao cấp với khả năng quay video 8K tuyệt vời",
        "features" => ["Cảm Biến Full Frame 45MP", "Quay 8K Video @ 24p", "Lấy Nét Chuyên Nghiệp AI"],
        "insurance" => 500000,
        "deposit" => 5000000
    ],
    // ... add more items from source if needed
    [
        "id" => 2,
        "category" => "cameras",
        "name" => "Nikon Z9",
        "basePrice" => 3200000,
        "specs" => "Full Frame Mirrorless, 45.7MP",
        "image" => "assets/equipment-nikon-z9.jpg",
        "description" => "Máy ảnh mirrorless hàng đầu của Nikon với hiệu năng quay phim vượt trội",
        "features" => ["Full Frame 45.7MP", "Quay 8K Video Chất Lượng Cao", "Lấy Nét 493 Điểm"],
        "insurance" => 600000,
        "deposit" => 6000000
    ],
    [
        "id" => 4,
        "category" => "lenses",
        "name" => "RF 24-70mm f/2.8L IS USM",
        "basePrice" => 1200000,
        "specs" => "Ống Kính Zoom Tiêu Chuẩn, Canon RF Mount",
        "image" => "assets/equipment-lens-24-70.jpg",
        "description" => "Ống kính zoom tiêu chuẩn chuyên nghiệp với khẩu độ f/2.8 cố định",
        "features" => ["Khoảng Zoom 24-70mm", "Khẩu Độ f/2.8 Cố Định", "Ổn Định Hình Ảnh 5.5 Stops"],
        "insurance" => 200000,
        "deposit" => 2400000
    ],
    // ... Add at least one item per category for demo
    [
        "id" => 9,
        "category" => "lighting",
        "name" => "Godox SL-60W Đèn LED Studio",
        "basePrice" => 600000,
        "specs" => "Bảng Đèn LED 60W Chuyên Nghiệp",
        "image" => "assets/equipment-lighting-godox.jpg",
        "description" => "Đèn LED liên tục chuyên nghiệp cho studio với độ chính xác màu sắc CRI 95+ xuất sắc",
        "features" => ["Công Suất 60W", "Nhiệt Độ Màu 5600K", "CRI 95+ Chuẩn Xác"],
        "insurance" => 100000,
        "deposit" => 1200000
    ],
    [
        "id" => 12,
        "category" => "stabilization",
        "name" => "DJI RS 3 Mini Gimbal Cầm Tay",
        "basePrice" => 1000000,
        "specs" => "Gimbal Ổn Định Máy Ảnh Mirrorless",
        "image" => "assets/equipment-tripod.jpg",
        "description" => "Gimbal cầm tay ổn định chuyên nghiệp nhẹ nhàng",
        "features" => ["Tải Trọng 1.9kg", "Thời Lượng Pin 11.5 Giờ", "Chế Độ 3 Trục Ổn Định"],
        "insurance" => 200000,
        "deposit" => 2000000
    ],
    [
        "id" => 15,
        "category" => "audio",
        "name" => "Rode Wireless GO II",
        "basePrice" => 350000,
        "specs" => "Micro Không Dây Chuyên Nghiệp",
        "image" => "assets/equipment-microphone.jpg",
        "description" => "Micro không dây nhỏ gọn chuyên nghiệp",
        "features" => ["Phạm Vi 200m Không Dây", "Ghi Âm Sao Lưu Tích Hợp", "Pin 7 Giờ Liên Tục"],
        "insurance" => 70000,
        "deposit" => 700000
    ]
];
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

                                <div class="flex gap-2 pt-2">
                                    <button 
                                        onclick="addRentalToCart(this, <?php echo $item['basePrice']; ?>, <?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>', '<?php echo $item['image']; ?>')"
                                        class="outline-primary text-primary-foreground hover:bg-primary/90 font-semibold py-6 rounded-md flex-1 transition-colors">
                                        Thêm vào giỏ
                                    </button>
                                    <a href="booking.php" class="flex-1">
                                        <button class="w-full bg-primary text-primary-foreground hover:bg-primary/90 font-semibold py-6 rounded-md">
                                            Cho Thuê
                                        </button>
                                    </a>
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
            window.addToCart(item); // Call global handler
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

<?php include 'includes/footer.php'; ?>
