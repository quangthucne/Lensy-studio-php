<footer class="bg-foreground text-background py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <h3 class="font-bold text-lg mb-4">
                    Studio Lensy
                </h3>
                <p class="text-sm opacity-80">
                    Dịch vụ chụp ảnh và cho thuê trang phục cao cấp
                    cho những khoảnh khắc đặc biệt
                </p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Dịch Vụ</h4>
                <ul class="space-y-2 text-sm opacity-80">
                    <li>
                        <a href="packages.php">Gói Chụp Ảnh</a>
                    </li>
                    <li>
                        <a href="costumes.php">
                            Thuê Trang Phục
                        </a>
                    </li>
                    <li>
                        <a href="equipment.php">Thuê Thiết Bị</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Thư Viện</h4>
                <ul class="space-y-2 text-sm opacity-80">
                    <li>
                        <a href="gallery.php">Portfolio</a>
                    </li>
                    <li>
                        <a href="gallery.php">Blog</a>
                    </li>
                    <li>
                        <a href="index.php">Về Chúng Tôi</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Liên Hệ</h4>
                <ul class="space-y-2 text-sm opacity-80">
                    <li>Điện thoại: (84) xxx-xxxx</li>
                    <li>Email: info@studiolensy.vn</li>
                    <li>Theo dõi chúng tôi trên Instagram</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-background/20 pt-8">
            <p class="text-center text-sm opacity-70">
                © 2025 Studio Lensy. Mọi quyền được bảo lưu.
            </p>
        </div>
    </div>
</footer>

<!-- Global Cart Logic -->
<script>
    window.cart = JSON.parse(localStorage.getItem('lensy_cart') || '[]');
    
    function updateCartCount() {
        const count = window.cart.reduce((acc, item) => acc + item.quantity, 0);
        const badge = document.getElementById('cart-badge');
        if (badge) {
            badge.innerText = count;
            if (count > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    window.addToCart = function(item) {
        const existing = window.cart.find(i => i.id === item.id && i.name === item.name);
        if (existing) {
            existing.quantity += item.quantity || 1;
        } else {
            window.cart.push({ ...item, quantity: item.quantity || 1 });
        }
        localStorage.setItem('lensy_cart', JSON.stringify(window.cart));
        updateCartCount();
        
        // Feedback
        const btn = event.currentTarget; // correctly target the button
        const originalContent = btn.innerHTML;
        btn.innerHTML = 'Đã thêm!';
        btn.disabled = true;
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }, 1000);
    };

    window.addEventListener('storage', () => {
        window.cart = JSON.parse(localStorage.getItem('lensy_cart') || '[]');
        updateCartCount();
    });
    
    window.addEventListener('cartUpdated', () => {
         window.cart = JSON.parse(localStorage.getItem('lensy_cart') || '[]');
         updateCartCount();
    });

    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

<!-- Initialize Icons if not already done in header -->
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
</body>
</html>
