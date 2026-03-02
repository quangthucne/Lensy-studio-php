<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/config/config.php'; 
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/helpers/functions.php'; 
?>
<?php
// active page logic
$current_page = basename($_SERVER['PHP_SELF'], ".php");
$nav_links = [
    ["href" => "index.php", "label" => "Trang Chủ", "page" => "index"],
    ["href" => "packages.php", "label" => "Gói Dịch Vụ", "page" => "packages"],
    ["href" => "gallery.php", "label" => "Thư Viện", "page" => "gallery"],
    ["href" => "costumes.php", "label" => "Thuê Áo", "page" => "costumes"],
    ["href" => "camera-rental.php", "label" => "Thuê thiết bị", "page" => "camera-rental"],
    ["href" => "booking.php", "label" => "Đặt Lịch", "page" => "booking"],
    ["href" => "track-order.php", "label" => "Tra Cứu", "page" => "track-order"],
];


$currentUser = null;
if (isLoggedIn() && isset($pdo)) {
    $currentUser = getCurrentUser($pdo);
}

$cart_count = 0; // Placeholder
?>

<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur border-b border-sidebar-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <a href="index.php" class="text-2xl font-bold text-primary">
            Lensy Studio
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex gap-6">
            <?php foreach ($nav_links as $link): 
                $activeClass = ($current_page == $link['page']) ? "text-secondary" : "";
            ?>
                <a href="<?php echo $link['href']; ?>" class="text-md hover:text-secondary transition py-4 px-3 font-medium whitespace-nowrap <?php echo $activeClass; ?>">
                    <?php echo $link['label']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center gap-4">
            <?php if (isLoggedIn() && $currentUser): ?>
                <div class="hidden md:flex items-center gap-4 relative">
                    <!-- User Dropdown Button -->
                    <button type="button" class="flex items-center gap-2 max-w-xs bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <?php if (!empty($currentUser['avatar_url'])): ?>
                            <img class="h-8 w-8 rounded-full object-cover" src="<?php echo htmlspecialchars($currentUser['avatar_url']); ?>" alt="">
                        <?php else: ?>
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <span class="text-sm font-medium text-foreground"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                        <i data-lucide="chevron-down" class="h-4 w-4 text-muted-foreground"></i>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="user-dropdown" class="hidden origin-top-right absolute right-0 top-full mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                             <p class="text-xs text-muted-foreground">Signed in as</p>
                             <p class="text-sm font-medium text-foreground truncate"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                        </div>
                        <?php if (hasRole($pdo, 'admin') || hasRole($pdo, 'manager')): ?>
                            <a href="admin/index.php" class="block px-4 py-2 text-sm font-medium text-primary hover:bg-gray-100">Dashboard</a>
                        <?php endif; ?>
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Hồ Sơ</a>
                        <a href="my-orders.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đơn Hàng Của Tôi</a>
                        <div class="border-t border-gray-100"></div>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Đăng Xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="hidden md:flex items-center gap-2">
                    <a href="login.php" class="px-4 py-2 text-sm font-medium text-foreground hover:text-primary transition-colors whitespace-nowrap">
                        Đăng Nhập
                    </a>
                    <a href="register.php" class="px-4 py-2 text-sm font-medium bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors whitespace-nowrap">
                        Đăng Ký
                    </a>
                </div>
            <?php endif; ?>

            <a href="cart.php">
                <button class="relative inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10">
                    <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                    <span id="cart-badge" class="absolute -top-2 -right-2 bg-primary text-primary-foreground text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                            0
                        </span>
                </button>
            </a>

            <button class="hidden md:inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 whitespace-nowrap">
                Liên Hệ
            </button>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-md text-primary">
                <i data-lucide="menu" id="menu-icon" class="h-6 w-6"></i>
                <i data-lucide="x" id="close-icon" class="h-6 w-6 hidden"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-border">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <?php foreach ($nav_links as $link): ?>
                <a href="<?php echo $link['href']; ?>" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-muted">
                    <?php echo $link['label']; ?>
                </a>
            <?php endforeach; ?>
            
             <div class="border-t border-border mt-2 pt-2">
                <?php if (isLoggedIn()): ?>
                    <div class="px-3 py-2">
                        <p class="text-sm font-medium text-muted-foreground mb-2">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                        <?php if (isset($pdo) && (hasRole($pdo, 'admin') || hasRole($pdo, 'manager'))): ?>
                            <a href="admin/index.php" class="block text-base font-medium text-primary hover:bg-primary/10 rounded-md py-2">Dashboard</a>
                        <?php endif; ?>
                        <a href="profile.php" class="block text-base font-medium text-foreground hover:bg-muted rounded-md py-2">Hồ Sơ Của Tôi</a>
                        <a href="my-orders.php" class="block text-base font-medium text-foreground hover:bg-muted rounded-md py-2">Đơn Hàng Của Tôi</a>
                        <a href="logout.php" class="block text-base font-medium text-destructive hover:bg-destructive/10 rounded-md py-2">
                            Đăng Xuất
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="block px-3 py-2 rounded-md text-base font-medium text-foreground hover:bg-muted">
                        Đăng Nhập
                    </a>
                    <a href="register.php" class="block px-3 py-2 rounded-md text-base font-medium text-primary hover:bg-primary/10">
                        Đăng Ký
                    </a>
                <?php endif; ?>
            </div>

            <div class="pt-4">
                <button class="w-full inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                    Liên Hệ
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
        
        // User Dropdown Logic
        const userMenuBtn = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        lucide.createIcons();
    });
</script>
<div class="h-16"></div> <!-- Spacer for fixed header -->
