<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$categories = [
    ["id" => "all", "name" => "Tất Cả"],
    ["id" => "tet", "name" => "Lễ Tết"],
    ["id" => "wedding", "name" => "Đám Cưới"],
    ["id" => "anniversary", "name" => "Kỷ Niệm"],
    ["id" => "family", "name" => "Gia Đình"],
    ["id" => "tours", "name" => "Chụp Ảnh Tour"],
];

$galleryItems = [
    [
        "id" => 1,
        "category" => "tet",
        "title" => "Chân Dung Gia Đình Áo Dài",
        "image" => "assets/gallery-tet-family-1.jpg",
        "description" => "Chân dung gia đình áo dài đỏ và vàng truyền thống",
    ],
    [
        "id" => 2,
        "category" => "tet",
        "title" => "Cặp Đôi Áo Dài Hiện Đại",
        "image" => "assets/gallery-tet-couple-1.jpg",
        "description" => "Buổi chụp ảnh cặp đôi áo dài theo phong cách đương đại",
    ],
    [
        "id" => 3,
        "category" => "wedding",
        "title" => "Chuẩn Bị Của Cô Dâu",
        "image" => "assets/gallery-wedding-prep-1.jpg",
        "description" => "Những khoảnh khắc tự nhiên trong quá trình chuẩn bị của cô dâu",
    ],
    [
        "id" => 4,
        "category" => "wedding",
        "title" => "Trao Nhẫn",
        "image" => "assets/gallery-wedding-ceremony-1.jpg",
        "description" => "Khoảnh khắc xúc động trong lễ cưới",
    ],
    [
        "id" => 5,
        "category" => "wedding",
        "title" => "Tiệc Cưới",
        "image" => "assets/gallery-wedding-reception-1.jpg",
        "description" => "Những khoảnh khắc vui vẻ trong tiệc cưới",
    ],
    [
        "id" => 6,
        "category" => "anniversary",
        "title" => "Cặp Đôi Giờ Vàng",
        "image" => "assets/gallery-anniversary-sunset-1.jpg",
        "description" => "Buổi chụp ảnh kỷ niệm lãng mạn vào giờ vàng",
    ],
    [
        "id" => 7,
        "category" => "family",
        "title" => "Gia Đình Đa Thế Hệ",
        "image" => "assets/gallery-family-generation-1.jpg",
        "description" => "Buổi chụp ảnh chân dung gia đình nhiều thế hệ",
    ],
    [
        "id" => 8,
        "category" => "family",
        "title" => "Gia Đình Tại Nhà",
        "image" => "assets/gallery-family-home-1.jpg",
        "description" => "Buổi chụp ảnh gia đình tự nhiên trong không gian tự nhiên",
    ],
    [
        "id" => 9,
        "category" => "tours",
        "title" => "Chụp Ảnh Điểm Đến",
        "image" => "assets/gallery-tour-destination-1.jpg",
        "description" => "Chụp ảnh du lịch và tour",
    ],
    [
        "id" => 10,
        "category" => "anniversary",
        "title" => "Cặp Đôi Phong Cách Cổ Điển",
        "image" => "assets/gallery-anniversary-vintage-1.jpg",
        "description" => "Buổi chụp ảnh kỷ niệm theo phong cách",
    ],
    [
        "id" => 11,
        "category" => "tet",
        "title" => "Chân Dung Tết Trẻ Em",
        "image" => "assets/gallery-tet-kids-1.jpg",
        "description" => "Những đứa trẻ đáng yêu trong trang phục truyền thống",
    ],
    [
        "id" => 12,
        "category" => "wedding",
        "title" => "Điệu Nhảy Đầu Tiên",
        "image" => "assets/gallery-wedding-dance-1.jpg",
        "description" => "Khoảnh khắc khiêu vũ đầu tiên lãng mạn",
    ],
];

