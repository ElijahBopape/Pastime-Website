DROP TABLE IF EXISTS `tblcart`;
CREATE TABLE `tblcart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cart_id`),
  UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `tblcart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `tblcart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tblproduct` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tblmessage`;
CREATE TABLE `tblmessage` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`message_id`),
  KEY `product_id` (`product_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_id` (`seller_id`),
  KEY `sender_id` (`sender_id`),
  CONSTRAINT `tblmessage_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tblproduct` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `tblmessage_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `tblmessage_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `tblmessage_ibfk_4` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tblorder`;
CREATE TABLE `tblorder` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(80) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('received','processing','delivered') NOT NULL DEFAULT 'received',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tblorder_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tblorderitem`;
CREATE TABLE `tblorderitem` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `tblorderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tblorder` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `tblorderitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tblproduct` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tblproduct`;
CREATE TABLE `tblproduct` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `product_code` varchar(20) NOT NULL,
  `name` varchar(120) NOT NULL,
  `brand` varchar(80) NOT NULL,
  `category` varchar(60) NOT NULL,
  `size` varchar(20) NOT NULL,
  `item_condition` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('available','sold') NOT NULL DEFAULT 'available',
  `admin_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_code` (`product_code`),
  KEY `seller_id` (`seller_id`),
  CONSTRAINT `tblproduct_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbluser`;
CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `seller_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

