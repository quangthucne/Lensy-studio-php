-- Disable foreign key checks to allow truncating tables
SET FOREIGN_KEY_CHECKS = 0;

-- Optional: Truncate tables to start fresh
DELETE FROM `order_rentals`;
ALTER TABLE `order_rentals` AUTO_INCREMENT = 1;

DELETE FROM `order_bookings`;
ALTER TABLE `order_bookings` AUTO_INCREMENT = 1;

DELETE FROM `orders`;
ALTER TABLE `orders` AUTO_INCREMENT = 1;

DELETE FROM `product_assets`;
ALTER TABLE `product_assets` AUTO_INCREMENT = 1;

DELETE FROM `products`;
ALTER TABLE `products` AUTO_INCREMENT = 1;

DELETE FROM `services`;
ALTER TABLE `services` AUTO_INCREMENT = 1;

DELETE FROM `categories`;
ALTER TABLE `categories` AUTO_INCREMENT = 1;

DELETE FROM `user_roles`;
ALTER TABLE `user_roles` AUTO_INCREMENT = 1;

DELETE FROM `role_permissions`;
ALTER TABLE `role_permissions` AUTO_INCREMENT = 1;

DELETE FROM `permissions`;
ALTER TABLE `permissions` AUTO_INCREMENT = 1;

DELETE FROM `roles`;
ALTER TABLE `roles` AUTO_INCREMENT = 1;

DELETE FROM `users`;
ALTER TABLE `users` AUTO_INCREMENT = 1;

DELETE FROM `cms_timelines`;
ALTER TABLE `cms_timelines` AUTO_INCREMENT = 1;

DELETE FROM `cms_testimonials`;
ALTER TABLE `cms_testimonials` AUTO_INCREMENT = 1;

-- Enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Insert Categories
INSERT INTO `categories` (`name`, `slug`, `type`, `icon`) VALUES
('Chụp ảnh', 'service-photography', 'service', '📷'),
('Máy Ảnh', 'rental-cameras', 'rental_gear', '📷'),
('Ống Kính', 'rental-lenses', 'rental_gear', '🔍'),
('Đèn & Ánh Sáng', 'rental-lighting', 'rental_gear', '💡'),
('Phụ Kiện', 'rental-accessories', 'rental_gear', '⚙️'),
('Áo Dài', 'fashion-ao-dai', 'rental_fashion', '👘'),
('Váy Cưới', 'fashion-wedding', 'rental_fashion', '👰'),
('Đồ Cổ Điển', 'fashion-vintage', 'rental_fashion', '🎩');

-- 2. Insert Services
-- Use subqueries to get category IDs based on slugs
INSERT INTO `services` (`category_id`, `name`, `description`, `image_url`, `base_price`) VALUES
((SELECT id FROM categories WHERE slug = 'service-photography' LIMIT 1), 'Chụp ảnh Tết', 'Tôn vinh năm mới với những bộ ảnh Áo Dài sang trọng và ảnh gia đình', 'assets/t-t-photography--o-d-i-family-portraits.jpg', 1500000),
((SELECT id FROM categories WHERE slug = 'service-photography' LIMIT 1), 'Ảnh Cưới', 'Ghi lại ngày trọng đại của bạn với phong cách nghệ thuật và bất biến', 'assets/wedding-photography-ceremony-reception.jpg', 5000000),
((SELECT id FROM categories WHERE slug = 'service-photography' LIMIT 1), 'Ảnh Kỷ Niệm', 'Tôn vinh câu chuyện tình yêu của bạn với chụp ảnh chuyên nghiệp', 'assets/anniversary-photography-couples-portraits.jpg', 2000000);

