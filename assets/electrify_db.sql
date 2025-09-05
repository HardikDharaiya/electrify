-- This script is for setting up the database schema for the Electrify project.
-- Database: `electrify_db`

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `categories`
--
CREATE TABLE `categories` (
  `category_id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(100) NOT NULL,
  `category_description` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `products`
--
CREATE TABLE `products` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10, 2) NOT NULL,
  `sku` VARCHAR(100) NOT NULL UNIQUE,
  `stock_quantity` INT(11) NOT NULL DEFAULT 0,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `orders`
--
CREATE TABLE `orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  `order_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
  `shipping_address` TEXT NOT NULL,
  `billing_address` TEXT NOT NULL,
  `payment_id` VARCHAR(255) DEFAULT NULL,
  `order_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `order_items`
--
CREATE TABLE `order_items` (
  `item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `price_per_unit` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`item_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- This script will insert sample data into the Electrify database.
-- We must insert into 'categories' first, because 'products' depends on it.

--
-- Populating the `categories` table
--
INSERT INTO `categories` (`category_id`, `category_name`, `category_description`) VALUES
(1, 'Passive Components', 'Essential building blocks for any circuit, including resistors, capacitors, and inductors.'),
(2, 'Active Components', 'Semiconductors that power your designs, such as diodes, transistors, and integrated circuits.'),
(3, 'Tools & Accessories', 'High-quality equipment for building and testing, including soldering irons, multimeters, and wires.');

--
-- Populating the `products` table with items from each category
--

-- Products for Category 1: Passive Components
INSERT INTO `products` (`category_id`, `product_name`, `description`, `price`, `sku`, `stock_quantity`) VALUES
(1, 'Carbon Film Resistor Kit', 'A 600-piece assortment of 1/4W carbon film resistors with 1% tolerance.', 9.99, 'ELEC-PASS-001', 150),
(1, 'Ceramic Capacitor Variety Pack', 'A 500-piece kit of assorted ceramic capacitors, ranging from 1pF to 100nF.', 12.50, 'ELEC-PASS-002', 120),
(1, 'Through-Hole Inductor Set', 'A 120-piece set of various through-hole inductors for filtering and energy storage applications.', 15.99, 'ELEC-PASS-003', 75);

-- Products for Category 2: Active Components
INSERT INTO `products` (`category_id`, `product_name`, `description`, `price`, `sku`, `stock_quantity`) VALUES
(2, '5mm Assorted LED Pack', 'A 300-piece pack of high-brightness 5mm LEDs in 5 colors (Red, Green, Blue, Yellow, White).', 8.75, 'ELEC-ACTV-001', 200),
(2, 'BC547 NPN Transistor', 'A general-purpose NPN bipolar junction transistor for switching and amplification.', 0.25, 'ELEC-ACTV-002', 1000),
(2, 'NE555 Precision Timer IC', 'The classic 555 timer integrated circuit, perfect for oscillators and timer circuits.', 0.99, 'ELEC-ACTV-003', 500);

-- Products for Category 3: Tools & Accessories
INSERT INTO `products` (`category_id`, `product_name`, `description`, `price`, `sku`, `stock_quantity`) VALUES
(3, 'Digital Multimeter DT830D', 'A reliable and easy-to-use digital multimeter for measuring voltage, current, and resistance.', 19.99, 'ELEC-TOOL-001', 50),
(3, '60W Adjustable Soldering Iron', 'A 110V/220V 60W soldering iron with adjustable temperature control from 200-450°C.', 24.50, 'ELEC-TOOL-002', 40),
(3, 'Pre-tinned Jumper Wire Kit', 'A box of 140 assorted U-shaped solderless breadboard jumper wires.', 7.99, 'ELEC-TOOL-003', 90);




-- This script updates the prices of our sample products to appropriate INR values.

-- Updating Category 1: Passive Components
UPDATE `products` SET `price` = 799.00 WHERE `sku` = 'ELEC-PASS-001';
UPDATE `products` SET `price` = 999.00 WHERE `sku` = 'ELEC-PASS-002';
UPDATE `products` SET `price` = 1249.00 WHERE `sku` = 'ELEC-PASS-003';

-- Updating Category 2: Active Components
UPDATE `products` SET `price` = 699.00 WHERE `sku` = 'ELEC-ACTV-001';
UPDATE `products` SET `price` = 20.00 WHERE `sku` = 'ELEC-ACTV-002';
UPDATE `products` SET `price` = 79.00 WHERE `sku` = 'ELEC-ACTV-003';

-- Updating Category 3: Tools & Accessories
UPDATE `products` SET `price` = 1599.00 WHERE `sku` = 'ELEC-TOOL-001';
UPDATE `products` SET `price` = 1999.00 WHERE `sku` = 'ELEC-TOOL-002';
UPDATE `products` SET `price` = 649.00 WHERE `sku` = 'ELEC-TOOL-003';


-- This script creates the `cart` table for storing persistent cart data for logged-in users.

CREATE TABLE `cart` (
  `cart_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `added_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`) ON DELETE CASCADE,
  UNIQUE KEY `user_product_unique` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;