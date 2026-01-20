<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
$suggestedCombos = [
    [
        "name" => "Chụp ảnh Tết + Thuê Áo Dài",
        "description" => "Hoàn hảo cho những buổi sum họp gia đình ngày Tết",
        "savings" => "1,500,000",
    ],
    [
        "name" => "Gói Cưới Toàn Diện",
        "description" => "Giải pháp trọn gói cho ngày cưới",
        "savings" => "2,500,000",
    ],
    [
        "name" => "Gói Sáng tạo Nội dung Chuyên nghiệp",
        "description" => "Mọi thứ cho sản phẩm của bạn",
        "savings" => "2,000,000",
    ],
];

$dateRanges = [
    ["id" => "1day", "label" => "1 Ngày"],
    ["id" => "3days", "label" => "3 Ngày"],
    ["id" => "7days", "label" => "1 Tuần"],
    ["id" => "30days", "label" => "1 Tháng"],
    ["id" => "90days", "label" => "3 Tháng"],
];

$faqs = [
    [
        "q" => "Tôi nên đặt lịch trước bao lâu?",
        "a" => "Chúng tôi khuyên bạn nên đặt trước ít nhất 2-4 tuần cho dịch vụ chụp ảnh và 1-2 tuần cho thuê. Tuy nhiên, chúng tôi chấp nhận đặt lịch gấp tùy thuộc vào tình trạng sẵn có.",
    ],
    [
        "q" => "Chính sách hủy của bạn là gì?",
        "a" => "Việc hủy bỏ được thực hiện trước 30 ngày so với sự kiện sẽ được hoàn lại tiền đầy đủ. Việc hủy trong vòng 30 ngày sẽ phải chịu một khoản phí hủy 50%. Không hoàn lại tiền trong vòng 7 ngày.",
    ],
    [
        "q" => "Bạn có cung cấp các kế hoạch thanh toán không?",
        "a" => "Có, chúng tôi cung cấp các kế hoạch thanh toán linh hoạt. Một khoản tiền gửi 30% sẽ đảm bảo ngày của bạn, với số dư đến hạn trước sự kiện của bạn 2 tuần.",
    ],
    [
        "q" => "Tôi có thể sửa đổi đặt phòng của mình không?",
        "a" => "Có, các sửa đổi đều được chào đón. Liên hệ với chúng tôi càng sớm càng tốt để thảo luận về những thay đổi đối với đặt phòng của bạn.",
    ],
    [
        "q" => "Bạn có cung cấp dịch vụ sắp xếp và giao hàng không?",
        "a" => "Có, chúng tôi cung cấp dịch vụ sắp xếp và giao hàng với một khoản phụ phí. Liên hệ với chúng tôi để biết chi tiết.",
    ],
    [
        "q" => "Nếu tôi không hài lòng với dịch vụ thì sao?",
        "a" => "Chúng tôi đứng sau công việc của mình 100%. Nếu bạn không hài lòng, chúng tôi sẽ làm việc với bạn để làm cho nó đúng.",
    ],
];

$processSteps = [
    [
        "step" => "1",
        "title" => "Gửi Yêu Cầu",
        "description" => "Điền vào biểu mẫu đặt lịch với chi tiết sự kiện của bạn",
    ],
    [
        "step" => "2",
        "title" => "Xác Nhận",
        "description" => "Chúng tôi sẽ liên hệ để xác nhận và thảo luận về nhu cầu của bạn",
    ],
    [
        "step" => "3",
        "title" => "Thanh Toán",
        "description" => "Đặt cọc 30% để giữ ngày của bạn",
    ],
    [
        "step" => "4",
        "title" => "Tận Hưởng Trải Nghiệm",
        "description" => "Đến và cùng nhau tạo nên những kỷ niệm đẹp",
    ],
];
?>