-- 3. Insert Products (Costumes)
INSERT INTO `products` (`category_id`, `name`, `rental_price_per_day`, `deposit_fee`, `image_url`, `description`, `sizes`, `is_featured`) VALUES
((SELECT id FROM categories WHERE slug = 'fashion-ao-dai' LIMIT 1), 'Áo Dài Đỏ Truyền Thống', 500000, 1500000, 'assets/costume-red-ao-dai.jpg', 'Áo dài lụa đỏ cổ điển với họa tiết thêu vàng cho ngày Tết', 'XS - XXL', 1),
((SELECT id FROM categories WHERE slug = 'fashion-ao-dai' LIMIT 1), 'Áo Dài Trắng Hiện Đại', 500000, 1500000, 'assets/costume-white-ao-dai.jpg', 'Áo dài trắng hiện đại với họa tiết tinh tế', 'XS - XXL', 0),
((SELECT id FROM categories WHERE slug = 'fashion-wedding' LIMIT 1), 'Váy Cưới Trắng Cổ Điển', 2000000, 6000000, 'assets/costume-wedding-white.jpg', 'Váy cưới trắng bất tận với những chi tiết thanh lịch', 'XS - XXL', 1),
((SELECT id FROM categories WHERE slug = 'fashion-vintage' LIMIT 1), 'Váy Cổ Điển Thập Niên 50', 600000, 1800000, 'assets/costume-vintage-50s.jpg', 'Váy lấy cảm hứng từ những năm 1950 với họa tiết chấm bi', 'XS - L', 0);

-- 4. Insert Products (Equipment)
INSERT INTO `products` (`category_id`, `name`, `rental_price_per_day`, `deposit_fee`, `insurance_fee`, `image_url`, `description`, `specifications`, `total_stock_quantity`) VALUES
((SELECT id FROM categories WHERE slug = 'rental-cameras' LIMIT 1), 'Canon EOS R5C', 2800000, 5000000, 500000, 'assets/equipment-canon-r5.jpg', 'Máy ảnh mirrorless full frame chuyên nghiệp cao cấp với khả năng quay video 8K tuyệt vời', '{"specs": "Full Frame Mirrorless, 45MP"}', 5),
((SELECT id FROM categories WHERE slug = 'rental-lenses' LIMIT 1), 'RF 24-70mm f/2.8L IS USM', 1200000, 2400000, 200000, 'assets/equipment-lens-24-70.jpg', 'Ống kính zoom tiêu chuẩn chuyên nghiệp với khẩu độ f/2.8 cố định', '{"specs": "Ống Kính Zoom Tiêu Chuẩn, Canon RF Mount"}', 5),
((SELECT id FROM categories WHERE slug = 'rental-lighting' LIMIT 1), 'Godox SL-60W Đèn LED Studio', 600000, 1200000, 100000, 'assets/equipment-lighting-godox.jpg', 'Đèn LED liên tục chuyên nghiệp cho studio', '{"specs": "Bảng Đèn LED 60W Chuyên Nghiệp"}', 10);

-- 5. Insert Roles
INSERT INTO `roles` (`name`, `display_name`, `description`) VALUES
('admin', 'Administrator', 'Full system access'),
('staff', 'Staff', 'Manage bookings and rentals'),
('customer', 'Customer', 'Regular user');

-- 6. Insert Permissions
INSERT INTO `permissions` (`slug`, `display_name`) VALUES
('manage_users', 'Manage Users'),
('manage_products', 'Manage Products'),
('manage_content', 'Manage Website Content'),
('manage_bookings', 'Manage Bookings'),
('create_booking', 'Create Booking');

-- 7. Assign Permissions to Roles
-- Admin gets management permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('manage_users', 'manage_products', 'manage_content') WHERE r.name = 'admin';

-- Staff gets booking management
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('manage_bookings') WHERE r.name = 'staff';

-- Customer gets create booking
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('create_booking') WHERE r.name = 'customer';

-- Staff also gets create booking (for walk-ins)
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('create_booking') WHERE r.name = 'staff';

