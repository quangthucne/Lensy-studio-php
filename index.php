<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<?php
require_once 'config/db.php';
// Fetch services from DB
$stmt = $pdo->prepare("SELECT * FROM services WHERE is_active = 1 LIMIT 3");
$stmt->execute();
$dbServices = $stmt->fetchAll();

// Map DB fields to view fields
$services = [];
foreach ($dbServices as $svc) {
    $services[] = [
        "id" => $svc['id'],
        "title" => $svc['name'],
        "description" => $svc['description'],
        "image" => $svc['image_url'] ?? 'assets/placeholder.jpg',
        "link" => "packages.php",
    ];
}

// Fallback if DB is empty
if (empty($services)) {
    $services = [
        [
            "id" => 1,
            "title" => "Chụp ảnh Tết",
            "description" => "Tôn vinh năm mới với những bộ ảnh Áo Dài sang trọng và ảnh gia đình",
            "image" => "assets/t-t-photography--o-d-i-family-portraits.jpg",
            "link" => "packages.php",
        ],
    ];
}

$testimonials = [
    [
        "name" => "Nguyễn Hương",
        "role" => "Khách hàng Tết",
        "text" => "Studio Lensy đã làm cho bộ ảnh gia đình của chúng tôi cực kỳ đẹp. Bộ sưu tập Áo Dài rất hoàn hảo!",
        "rating" => 5,
    ],
    [
        "name" => "Trần Linh",
        "role" => "Cặp Vợ Chồng Cưới",
        "text" => "Ảnh cưới của chúng tôi vượt quá mọi mong đợi. Mỗi khoảnh khắc đều được chụp hoàn hảo.",
        "rating" => 5,
    ],
    [
        "name" => "Võ Minh",
        "role" => "Khách hàng Kỷ Niệm",
        "text" => "Đội ngũ rất chuyên nghiệp và sáng tạo. Chúng tôi yêu thích bộ ảnh kỷ niệm của mình!",
        "rating" => 5,
    ],
];
?>

<main class="w-full bg-background text-foreground">
    <!-- Hero Section with Slider -->
    <section class="pt-20 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h1 class="text-5xl md:text-6xl font-bold leading-tight text-balance">
                        Ghi Lại Những Khoảnh Khắc Quý Báu
                    </h1>
                    <p class="text-lg text-muted-foreground leading-relaxed text-pretty">
                        Dịch vụ chụp ảnh chuyên nghiệp cho lễ Tết, cưới
                        hỏi, kỷ niệm và những dịp đặc biệt khác. Chúng
                        tôi lưu giữ những kỷ niệm của bạn với sự xuất
                        sắc nghệ thuật và chăm sóc chuyên nghiệp.
                    </p>
                    <div class="w-full flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="packages.php" class="w-full sm:w-auto">
                            <button class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-8 py-6 text-base w-full sm:w-auto">
                                Khám Phá Gói Dịch Vụ
                            </button>
                        </a>
                        <a href="gallery.php" class="w-full sm:w-auto">
                            <button class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background border border-primary text-primary hover:bg-primary/10 bg-transparent h-10 px-8 py-6 text-base w-full sm:w-auto">
                                Xem Thư Viện
                            </button>
                        </a>
                    </div>
                </div>
                <div class="relative h-96 rounded-lg overflow-hidden shadow-xl">
                    <img
                        src="assets/professional-photography-studio-setup.jpg"
                        alt="Photography studio"
                        title="Photography studio"
                        class="object-cover w-full h-full"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Services -->
    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-balance">
                    Dịch Vụ Của Chúng Tôi
                </h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Từ lễ Tết truyền thống đến các bữa tiệc cưới không
                    quên, chúng tôi cung cấp các giải pháp chụp ảnh toàn
                    diện
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($services as $service): ?>
                    <a href="<?php echo $service['link']; ?>">
                        <div class="group cursor-pointer h-full">
                            <div class="relative h-64 rounded-lg overflow-hidden mb-4 shadow-lg">
                                <img
                                    src="<?php echo $service['image']; ?>"
                                    alt="<?php echo $service['title']; ?>"
                                    title="<?php echo $service['title']; ?>"
                                    class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
                                />
                            </div>
                            <h3 class="text-2xl font-bold mb-2 text-primary">
                                <?php echo $service['title']; ?>
                            </h3>
                            <p class="text-muted-foreground mb-4 line-clamp-2">
                                <?php echo $service['description']; ?>
                            </p>
                            <div class="inline-flex items-center text-primary font-semibold group-hover:translate-x-2 transition-transform">
                                Tìm Hiểu Thêm &rarr;
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Collections Highlight -->
    <section class="py-20 bg-secondary/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative h-96 rounded-lg overflow-hidden shadow-xl order-2 lg:order-1">
                    <img
                        src="assets/costume-rental--o-d-i-wedding-dress-collection.jpg"
                        alt="Costume collection"
                        title="Costume collection"
                        class="object-cover w-full h-full"
                    />
                </div>
                <div class="space-y-6 order-1 lg:order-2">
                    <h2 class="text-4xl font-bold text-primary">
                        Thuê Áo & Thiết Bị Chuyên Nghiệp
                    </h2>
                    <p class="text-lg text-muted-foreground leading-relaxed">
                        Ngoài chụp ảnh, chúng tôi cung cấp Áo Dài thanh
                        lịch, áo cưới, và thuê thiết bị chụp ảnh chuyên
                        nghiệp. Hoàn thành tầm nhìn của bạn với bộ sưu
                        tập được chọn lọc của chúng tôi.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold">
                                &check;
                            </span>
                            <span>Áo Dài truyền thống và hiện đại</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold">
                                &check;
                            </span>
                            <span>Áo cưới thiết kế</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold">
                                &check;
                            </span>
                            <span>
                                Thiết bị máy ảnh và ống kính chuyên
                                nghiệp
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-primary font-bold">
                                &check;
                            </span>
                            <span>Áo quần cổ điển và đặc biệt</span>
                        </li>
                    </ul>
                    <a href="costumes.php">
                        <button class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-8 py-6 text-base">
                            Khám Phá Dịch Vụ Thuê
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-balance">
                    Những Câu Chuyện Khách Hàng
                </h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Nghe từ các khách hàng hài lòng về trải nghiệm của
                    họ
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
                        <div class="flex gap-1 mb-4">
                            <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                <span class="text-secondary text-xl">
                                    ★
                                </span>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted-foreground mb-6 italic">
                            "<?php echo $testimonial['text']; ?>"
                        </p>
                        <div>
                            <p class="font-bold text-foreground">
                                <?php echo $testimonial['name']; ?>
                            </p>
                            <p class="text-sm text-muted-foreground">
                                <?php echo $testimonial['role']; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary text-primary-foreground">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <h2 class="text-4xl md:text-5xl font-bold text-balance">
                Sẵn Sàng Tạo Ra Phép Màu?
            </h2>
            <p class="text-lg opacity-90 text-pretty">
                Đặt buổi chụp ảnh của bạn hôm nay và để chúng tôi ghi
                lại những khoảnh khắc quý báu nhất của bạn
            </p>
            <a href="booking.php">
                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary-foreground text-primary hover:bg-primary-foreground/90 px-8 py-6 text-base font-semibold">
                    Bắt Đầu Đặt Lịch
                </button>
            </a>
        </div>
    </section>
</main>

<?php include 'components/footer.php'; ?>