<main class="w-full bg-background text-foreground">
    <!-- Hero Section -->
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-5xl md:text-6xl font-bold text-balance">
                    Đặt Lịch Trải Nghiệm
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Đặt lịch chụp ảnh, thuê trang phục và thiết bị một cách tiện lợi
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="bg-card border border-border rounded-lg p-8">
                        <form action="" method="POST" class="space-y-6">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">
                                    Thông Tin Cá Nhân
                                </h3>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <input type="text" name="firstName" placeholder="Tên" required class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                    <input type="text" name="lastName" placeholder="Họ" required class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <input type="email" name="email" placeholder="Địa chỉ Email" required class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary mb-4">
                                <input type="tel" name="phone" placeholder="Số Điện Thoại" required class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Service Selection -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">
                                    Loại Dịch Vụ
                                </h3>
                                <select name="serviceType" class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="photography">Dịch Vụ Chụp Ảnh</option>
                                    <option value="costume">Thuê Trang Phục</option>
                                    <option value="equipment">Thuê Thiết Bị</option>
                                    <option value="combined">Dịch Vụ Kết Hợp</option>
                                </select>
                            </div>

                            <!-- Event Details -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">
                                    Chi Tiết Sự Kiện
                                </h3>
                                <input type="date" name="eventDate" required class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary mb-4">
                                <select name="eventType" required class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">Chọn Loại Sự Kiện</option>
                                    <option value="tet">Lễ Tết</option>
                                    <option value="wedding">Đám Cưới</option>
                                    <option value="anniversary">Kỷ Niệm</option>
                                    <option value="family">Gia Đình</option>
                                    <option value="corporate">Sự Kiện Công Ty</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>

                            <!-- Rental Period -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">
                                    Thời Hạn Thuê
                                </h3>
                                <select name="rentalPeriod" class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                                    <?php foreach ($dateRanges as $range): ?>
                                        <option value="<?php echo $range['id']; ?>" <?php echo $range['id'] === '3days' ? 'selected' : ''; ?>><?php echo $range['label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Additional Services -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">
                                    Dịch Vụ Bổ Sung
                                </h3>
                                <div class="space-y-3">
                                    <?php 
                                    $additionalServices = ["Tạo Kiểu Chuyên Nghiệp", "Ảnh Đính Hôn", "Video Nổi Bật", "Album Cao Cấp", "Thêm Giờ"];
                                    foreach ($additionalServices as $service): 
                                    ?>
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" name="additionalServices[]" value="<?php echo $service; ?>" class="w-4 h-4 rounded border-border">
                                            <span><?php echo $service; ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Message -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">
                                    Yêu Cầu Đặc Biệt
                                </h3>
                                <textarea name="message" rows="4" placeholder="Hãy cho chúng tôi biết về ý tưởng, sở thích hoặc yêu cầu đặc biệt của bạn" class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-primary text-primary-foreground hover:bg-primary/90 py-6 font-semibold text-base rounded-md transition-colors">
                                Gửi Yêu Cầu Đặt Lịch
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Contact Information -->
                    <div class="bg-card border border-border rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-6">Liên Hệ</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="mt-1"><i data-lucide="phone" class="w-5 h-5 text-primary"></i></div>
                                <div>
                                    <p class="font-semibold">(84) xxx-xxxx</p>
                                    <p class="text-sm text-muted-foreground">Thứ Hai - Chủ Nhật 9am-9pm</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="mt-1"><i data-lucide="mail" class="w-5 h-5 text-primary"></i></div>
                                <div>
                                    <p class="font-semibold">info@studiolensy.vn</p>
                                    <p class="text-sm text-muted-foreground">Chúng tôi trả lời trong vòng 24 giờ</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="mt-1"><i data-lucide="map-pin" class="w-5 h-5 text-primary"></i></div>
                                <div>
                                    <p class="font-semibold">Địa chỉ Studio</p>
                                    <p class="text-sm text-muted-foreground">Hà Nội, Việt Nam</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="mt-1"><i data-lucide="clock" class="w-5 h-5 text-primary"></i></div>
                                <div>
                                    <p class="font-semibold">Giờ Mở Cửa</p>
                                    <p class="text-sm text-muted-foreground">9:00 AM - 9:00 PM Hàng Ngày</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suggested Combos -->
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-primary">Gói Thông Minh</h3>
                        <div class="space-y-3">
                            <?php foreach ($suggestedCombos as $combo): ?>
                                <div class="bg-secondary/10 border border-secondary/30 rounded-lg p-4 cursor-pointer hover:bg-secondary/20 transition">
                                    <h4 class="font-semibold text-sm mb-1"><?php echo $combo['name']; ?></h4>
                                    <p class="text-xs text-muted-foreground mb-2"><?php echo $combo['description']; ?></p>
                                    <p class="text-xs font-bold text-primary">Tiết kiệm <?php echo $combo['savings']; ?> VND</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Steps -->
    <section class="py-20 bg-secondary/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-12 text-center text-balance">
                Quy Trình Hoạt Động
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <?php foreach ($processSteps as $item): ?>
                    <div class="relative bg-card border border-border rounded-lg p-6 text-center flex flex-col">
                        <div class="text-5xl font-bold text-primary mb-4"><?php echo $item['step']; ?></div>
                        <h3 class="text-lg font-bold mb-2"><?php echo $item['title']; ?></h3>
                        <p class="text-sm text-muted-foreground"><?php echo $item['description']; ?></p>
                        <?php if ((int)$item['step'] < 4): ?>
                            <div class="hidden md:flex absolute -right-3 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-primary text-primary-foreground rounded-full items-center justify-center text-sm font-bold">
                                &rarr;
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-background">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-12 text-center text-balance">
                Câu Hỏi Thường Gặp Về Đặt Lịch
            </h2>
            <div class="space-y-6">
                <?php foreach ($faqs as $faq): ?>
                    <div class="border-b border-border pb-6 last:border-0">
                        <h3 class="font-semibold text-lg mb-3"><?php echo $faq['q']; ?></h3>
                        <p class="text-muted-foreground"><?php echo $faq['a']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-20 bg-primary text-primary-foreground">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            <h2 class="text-4xl font-bold text-balance">Sẵn Sàng Tạo Ra Điều Kỳ Diệu?</h2>
            <p class="text-lg opacity-90 text-pretty">
                Điền vào biểu mẫu đặt lịch của chúng tôi ở trên hoặc liên hệ trực tiếp để bắt đầu lên kế hoạch cho sự kiện hoàn hảo của bạn
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-primary-foreground text-primary hover:bg-primary-foreground/90 px-8 py-6 font-semibold rounded-md transition-colors">
                    Gọi Cho Chúng Tôi Ngay
                </button>
                <button class="px-8 py-6 border border-primary-foreground text-primary-foreground hover:bg-primary-foreground/10 bg-transparent rounded-md transition-colors font-semibold">
                    Gửi Email Cho Chúng Tôi
                </button>
            </div>
        </div>
    </section>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Simulate API call
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            btn.innerText = 'Đang gửi...';
            btn.disabled = true;

            await new Promise(resolve => setTimeout(resolve, 1000));

            Swal.fire({
                title: "Thành công!",
                text: "Yêu cầu đặt lịch của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ với bạn trong vòng 24 giờ để xác nhận đặt lịch của bạn.",
                icon: "success",
                confirmButtonColor: '#e1ad01', // Hardcoded primary color or use CSS var if supported by Swal customClass
                confirmButtonText: "OK",
            });

            form.reset();
            btn.innerText = originalText;
            btn.disabled = false;
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