-- 8. Insert Users
-- Password for all users: 'password'
INSERT INTO `users` (`full_name`, `email`, `phone`, `password_hash`, `status`, `avatar_url`) VALUES
('Admin User', 'admin@lensy.com', '0900000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'assets/avatar-admin.jpg'),
('Staff User', 'staff@lensy.com', '0900000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'assets/avatar-manager.jpg'),
('Nguyen Van A', 'customer@lensy.com', '0900000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'assets/avatar-customer.jpg');

-- 9. Assign Roles to Users
INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
((SELECT id FROM users WHERE email='admin@lensy.com'), (SELECT id FROM roles WHERE name='admin')),
((SELECT id FROM users WHERE email='staff@lensy.com'), (SELECT id FROM roles WHERE name='staff')),
((SELECT id FROM users WHERE email='customer@lensy.com'), (SELECT id FROM roles WHERE name='customer'));

-- 10. Insert Product Assets (Inventory)
INSERT INTO `product_assets` (`product_id`, `serial_number`, `sku_code`, `status`, `condition_note`, `purchase_date`) VALUES
((SELECT id FROM products WHERE name LIKE '%Canon EOS R5C%' LIMIT 1), 'SN-R5C-001', 'CAM-R5C-01', 'available', 'New condition', '2023-01-15'),
((SELECT id FROM products WHERE name LIKE '%Canon EOS R5C%' LIMIT 1), 'SN-R5C-002', 'CAM-R5C-02', 'rented', 'Minor scratch on body', '2023-01-15'),
((SELECT id FROM products WHERE name LIKE '%RF 24-70mm%' LIMIT 1), 'SN-LENS-001', 'LENS-2470-01', 'available', 'Perfect optics', '2023-02-20'),
((SELECT id FROM products WHERE name LIKE '%Godox SL-60W%' LIMIT 1), 'SN-LIGHT-001', 'LIGHT-60W-01', 'maintenance', 'Bulb replacement needed', '2023-03-10');

-- 11. Insert CMS Timelines
INSERT INTO `cms_timelines` (`year`, `title`, `description`, `icon_url`, `sort_order`) VALUES
('2018', 'Thành Lập', 'Lensy Studio được thành lập với niềm đam mê nhiếp ảnh.', 'assets/icon-start.png', 1),
('2020', 'Mở Rộng', 'Mở rộng sang dịch vụ cho thuê thiết bị và trang phục.', 'assets/icon-expand.png', 2),
('2023', 'Top Studio', 'Đạt giải thưởng Studio được yêu thích nhất năm.', 'assets/icon-award.png', 3);

-- 12. Insert CMS Testimonials
INSERT INTO `cms_testimonials` (`customer_name`, `customer_role`, `content`, `avatar_url`, `rating`) VALUES
('Tran Thi B', 'Model', 'Dịch vụ tuyệt vời, trang phục rất đẹp và mới.', 'assets/avatar-1.jpg', 5),
('Le Van C', 'Photographer', 'Thiết bị chất lượng cao, giá cả hợp lý.', 'assets/avatar-2.jpg', 5),
('Pham Thi D', 'Bride', 'Ekip chụp ảnh rất nhiệt tình, ảnh cưới đẹp lung linh.', 'assets/avatar-3.jpg', 4);

-- 13. Insert Sample Orders
INSERT INTO `orders` (`user_id`, `customer_name`, `customer_email`, `customer_phone`, `code`, `total_amount`, `deposit_paid`, `status`, `payment_status`, `note`) VALUES
((SELECT id FROM users WHERE email='customer@lensy.com'), 'Nguyen Van A', 'customer@lensy.com', '0900000003', 'ORD-20231001-01', 3500000, 0, 'confirmed', 'unpaid', 'Giao hàng buổi sáng'),
((SELECT id FROM users WHERE email='customer@lensy.com'), 'Nguyen Van A', 'customer@lensy.com', '0900000003', 'ORD-20231005-02', 1200000, 500000, 'completed', 'paid', '');

-- 14. Insert Order Rentals
INSERT INTO `order_rentals` (`order_id`, `product_id`, `quantity`, `start_time`, `end_time`, `actual_return_time`, `price_per_day`, `deposit_amount`, `assigned_asset_id`, `status`) VALUES
((SELECT id FROM orders WHERE code='ORD-20231001-01'), (SELECT id FROM products WHERE name LIKE '%Canon EOS R5C%' LIMIT 1), 1, '2023-10-02 08:00:00', '2023-10-03 08:00:00', NULL, 2800000, 5000000, (SELECT id FROM product_assets WHERE sku_code='CAM-R5C-02'), 'picked_up');

