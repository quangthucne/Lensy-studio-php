<?php
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'helpers/functions.php';

requireLogin();

$currentUser = getCurrentUser($pdo);
$email = $currentUser['email'];

// Fetch all orders for this customer
$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY created_at DESC");
$stmt->execute([$email]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statusLabels = [
    'pending' => 'Chờ xử lý',
    'confirmed' => 'Đã xác nhận',
    'completed' => 'Đã hoàn thành',
    'cancelled' => 'Đã hủy'
];

$statusColors = [
    'pending' => 'bg-yellow-100 text-yellow-800',
    'confirmed' => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-green-100 text-green-800',
    'cancelled' => 'bg-red-100 text-red-800'
];

$paymentStatusLabels = [
    'unpaid' => 'Chưa thanh toán',
    'paid' => 'Đã thanh toán'
];

include 'components/head.php';
include 'components/header.php';
?>

<main class="w-full bg-background text-foreground min-h-screen">
    <!-- Hero Section -->
    <section class="pt-28 pb-12 bg-gradient-to-b from-muted to-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4">
                <h1 class="text-4xl md:text-5xl font-bold text-balance">
                    Đơn Hàng Của Tôi
                </h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto text-pretty">
                    Quản lý lịch sử đặt lịch và dịch vụ thuê thiết bị của bạn.
                </p>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-card border border-border rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border">
                        <thead class="bg-muted">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Mã Đơn</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Ngày Tạo</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Tổng Tiền</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Thanh Toán</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Trạng Thái</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-card divide-y divide-border">
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-muted-foreground flex items-center justify-center flex-col">
                                        <i data-lucide="package-open" class="w-12 h-12 mb-4 opacity-50"></i>
                                        <p>Bạn chưa có đơn hàng nào trong hệ thống.</p>
                                        <a href="camera-rental.php" class="mt-4 text-primary hover:underline font-medium">Thuê Thiết Bị Ngay</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr class="hover:bg-muted/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                            <?php echo htmlspecialchars($order['code']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground">
                                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-foreground font-semibold">
                                            <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                            <?php 
                                                $pStatus = $order['payment_status'];
                                                echo $paymentStatusLabels[$pStatus] ?? ucfirst($pStatus); 
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php 
                                                $status = $order['status'];
                                                $label = $statusLabels[$status] ?? ucfirst($status);
                                                $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?php echo $color; ?>">
                                                <?php echo $label; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button onclick="openOrderModal(<?php echo $order['id']; ?>, '<?php echo htmlspecialchars($order['code']); ?>')" class="inline-flex items-center text-primary hover:text-primary/80 focus:outline-none bg-primary/10 px-3 py-1.5 rounded transition">
                                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Chi tiết
                                            </button>
                                            
                                            <?php if ($order['status'] === 'pending'): ?>
                                                <button onclick="handleCancelOrder('<?php echo htmlspecialchars($order['code']); ?>', '<?php echo htmlspecialchars($email); ?>')" class="inline-flex items-center text-red-600 hover:text-red-700 focus:outline-none bg-red-50 px-3 py-1.5 rounded transition">
                                                    <i data-lucide="x-circle" class="w-4 h-4 mr-1"></i> Hủy
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Order Detail Modal -->
<div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeOrderModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-card rounded-lg border border-border sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeOrderModal()" class="text-muted-foreground bg-card rounded-md hover:text-foreground focus:outline-none">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-xl font-bold leading-6 text-foreground border-b border-border pb-3 mb-4 flex justify-between items-center" id="modal-headline">
                        Chi Tiết Đơn Hàng: <span id="modalOrderCode" class="text-primary ml-2 font-mono"></span>
                    </h3>
                    
                    <div id="modalLoading" class="text-center py-8">
                        <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-primary mx-auto"></i>
                        <p class="mt-2 text-sm text-muted-foreground">Đang tải dữ liệu...</p>
                    </div>

                    <div id="modalContent" class="hidden text-left">
                        <div class="grid grid-cols-2 gap-4 mb-6 bg-muted/30 p-4 rounded-lg border border-border">
                            <div>
                                <p class="text-xs text-muted-foreground uppercase font-semibold">Ngày Tạo</p>
                                <p class="text-sm font-medium text-foreground" id="modDate"></p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground uppercase font-semibold">Tổng Thanh Toán</p>
                                <p class="text-sm font-bold text-primary" id="modTotal"></p>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div id="modBookingSection" class="mb-6 hidden">
                            <h4 class="text-sm font-bold text-foreground mb-2 border-b border-border pb-1">Dịch Vụ Đặt Lịch (Booking)</h4>
                            <div class="bg-secondary/10 border border-secondary/20 rounded-md p-3">
                                <p class="text-sm text-foreground mb-1"><span class="font-medium">Dịch Vụ:</span> <span id="modBServiceName"></span></p>
                                <p class="text-sm text-foreground mb-1"><span class="font-medium">Thời Gian:</span> <span id="modBTime"></span></p>
                                <p class="text-sm text-foreground"><span class="font-medium">Trạng Thái:</span> <span id="modBStatus" class="font-bold text-secondary"></span></p>
                            </div>
                        </div>

                        <!-- Rental Details -->
                        <div id="modRentalSection" class="hidden">
                            <h4 class="text-sm font-bold text-foreground mb-2 border-b border-border pb-1">Chi Tiết Thiết Bị / Phục Trang</h4>
                            <div id="modRentalList" class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                <!-- injected via js -->
                            </div>
                        </div>
                    </div>

                    <div id="modalError" class="hidden text-center py-6 text-red-600">
                        <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                        <p id="modalErrorText">Đã có lỗi xảy ra.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse border-t border-border pt-4">
                <button type="button" onclick="closeOrderModal()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-primary-foreground bg-primary rounded-md hover:bg-primary/90 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function openOrderModal(id, code) {
        document.getElementById('orderModal').classList.remove('hidden');
        document.getElementById('modalOrderCode').innerText = code;
        
        document.getElementById('modalLoading').classList.remove('hidden');
        document.getElementById('modalContent').classList.add('hidden');
        document.getElementById('modalError').classList.add('hidden');

        fetch(`controllers/get_order_details.php?id=${id}`)
            .then(res => res.json())
            .then(res => {
                document.getElementById('modalLoading').classList.add('hidden');
                if (res.success) {
                    const data = res.data;
                    document.getElementById('modTotal').innerText = data.total_amount_formatted;
                    document.getElementById('modDate').innerText = data.created_at_formatted;

                    const bookingSec = document.getElementById('modBookingSection');
                    if (data.booking) {
                        bookingSec.classList.remove('hidden');
                        document.getElementById('modBServiceName').innerText = data.booking.service_name;
                        document.getElementById('modBTime').innerText = data.booking.booking_time_formatted;
                        document.getElementById('modBStatus').innerText = data.booking.status_formatted;
                    } else {
                        bookingSec.classList.add('hidden');
                    }

                    const rentalSec = document.getElementById('modRentalSection');
                    const rentalList = document.getElementById('modRentalList');
                    rentalList.innerHTML = '';
                    if (data.rentals && data.rentals.length > 0) {
                        data.rentals.forEach(item => {
                            let imgPath = item.product_image ? (item.product_image.startsWith('http') ? item.product_image : 'uploads/' + item.product_image) : 'assets/placeholder.jpg';
                            rentalList.innerHTML += `
                                <div class="flex items-center justify-between p-3 bg-card border border-border rounded-lg shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <img src="${imgPath}" alt="${item.product_name}" class="w-12 h-12 object-cover rounded">
                                        <div>
                                            <p class="text-sm font-medium text-foreground">${item.product_name}</p>
                                            <p class="text-xs text-muted-foreground">Số lượng: ${item.quantity} x ${item.price_formatted}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-primary">${item.total_formatted}</p>
                                </div>
                            `;
                        });
                        rentalSec.classList.remove('hidden');
                    } else {
                        rentalSec.classList.add('hidden');
                    }

                    document.getElementById('modalContent').classList.remove('hidden');
                } else {
                    document.getElementById('modalError').classList.remove('hidden');
                    document.getElementById('modalErrorText').innerText = res.message;
                }
            })
            .catch(err => {
                document.getElementById('modalLoading').classList.add('hidden');
                document.getElementById('modalError').classList.remove('hidden');
                document.getElementById('modalErrorText').innerText = 'Lỗi kết nối mạng';
            });
    }

    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }

    // Cancellation Logic (reused from track-order)
    window.handleCancelOrder = function(orderCode, email) {
        Swal.fire({
            title: 'Hủy đơn hàng này?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Có, Hủy bỏ!',
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
                        Swal.fire('Thành công', 'Đã hủy đơn hàng.', 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Lỗi', res.message || 'Không thể hủy đơn lúc này.', 'error');
                    }
                } catch (e) {
                     Swal.fire('Lỗi', 'Lỗi kết nối mạng.', 'error');
                }
            }
        });
    };
</script>

<?php include 'components/footer.php'; ?>
