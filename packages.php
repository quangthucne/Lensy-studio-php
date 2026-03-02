<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<?php
$packages = require 'config/packages_data.php';
?>

<main class="w-full bg-background text-foreground">
    <!-- Hero Section -->
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6 mb-12">
                <h1 class="text-5xl md:text-6xl font-bold text-balance">
                    Gói Chụp Ảnh
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Các tùy chọn giá linh hoạt phù hợp với mọi lễ kỷ niệm và ngân sách
                </p>
            </div>
        </div>
    </section>

    <?php foreach ($packages as $idx => $packageCategory): ?>
        <section class="<?php echo $idx % 2 === 0 ? 'py-20 bg-background' : 'py-20 bg-secondary/5'; ?>">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-16">
                    <h2 class="text-4xl font-bold mb-3 text-primary">
                        <?php echo $packageCategory['category']; ?>
                    </h2>
                    <p class="text-lg text-muted-foreground">
                        <?php echo $packageCategory['description']; ?>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($packageCategory['tiers'] as $tier): ?>
                        <div class="rounded-lg border transition-all duration-300 flex flex-col h-full relative <?php echo $tier['featured'] ? 'border-primary bg-primary/5 shadow-xl scale-105 md:scale-110 z-10' : 'border-border bg-card hover:shadow-lg'; ?>">
                            <?php if ($tier['featured']): ?>
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                    <span class="bg-primary text-primary-foreground px-4 py-1 rounded-full text-sm font-semibold">
                                        Phổ Biến Nhất
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="p-8 flex-1 flex flex-col">
                                <h3 class="text-2xl font-bold mb-2">
                                    <?php echo $tier['name']; ?>
                                </h3>
                                <p class="text-muted-foreground text-sm mb-6">
                                    <?php echo $tier['duration']; ?>
                                </p>

                                <div class="mb-6">
                                    <span class="text-4xl font-bold text-primary">
                                        <?php echo $tier['price']; ?>
                                    </span>
                                    <span class="text-muted-foreground ml-2">
                                        VND
                                    </span>
                                </div>

                                <p class="text-sm text-muted-foreground mb-6">
                                    <span class="font-semibold text-foreground">
                                        <?php echo $tier['photos']; ?>
                                    </span>
                                </p>

                                <ul class="space-y-4 mb-8 flex-1">
                                    <?php foreach ($tier['features'] as $feature): ?>
                                        <li class="flex gap-3">
                                            <i data-lucide="check" class="w-5 h-5 text-primary flex-shrink-0 mt-0.5"></i>
                                            <span class="text-sm">
                                                <?php echo $feature; ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <a href="booking.php?pkg_id=<?php echo $tier['id']; ?>">
                                    <button class="w-full py-6 font-semibold rounded-md transition-colors <?php echo $tier['featured'] ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'bg-foreground text-background hover:bg-foreground/90'; ?>">
                                        <?php echo $tier['cta']; ?>
                                    </button>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <!-- Add-ons Section -->
    <section class="py-20 bg-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-3 text-balance">Nâng Cao Gói Dịch Vụ</h2>
                <p class="text-lg text-muted-foreground">Các add-on tùy chọn để cá nhân hóa trải nghiệm của bạn</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $addons = [
                    ["name" => "Phiên Kỷ Niệm", "price" => "+1,200,000"],
                    ["name" => "Ảnh Tiền Hôn Nhân", "price" => "+1,800,000"],
                    ["name" => "Video Tóm Tắt", "price" => "+2,000,000"],
                    ["name" => "Album Premium", "price" => "+1,500,000"],
                    ["name" => "In Trên Canvas", "price" => "+800,000"],
                    ["name" => "Thiết Bị Chụp Ảnh", "price" => "Thay Đổi"],
                    ["name" => "Trang Phục Chuyên Nghiệp", "price" => "+500,000"],
                    ["name" => "Chỉnh Sửa Kéo Dài", "price" => "+300,000"],
                ];
                foreach ($addons as $addon):
                ?>
                    <div class="bg-card border border-border rounded-lg p-6 text-center">
                        <h3 class="font-semibold mb-2"><?php echo $addon['name']; ?></h3>
                        <p class="text-primary font-bold"><?php echo $addon['price']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-background">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-12 text-center text-balance">Câu Hỏi Thường Gặp</h2>
            <div class="space-y-6">
                <?php
                $faqs = [
                    ["q" => "Chỉnh sửa bao gồm những gì?", "a" => "Tất cả các gói bao gồm điều chỉnh màu chuyên nghiệp, retouching và nâng cao. Chúng tôi cung cấp hình ảnh độ phân giải cao sẵn sàng in."],
                    ["q" => "Tôi có thể tùy chỉnh gói được không?", "a" => "Chúng tôi cung cấp tùy chỉnh linh hoạt. Liên hệ với chúng tôi để thảo luận về nhu cầu cụ thể của bạn và tạo gói phù hợp."],
                    ["q" => "Lịch trình thanh toán là gì?", "a" => "Chúng tôi yêu cầu 30% tiền cọc để bảo đảm ngày hôm đó, với số tiền còn lại đến hạn 2 tuần trước buổi chụp ảnh của bạn."],
                    ["q" => "Bạn có cung cấp gói combo không?", "a" => "Có! Chúng tôi cung cấp giá combo đặc biệt cho những khách hàng đặt nhiều dịch vụ như chụp ảnh + thuê áo."],
                    ["q" => "Cần bao lâu để nhận ảnh cuối cùng?", "a" => "Thời gian giao hàng tiêu chuẩn là 2-3 tuần. Giao hàng khẩn cấp có sẵn với phí bổ sung."],
                    ["q" => "Tôi có thể đặt nhiều dịch vụ cùng nhau được không?", "a" => "Có! Kết hợp chụp ảnh với thuê áo hoặc thuê thiết bị để nhận chiết khấu gói lót đặc biệt."],
                ];
                foreach ($faqs as $faq):
                ?>
                    <div class="border-b border-border pb-6 last:border-0">
                        <h3 class="font-semibold text-lg mb-3"><?php echo $faq['q']; ?></h3>
                        <p class="text-muted-foreground"><?php echo $faq['a']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary text-primary-foreground">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <h2 class="text-4xl font-bold text-balance">Sẵn Sàng Đặt Buổi Chụp Ảnh Của Bạn?</h2>
            <p class="text-lg opacity-90 text-pretty">Chọn gói dịch vụ của bạn và lên lịch trải nghiệm chụp ảnh hoàn hảo hôm nay</p>
            <a href="booking.php">
                <button class="bg-primary-foreground text-primary hover:bg-primary-foreground/90 px-8 py-6 text-base font-semibold rounded-md transition-colors">
                    Bắt Đầu Đặt Lịch
                </button>
            </a>
        </div>
    </section>
</main>

<?php include 'components/footer.php'; ?>
