-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th3 02, 2026 lúc 07:33 AM
-- Phiên bản máy phục vụ: 8.0.40
-- Phiên bản PHP: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `lensy-studio`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `type` enum('service','rental_gear','rental_fashion') NOT NULL,
  `parent_id` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `parent_id`, `image_url`, `icon`, `is_active`) VALUES
(1, 'Chụp ảnh', 'service-photography', 'service', NULL, NULL, '📷', 1),
(2, 'Máy Ảnh', 'rental-cameras', 'rental_gear', NULL, NULL, '📷', 1),
(3, 'Ống Kính', 'rental-lenses', 'rental_gear', NULL, NULL, '🔍', 1),
(4, 'Đèn & Ánh Sáng', 'rental-lighting', 'rental_gear', NULL, NULL, '💡', 1),
(5, 'Phụ Kiện', 'rental-accessories', 'rental_gear', NULL, NULL, '⚙️', 1),
(6, 'Áo Dài', 'fashion-ao-dai', 'rental_fashion', NULL, NULL, '👘', 1),
(7, 'Váy Cưới', 'fashion-wedding', 'rental_fashion', NULL, NULL, '👰', 1),
(8, 'Đồ Cổ Điển', 'fashion-vintage', 'rental_fashion', NULL, NULL, '🎩', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cms_testimonials`
--

CREATE TABLE `cms_testimonials` (
  `id` int NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_role` varchar(100) DEFAULT NULL,
  `content` text,
  `avatar_url` varchar(255) DEFAULT NULL,
  `rating` int DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `cms_testimonials`
--

INSERT INTO `cms_testimonials` (`id`, `customer_name`, `customer_role`, `content`, `avatar_url`, `rating`) VALUES
(1, 'Tran Thi B', 'Model', 'Dịch vụ tuyệt vời, trang phục rất đẹp và mới.', 'assets/avatar-1.jpg', 5),
(2, 'Le Van C', 'Photographer', 'Thiết bị chất lượng cao, giá cả hợp lý.', 'assets/avatar-2.jpg', 5),
(3, 'Pham Thi D', 'Bride', 'Ekip chụp ảnh rất nhiệt tình, ảnh cưới đẹp lung linh.', 'assets/avatar-3.jpg', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cms_timelines`
--

CREATE TABLE `cms_timelines` (
  `id` int NOT NULL,
  `year` varchar(4) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `icon_url` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `cms_timelines`
--

INSERT INTO `cms_timelines` (`id`, `year`, `title`, `description`, `icon_url`, `sort_order`) VALUES
(1, '2018', 'Thành Lập', 'Lensy Studio được thành lập với niềm đam mê nhiếp ảnh.', 'assets/icon-start.png', 1),
(2, '2020', 'Mở Rộng', 'Mở rộng sang dịch vụ cho thuê thiết bị và trang phục.', 'assets/icon-expand.png', 2),
(3, '2023', 'Top Studio', 'Đạt giải thưởng Studio được yêu thích nhất năm.', 'assets/icon-award.png', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `deposit_paid` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','partially_paid','paid','refunded') DEFAULT 'unpaid',
  `payment_method` varchar(50) DEFAULT 'cash',
  `note` text,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT (now())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `code`, `total_amount`, `discount_amount`, `deposit_paid`, `status`, `payment_status`, `payment_method`, `note`, `created_by`, `created_at`) VALUES
(1, 3, 'Nguyen Van A', 'customer@lensy.com', '0900000003', 'ORD-20231001-01', 3500000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'cash', 'Giao hàng buổi sáng', NULL, '2026-02-19 22:37:05'),
(2, 3, 'Nguyen Van A', 'customer@lensy.com', '0900000003', 'ORD-20231005-02', 1200000.00, 0.00, 500000.00, 'completed', 'paid', 'cash', '', NULL, '2026-02-19 22:37:05'),
(3, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'BKG-6999C64102D35', 1500000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'cash', '', NULL, '2026-02-21 21:50:41'),
(4, NULL, 'Quang Thực asdfgh', 'thucbqpc08717@gmail.com', '0917988192', 'BKG-6999CA77222ED', 1500000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'cash', '', NULL, '2026-02-21 22:08:39'),
(5, NULL, 'Quang Thực asdfgh', 'thucbqpc08717@gmail.com', '0917988192', 'BKG-6999CD4CF25D3', 1500000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'cash', '', NULL, '2026-02-21 22:20:45'),
(6, NULL, 'Quang Thực Bùi', 'thucbqpc08717@gmail.com', '0917988192', 'BKG-6999CEE5C237C', 1500000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'cash', '', NULL, '2026-02-21 22:27:33'),
(7, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A29B41948', 550000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 20:56:43'),
(8, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0987654322', 'ORD-69A1A334B8C52', 346500.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 20:59:16'),
(9, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A403043FA', 346500.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 21:02:43'),
(10, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A55C2C66E', 550000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 21:08:28'),
(11, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A7FC1664C', 550000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 21:19:40'),
(12, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A838D69A0', 550000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 21:20:40'),
(13, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A8EFD2DEE', 550000.00, 0.00, 0.00, 'cancelled', 'paid', 'vnpay', NULL, NULL, '2026-02-27 21:23:43'),
(14, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A9378D0A7', 550000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 21:24:55'),
(15, NULL, 'Quang Thực asdfgh', 'bthuc000@gmail.com', '0917988192', 'ORD-69A1A972AB759', 2200000.00, 0.00, 0.00, 'pending', 'paid', 'vnpay', NULL, NULL, '2026-02-27 21:25:54'),
(16, 3, 'Khách Hàng 730', 'khach180@example.com', '0924871553', 'ORD-SEED-8EB6C2FE', 2970000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-12 02:11:25'),
(17, 1, 'Khách Hàng 359', 'khach625@example.com', '0981630130', 'ORD-SEED-F66E3EC2', 12980000.00, 0.00, 0.00, 'processing', 'paid', 'vnpay', NULL, NULL, '2026-02-06 09:00:44'),
(18, 2, 'Khách Hàng 788', 'khach691@example.com', '0977769285', 'ORD-SEED-07CD566F', 9680000.00, 0.00, 0.00, 'completed', 'unpaid', 'cash', NULL, NULL, '2026-03-01 05:06:29'),
(19, 3, 'Khách Hàng 227', 'khach227@example.com', '0918663474', 'ORD-SEED-82665123', 8250000.00, 0.00, 0.00, 'processing', 'unpaid', 'cash', NULL, NULL, '2026-02-06 17:18:54'),
(20, 2, 'Khách Hàng 813', 'khach504@example.com', '0935756351', 'ORD-SEED-97A28D90', 5500000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-25 10:31:46'),
(21, 3, 'Khách Hàng 169', 'khach590@example.com', '0984007693', 'ORD-SEED-D600BB2D', 2475000.00, 0.00, 0.00, 'confirmed', 'paid', 'cash', NULL, NULL, '2026-02-19 05:17:15'),
(22, 1, 'Khách Hàng 898', 'khach114@example.com', '0990019139', 'ORD-SEED-4022C15B', 2640000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-13 09:03:05'),
(23, 2, 'Khách Hàng 850', 'khach276@example.com', '0985314103', 'ORD-SEED-A7A621B3', 5280000.00, 0.00, 0.00, 'confirmed', 'paid', 'cash', NULL, NULL, '2026-02-22 04:24:36'),
(24, 3, 'Khách Hàng 246', 'khach436@example.com', '0932350851', 'ORD-SEED-E3E204DE', 10450000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-06 17:36:57'),
(25, 3, 'Khách Hàng 215', 'khach515@example.com', '0997332646', 'ORD-SEED-1865AD44', 3080000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-03-01 14:32:49'),
(26, 1, 'Khách Hàng 192', 'khach961@example.com', '0955216382', 'ORD-SEED-31AA9131', 7260000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-02-06 08:13:55'),
(27, 3, 'Khách Hàng 617', 'khach611@example.com', '0975221929', 'ORD-SEED-8957A5F0', 3520000.00, 0.00, 0.00, 'confirmed', 'paid', 'vnpay', NULL, NULL, '2026-02-28 22:06:19'),
(28, 1, 'Khách Hàng 948', 'khach840@example.com', '0913152109', 'ORD-SEED-DB66269D', 28710000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-08 00:01:21'),
(29, 3, 'Khách Hàng 572', 'khach541@example.com', '0978105760', 'ORD-SEED-89E1834A', 9350000.00, 0.00, 0.00, 'completed', 'unpaid', 'vnpay', NULL, NULL, '2026-02-27 03:29:46'),
(30, 3, 'Khách Hàng 341', 'khach570@example.com', '0990700238', 'ORD-SEED-5A21894A', 9350000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-26 03:02:55'),
(31, 3, 'Khách Hàng 934', 'khach893@example.com', '0959707618', 'ORD-SEED-1420365F', 24640000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-03-01 07:53:25'),
(32, 2, 'Khách Hàng 279', 'khach138@example.com', '0929359094', 'ORD-SEED-1D96D8ED', 2805000.00, 0.00, 0.00, 'cancelled', 'paid', 'cash', NULL, NULL, '2026-02-05 22:47:53'),
(33, 2, 'Khách Hàng 990', 'khach293@example.com', '0995990614', 'ORD-SEED-D3C63847', 58960000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-16 17:12:55'),
(34, 3, 'Khách Hàng 293', 'khach638@example.com', '0993712526', 'ORD-SEED-5D8BB938', 13420000.00, 0.00, 0.00, 'processing', 'paid', 'bank_transfer', NULL, NULL, '2026-02-02 06:32:32'),
(35, 3, 'Khách Hàng 183', 'khach928@example.com', '0976454728', 'ORD-SEED-5D8E7186', 2860000.00, 0.00, 0.00, 'completed', 'unpaid', 'cash', NULL, NULL, '2026-02-06 04:12:30'),
(36, 3, 'Khách Hàng 278', 'khach839@example.com', '0976832994', 'ORD-SEED-04A27426', 16170000.00, 0.00, 0.00, 'confirmed', 'paid', 'cash', NULL, NULL, '2026-02-20 13:05:35'),
(37, 3, 'Khách Hàng 652', 'khach851@example.com', '0940346005', 'ORD-SEED-3B5EA534', 7700000.00, 0.00, 0.00, 'processing', 'paid', 'vnpay', NULL, NULL, '2026-02-22 18:35:47'),
(38, 2, 'Khách Hàng 107', 'khach494@example.com', '0988902477', 'ORD-SEED-86C3D696', 8250000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-18 12:33:20'),
(39, 3, 'Khách Hàng 567', 'khach925@example.com', '0937715813', 'ORD-SEED-06BD4261', 8800000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-01-30 18:38:02'),
(40, 1, 'Khách Hàng 212', 'khach803@example.com', '0998831608', 'ORD-SEED-131F1F26', 10340000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-11 12:49:21'),
(41, 3, 'Khách Hàng 420', 'khach312@example.com', '0945729844', 'ORD-SEED-74181988', 2640000.00, 0.00, 0.00, 'processing', 'paid', 'vnpay', NULL, NULL, '2026-02-19 20:07:01'),
(42, 1, 'Khách Hàng 445', 'khach556@example.com', '0988018129', 'ORD-SEED-77DFFDD0', 2475000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-02-12 19:16:50'),
(43, 2, 'Khách Hàng 900', 'khach749@example.com', '0918619489', 'ORD-SEED-30D22B6E', 6600000.00, 0.00, 0.00, 'confirmed', 'paid', 'cash', NULL, NULL, '2026-02-03 09:54:57'),
(44, 2, 'Khách Hàng 731', 'khach958@example.com', '0940349604', 'ORD-SEED-7BA98537', 4180000.00, 0.00, 0.00, 'pending', 'paid', 'cash', NULL, NULL, '2026-02-20 07:03:48'),
(45, 1, 'Khách Hàng 118', 'khach988@example.com', '0996390753', 'ORD-SEED-22C3AF52', 7700000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-19 08:22:06'),
(46, 3, 'Khách Hàng 901', 'khach424@example.com', '0993529469', 'ORD-SEED-ECC1631A', 8800000.00, 0.00, 0.00, 'processing', 'paid', 'bank_transfer', NULL, NULL, '2026-01-30 16:00:50'),
(47, 1, 'Khách Hàng 324', 'khach585@example.com', '0916374645', 'ORD-SEED-53E5E3E2', 4400000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-02-26 17:17:43'),
(48, 1, 'Khách Hàng 307', 'khach475@example.com', '0913329873', 'ORD-SEED-73F182E3', 17875000.00, 0.00, 0.00, 'completed', 'unpaid', 'bank_transfer', NULL, NULL, '2026-02-06 10:36:01'),
(49, 2, 'Khách Hàng 122', 'khach836@example.com', '0992044305', 'ORD-SEED-9E7BB05A', 3520000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'cash', NULL, NULL, '2026-02-18 15:55:48'),
(50, 2, 'Khách Hàng 517', 'khach205@example.com', '0948833747', 'ORD-SEED-45634136', 3300000.00, 0.00, 0.00, 'completed', 'unpaid', 'bank_transfer', NULL, NULL, '2026-02-26 15:34:32'),
(51, 2, 'Khách Hàng 753', 'khach944@example.com', '0950897535', 'ORD-SEED-B76827CC', 25520000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-15 16:49:05'),
(52, 3, 'Khách Hàng 497', 'khach738@example.com', '0960011352', 'ORD-SEED-4A900AED', 2200000.00, 0.00, 0.00, 'completed', 'unpaid', 'cash', NULL, NULL, '2026-02-24 05:37:45'),
(53, 1, 'Khách Hàng 268', 'khach520@example.com', '0929276427', 'ORD-SEED-935FB722', 8800000.00, 0.00, 0.00, 'confirmed', 'paid', 'cash', NULL, NULL, '2026-02-15 20:06:02'),
(54, 3, 'Khách Hàng 739', 'khach550@example.com', '0965825856', 'ORD-SEED-E418BC30', 6820000.00, 0.00, 0.00, 'pending', 'unpaid', 'cash', NULL, NULL, '2026-02-04 10:47:46'),
(55, 2, 'Khách Hàng 265', 'khach173@example.com', '0970599743', 'ORD-SEED-7DB15997', 6820000.00, 0.00, 0.00, 'confirmed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-25 13:15:50'),
(56, 1, 'Khách Hàng 726', 'khach551@example.com', '0963103463', 'ORD-SEED-076B0A23', 11000000.00, 0.00, 0.00, 'processing', 'unpaid', 'vnpay', NULL, NULL, '2026-02-09 15:17:29'),
(57, 1, 'Khách Hàng 999', 'khach918@example.com', '0927252471', 'ORD-SEED-E1F66D21', 11000000.00, 0.00, 0.00, 'cancelled', 'paid', 'bank_transfer', NULL, NULL, '2026-02-19 20:24:30'),
(58, 3, 'Khách Hàng 499', 'khach306@example.com', '0981066226', 'ORD-SEED-A487C611', 2805000.00, 0.00, 0.00, 'completed', 'unpaid', 'bank_transfer', NULL, NULL, '2026-02-22 23:41:19'),
(59, 3, 'Khách Hàng 714', 'khach706@example.com', '0943166034', 'ORD-SEED-9995F0F9', 42680000.00, 0.00, 0.00, 'cancelled', 'paid', 'cash', NULL, NULL, '2026-02-13 16:43:52'),
(60, 1, 'Khách Hàng 598', 'khach848@example.com', '0946331936', 'ORD-SEED-A4D6A7A3', 2640000.00, 0.00, 0.00, 'cancelled', 'paid', 'bank_transfer', NULL, NULL, '2026-02-11 13:21:19'),
(61, 2, 'Khách Hàng 700', 'khach605@example.com', '0921996469', 'ORD-SEED-73124E54', 7700000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-23 11:04:36'),
(62, 2, 'Khách Hàng 577', 'khach156@example.com', '0920012137', 'ORD-SEED-17F1FC3A', 2860000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-02-07 16:37:51'),
(63, 1, 'Khách Hàng 400', 'khach890@example.com', '0984276743', 'ORD-SEED-F9E65C2D', 13750000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-06 10:30:03'),
(64, 3, 'Khách Hàng 583', 'khach199@example.com', '0974993499', 'ORD-SEED-A3B7AA8C', 2805000.00, 0.00, 0.00, 'pending', 'paid', 'bank_transfer', NULL, NULL, '2026-02-17 00:14:09'),
(65, 2, 'Khách Hàng 656', 'khach701@example.com', '0936338191', 'ORD-SEED-79D11602', 2145000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-05 23:30:05'),
(66, 1, 'Khách Hàng 348', 'khach803@example.com', '0960323017', 'ORD-SEED-10643819', 5500000.00, 0.00, 0.00, 'processing', 'unpaid', 'vnpay', NULL, NULL, '2026-02-22 22:36:06'),
(67, 2, 'Khách Hàng 485', 'khach986@example.com', '0914517086', 'ORD-SEED-90D14372', 10340000.00, 0.00, 0.00, 'completed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-05 19:26:24'),
(68, 1, 'Khách Hàng 142', 'khach812@example.com', '0916879529', 'ORD-SEED-557FADE0', 6600000.00, 0.00, 0.00, 'confirmed', 'paid', 'bank_transfer', NULL, NULL, '2026-02-23 15:10:42'),
(69, 3, 'Khách Hàng 264', 'khach344@example.com', '0939446340', 'ORD-SEED-2E5C2EB1', 16280000.00, 0.00, 0.00, 'confirmed', 'paid', 'vnpay', NULL, NULL, '2026-02-05 10:46:24'),
(70, 2, 'Khách Hàng 831', 'khach245@example.com', '0938618374', 'ORD-SEED-1ADC98DF', 6380000.00, 0.00, 0.00, 'completed', 'unpaid', 'vnpay', NULL, NULL, '2026-02-13 19:53:18'),
(71, 2, 'Khách Hàng 169', 'khach717@example.com', '0918616642', 'ORD-SEED-045036E2', 2310000.00, 0.00, 0.00, 'processing', 'paid', 'bank_transfer', NULL, NULL, '2026-02-26 00:56:08'),
(72, 2, 'Khách Hàng 573', 'khach161@example.com', '0986373507', 'ORD-SEED-B36626F1', 9350000.00, 0.00, 0.00, 'pending', 'paid', 'bank_transfer', NULL, NULL, '2026-02-09 17:50:40'),
(73, 1, 'Khách Hàng 118', 'khach619@example.com', '0992859942', 'ORD-SEED-9937A310', 5940000.00, 0.00, 0.00, 'cancelled', 'paid', 'bank_transfer', NULL, NULL, '2026-02-16 23:51:19'),
(74, 3, 'Khách Hàng 871', 'khach403@example.com', '0942593731', 'ORD-SEED-FF50C7C0', 10615000.00, 0.00, 0.00, 'pending', 'paid', 'cash', NULL, NULL, '2026-02-05 07:53:57'),
(75, 3, 'Khách Hàng 983', 'khach108@example.com', '0911620260', 'ORD-SEED-8C8FD7B3', 8800000.00, 0.00, 0.00, 'cancelled', 'paid', 'bank_transfer', NULL, NULL, '2026-02-18 03:47:53'),
(76, 1, 'Khách Hàng 497', 'khach934@example.com', '0978204841', 'ORD-SEED-05193018', 11000000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-24 22:48:51'),
(77, 2, 'Khách Hàng 129', 'khach580@example.com', '0927154520', 'ORD-SEED-A01707EE', 9295000.00, 0.00, 0.00, 'pending', 'unpaid', 'cash', NULL, NULL, '2026-02-15 13:54:16'),
(78, 3, 'Khách Hàng 844', 'khach798@example.com', '0926647188', 'ORD-SEED-50AEE1F2', 19360000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-03-01 03:21:06'),
(79, 3, 'Khách Hàng 443', 'khach610@example.com', '0970663203', 'ORD-SEED-E40E5F08', 6600000.00, 0.00, 0.00, 'completed', 'unpaid', 'bank_transfer', NULL, NULL, '2026-02-28 06:06:18'),
(80, 2, 'Khách Hàng 375', 'khach506@example.com', '0917705607', 'ORD-SEED-B094F0D3', 18370000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-05 13:03:21'),
(81, 3, 'Khách Hàng 781', 'khach219@example.com', '0956571185', 'ORD-SEED-13C26780', 8800000.00, 0.00, 0.00, 'completed', 'unpaid', 'bank_transfer', NULL, NULL, '2026-02-25 23:41:04'),
(82, 1, 'Khách Hàng 236', 'khach529@example.com', '0912410605', 'ORD-SEED-483CBEC5', 14520000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-06 23:58:48'),
(83, 2, 'Khách Hàng 807', 'khach338@example.com', '0934520461', 'ORD-SEED-FAE5254D', 18480000.00, 0.00, 0.00, 'confirmed', 'paid', 'vnpay', NULL, NULL, '2026-02-10 00:23:03'),
(84, 3, 'Khách Hàng 120', 'khach831@example.com', '0917344376', 'ORD-SEED-D2D96E13', 5280000.00, 0.00, 0.00, 'cancelled', 'paid', 'vnpay', NULL, NULL, '2026-02-11 17:59:51'),
(85, 2, 'Khách Hàng 624', 'khach648@example.com', '0964796023', 'ORD-SEED-5531E03B', 2200000.00, 0.00, 0.00, 'processing', 'paid', 'bank_transfer', NULL, NULL, '2026-02-21 21:35:24'),
(86, 3, 'Khách Hàng 255', 'khach729@example.com', '0993254595', 'ORD-SEED-DC4AEC08', 25960000.00, 0.00, 0.00, 'cancelled', 'paid', 'vnpay', NULL, NULL, '2026-02-03 04:50:56'),
(87, 2, 'Khách Hàng 248', 'khach881@example.com', '0938439288', 'ORD-SEED-71EE3B82', 9240000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-01-31 20:26:19'),
(88, 3, 'Khách Hàng 752', 'khach346@example.com', '0950968164', 'ORD-SEED-0ABA58BB', 7920000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-25 05:18:37'),
(89, 1, 'Khách Hàng 809', 'khach453@example.com', '0919606083', 'ORD-SEED-A5702776', 3740000.00, 0.00, 0.00, 'cancelled', 'unpaid', 'vnpay', NULL, NULL, '2026-02-15 21:38:05'),
(90, 2, 'Khách Hàng 929', 'khach485@example.com', '0989159695', 'ORD-SEED-C5D36C72', 13200000.00, 0.00, 0.00, 'cancelled', 'paid', 'cash', NULL, NULL, '2026-02-07 17:51:42'),
(91, 3, 'Khách Hàng 639', 'khach715@example.com', '0979840262', 'ORD-SEED-9982EFF2', 3740000.00, 0.00, 0.00, 'completed', 'unpaid', 'bank_transfer', NULL, NULL, '2026-02-03 01:20:52'),
(92, 3, 'Khách Hàng 601', 'khach795@example.com', '0959653387', 'ORD-SEED-C30A5A08', 2970000.00, 0.00, 0.00, 'completed', 'paid', 'vnpay', NULL, NULL, '2026-02-11 22:06:15'),
(93, 1, 'Khách Hàng 852', 'khach413@example.com', '0948469111', 'ORD-SEED-FAC640F6', 6600000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-15 21:40:29'),
(94, 2, 'Khách Hàng 185', 'khach381@example.com', '0930650028', 'ORD-SEED-09152DCE', 4180000.00, 0.00, 0.00, 'completed', 'paid', 'cash', NULL, NULL, '2026-02-19 07:01:57'),
(95, 1, 'Khách Hàng 969', 'khach248@example.com', '0936633628', 'ORD-SEED-05C613F7', 4400000.00, 0.00, 0.00, 'pending', 'paid', 'vnpay', NULL, NULL, '2026-02-27 12:25:56'),
(96, NULL, 'QUANG THUC asdfgh', 'bthuc000@gmail.com', '0917988192', 'BKG-69A459C783C9B', 1500000.00, 0.00, 0.00, 'pending', 'unpaid', 'vnpay', '', NULL, '2026-03-01 22:22:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_bookings`
--

CREATE TABLE `order_bookings` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `booking_time` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `photographer_id` int DEFAULT NULL,
  `makeup_artist_id` int DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `status` enum('scheduled','shooting','editing','delivered_files','finished') DEFAULT 'scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `order_bookings`
--

INSERT INTO `order_bookings` (`id`, `order_id`, `service_id`, `booking_time`, `location`, `photographer_id`, `makeup_artist_id`, `price`, `status`) VALUES
(1, 3, 1, '2026-02-21 09:00:00', 'Studio', NULL, NULL, 1500000.00, 'scheduled'),
(2, 4, 1, '2026-02-21 09:00:00', 'Studio', NULL, NULL, 1500000.00, 'scheduled'),
(3, 5, 1, '2026-02-22 09:00:00', 'Studio', NULL, NULL, 1500000.00, 'scheduled'),
(4, 6, 1, '2026-02-22 09:00:00', 'Studio', NULL, NULL, 1500000.00, 'scheduled'),
(5, 16, 1, '2026-02-15 02:11:25', 'Studio Lensy 1', NULL, NULL, 2700000.00, 'finished'),
(6, 17, 2, '2026-02-20 09:00:44', 'Studio Lensy 3', NULL, NULL, 10000000.00, 'scheduled'),
(7, 19, 2, '2026-02-08 17:18:54', 'Studio Lensy 1', NULL, NULL, 7500000.00, 'scheduled'),
(8, 20, 2, '2026-02-27 10:31:46', 'Studio Lensy 1', NULL, NULL, 5000000.00, 'finished'),
(9, 21, 1, '2026-03-03 05:17:15', 'Studio Lensy 2', NULL, NULL, 2250000.00, 'scheduled'),
(10, 23, 1, '2026-02-26 04:24:36', 'Studio Lensy 2', NULL, NULL, 2400000.00, 'scheduled'),
(11, 24, 2, '2026-02-09 17:36:57', 'Studio Lensy 2', NULL, NULL, 9500000.00, 'finished'),
(12, 27, 3, '2026-03-02 22:06:19', 'Studio Lensy 1', NULL, NULL, 3200000.00, 'scheduled'),
(13, 28, 2, '2026-02-13 00:01:21', 'Studio Lensy 1', NULL, NULL, 8500000.00, 'finished'),
(14, 29, 2, '2026-03-10 03:29:46', 'Studio Lensy 2', NULL, NULL, 8500000.00, 'finished'),
(15, 32, 1, '2026-02-13 22:47:53', 'Studio Lensy 3', NULL, NULL, 2550000.00, 'scheduled'),
(16, 35, 3, '2026-02-13 04:12:30', 'Studio Lensy 2', NULL, NULL, 2600000.00, 'finished'),
(17, 36, 1, '2026-02-25 13:05:35', 'Studio Lensy 3', NULL, NULL, 2700000.00, 'scheduled'),
(18, 37, 2, '2026-02-24 18:35:47', 'Studio Lensy 2', NULL, NULL, 7000000.00, 'scheduled'),
(19, 39, 2, '2026-02-01 18:38:02', 'Studio Lensy 3', NULL, NULL, 8000000.00, 'finished'),
(20, 40, 3, '2026-02-20 12:49:21', 'Studio Lensy 1', NULL, NULL, 3800000.00, 'finished'),
(21, 41, 1, '2026-02-26 20:07:01', 'Studio Lensy 1', NULL, NULL, 2400000.00, 'scheduled'),
(22, 42, 1, '2026-02-23 19:16:50', 'Studio Lensy 1', NULL, NULL, 2250000.00, 'finished'),
(23, 43, 2, '2026-02-13 09:54:57', 'Studio Lensy 2', NULL, NULL, 6000000.00, 'scheduled'),
(24, 44, 3, '2026-02-22 07:03:48', 'Studio Lensy 1', NULL, NULL, 3800000.00, 'scheduled'),
(25, 45, 2, '2026-02-20 08:22:06', 'Studio Lensy 3', NULL, NULL, 7000000.00, 'finished'),
(26, 46, 2, '2026-02-11 16:00:50', 'Studio Lensy 1', NULL, NULL, 8000000.00, 'scheduled'),
(27, 48, 1, '2026-02-10 10:36:01', 'Studio Lensy 3', NULL, NULL, 2250000.00, 'finished'),
(28, 49, 3, '2026-02-27 15:55:48', 'Studio Lensy 2', NULL, NULL, 3200000.00, 'scheduled'),
(29, 52, 3, '2026-03-06 05:37:45', 'Studio Lensy 2', NULL, NULL, 2000000.00, 'finished'),
(30, 54, 3, '2026-02-07 10:47:46', 'Studio Lensy 3', NULL, NULL, 3800000.00, 'scheduled'),
(31, 55, 3, '2026-02-27 13:15:50', 'Studio Lensy 1', NULL, NULL, 2600000.00, 'scheduled'),
(32, 56, 2, '2026-02-16 15:17:29', 'Studio Lensy 3', NULL, NULL, 10000000.00, 'scheduled'),
(33, 57, 2, '2026-03-01 20:24:30', 'Studio Lensy 3', NULL, NULL, 10000000.00, 'scheduled'),
(34, 58, 1, '2026-02-26 23:41:19', 'Studio Lensy 1', NULL, NULL, 2550000.00, 'finished'),
(35, 60, 1, '2026-02-18 13:21:19', 'Studio Lensy 3', NULL, NULL, 2400000.00, 'scheduled'),
(36, 61, 2, '2026-03-09 11:04:36', 'Studio Lensy 1', NULL, NULL, 5000000.00, 'finished'),
(37, 62, 3, '2026-02-10 16:37:51', 'Studio Lensy 1', NULL, NULL, 2600000.00, 'finished'),
(38, 63, 2, '2026-02-18 10:30:03', 'Studio Lensy 2', NULL, NULL, 5500000.00, 'finished'),
(39, 64, 1, '2026-02-26 00:14:09', 'Studio Lensy 2', NULL, NULL, 2550000.00, 'scheduled'),
(40, 65, 1, '2026-02-14 23:30:05', 'Studio Lensy 2', NULL, NULL, 1950000.00, 'finished'),
(41, 68, 2, '2026-03-01 15:10:42', 'Studio Lensy 3', NULL, NULL, 6000000.00, 'scheduled'),
(42, 69, 2, '2026-02-18 10:46:24', 'Studio Lensy 1', NULL, NULL, 10000000.00, 'scheduled'),
(43, 71, 1, '2026-03-12 00:56:08', 'Studio Lensy 3', NULL, NULL, 2100000.00, 'scheduled'),
(44, 72, 2, '2026-02-12 17:50:40', 'Studio Lensy 3', NULL, NULL, 8500000.00, 'scheduled'),
(45, 74, 1, '2026-02-10 07:53:57', 'Studio Lensy 1', NULL, NULL, 2550000.00, 'scheduled'),
(46, 75, 2, '2026-03-01 03:47:53', 'Studio Lensy 3', NULL, NULL, 8000000.00, 'scheduled'),
(47, 76, 2, '2026-03-06 22:48:51', 'Studio Lensy 3', NULL, NULL, 10000000.00, 'finished'),
(48, 77, 1, '2026-02-20 13:54:16', 'Studio Lensy 1', NULL, NULL, 1650000.00, 'scheduled'),
(49, 79, 2, '2026-03-11 06:06:18', 'Studio Lensy 1', NULL, NULL, 6000000.00, 'finished'),
(50, 80, 2, '2026-02-08 13:03:21', 'Studio Lensy 3', NULL, NULL, 5500000.00, 'finished'),
(51, 81, 2, '2026-02-26 23:41:04', 'Studio Lensy 1', NULL, NULL, 8000000.00, 'finished'),
(52, 83, 3, '2026-02-13 00:23:03', 'Studio Lensy 3', NULL, NULL, 3800000.00, 'scheduled'),
(53, 85, 3, '2026-03-05 21:35:24', 'Studio Lensy 1', NULL, NULL, 2000000.00, 'scheduled'),
(54, 89, 3, '2026-02-19 21:38:05', 'Studio Lensy 2', NULL, NULL, 3400000.00, 'scheduled'),
(55, 92, 1, '2026-02-13 22:06:15', 'Studio Lensy 3', NULL, NULL, 2700000.00, 'finished'),
(56, 94, 3, '2026-02-23 07:01:57', 'Studio Lensy 2', NULL, NULL, 3800000.00, 'finished'),
(57, 96, 1, '2026-03-02 09:00:00', 'Studio', NULL, NULL, 1500000.00, 'scheduled');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_rentals`
--

CREATE TABLE `order_rentals` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `actual_return_time` datetime DEFAULT NULL,
  `price_per_day` decimal(15,2) DEFAULT NULL,
  `deposit_amount` decimal(15,2) DEFAULT NULL,
  `assigned_asset_id` int DEFAULT NULL,
  `status` enum('reserved','picked_up','returned','overdue') DEFAULT 'reserved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `order_rentals`
--

INSERT INTO `order_rentals` (`id`, `order_id`, `product_id`, `quantity`, `start_time`, `end_time`, `actual_return_time`, `price_per_day`, `deposit_amount`, `assigned_asset_id`, `status`) VALUES
(1, 1, 5, 1, '2023-10-02 08:00:00', '2023-10-03 08:00:00', '2026-02-27 21:58:25', 2800000.00, 5000000.00, 2, 'returned'),
(2, 7, 1, 1, '2026-03-01 00:00:00', '2026-03-02 23:59:59', NULL, 500000.00, NULL, NULL, 'reserved'),
(3, 10, 1, 1, '2026-02-28 00:00:00', '2026-03-01 23:59:59', NULL, 500000.00, NULL, NULL, 'reserved'),
(4, 11, 2, 1, '2026-02-28 00:00:00', '2026-03-01 23:59:59', NULL, 500000.00, NULL, NULL, 'reserved'),
(5, 12, 2, 1, '2026-02-28 00:00:00', '2026-03-01 23:59:59', NULL, 500000.00, NULL, NULL, 'reserved'),
(6, 13, 2, 1, '2026-02-28 00:00:00', '2026-03-01 23:59:59', NULL, 500000.00, NULL, NULL, 'reserved'),
(7, 14, 2, 1, '2026-02-28 00:00:00', '2026-03-01 23:59:59', NULL, 500000.00, NULL, NULL, 'reserved'),
(8, 15, 3, 1, '2026-02-28 00:00:00', '2026-03-01 23:59:59', NULL, 2000000.00, NULL, NULL, 'reserved'),
(9, 17, 7, 1, '2026-02-07 09:00:44', '2026-02-10 09:00:44', NULL, 600000.00, 1200000.00, NULL, 'picked_up'),
(10, 18, 5, 1, '2026-03-02 05:06:29', '2026-03-03 05:06:29', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(11, 18, 7, 2, '2026-03-02 05:06:29', '2026-03-07 05:06:29', NULL, 600000.00, 2400000.00, NULL, 'returned'),
(12, 22, 7, 1, '2026-02-14 09:03:05', '2026-02-18 09:03:05', NULL, 600000.00, 1200000.00, NULL, 'returned'),
(13, 23, 7, 1, '2026-02-23 04:24:36', '2026-02-27 04:24:36', NULL, 600000.00, 1200000.00, NULL, 'picked_up'),
(14, 25, 5, 1, '2026-03-02 14:32:49', '2026-03-03 14:32:49', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(15, 26, 1, 1, '2026-02-07 08:13:55', '2026-02-09 08:13:55', NULL, 500000.00, 1500000.00, NULL, 'returned'),
(16, 26, 6, 1, '2026-02-07 08:13:55', '2026-02-10 08:13:55', NULL, 1200000.00, 2400000.00, NULL, 'returned'),
(17, 26, 3, 1, '2026-02-07 08:13:55', '2026-02-08 08:13:55', NULL, 2000000.00, 6000000.00, NULL, 'returned'),
(18, 28, 3, 1, '2026-02-09 00:01:21', '2026-02-12 00:01:21', NULL, 2000000.00, 6000000.00, NULL, 'returned'),
(19, 28, 1, 1, '2026-02-09 00:01:21', '2026-02-13 00:01:21', NULL, 500000.00, 1500000.00, NULL, 'returned'),
(20, 28, 6, 2, '2026-02-09 00:01:21', '2026-02-13 00:01:21', NULL, 1200000.00, 4800000.00, NULL, 'returned'),
(21, 30, 3, 2, '2026-02-27 03:02:55', '2026-03-01 03:02:55', NULL, 2000000.00, 12000000.00, NULL, 'returned'),
(22, 30, 2, 1, '2026-02-27 03:02:55', '2026-02-28 03:02:55', NULL, 500000.00, 1500000.00, NULL, 'returned'),
(23, 31, 5, 1, '2026-03-02 07:53:25', '2026-03-07 07:53:25', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(24, 31, 5, 1, '2026-03-02 07:53:25', '2026-03-05 07:53:25', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(25, 33, 3, 2, '2026-02-17 17:12:55', '2026-02-21 17:12:55', NULL, 2000000.00, 12000000.00, NULL, 'returned'),
(26, 33, 5, 2, '2026-02-17 17:12:55', '2026-02-22 17:12:55', NULL, 2800000.00, 10000000.00, NULL, 'returned'),
(27, 33, 6, 2, '2026-02-17 17:12:55', '2026-02-21 17:12:55', NULL, 1200000.00, 4800000.00, NULL, 'returned'),
(28, 34, 6, 2, '2026-02-03 06:32:32', '2026-02-06 06:32:32', NULL, 1200000.00, 4800000.00, NULL, 'picked_up'),
(29, 34, 1, 2, '2026-02-03 06:32:32', '2026-02-08 06:32:32', NULL, 500000.00, 3000000.00, NULL, 'picked_up'),
(30, 36, 3, 2, '2026-02-21 13:05:35', '2026-02-24 13:05:35', NULL, 2000000.00, 12000000.00, NULL, 'picked_up'),
(31, 38, 2, 1, '2026-02-19 12:33:20', '2026-02-22 12:33:20', NULL, 500000.00, 1500000.00, NULL, 'returned'),
(32, 38, 2, 2, '2026-02-19 12:33:20', '2026-02-22 12:33:20', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(33, 38, 2, 2, '2026-02-19 12:33:20', '2026-02-22 12:33:20', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(34, 40, 5, 2, '2026-02-12 12:49:21', '2026-02-13 12:49:21', NULL, 2800000.00, 10000000.00, NULL, 'returned'),
(35, 47, 3, 2, '2026-02-27 17:17:43', '2026-02-28 17:17:43', NULL, 2000000.00, 12000000.00, NULL, 'returned'),
(36, 48, 3, 2, '2026-02-07 10:36:01', '2026-02-10 10:36:01', NULL, 2000000.00, 12000000.00, NULL, 'returned'),
(37, 48, 2, 2, '2026-02-07 10:36:01', '2026-02-09 10:36:01', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(38, 50, 4, 1, '2026-02-27 15:34:32', '2026-03-04 15:34:32', NULL, 600000.00, 1800000.00, NULL, 'returned'),
(39, 51, 3, 1, '2026-02-16 16:49:05', '2026-02-21 16:49:05', NULL, 2000000.00, 6000000.00, NULL, 'returned'),
(40, 51, 6, 2, '2026-02-16 16:49:05', '2026-02-19 16:49:05', NULL, 1200000.00, 4800000.00, NULL, 'returned'),
(41, 51, 3, 1, '2026-02-16 16:49:05', '2026-02-19 16:49:05', NULL, 2000000.00, 6000000.00, NULL, 'returned'),
(42, 53, 3, 1, '2026-02-16 20:06:02', '2026-02-20 20:06:02', NULL, 2000000.00, 6000000.00, NULL, 'picked_up'),
(43, 54, 6, 2, '2026-02-05 10:47:46', '2026-02-06 10:47:46', NULL, 1200000.00, 4800000.00, NULL, 'picked_up'),
(44, 55, 7, 2, '2026-02-26 13:15:50', '2026-03-01 13:15:50', NULL, 600000.00, 2400000.00, NULL, 'picked_up'),
(45, 59, 3, 1, '2026-02-14 16:43:52', '2026-02-17 16:43:52', NULL, 2000000.00, 6000000.00, NULL, 'picked_up'),
(46, 59, 5, 2, '2026-02-14 16:43:52', '2026-02-17 16:43:52', NULL, 2800000.00, 10000000.00, NULL, 'picked_up'),
(47, 59, 3, 2, '2026-02-14 16:43:52', '2026-02-18 16:43:52', NULL, 2000000.00, 12000000.00, NULL, 'picked_up'),
(48, 61, 1, 2, '2026-02-24 11:04:36', '2026-02-26 11:04:36', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(49, 63, 6, 1, '2026-02-07 10:30:03', '2026-02-09 10:30:03', NULL, 1200000.00, 2400000.00, NULL, 'returned'),
(50, 63, 6, 1, '2026-02-07 10:30:03', '2026-02-10 10:30:03', NULL, 1200000.00, 2400000.00, NULL, 'returned'),
(51, 63, 2, 2, '2026-02-07 10:30:03', '2026-02-08 10:30:03', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(52, 66, 1, 2, '2026-02-23 22:36:06', '2026-02-28 22:36:06', NULL, 500000.00, 3000000.00, NULL, 'picked_up'),
(53, 67, 5, 1, '2026-02-06 19:26:24', '2026-02-07 19:26:24', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(54, 67, 7, 2, '2026-02-06 19:26:24', '2026-02-11 19:26:24', NULL, 600000.00, 2400000.00, NULL, 'returned'),
(55, 67, 4, 1, '2026-02-06 19:26:24', '2026-02-07 19:26:24', NULL, 600000.00, 1800000.00, NULL, 'returned'),
(56, 69, 6, 2, '2026-02-06 10:46:24', '2026-02-08 10:46:24', NULL, 1200000.00, 4800000.00, NULL, 'picked_up'),
(57, 70, 5, 1, '2026-02-14 19:53:18', '2026-02-15 19:53:18', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(58, 70, 2, 2, '2026-02-14 19:53:18', '2026-02-17 19:53:18', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(59, 73, 1, 2, '2026-02-17 23:51:19', '2026-02-19 23:51:19', NULL, 500000.00, 3000000.00, NULL, 'picked_up'),
(60, 73, 4, 2, '2026-02-17 23:51:19', '2026-02-19 23:51:19', NULL, 600000.00, 3600000.00, NULL, 'picked_up'),
(61, 73, 2, 1, '2026-02-17 23:51:19', '2026-02-19 23:51:19', NULL, 500000.00, 1500000.00, NULL, 'picked_up'),
(62, 74, 5, 1, '2026-02-06 07:53:57', '2026-02-08 07:53:57', NULL, 2800000.00, 5000000.00, NULL, 'picked_up'),
(63, 74, 2, 1, '2026-02-06 07:53:57', '2026-02-09 07:53:57', NULL, 500000.00, 1500000.00, NULL, 'picked_up'),
(64, 77, 4, 2, '2026-02-16 13:54:16', '2026-02-20 13:54:16', NULL, 600000.00, 3600000.00, NULL, 'picked_up'),
(65, 77, 1, 2, '2026-02-16 13:54:16', '2026-02-18 13:54:16', NULL, 500000.00, 3000000.00, NULL, 'picked_up'),
(66, 78, 6, 2, '2026-03-02 03:21:06', '2026-03-07 03:21:06', NULL, 1200000.00, 4800000.00, NULL, 'picked_up'),
(67, 78, 5, 2, '2026-03-02 03:21:06', '2026-03-03 03:21:06', NULL, 2800000.00, 10000000.00, NULL, 'picked_up'),
(68, 80, 2, 2, '2026-02-06 13:03:21', '2026-02-11 13:03:21', NULL, 500000.00, 3000000.00, NULL, 'returned'),
(69, 80, 5, 1, '2026-02-06 13:03:21', '2026-02-08 13:03:21', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(70, 80, 4, 1, '2026-02-06 13:03:21', '2026-02-07 13:03:21', NULL, 600000.00, 1800000.00, NULL, 'returned'),
(71, 82, 6, 1, '2026-02-07 23:58:48', '2026-02-11 23:58:48', NULL, 1200000.00, 2400000.00, NULL, 'returned'),
(72, 82, 5, 1, '2026-02-07 23:58:48', '2026-02-10 23:58:48', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(73, 83, 7, 1, '2026-02-11 00:23:03', '2026-02-12 00:23:03', NULL, 600000.00, 1200000.00, NULL, 'picked_up'),
(74, 83, 3, 1, '2026-02-11 00:23:03', '2026-02-16 00:23:03', NULL, 2000000.00, 6000000.00, NULL, 'picked_up'),
(75, 83, 4, 2, '2026-02-11 00:23:03', '2026-02-13 00:23:03', NULL, 600000.00, 3600000.00, NULL, 'picked_up'),
(76, 84, 7, 2, '2026-02-12 17:59:51', '2026-02-16 17:59:51', NULL, 600000.00, 2400000.00, NULL, 'picked_up'),
(77, 86, 3, 1, '2026-02-04 04:50:56', '2026-02-07 04:50:56', NULL, 2000000.00, 6000000.00, NULL, 'picked_up'),
(78, 86, 5, 2, '2026-02-04 04:50:56', '2026-02-05 04:50:56', NULL, 2800000.00, 10000000.00, NULL, 'picked_up'),
(79, 86, 3, 2, '2026-02-04 04:50:56', '2026-02-07 04:50:56', NULL, 2000000.00, 12000000.00, NULL, 'picked_up'),
(80, 87, 5, 1, '2026-02-01 20:26:19', '2026-02-04 20:26:19', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(81, 88, 6, 2, '2026-02-26 05:18:37', '2026-03-01 05:18:37', NULL, 1200000.00, 4800000.00, NULL, 'picked_up'),
(82, 90, 6, 2, '2026-02-08 17:51:42', '2026-02-13 17:51:42', NULL, 1200000.00, 4800000.00, NULL, 'picked_up'),
(83, 91, 5, 1, '2026-02-04 01:20:52', '2026-02-05 01:20:52', NULL, 2800000.00, 5000000.00, NULL, 'returned'),
(84, 91, 4, 1, '2026-02-04 01:20:52', '2026-02-05 01:20:52', NULL, 600000.00, 1800000.00, NULL, 'returned'),
(85, 93, 6, 1, '2026-02-16 21:40:29', '2026-02-21 21:40:29', NULL, 1200000.00, 2400000.00, NULL, 'returned'),
(86, 95, 3, 2, '2026-02-28 12:25:56', '2026-03-01 12:25:56', NULL, 2000000.00, 12000000.00, NULL, 'picked_up');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `slug` varchar(50) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `slug`, `display_name`) VALUES
(1, 'manage_users', 'Manage Users'),
(2, 'manage_products', 'Manage Products'),
(3, 'manage_content', 'Manage Website Content'),
(4, 'manage_bookings', 'Manage Bookings'),
(5, 'create_booking', 'Create Booking');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `rental_price_per_day` decimal(15,2) NOT NULL,
  `deposit_fee` decimal(15,2) NOT NULL,
  `insurance_fee` decimal(15,2) DEFAULT '0.00',
  `specifications` json DEFAULT NULL,
  `sizes` varchar(100) DEFAULT NULL,
  `total_stock_quantity` int DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `image_url`, `rental_price_per_day`, `deposit_fee`, `insurance_fee`, `specifications`, `sizes`, `total_stock_quantity`, `is_featured`, `is_active`) VALUES
(1, 6, 'Áo Dài Đỏ Truyền Thống', NULL, 'Áo dài lụa đỏ cổ điển với họa tiết thêu vàng cho ngày Tết', 'assets/costume-red-ao-dai.jpg', 500000.00, 1500000.00, 0.00, NULL, 'XS - XXL', 0, 1, 1),
(2, 6, 'Áo Dài Trắng Hiện Đại', NULL, 'Áo dài trắng hiện đại với họa tiết tinh tế', 'assets/costume-white-ao-dai.jpg', 500000.00, 1500000.00, 0.00, NULL, 'XS - XXL', 0, 0, 1),
(3, 7, 'Váy Cưới Trắng Cổ Điển', NULL, 'Váy cưới trắng bất tận với những chi tiết thanh lịch', 'assets/costume-wedding-white.jpg', 2000000.00, 6000000.00, 0.00, NULL, 'XS - XXL', 0, 1, 1),
(4, 8, 'Váy Cổ Điển Thập Niên 50', NULL, 'Váy lấy cảm hứng từ những năm 1950 với họa tiết chấm bi', 'assets/costume-vintage-50s.jpg', 600000.00, 1800000.00, 0.00, NULL, 'XS - L', 0, 0, 1),
(5, 2, 'Canon EOS R5C', NULL, 'Máy ảnh mirrorless full frame chuyên nghiệp cao cấp với khả năng quay video 8K tuyệt vời', 'assets/uploads/1772380052_69a45f942a789.webp', 2800000.00, 5000000.00, 500000.00, '{\"specs\": \"Full Frame Mirrorless, 45MP\"}', NULL, 5, 0, 1),
(6, 3, 'RF 24-70mm f/2.8L IS USM', NULL, 'Ống kính zoom tiêu chuẩn chuyên nghiệp với khẩu độ f/2.8 cố định', 'assets/uploads/1772380021_69a45f75de64a.jpg', 1200000.00, 2400000.00, 200000.00, '{\"specs\": \"Ống Kính Zoom Tiêu Chuẩn, Canon RF Mount\"}', NULL, 5, 0, 1),
(7, 4, 'Godox SL-60W Đèn LED Studio', NULL, 'Đèn LED liên tục chuyên nghiệp cho studio', 'assets/uploads/1771573966_699812cebbb8f.jpg', 600000.00, 1200000.00, 100000.00, '{\"specs\": \"Bảng Đèn LED 60W Chuyên Nghiệp\"}', NULL, 10, 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_assets`
--

CREATE TABLE `product_assets` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `sku_code` varchar(100) DEFAULT NULL,
  `status` enum('available','rented','maintenance','lost','liquidated') DEFAULT 'available',
  `condition_note` text,
  `purchase_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `product_assets`
--

INSERT INTO `product_assets` (`id`, `product_id`, `serial_number`, `sku_code`, `status`, `condition_note`, `purchase_date`) VALUES
(1, 5, 'SN-R5C-001', 'CAM-R5C-01', 'available', 'New condition', '2023-01-15'),
(2, 5, 'SN-R5C-002', 'CAM-R5C-02', 'rented', 'Minor scratch on body', '2023-01-15'),
(3, 6, 'SN-LENS-001', 'LENS-2470-01', 'available', 'Perfect optics', '2023-02-20'),
(4, 7, 'SN-LIGHT-001', 'LIGHT-60W-01', 'maintenance', 'Bulb replacement needed', '2023-03-10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`) VALUES
(1, 'admin', 'Administrator', 'Full system access'),
(2, 'staff', 'Staff', 'Manage bookings and rentals'),
(3, 'customer', 'Customer', 'Regular user');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(3, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `base_price` decimal(15,2) NOT NULL,
  `deposit_required` decimal(15,2) DEFAULT '0.00',
  `duration_minutes` int DEFAULT '60',
  `max_photos_deliver` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id`, `category_id`, `name`, `slug`, `description`, `base_price`, `deposit_required`, `duration_minutes`, `max_photos_deliver`, `image_url`, `is_active`) VALUES
(1, 1, 'Chụp ảnh Tết', NULL, 'Tôn vinh năm mới với những bộ ảnh Áo Dài sang trọng và ảnh gia đình', 1500000.00, 0.00, 60, NULL, 'assets/t-t-photography--o-d-i-family-portraits.jpg', 1),
(2, 1, 'Ảnh Cưới', NULL, 'Ghi lại ngày trọng đại của bạn với phong cách nghệ thuật và bất biến', 5000000.00, 0.00, 60, NULL, 'assets/wedding-photography-ceremony-reception.jpg', 1),
(3, 1, 'Ảnh Kỷ Niệm', NULL, 'Tôn vinh câu chuyện tình yêu của bạn với chụp ảnh chuyên nghiệp', 2000000.00, 0.00, 60, NULL, 'assets/anniversary-photography-couples-portraits.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `created_at` datetime DEFAULT (now()),
  `updated_at` datetime DEFAULT (now())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password_hash`, `avatar_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@lensy.com', '0900000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'assets/uploads/1771573567_6998113f26840.png', 'active', '2026-02-19 22:37:05', '2026-02-19 22:37:05'),
(2, 'Staff User', 'staff@lensy.com', '0900000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'assets/avatar-manager.jpg', 'active', '2026-02-19 22:37:05', '2026-02-19 22:37:05'),
(3, 'Nguyen Van A', 'customer@lensy.com', '0900000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'assets/avatar-customer.jpg', 'active', '2026-02-19 22:37:05', '2026-02-19 22:37:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(3, 3);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `cms_testimonials`
--
ALTER TABLE `cms_testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cms_timelines`
--
ALTER TABLE `cms_timelines`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_bookings`
--
ALTER TABLE `order_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `photographer_id` (`photographer_id`),
  ADD KEY `makeup_artist_id` (`makeup_artist_id`);

--
-- Chỉ mục cho bảng `order_rentals`
--
ALTER TABLE `order_rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `assigned_asset_id` (`assigned_asset_id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_assets`
--
ALTER TABLE `product_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku_code` (`sku_code`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Chỉ mục cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `cms_testimonials`
--
ALTER TABLE `cms_testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `cms_timelines`
--
ALTER TABLE `cms_timelines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `order_bookings`
--
ALTER TABLE `order_bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT cho bảng `order_rentals`
--
ALTER TABLE `order_rentals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `product_assets`
--
ALTER TABLE `product_assets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `order_bookings`
--
ALTER TABLE `order_bookings`
  ADD CONSTRAINT `order_bookings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_bookings_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `order_bookings_ibfk_3` FOREIGN KEY (`photographer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_bookings_ibfk_4` FOREIGN KEY (`makeup_artist_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `order_rentals`
--
ALTER TABLE `order_rentals`
  ADD CONSTRAINT `order_rentals_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_rentals_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_rentals_ibfk_3` FOREIGN KEY (`assigned_asset_id`) REFERENCES `product_assets` (`id`);

--
-- Ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ràng buộc cho bảng `product_assets`
--
ALTER TABLE `product_assets`
  ADD CONSTRAINT `product_assets_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ràng buộc cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ràng buộc cho bảng `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
