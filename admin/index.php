<?php require_once 'components/header.php'; ?>

<?php
// Calculate Stats
try {
    // Total Revenue
    $stmt = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'");
    $revenue = $stmt->fetchColumn() ?: 0;

    // Pending Orders
    $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
    $pendingOrders = $stmt->fetchColumn();

    // Active Rentals
    $stmt = $pdo->query("SELECT COUNT(*) FROM order_rentals WHERE status = 'picked_up'");
    $activeRentals = $stmt->fetchColumn();

    // Total Users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmt->fetchColumn();

    // ---------------- NEW STATS ----------------
    
    // Get filter days
    $daysFilter = isset($_GET['days']) ? (int)$_GET['days'] : 30;
    if (!in_array($daysFilter, [3, 7, 30])) {
        $daysFilter = 30;
    }

    // 1. Revenue History
    $stmtRevenue = $pdo->prepare("
        SELECT DATE(created_at) as date, SUM(total_amount) as daily_revenue 
        FROM orders 
        WHERE status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY DATE(created_at) 
        ORDER BY DATE(created_at) ASC
    ");
    $stmtRevenue->execute([$daysFilter]);
    $revenueData = $stmtRevenue->fetchAll(PDO::FETCH_ASSOC);
    $chartDates = [];
    $chartRevenues = [];
    foreach ($revenueData as $row) {
        $chartDates[] = date('d/m', strtotime($row['date']));
        $chartRevenues[] = $row['daily_revenue'];
    }
    $datesJson = json_encode($chartDates);
    $revenuesJson = json_encode($chartRevenues);

    // 2. Top 5 Products Rented
    $stmtTopProducts = $pdo->query("
        SELECT p.name, p.image_url, SUM(or_.quantity) as total_rented, SUM(or_.price_per_day * or_.quantity) as total_revenue
        FROM order_rentals or_
        JOIN products p ON or_.product_id = p.id
        JOIN orders o ON or_.order_id = o.id
        WHERE o.status != 'cancelled'
        GROUP BY p.id
        ORDER BY total_rented DESC
        LIMIT 5
    ");
    $topProducts = $stmtTopProducts->fetchAll(PDO::FETCH_ASSOC);

    // 3. Top 5 Customers
    $stmtTopCustomers = $pdo->query("
        SELECT customer_name, customer_email, SUM(total_amount) as total_spent, COUNT(id) as total_orders
        FROM orders
        WHERE status = 'completed'
        GROUP BY customer_email, customer_name
        ORDER BY total_spent DESC
        LIMIT 5
    ");
    $topCustomers = $stmtTopCustomers->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error fetching stats: " . $e->getMessage();
}
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Thống kê Tổng quan</h2>
    <p class="text-gray-600">Theo dõi hoạt động của hệ thống.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue Card -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i data-lucide="dollar-sign" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Tổng Doanh Thu</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo number_format($revenue, 0, ',', '.'); ?> ₫</p>
            </div>
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                <i data-lucide="shopping-cart" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Đơn Hàng Chờ Xử Lý</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $pendingOrders; ?></p>
            </div>
        </div>
    </div>

    <!-- Active Rentals Card -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i data-lucide="camera" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Đơn Thuê Đang Mượn</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $activeRentals; ?></p>
            </div>
        </div>
    </div>

    <!-- Users Card -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                <i data-lucide="users" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Tổng Người Dùng</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Rankings Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Revenue Chart (Takes 2 columns on wide screens) -->
    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center justify-between">
            <div class="flex items-center">
                <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-indigo-500"></i> Xu hướng Doanh Thu
            </div>
            <form method="GET" action="index.php">
                <select name="days" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 pl-2 pr-8">
                    <option value="3" <?php echo $daysFilter == 3 ? 'selected' : ''; ?>>3 Ngày</option>
                    <option value="7" <?php echo $daysFilter == 7 ? 'selected' : ''; ?>>7 Ngày</option>
                    <option value="30" <?php echo $daysFilter == 30 ? 'selected' : ''; ?>>30 Ngày</option>
                </select>
            </form>
        </h3>
        <div class="relative h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Products Ranking -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i data-lucide="award" class="w-5 h-5 mr-2 text-yellow-500"></i> Sản Phẩm Hot Nhất
        </h3>
        <ul class="divide-y divide-gray-200">
            <?php if (empty($topProducts)): ?>
                <li class="py-4 text-center text-gray-500 text-sm">Chưa có dữ liệu thuê mượn.</li>
            <?php else: ?>
                <?php foreach ($topProducts as $index => $product): ?>
                    <li class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold <?php echo $index < 3 ? 'text-indigo-600' : 'text-gray-400'; ?>">#<?php echo $index + 1; ?></span>
                            <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="w-10 h-10 rounded object-cover border">
                            <div>
                                <p class="text-sm font-medium text-gray-800 line-clamp-1"><?php echo htmlspecialchars($product['name']); ?></p>
                                <p class="text-xs text-gray-500">Đã thuê: <?php echo $product['total_rented']; ?> lượt</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-green-600">
                            <?php echo number_format($product['total_revenue'], 0, ',', '.'); ?> ₫
                        </span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- Customer Rankings -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="crown" class="w-5 h-5 mr-2 text-amber-500"></i> Khách Hàng Thân Thiết (VIP)
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Hạng</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Khách Hàng</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Số Đơn</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase">Tổng Chi Tiêu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($topCustomers)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Chưa có khách hàng hoàn thành đơn.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($topCustomers as $index => $customer): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-600">#<?php echo $index + 1; ?></td>
                            <td class="px-4 py-3 font-medium text-gray-900"><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                            <td class="px-4 py-3 text-gray-500"><?php echo htmlspecialchars($customer['customer_email']); ?></td>
                            <td class="px-4 py-3 text-center font-semibold text-indigo-600"><?php echo $customer['total_orders']; ?></td>
                            <td class="px-4 py-3 text-right font-bold text-green-600">
                                <?php echo number_format($customer['total_spent'], 0, ',', '.'); ?> ₫
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const labels = <?php echo isset($datesJson) ? $datesJson : '[]'; ?>;
        const data = <?php echo isset($revenuesJson) ? $revenuesJson : '[]'; ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data,
                    borderColor: '#6366f1', // Indigo 500
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#6366f1',
                    fill: true,
                    tension: 0.3 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000) + 'K';
                                }
                                return value;
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
});
</script>

<?php require_once 'components/footer.php'; ?>
