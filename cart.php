<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<main class="w-full bg-background text-foreground min-h-screen">
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-5xl md:text-6xl font-bold text-balance">
                    Giỏ Hàng
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Xem lại các mặt hàng của bạn và tiến hành thanh toán
                </p>
            </div>
        </div>
    </section>

    <section class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12" id="cart-container">
                <!-- Cart Items Column -->
                <div class="lg:col-span-2">
                    <div class="space-y-6" id="cart-items-list">
                        <!-- Items will be injected here by JS -->
                        <div class="text-center py-12 hidden" id="empty-cart-message">
                            <h3 class="text-xl font-semibold">Giỏ hàng của bạn đang trống</h3>
                            <a href="index.php">
                                <button class="mt-4 px-6 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
                                    Tiếp tục tham khảo
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Column -->
                <div class="bg-card border border-border rounded-lg p-8 space-y-6 h-fit sticky top-24">
                    <h2 class="text-2xl font-bold">Tóm Tắt Đơn Hàng</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span>Tạm tính</span>
                            <span id="subtotal-display">0 VND</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Thuế (10%)</span>
                            <span id="tax-display">0 VND</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-4 border-t border-border">
                            <span>Tổng cộng</span>
                            <span id="total-display">0 VND</span>
                        </div>
                    </div>
                    <a href="checkout.php" id="checkout-btn" class="block pointer-events-none opacity-50">
                        <button class="w-full bg-primary text-primary-foreground hover:bg-primary/90 py-6 rounded-md font-semibold transition-colors">
                            Tiến Hành Thanh Toán
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartItemsList = document.getElementById('cart-items-list');
        const emptyCartMsg = document.getElementById('empty-cart-message');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const taxDisplay = document.getElementById('tax-display');
        const totalDisplay = document.getElementById('total-display');
        const checkoutBtn = document.getElementById('checkout-btn');

        // Cart is loaded by fetchCart() in footer.php
        // We listen to the custom event to know when data is ready
        window.addEventListener('cartDataLoaded', () => {
            renderCart();
        });

        function getCart() {
            return window.cart || [];
        }

        function renderCart() {
            const currentCart = getCart();

            // Clear existing items (except empty message)
            Array.from(cartItemsList.children).forEach(child => {
                if (child.id !== 'empty-cart-message') {
                    child.remove();
                }
            });

            if (currentCart.length === 0) {
                emptyCartMsg.classList.remove('hidden');
                checkoutBtn.classList.add('pointer-events-none', 'opacity-50');
                updateTotals();
                return;
            }

            emptyCartMsg.classList.add('hidden');
            checkoutBtn.classList.remove('pointer-events-none', 'opacity-50');

            currentCart.forEach((item, index) => {
                const itemEl = document.createElement('div');
                itemEl.className = 'flex flex-col sm:flex-row items-center gap-6 bg-card border border-border rounded-lg p-4 animate-in fade-in slide-in-from-bottom-4 duration-500';
                itemEl.innerHTML = `
                    <div class="relative w-24 h-24 flex-shrink-0">
                        <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover rounded-lg text-xs">
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="font-semibold text-lg">${item.name}</h3>
                        <p class="text-primary font-bold">${parseInt(item.price).toLocaleString('vi-VN')} VND</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-border rounded-md">
                            <button class="px-3 py-1 hover:bg-muted font-bold" onclick="updateQuantity(${item.cart_id}, ${item.quantity - 1})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                            <span class="w-12 text-center">${item.quantity}</span>
                            <button class="px-3 py-1 hover:bg-muted font-bold" onclick="updateQuantity(${item.cart_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <button class="p-2 text-destructive hover:bg-destructive/10 rounded-full transition-colors" onclick="removeItem(${item.cart_id})">
                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                        </button>
                    </div>
                `;
                cartItemsList.appendChild(itemEl);
            });

            // Re-initialize icons
            if (typeof lucide !== 'undefined') lucide.createIcons();
            updateTotals();
        }

        window.updateQuantity = async (cartId, newQuantity) => {
            if (newQuantity < 1) newQuantity = 1;

            try {
                const res = await fetch('controllers/cart/update.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ cart_id: cartId, quantity: newQuantity })
                });
                const result = await res.json();
                if (result.success) {
                    window.dispatchEvent(new Event('cartUpdated')); // Triggers re-fetch in footer
                } else {
                    Swal.fire({ title: 'Lỗi!', text: result.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
                }
            } catch (e) {
                console.error(e);
            }
        };

        window.removeItem = async (cartId) => {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Sản phẩm này sẽ bị xóa khỏi giỏ hàng!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280', // distinct gray color
                confirmButtonText: 'Đồng ý, xóa ngay!',
                cancelButtonText: 'Hủy'
            }).then(async (swalResult) => {
                if (swalResult.isConfirmed) {
                    try {
                        const res = await fetch('controllers/cart/remove.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({ cart_id: cartId })
                        });
                        const result = await res.json();
                        if (result.success) {
                            window.dispatchEvent(new Event('cartUpdated'));
                            Swal.fire({
                                title: 'Đã xóa!',
                                text: 'Sản phẩm đã được xóa khỏi giỏ.',
                                icon: 'success',
                                confirmButtonColor: 'var(--primary)',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({ title: 'Lỗi!', text: result.message, icon: 'error', confirmButtonColor: 'var(--primary)' });
                        }
                    } catch (e) {
                        console.error(e);
                        Swal.fire({ title: 'Lỗi!', text: 'Không kết nối được server.', icon: 'error', confirmButtonColor: 'var(--primary)' });
                    }
                }
            });
        };

        function updateTotals() {
            const currentCart = getCart();
            const subtotal = currentCart.reduce((acc, item) => acc + (parseInt(item.price) * item.quantity), 0);
            const tax = subtotal * 0.1;
            const total = subtotal + tax;

            subtotalDisplay.innerText = subtotal.toLocaleString('vi-VN') + ' VND';
            taxDisplay.innerText = tax.toLocaleString('vi-VN') + ' VND';
            totalDisplay.innerText = total.toLocaleString('vi-VN') + ' VND';
        }

        // Initial render if data is already loaded
        if (window.cart && window.cart.length >= 0) {
            renderCart();
        }
    });
</script>

<?php include 'components/footer.php'; ?>
