CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `phone` VARCHAR(20) UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `avatar_url` VARCHAR(255),
  `status` ENUM ('active', 'inactive', 'banned') DEFAULT 'active',
  `created_at` DATETIME DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` DATETIME DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `roles` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(50) UNIQUE NOT NULL,
  `display_name` VARCHAR(100),
  `description` TEXT
);

CREATE TABLE `permissions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `slug` VARCHAR(50) UNIQUE NOT NULL,
  `display_name` VARCHAR(100)
);

CREATE TABLE `role_permissions` (
  `role_id` INT,
  `permission_id` INT,
  PRIMARY KEY (`role_id`, `permission_id`)
);

CREATE TABLE `user_roles` (
  `user_id` INT,
  `role_id` INT,
  PRIMARY KEY (`user_id`, `role_id`)
);

CREATE TABLE `categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE,
  `type` ENUM ('service', 'rental_gear', 'rental_fashion') NOT NULL,
  `parent_id` INT,
  `image_url` VARCHAR(255),
  `icon` VARCHAR(50)
);

CREATE TABLE `services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE,
  `description` TEXT,
  `base_price` DECIMAL(15,2) NOT NULL,
  `deposit_required` DECIMAL(15,2) DEFAULT 0,
  `duration_minutes` INT DEFAULT 60,
  `max_photos_deliver` INT,
  `image_url` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT true
);

CREATE TABLE `products` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) UNIQUE,
  `description` TEXT,
  `image_url` VARCHAR(255),
  `rental_price_per_day` DECIMAL(15,2) NOT NULL,
  `deposit_fee` DECIMAL(15,2) NOT NULL,
  `insurance_fee` DECIMAL(15,2) DEFAULT 0,
  `specifications` JSON,
  `sizes` VARCHAR(100),
  `total_stock_quantity` INT DEFAULT 0,
  `is_featured` BOOLEAN DEFAULT false,
  `is_active` BOOLEAN DEFAULT true
);

CREATE TABLE `product_assets` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT,
  `serial_number` VARCHAR(100),
  `sku_code` VARCHAR(100) UNIQUE,
  `status` ENUM ('available', 'rented', 'maintenance', 'lost', 'liquidated') DEFAULT 'available',
  `condition_note` TEXT,
  `purchase_date` DATE
);

CREATE TABLE `orders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `customer_name` VARCHAR(100),
  `customer_email` VARCHAR(100),
  `customer_phone` VARCHAR(20),
  `code` VARCHAR(20) UNIQUE NOT NULL,
  `total_amount` DECIMAL(15,2) NOT NULL,
  `discount_amount` DECIMAL(15,2) DEFAULT 0,
  `deposit_paid` DECIMAL(15,2) DEFAULT 0,
  `status` ENUM ('pending', 'confirmed', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
  `payment_status` ENUM ('unpaid', 'partially_paid', 'paid', 'refunded') DEFAULT 'unpaid',
  `note` TEXT,
  `created_by` INT,
  `created_at` DATETIME DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `order_bookings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT,
  `service_id` INT,
  `booking_time` DATETIME NOT NULL,
  `location` VARCHAR(255),
  `photographer_id` INT,
  `makeup_artist_id` INT,
  `price` DECIMAL(15,2),
  `status` ENUM ('scheduled', 'shooting', 'editing', 'delivered_files', 'finished') DEFAULT 'scheduled'
);

CREATE TABLE `order_rentals` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT,
  `product_id` INT,
  `quantity` INT DEFAULT 1,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME NOT NULL,
  `actual_return_time` DATETIME,
  `price_per_day` DECIMAL(15,2),
  `deposit_amount` DECIMAL(15,2),
  `assigned_asset_id` INT,
  `status` ENUM ('reserved', 'picked_up', 'returned', 'overdue') DEFAULT 'reserved'
);

CREATE TABLE `cms_timelines` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `year` VARCHAR(4),
  `title` VARCHAR(255),
  `description` TEXT,
  `icon_url` VARCHAR(255),
  `sort_order` INT DEFAULT 0
);

CREATE TABLE `cms_testimonials` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `customer_name` VARCHAR(100),
  `customer_role` VARCHAR(100),
  `content` TEXT,
  `avatar_url` VARCHAR(255),
  `rating` INT DEFAULT 5
);

ALTER TABLE `role_permissions` ADD FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `role_permissions` ADD FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_roles` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_roles` ADD FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `categories` ADD FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

ALTER TABLE `services` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `products` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `product_assets` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_bookings` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `order_bookings` ADD FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

ALTER TABLE `order_bookings` ADD FOREIGN KEY (`photographer_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_bookings` ADD FOREIGN KEY (`makeup_artist_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_rentals` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `order_rentals` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `order_rentals` ADD FOREIGN KEY (`assigned_asset_id`) REFERENCES `product_assets` (`id`);
