<?php include 'components/head.php'; ?>
<?php include 'components/header.php'; ?>

<main class="w-full bg-background text-foreground min-h-screen">
    <!-- Hero Section -->
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold text-balance">
                    Tra Cứu Đơn Hàng
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Kiểm tra trạng thái đặt lịch hoặc thuê thiết bị của bạn.
                </p>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12 bg-background">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-card border border-border rounded-lg p-8 shadow-sm">
                <form id="trackForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-2">Mã Đơn Hàng (Ví dụ: BKG-1234)</label>
                        <input type="text" name="code" id="orderCode" required placeholder="Nhập mã đơn hàng" class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-2">Địa Chỉ Email</label>
                        <input type="email" name="email" id="customerEmail" required placeholder="Nhập email đã đăng ký đơn" class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <button type="submit" class="w-full bg-primary text-primary-foreground hover:bg-primary/90 py-3 font-semibold rounded-md transition-colors">
                        Kiểm Tra
                    </button>
                </form>
            </div>

            <!-- Result Container -->
            <div id="resultContainer" class="mt-8 hidden">
                <div class="bg-white border rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-bold border-b pb-3 mb-4 flex justify-between items-center">
                        <span>Chi Tiết Đơn Hàng: <span id="resCode" class="text-primary font-mono ml-2"></span></span>
                        <span id="resStatus" class="px-3 py-1 rounded-full text-sm font-semibold"></span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Khách Hàng</p>
                            <p id="resName" class="font-medium text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Ngày Tạo</p>
                            <p id="resDate" class="font-medium text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tổng Tiền</p>
                            <p id="resAmount" class="font-medium text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Thanh Toán</p>
                            <p id="resPayment" class="font-medium text-gray-900"></p>
                        </div>
                    </div>
                    <div id="bookingDetails" class="mt-6 pt-4 border-t hidden">
                        <h4 class="font-semibold text-gray-800 mb-3">Chi tiết Đặt Lịch (Booking)</h4>
                        <div class="bg-blue-50 text-blue-800 p-4 rounded-md">
                            <p class="mb-1"><span class="font-medium">Dịch Vụ:</span> <span id="resService"></span></p>
                            <p class="mb-1"><span class="font-medium">Thời Gian:</span> <span id="resTime"></span></p>
                            <p class="mb-1"><span class="font-medium">Trạng Thái Chụp:</span> <span id="resSubStatus"></span></p>
                        </div>
                    </div>
                    <div id="rentalDetails" class="mt-6 pt-4 border-t hidden">
                        <h4 class="font-semibold text-gray-800 mb-3">Chi tiết Thuê Thiết bị / Phục trang</h4>
                        <div id="rentalItemsList" class="space-y-3">
                            <!-- Items injected here via JS -->
                        </div>
                    </div>

                    <div id="actionContainer" class="mt-6 pt-4 border-t hidden text-right">
                        <button id="cancelOrderBtn" class="bg-red-600 text-white font-semibold py-2 px-6 rounded hover:bg-red-700 transition">Hủy Đơn Hàng</button>
                    </div>
                </div>
            </div>

            <div id="errorContainer" class="mt-8 hidden">
                 <div class="bg-red-50 text-red-700 p-4 rounded-lg flex items-start">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold">Lỗi Trích Xuất Dữ Liệu</h4>
                        <p id="errorMsg" class="text-sm mt-1"></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('trackForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        const resultContainer = document.getElementById('resultContainer');
        const errorContainer = document.getElementById('errorContainer');
        const bookingDetails = document.getElementById('bookingDetails');

        const statusColors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        const statusLabels = {
            'pending': 'Chờ Xử Lý',
            'confirmed': 'Đã Xác Nhận',
            'completed': 'Hoàn Thành',
            'cancelled': 'Đã Hủy'
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            resultContainer.classList.add('hidden');
            errorContainer.classList.add('hidden');
            bookingDetails.classList.add('hidden');
            
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Đang Kiểm Tra...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(form);
                const response = await fetch('controllers/process_track_order.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    document.getElementById('resCode').innerText = data.code;
                    document.getElementById('resName').innerText = data.customer_name;
                    document.getElementById('resAmount').innerText = data.total_amount_formatted + ' ₫';
                    document.getElementById('resDate').innerText = data.created_at_formatted;
                    document.getElementById('resPayment').innerText = data.payment_status === 'paid' ? 'Đã Thanh Toán' : 'Chưa Thanh Toán';
                    
                    const statusClass = statusColors[data.status] || 'bg-gray-100 text-gray-800';
                    const statusEl = document.getElementById('resStatus');
                    statusEl.className = `px-3 py-1 rounded-full text-sm font-semibold ${statusClass}`;
                    statusEl.innerText = statusLabels[data.status] || data.status;

                    if (data.booking) {
                        bookingDetails.classList.remove('hidden');
                        document.getElementById('resService').innerText = data.booking.service_name;
                        document.getElementById('resTime').innerText = data.booking.booking_time_formatted;
                        document.getElementById('resSubStatus').innerText = data.booking.status_formatted;
                    }

                    if (data.rentals && data.rentals.length > 0) {
                        const rentalList = document.getElementById('rentalItemsList');
                        rentalList.innerHTML = '';
                        data.rentals.forEach(item => {
                            let imgPath = item.product_image ? (item.product_image.startsWith('http') ? item.product_image : item.product_image) : 'assets/placeholder.jpg';
                            rentalList.innerHTML += `
                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded border">
                                    <div class="flex items-center gap-3">
                                        <img src="${imgPath}" alt="${item.product_name}" class="w-12 h-12 object-cover rounded">
                                        <div>
                                            <p class="font-medium text-gray-800">${item.product_name}</p>
                                            <p class="text-sm text-gray-500">Số lượng: ${item.quantity} x ${item.price_formatted}</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-primary">${item.total_formatted}</p>
                                </div>
                            `;
                        });
                        document.getElementById('rentalDetails').classList.remove('hidden');
                    } else {
                        document.getElementById('rentalDetails').classList.add('hidden');
                    }

                    const actionContainer = document.getElementById('actionContainer');
                    const cancelBtn = document.getElementById('cancelOrderBtn');
                    
                    if (data.status === 'pending') {
                        actionContainer.classList.remove('hidden');
                        cancelBtn.onclick = () => handleCancelOrder(data.code, document.getElementById('customerEmail').value);
                    } else {
                        actionContainer.classList.add('hidden');
                    }

                    resultContainer.classList.remove('hidden');
                } else {
                    document.getElementById('errorMsg').innerText = result.message || 'Không tìm thấy đơn hàng. Vui lòng kiểm tra lại Mã Đơn Hàng và Email.';
                    errorContainer.classList.remove('hidden');
                }
            } catch (error) {
                document.getElementById('errorMsg').innerText = 'Lỗi kết nối. Vui lòng thử lại sau.';
                errorContainer.classList.remove('hidden');
            } finally {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        });
        window.handleCancelOrder = function(orderCode, email) {
            Swal.fire({
                title: 'Xác nhận hủy đơn?',
                text: "Bạn có chắc chắn muốn hủy đơn hàng này không?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Đồng ý hủy',
                cancelButtonText: 'Không'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const formData = new FormData();
                        formData.append('code', orderCode);
                        formData.append('email', email);
                        
                        const response = await fetch('controllers/process_cancel_order.php', {
                            method: 'POST',
                            body: formData
                        });
                        const res = await response.json();
                        if (res.success) {
                            Swal.fire('Thành công', 'Đã hủy đơn hàng.', 'success');
                            // Re-submit the form to refresh the tracked order view
                            form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                        } else {
                            Swal.fire('Lỗi', res.message || 'Không thể hủy đơn lúc này.', 'error');
                        }
                    } catch (e) {
                         Swal.fire('Lỗi', 'Lỗi kết nối mạng.', 'error');
                    }
                }
            });
        };
    });
</script>

<?php include 'components/footer.php'; ?>
