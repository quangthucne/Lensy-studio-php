<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="w-full bg-background text-foreground min-h-screen">
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-5xl md:text-6xl font-bold text-balance">
                    Thanh Toán & Đặt Lịch
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Hoàn tất đơn hàng và lịch hẹn của bạn
                </p>
            </div>
        </div>
    </section>

    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Form Fields -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Booking Info -->
                    <div class="bg-card border border-border rounded-lg p-8">
                        <h2 class="text-2xl font-bold mb-6">Thông Tin Đặt Lịch</h2>
                        <div class="space-y-6">
                            <!-- Personal Info -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">Thông Tin Cá Nhân</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <input type="text" name="firstName" placeholder="Tên" required 
                                        class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                                    <input type="text" name="lastName" placeholder="Họ" required 
                                        class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <input type="email" name="email" placeholder="Địa chỉ Email" required 
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary mb-4">
                                <input type="tel" name="phone" placeholder="Số Điện Thoại" required 
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Event Details -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">Chi Tiết Sự Kiện</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <input type="date" name="eventDate" required 
                                        class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                                    <select name="eventType" required 
                                        class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">Chọn Loại Sự Kiện</option>
                                        <option value="tet">Lễ Tết</option>
                                        <option value="wedding">Đám Cưới</option>
                                        <option value="anniversary">Kỷ Niệm</option>
                                        <option value="family">Gia Đình</option>
                                        <option value="corporate">Sự Kiện Công Ty</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Message -->
                             <div>
                                <h3 class="text-lg font-semibold mb-4 text-primary">Yêu Cầu Đặc Biệt</h3>
                                <textarea name="message" rows="4" placeholder="Ghi chú thêm..."
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info Placeholder -->
                      <div class="bg-card border border-border rounded-lg p-8">
                        <h2 class="text-2xl font-bold mb-6">Thông Tin Thanh Toán</h2>
                        <div class="space-y-4">
                            <input type="text" placeholder="Số thẻ" class="w-full px-4 py-3 rounded-lg border border-border bg-background">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" placeholder="MM/YY" class="w-full px-4 py-3 rounded-lg border border-border bg-background">
                                <input type="text" placeholder="CVC" class="w-full px-4 py-3 rounded-lg border border-border bg-background">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-card border border-border rounded-lg p-8 space-y-6 h-fit sticky top-24">
                    <h2 class="text-2xl font-bold">Tóm Tắt Đơn Hàng</h2>
                    <div id="checkout-items" class="space-y-4 max-h-60 overflow-y-auto pr-2">
                        <!-- Items injected here -->
                    </div>
                    
                    <div class="border-t border-border pt-4 space-y-4">
                        <div class="flex justify-between">
                            <span>Tạm tính</span>
                            <span id="checkout-subtotal">0 VND</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Thuế</span>
                            <span id="checkout-tax">0 VND</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-primary">
                            <span>Tổng cộng</span>
                            <span id="checkout-total">0 VND</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-primary-foreground hover:bg-primary/90 py-6 rounded-md font-semibold transition-colors">
                        Đặt Hàng & Thanh Toán
                    </button>
                    <a href="cart.php" class="block text-center text-sm text-muted-foreground hover:underline">
                        Quay lại giỏ hàng
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkoutItems = document.getElementById('checkout-items');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const taxEl = document.getElementById('checkout-tax');
        const totalEl = document.getElementById('checkout-total');
        const form = document.getElementById('checkout-form');

        const cart = JSON.parse(localStorage.getItem('lensy_cart') || '[]');

        if (cart.length === 0) {
            window.location.href = 'cart.php'; // Redirect if empty
            return;
        }

        // Render Summary
        let subtotal = 0;
        cart.forEach(item => {
            const itemTotal = parseInt(item.price) * item.quantity;
            subtotal += itemTotal;
            
            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex justify-between items-center text-sm';
            itemDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <img src="${item.image}" class="w-12 h-12 rounded object-cover">
                    <div>
                        <p class="font-semibold">${item.name}</p>
                        <p class="text-muted-foreground">SL: ${item.quantity}</p>
                    </div>
                </div>
                <span>${parseInt(item.price).toLocaleString('vi-VN')}</span>
            `;
            checkoutItems.appendChild(itemDiv);
        });

        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        subtotalEl.innerText = subtotal.toLocaleString('vi-VN') + ' VND';
        taxEl.innerText = tax.toLocaleString('vi-VN') + ' VND';
        totalEl.innerText = total.toLocaleString('vi-VN') + ' VND';

        // Handle Submit
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Validate form (basic)
            
            Swal.fire({
                title: 'Thành công!',
                text: 'Yêu cầu của bạn đã được gửi thành công. Chúng tôi sẽ liên hệ với bạn trong vòng 24 giờ.',
                icon: 'success',
                confirmButtonColor: 'var(--primary)',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('lensy_cart');
                    window.location.href = 'index.php';
                }
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