$blogPosts = [
    [
        "id" => 1,
        "title" => "Mẹo Chụp Ảnh Chân Dung Tết Hoàn Hảo",
        "category" => "Mẹo Chụp Ảnh",
        "date" => "Ngày 15 tháng 1 năm 2025",
        "excerpt" => "Tìm hiểu cách chọn màu sắc và kiểu dáng Áo Dài phù hợp cho ảnh Tết gia đình bạn.",
        "image" => "assets/blog-tet-tips-1.jpg",
    ],
    [
        "id" => 2,
        "title" => "Địa Điểm Chụp Ảnh Cưới Đẹp Nhất Việt Nam",
        "category" => "Hướng Dẫn Điểm Đến",
        "date" => "Ngày 10 tháng 1 năm 2025",
        "excerpt" => "Khám phá những địa điểm phong cảnh yêu thích của chúng tôi để ghi lại những khoảnh khắc cưới tuyệt đẹp.",
        "image" => "assets/blog-wedding-locations-1.jpg",
    ],
    [
        "id" => 3,
        "title" => "Hướng Dẫn Tạo Dáng Cho Các Buổi Chụp Ảnh Cặp Đôi",
        "category" => "Mẹo Chụp Ảnh",
        "date" => "Ngày 5 tháng 1 năm 2025",
        "excerpt" => "Những tư thế tự nhiên và tôn dáng để thể hiện sự ăn ý và câu chuyện tình yêu của bạn.",
        "image" => "assets/blog-couple-poses-1.jpg",
    ],
];
?>

<main class="w-full bg-background text-foreground">
    <!-- Hero Section -->
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-5xl md:text-6xl font-bold text-balance">
                    Thư Viện Của Chúng Tôi
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Khám phá portfolio của chúng tôi về những khoảnh khắc đẹp được ghi lại trong những dịp đặc biệt
                </p>
            </div>
        </div>
    </section>

    <!-- Filter Categories -->
    <section class="py-6 bg-background sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-3 justify-center" id="category-filters">
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

    <!-- Gallery Grid -->
    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gallery-grid">
                <?php foreach ($galleryItems as $item): ?>
                    <div 
                        class="gallery-item group cursor-pointer rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300" 
                        data-category="<?php echo $item['category']; ?>"
                    >
                        <div class="relative h-72 bg-muted overflow-hidden">
                            <img 
                                src="<?php echo $item['image']; ?>" 
                                alt="<?php echo $item['title']; ?>" 
                                class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                <h3 class="text-white text-xl font-bold mb-2">
                                    <?php echo $item['title']; ?>
                                </h3>
                                <p class="text-white/90 text-sm">
                                    <?php echo $item['description']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="py-20 bg-secondary/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16">
                <h2 class="text-4xl font-bold mb-3 text-balance">
                    Mẹo & Hướng Dẫn Chụp Ảnh
                </h2>
                <p class="text-lg text-muted-foreground">
                    Học hỏi từ các chuyên gia của chúng tôi để tận dụng tối đa buổi chụp của bạn
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($blogPosts as $post): ?>
                    <div class="bg-card border border-border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="relative h-48 bg-muted">
                            <img 
                                src="<?php echo $post['image']; ?>" 
                                alt="<?php echo $post['title']; ?>" 
                                class="object-cover w-full h-full"
                            >
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-semibold text-primary uppercase">
                                    <?php echo $post['category']; ?>
                                </span>
                                <span class="text-xs text-muted-foreground">
                                    <?php echo $post['date']; ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">
                                <?php echo $post['title']; ?>
                            </h3>
                            <p class="text-muted-foreground text-sm mb-4">
                                <?php echo $post['excerpt']; ?>
                            </p>
                            <a href="#" class="inline-flex items-center text-primary font-semibold hover:translate-x-1 transition-transform">
                                Đọc Thêm &rarr;
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary text-primary-foreground">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <h2 class="text-4xl font-bold text-balance">
                Bạn Thích Những Gì Bạn Thấy?
            </h2>
            <p class="text-lg opacity-90 text-pretty">
                Lên lịch cho buổi chụp của bạn ngay hôm nay và tạo ra những khoảnh khắc đẹp của riêng bạn
            </p>
            <a href="booking.php">
                <button class="bg-primary-foreground text-primary hover:bg-primary-foreground/90 px-8 py-6 text-base font-semibold rounded-md transition-colors">
                    Đặt Lịch Chụp
                </button>
            </a>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');

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
                galleryItems.forEach(item => {
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

<?php include 'includes/footer.php'; ?>
