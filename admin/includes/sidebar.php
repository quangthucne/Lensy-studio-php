<?php
$nav_items = [
    ['label' => 'Dashboard', 'href' => 'index.php', 'icon' => 'layout-dashboard', 'permission' => null], // Accessible to all authorized
    ['label' => 'Đơn hàng', 'href' => 'orders.php', 'icon' => 'shopping-bag', 'permission' => 'manage_bookings'],
    ['label' => 'Bookings', 'href' => 'bookings.php', 'icon' => 'calendar', 'permission' => 'manage_bookings'],
    ['label' => 'Rentals', 'href' => 'rentals.php', 'icon' => 'camera', 'permission' => 'manage_bookings'], // rentals usually go with bookings
    ['label' => 'Danh mục', 'href' => 'categories.php', 'icon' => 'folder-tree', 'permission' => 'manage_products'],
    ['label' => 'Products', 'href' => 'products.php', 'icon' => 'package', 'permission' => 'manage_products'],
    ['label' => 'Users', 'href' => 'users.php', 'icon' => 'users', 'permission' => 'manage_users'],
];
// Filter items based on permission
$nav_items = array_filter($nav_items, function($item) use ($pdo) {
    if ($item['permission'] === null) return true;
    return hasPermission($pdo, $item['permission']);
});
?>
<aside class="w-64 bg-primary text-white flex-shrink-0 hidden md:flex flex-col">
    <div class="h-16 flex items-center px-6 border-b border-gray-700">
        <span class="text-2xl font-bold">Lensy Admin</span>
    </div>
    <nav class="flex-1 py-6 px-3 space-y-1">
        <?php foreach ($nav_items as $item): 
            $isActive = ($current_page == basename($item['href'], ".php"));
            $activeClass = $isActive ? "bg-gray-800 text-white" : "text-gray-300 hover:bg-gray-800 hover:text-white";
        ?>
            <a href="<?php echo $item['href']; ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?php echo $activeClass; ?>">
                <i data-lucide="<?php echo $item['icon']; ?>" class="mr-3 h-5 w-5 flex-shrink-0"></i>
                <?php echo $item['label']; ?>
            </a>
        <?php endforeach; ?>
        
        <div class="mt-8 px-3">
             <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">System</div>
             <a href="../index.php" target="_blank" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-800 hover:text-white">
                <i data-lucide="external-link" class="mr-3 h-5 w-5 flex-shrink-0"></i>
                View Website
            </a>
        </div>
    </nav>
</aside>
