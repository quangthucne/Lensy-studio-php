<?php require_once 'components/header.php'; ?>
<?php requirePermission($pdo, 'manage_bookings'); ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Quản lý Đơn hàng</h2>
        <p class="text-gray-600">Xem và cập nhật trạng thái các đơn hàng.</p>
    </div>
</div>

<?php
$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = '';
$params = [];
if ($search !== '') {
    $whereClause = "WHERE code LIKE ? OR customer_phone LIKE ? OR customer_email LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

// Count total
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM orders $whereClause");
$countStmt->execute($params);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Fetch all orders
$stmt = $pdo->prepare("SELECT * FROM orders $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
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
?>

<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm mã đơn, email, sđt..." class="border border-gray-300 rounded-md p-2 text-sm w-64 focus:ring-indigo-500 focus:border-indigo-500">
    <button type="submit" class="bg-indigo-600 border border-transparent text-white rounded-md px-4 py-2 text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Tìm kiếm</button>
    <?php if($search): ?>
        <a href="orders.php" class="text-sm text-gray-500 self-center hover:text-gray-700 underline">Xóa bộ lọc</a>
    <?php endif; ?>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách Hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng Tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh Toán</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Tạo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($order['code']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> ₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
                                    <?php echo $label; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled'): ?>
                                    <form action="../controllers/admin_update_order_status.php" method="POST" class="inline-block mt-1 mr-2">
                                        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                        <select name="status" class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 py-1" onchange="this.form.submit()">
                                            <option value="">-- Cập nhật --</option>
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'disabled' : ''; ?>>Chờ xử lý</option>
                                            <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'disabled' : ''; ?>>Xác nhận</option>
                                            <option value="completed">Đã hoàn thành</option>
                                            <option value="cancelled">Hủy đơn</option>
                                        </select>
                                    </form>
                                <?php endif; ?>
                                <button onclick="openOrderModal(<?php echo $order['id']; ?>, '<?php echo htmlspecialchars($order['code']); ?>')" class="inline-block mt-1 text-indigo-600 hover:text-indigo-900 focus:outline-none" title="Xem chi tiết">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <nav class="flex items-center justify-between">
            <div class="hidden sm:block">
                <p class="text-sm text-gray-700">
                    Hiển thị trang <span class="font-medium"><?php echo $page; ?></span> trên <span class="font-medium"><?php echo $totalPages; ?></span>
                </p>
            </div>
            <div class="flex-1 flex justify-between sm:justify-end">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trang trước
                    </a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Trang sau
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    <?php endif; ?>
</div>

<!-- Order Detail Modal -->
<div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeOrderModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeOrderModal()" class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            
            <div class="sm:flex sm:items-start">
                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-3 mb-4 flex justify-between items-center" id="modal-headline">
                        Chi Tiết Đơn Hàng: <span id="modalOrderCode" class="text-indigo-600 ml-2 font-mono"></span>
                    </h3>
                    
                    <div id="modalLoading" class="text-center py-8">
                        <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-indigo-500 mx-auto"></i>
                        <p class="mt-2 text-sm text-gray-500">Đang tải dữ liệu...</p>
                    </div>

                    <div id="modalContent" class="hidden">
                        <!-- Summary -->
                        <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Khách Hàng</p>
                                <p class="text-sm font-medium text-gray-900" id="modName"></p>
                                <p class="text-sm text-gray-600" id="modEmail"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Tóm Tắt</p>
                                <p class="text-sm text-gray-900">Tổng Tiền: <span class="font-bold text-indigo-600" id="modTotal"></span></p>
                                <p class="text-sm text-gray-900">Ngày Tạo: <span id="modDate"></span></p>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div id="modBookingSection" class="mb-6 hidden">
                            <h4 class="text-sm font-bold text-gray-800 mb-2 border-b pb-1">Dịch Vụ Đặt Lịch (Booking)</h4>
                            <div class="bg-blue-50 border border-blue-100 rounded-md p-3">
                                <p class="text-sm text-blue-900"><strong>Dịch Vụ:</strong> <span id="modBServiceName"></span></p>
                                <p class="text-sm text-blue-900"><strong>Thời Gian:</strong> <span id="modBTime"></span></p>
                                <p class="text-sm text-blue-900"><strong>Trạng Thái:</strong> <span id="modBStatus" class="font-semibold"></span></p>
                            </div>
                        </div>

                        <!-- Rental Details -->
                        <div id="modRentalSection" class="hidden">
                            <h4 class="text-sm font-bold text-gray-800 mb-2 border-b pb-1">Chi Tiết Thiết Bị / Phục Trang</h4>
                            <div id="modRentalList" class="space-y-2 max-h-60 overflow-y-auto pr-2">
                                <!-- list injected via JS -->
                            </div>
                        </div>
                    </div>

                    <div id="modalError" class="hidden text-center py-6 text-red-600">
                        <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                        <p id="modalErrorText">Đã có lỗi xảy ra.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeOrderModal()" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openOrderModal(id, code) {
        document.getElementById('orderModal').classList.remove('hidden');
        document.getElementById('modalOrderCode').innerText = code;
        
        document.getElementById('modalLoading').classList.remove('hidden');
        document.getElementById('modalContent').classList.add('hidden');
        document.getElementById('modalError').classList.add('hidden');

        fetch(`../controllers/get_order_details.php?id=${id}`)
            .then(res => res.json())
            .then(res => {
                document.getElementById('modalLoading').classList.add('hidden');
                if (res.success) {
                    const data = res.data;
                    document.getElementById('modName').innerText = data.customer_name;
                    document.getElementById('modEmail').innerText = data.customer_email;
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
                            let imgPath = item.product_image ? (item.product_image.startsWith('http') ? item.product_image : '../' + item.product_image) : '../assets/placeholder.jpg';
                            rentalList.innerHTML += `
                                <div class="flex items-center justify-between p-2 bg-white border rounded">
                                    <div class="flex items-center gap-3">
                                        <img src="${imgPath}" alt="${item.product_name}" class="w-10 h-10 object-cover rounded">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">${item.product_name}</p>
                                            <p class="text-xs text-gray-500">${item.quantity} x ${item.price_formatted}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">${item.total_formatted}</p>
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

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php require_once 'components/footer.php'; ?>
