<?php
require_once 'config/db.php';
require_once 'helpers/functions.php';

echo "Seeding database...\n";

try {
    // 1. Categories
    $categories = [
        ['name' => 'Chụp ảnh', 'slug' => 'service-photography', 'type' => 'service', 'icon' => '📷'],
        ['name' => 'Máy Ảnh', 'slug' => 'rental-cameras', 'type' => 'rental_gear', 'icon' => '📷'],
        ['name' => 'Ống Kính', 'slug' => 'rental-lenses', 'type' => 'rental_gear', 'icon' => '🔍'],
        ['name' => 'Đèn & Ánh Sáng', 'slug' => 'rental-lighting', 'type' => 'rental_gear', 'icon' => '💡'],
        ['name' => 'Phụ Kiện', 'slug' => 'rental-accessories', 'type' => 'rental_gear', 'icon' => '⚙️'],
        ['name' => 'Áo Dài', 'slug' => 'fashion-ao-dai', 'type' => 'rental_fashion', 'icon' => '👘'],
        ['name' => 'Váy Cưới', 'slug' => 'fashion-wedding', 'type' => 'rental_fashion', 'icon' => '👰'],
        ['name' => 'Đồ Cổ Điển', 'slug' => 'fashion-vintage', 'type' => 'rental_fashion', 'icon' => '🎩'],
    ];

    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, type, icon) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=name");
    
    foreach ($categories as $cat) {
        $stmt->execute([$cat['name'], $cat['slug'], $cat['type'], $cat['icon']]);
    }
    echo "Categories seeded.\n";

    // Get Category IDs
    $catMap = [];
    $stmt = $pdo->query("SELECT id, slug FROM categories");
    while ($row = $stmt->fetch()) {
        $catMap[$row['slug']] = $row['id'];
    }

    // 2. Services (Packages)
    $services = [
         [
            "title" => "Chụp ảnh Tết",
            "description" => "Tôn vinh năm mới với những bộ ảnh Áo Dài sang trọng và ảnh gia đình",
            "image" => "assets/t-t-photography--o-d-i-family-portraits.jpg",
            "price" => 1500000 
        ],
        [
            "title" => "Ảnh Cưới",
            "description" => "Ghi lại ngày trọng đại của bạn với phong cách nghệ thuật và bất biến",
            "image" => "assets/wedding-photography-ceremony-reception.jpg",
            "price" => 5000000
        ],
        [
            "title" => "Ảnh Kỷ Niệm",
            "description" => "Tôn vinh câu chuyện tình yêu của bạn với chụp ảnh chuyên nghiệp",
            "image" => "assets/anniversary-photography-couples-portraits.jpg",
            "price" => 2000000
        ],
    ];

    $stmt = $pdo->prepare("INSERT INTO services (category_id, name, description, image_url, base_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($services as $svc) {
        $stmt->execute([$catMap['service-photography'], $svc['title'], $svc['description'], $svc['image'], $svc['price']]);
    }
    echo "Services seeded.\n";

    // 3. Products (Costumes)
    $costumes = [
        [
            "category" => "fashion-ao-dai",
            "name" => "Áo Dài Đỏ Truyền Thống",
            "price" => 500000,
            "image" => "assets/costume-red-ao-dai.jpg",
            "description" => "Áo dài lụa đỏ cổ điển với họa tiết thêu vàng cho ngày Tết",
            "sizes" => "XS - XXL",
            "featured" => true,
        ],
         [
            "category" => "fashion-ao-dai",
            "name" => "Áo Dài Trắng Hiện Đại",
            "price" => 500000,
            "image" => "assets/costume-white-ao-dai.jpg",
            "description" => "Áo dài trắng hiện đại với họa tiết tinh tế",
            "sizes" => "XS - XXL",
            "featured" => false,
        ],
        [
            "category" => "fashion-wedding",
            "name" => "Váy Cưới Trắng Cổ Điển",
            "price" => 2000000,
            "image" => "assets/costume-wedding-white.jpg",
            "description" => "Váy cưới trắng bất tận với những chi tiết thanh lịch",
            "sizes" => "XS - XXL",
            "featured" => true,
        ],
        [
            "category" => "fashion-vintage",
            "name" => "Váy Cổ Điển Thập Niên 50",
            "price" => 600000,
            "image" => "assets/costume-vintage-50s.jpg",
            "description" => "Váy lấy cảm hứng từ những năm 1950 với họa tiết chấm bi",
            "sizes" => "XS - L",
            "featured" => false,
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, rental_price_per_day, deposit_fee, image_url, description, sizes, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($costumes as $item) {
        $stmt->execute([
            $catMap[$item['category']], 
            $item['name'], 
            $item['price'], 
            $item['price'] * 3, // Deposit roughly 3x
            $item['image'], 
            $item['description'], 
            $item['sizes'],
            $item['featured'] ? 1 : 0
        ]);
    }
    echo "Costumes seeded.\n";

    // 4. Products (Equipment)
    $equipment = [
        [
            "category" => "rental-cameras",
            "name" => "Canon EOS R5C",
            "basePrice" => 2800000,
            "specs" => "Full Frame Mirrorless, 45MP",
            "image" => "assets/equipment-canon-r5.jpg",
            "description" => "Máy ảnh mirrorless full frame chuyên nghiệp cao cấp với khả năng quay video 8K tuyệt vời",
            "insurance" => 500000,
            "deposit" => 5000000
        ],
        [
            "category" => "rental-lenses",
            "name" => "RF 24-70mm f/2.8L IS USM",
            "basePrice" => 1200000,
            "specs" => "Ống Kính Zoom Tiêu Chuẩn, Canon RF Mount",
            "image" => "assets/equipment-lens-24-70.jpg",
            "description" => "Ống kính zoom tiêu chuẩn chuyên nghiệp với khẩu độ f/2.8 cố định",
            "insurance" => 200000,
            "deposit" => 2400000
        ],
        [
            "category" => "rental-lighting",
            "name" => "Godox SL-60W Đèn LED Studio",
            "basePrice" => 600000,
            "specs" => "Bảng Đèn LED 60W Chuyên Nghiệp",
            "image" => "assets/equipment-lighting-godox.jpg",
            "description" => "Đèn LED liên tục chuyên nghiệp cho studio",
            "insurance" => 100000,
            "deposit" => 1200000
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, rental_price_per_day, deposit_fee, insurance_fee, image_url, description, specifications) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($equipment as $item) {
        $specs = json_encode(["specs" => $item['specs']]);
        $stmt->execute([
            $catMap[$item['category']], 
            $item['name'], 
            $item['basePrice'], 
            $item['deposit'], 
            $item['insurance'],
            $item['image'], 
            $item['description'],
            $specs
        ]);
    }
    echo "Equipment seeded.\n";
    echo "Done!";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
