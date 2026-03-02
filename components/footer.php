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
    window.cart = [];
    
    async function fetchCart() {
        try {
            const res = await fetch('controllers/cart/get.php');
            const data = await res.json();
            if (data.success) {
                window.cart = data.data;
                updateCartCount();
                // Dispatch event in case other components (like cart page) need to re-render
                window.dispatchEvent(new Event('cartDataLoaded'));
            }
        } catch (e) {
            console.error("Error fetching cart", e);
        }
    }

    function updateCartCount() {
        const count = window.cart.reduce((acc, item) => acc + parseInt(item.quantity), 0);
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

    window.addToCart = async function(item, btnElement = null) {
        const btn = btnElement || (window.event && window.event.currentTarget);
        let originalContent = '';
        if (btn) {
            originalContent = btn.innerHTML;
            btn.innerHTML = 'Đang thêm...';
            btn.disabled = true;
        }

        try {
            const res = await fetch('controllers/cart/add.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    product_id: item.id,
                    quantity: item.quantity || 1
                })
            });
            const result = await res.json();
            
            if (result.success) {
                await fetchCart();
                if (btn) btn.innerHTML = 'Đã thêm!';
            } else {
                if (btn) btn.innerHTML = 'Lỗi!';
                console.error(result.message);
            }
        } catch (error) {
            if (btn) btn.innerHTML = 'Lỗi!';
            console.error("Network error", error);
        }
        
        if (btn) {
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 1000);
        }
    };

    window.addEventListener('cartUpdated', fetchCart);
    document.addEventListener('DOMContentLoaded', fetchCart);
</script>

<!-- Initialize Icons if not already done in header -->
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
</body>
</html>
