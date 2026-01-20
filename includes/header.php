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
];

$cart_count = 0; // Placeholder
?>

<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur border-b border-sidebar-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <a href="index.php" class="text-2xl font-bold text-primary">
            Lensy Studio
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex gap-8">
            <?php foreach ($nav_links as $link): 
                $activeClass = ($current_page == $link['page']) ? "text-secondary" : "";
            ?>
                <a href="<?php echo $link['href']; ?>" class="text-md hover:text-secondary transition py-4 px-3 font-medium <?php echo $activeClass; ?>">
                    <?php echo $link['label']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center gap-4">
            <a href="cart.php">
                <button class="relative inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10">
                    <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                    <span id="cart-badge" class="absolute -top-2 -right-2 bg-primary text-primary-foreground text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                            0
                        </span>
                </button>
            </a>

            <button class="hidden md:inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
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
        
        lucide.createIcons();
    });
</script>
<div class="h-16"></div> <!-- Spacer for fixed header -->
