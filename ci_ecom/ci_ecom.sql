-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.31-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for ci_ecom
CREATE DATABASE IF NOT EXISTS `ci_ecom` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ci_ecom`;

-- Dumping structure for table ci_ecom.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int(100) NOT NULL AUTO_INCREMENT,
  `brand_title` text NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table ci_ecom.brands: ~6 rows (approximately)
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` (`brand_id`, `brand_title`) VALUES
	(1, 'HP'),
	(2, 'Samsung'),
	(3, 'Apple'),
	(4, 'Sony'),
	(5, 'LG'),
	(6, 'Cloth Brand');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `p_id` int(10) NOT NULL,
  `ip_add` varchar(250) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `qty` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table ci_ecom.cart: ~0 rows (approximately)
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` (`id`, `p_id`, `ip_add`, `user_id`, `qty`) VALUES
	(4, 32, '::1', -1, 1);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(100) NOT NULL AUTO_INCREMENT,
  `brand_id` int(100) NOT NULL DEFAULT '0',
  `cat_title` text NOT NULL,
  PRIMARY KEY (`cat_id`,`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table ci_ecom.categories: ~11 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`cat_id`, `brand_id`, `cat_title`) VALUES
	(1, 1, 'Electronics'),
	(2, 1, 'Ladies Wears'),
	(3, 2, 'Mens Wear'),
	(4, 2, 'Kids Wear'),
	(5, 3, 'Furnitures'),
	(6, 3, 'Home Appliances'),
	(7, 3, 'Electronics Gadgets'),
	(8, 6, 'សំលៀកបំពាក់ស្ត្រី'),
	(9, 6, 'សំលៀកបំពាក់បុរស'),
	(10, 5, 'LG 450'),
	(11, 4, 'Sony 450');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT '0',
  `email` varchar(250) DEFAULT '0',
  `address` varchar(250) DEFAULT '0',
  `phone` varchar(250) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table ci_ecom.customers: ~4 rows (approximately)
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` (`id`, `name`, `email`, `address`, `phone`) VALUES
	(1, NULL, NULL, NULL, NULL),
	(2, NULL, NULL, NULL, NULL),
	(3, NULL, NULL, NULL, NULL),
	(4, NULL, NULL, NULL, NULL),
	(5, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `trx_id` varchar(255) NOT NULL,
  `p_status` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `customerid` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table ci_ecom.orders: ~5 rows (approximately)
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `qty`, `trx_id`, `p_status`, `date`, `customerid`) VALUES
	(1, 2, 7, 1, '07M47684BS5725041', 'Completed', '', 0),
	(2, 2, 2, 1, '07M47684BS5725041', 'Completed', '', 0),
	(3, 3, 4, 3, '1233333333', 'Completed', '', 0),
	(4, 0, 0, 0, '', '', '2018-06-20', 3),
	(5, 0, 0, 0, '', '', '2018-06-20', 4),
	(6, 0, 0, 0, '', '', '2018-06-20', 5);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.order_detail
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `productid` int(11) DEFAULT '0',
  `quantity` int(11) DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table ci_ecom.order_detail: ~0 rows (approximately)
/*!40000 ALTER TABLE `order_detail` DISABLE KEYS */;
INSERT INTO `order_detail` (`id`, `orderid`, `productid`, `quantity`, `price`) VALUES
	(6, 5, 7, 2, 15000.24),
	(7, 6, 7, 2, 15000.24),
	(8, 6, 1, 1, 3751.31);
/*!40000 ALTER TABLE `order_detail` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.products
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(100) NOT NULL AUTO_INCREMENT,
  `product_cat` int(100) NOT NULL,
  `product_brand` int(100) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_dist` decimal(10,2) NOT NULL,
  `product_desc` text NOT NULL,
  `product_image` text NOT NULL,
  `product_image1` text NOT NULL,
  `product_image2` text NOT NULL,
  `product_image3` text NOT NULL,
  `top_sell` tinyint(4) NOT NULL,
  `condition` varchar(50) NOT NULL,
  `product_keywords` text NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- Dumping data for table ci_ecom.products: ~39 rows (approximately)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`product_id`, `product_cat`, `product_brand`, `product_title`, `product_price`, `product_dist`, `product_desc`, `product_image`, `product_image1`, `product_image2`, `product_image3`, `top_sell`, `condition`, `product_keywords`) VALUES
	(1, 3, 2, 'Samsung Dous 2', 5001.75, 25.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08e76443282_1527310140_large(1).jpg', '33401842_175709929795684_5658139969575714816_n(1).jpg', '33401842_175709929795684_5658139969575714816_n(1).jpg', 0, 'new', 'samsung mobile electronics'),
	(2, 6, 3, 'iPhone 5s', 25000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', 'j.png', 'cheasophea.JPG', '8092177c184bf63f0a6a03c6cc3e3319.jpg', '381b4941cb8e99f406bd6c2fa038a0b4.jpg', 0, 'new', 'mobile iphone apple'),
	(3, 7, 3, 'iPad', 305.00, 10.00, '<p>ipad apple brand</p>', 'product1.jpg', 'product1_2.jpg', 'product1_2.jpg', 'product1_2.jpg', 0, 'new', 'apple ipad tablet'),
	(4, 7, 3, 'iPhone 6s', 32000.00, 0.00, '<p>Apple iPhone</p>', 'product3.jpg', 'product3_2.jpg', '4.jpg', '5.jpg', 0, 'new', 'iphone apple mobile'),
	(5, 4, 2, 'iPad 2', 10000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '70e176369412dcfb286df1d451a351b6.png', 'boy-hairstyle-500x500.jpg', 'Drop-Fade-Haircut-500x500.jpg', 'grooming-tips-500x500.jpg', 0, 'second', 'ipad tablet samsung'),
	(6, 1, 1, 'Hp Laptop r series', 100.00, 2.00, '<p>Hp Red and Black combination Laptop</p>', 'indoor-retractable-clothes-line.jpg', 'Leifheit-72708-Adria-110-Bathtub-Dryer.jpg', 'Over-the-door-drying-rack.jpg', 'G4568-2.jpg', 1, 'new', 'hp laptop'),
	(7, 1, 1, 'Laptop Pavillion', 50000.80, 70.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '13684210-fashion-red-haired-girl-portrait-jewelry.jpg', '13931978-fashion-girl-portrait-golden-makeup.jpg', '21531703-a-portrait-of-stylish-elegant-redheaded-girl-is-in-lace-clothes.jpg', '51755917-beauty-fashion-woman-with-golden-makeup-accessories-and-nails.jpg', 0, 'new', 'Laptop Hp Pavillion'),
	(8, 11, 4, 'Sony', 400.00, 13.00, '<p>Sony Mobile</p>', '67522028-beauty-fashion-brunette-model-girl-portrait-sexy-young-woman-with-perfect-makeup-and-trendy-golden-a.jpg', '21531703-a-portrait-of-stylish-elegant-redheaded-girl-is-in-lace-clothes(1).jpg', 'accordion-drying-rack (1).jpg', 'G4568-2(1).jpg', 0, 'new', 'sony mobile'),
	(9, 7, 3, 'iPhone New', 300.00, 10.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', 'grooming-tips-500x500(1).jpg', 'Jake-Gyllenhaal-beard-500x500.jpg', 'medium-hairstyle-side-swept-500x500.jpg', 'swept-back-side-part-square-500x500.jpg', 0, 'second', 'iphone apple mobile'),
	(10, 9, 6, 'Red Ladies dress', 1000.00, 0.00, '<p>red dress for girls</p>', 'Jake-Gyllenhaal-beard-500x500(1).jpg', 'medium-hairstyle-side-swept-500x500(1).jpg', 'swept-back-side-part-square-500x500(1).jpg', 'Drop-Fade-Haircut-500x500(1).jpg', 0, 'second', 'red dress'),
	(11, 8, 6, 'Blue Heave dress', 1200.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '21531703-a-portrait-of-stylish-elegant-redheaded-girl-is-in-lace-clothes(2).jpg', '13684210-fashion-red-haired-girl-portrait-jewelry(1).jpg', '51755917-beauty-fashion-woman-with-golden-makeup-accessories-and-nails(1).jpg', 'accordion-drying-rack (2).jpg', 0, 'new', 'blue dress cloths'),
	(12, 8, 6, 'Ladies Casual Cloths', 1500.00, 0.00, '<p>ladies casual summer two colors pleted</p>', 'indoor-retractable-clothes-line(1).jpg', 'G4568-2(2).jpg', 'designer-clothes-rails-storage-ideas-within-clothes-rail-with-shoe-rack.jpg', '81JnOpVyzaL._SL1500_.jpg', 0, 'new', 'girl dress cloths casual'),
	(13, 9, 6, 'SpringAutumnDress', 1200.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', 'indoor-retractable-clothes-line(2).jpg', '51755917-beauty-fashion-woman-with-golden-makeup-accessories-and-nails(2).jpg', 'aaron-paul-short-hairstyle-500x500.jpg', 'accordion-drying-rack.jpg', 0, 'new', 'girl dress'),
	(14, 9, 6, 'Casual Dress', 1400.00, 20.00, '<p>girl dress</p>', 'indoor-retractable-clothes-line(3).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'ladies cloths girl'),
	(15, 2, 6, 'Formal Look', 1500.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, '', 'ladies wears dress girl'),
	(16, 3, 6, 'Sweter for men', 600.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'black sweter cloth winter'),
	(17, 3, 6, 'Gents formal', 1000.00, 0.00, 'gents formal look', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'gents wear cloths'),
	(19, 3, 6, 'Formal Coat', 3000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'coat blazer gents'),
	(20, 8, 6, 'Mens Sweeter', 600.00, 4.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 1, 'new', 'sweeter gents'),
	(21, 3, 6, 'T shirt', 800.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'formal t shirt black'),
	(22, 4, 6, 'Yellow T shirt ', 1300.00, 0.00, 'yello t shirt with pant', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'kids yellow t shirt'),
	(23, 4, 6, 'Girls cloths', 1900.00, 40.00, 'sadsf', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'formal kids wear dress'),
	(24, 4, 6, 'Blue T shirt', 700.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'kids dress'),
	(25, 4, 6, 'Yellow girls dress', 750.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'yellow kids dress'),
	(26, 4, 6, 'Skyblue dress', 650.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'skyblue kids dress'),
	(27, 4, 6, 'Formal look', 690.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'formal kids dress'),
	(32, 5, 0, 'Book Shelf', 2500.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'new', 'book shelf furniture'),
	(33, 6, 2, 'Refrigerator', 35000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'refrigerator samsung'),
	(34, 6, 4, 'Emergency Light', 1000.00, 0.00, 'Emergency Light', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'emergency light'),
	(35, 6, 0, 'Vaccum Cleaner', 6000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'Vaccum Cleaner'),
	(36, 6, 5, 'Iron', 1500.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'iron'),
	(37, 6, 5, 'LED TV', 20000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'led tv lg'),
	(38, 6, 4, 'Microwave Oven', 3500.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'Microwave Oven'),
	(39, 6, 5, 'Mixer Grinder', 2500.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'Mixer Grinder'),
	(40, 2, 6, 'Formal girls dress', 3000.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'ladies'),
	(45, 1, 2, 'Samsung Galaxy Note 3', 10000.00, 0.00, 'Product details\r\nWhite lace top, woven, has a round neck, short sleeves, has knitted lining attached\r\n\r\nMaterial & care\r\nPolyester\r\nMachine wash\r\nSize & Fit\r\nRegular fit\r\nThe model (height 5\'8" and chest 33") is wearing a size S\r\nDefine style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'samsung galaxy Note 3 neo'),
	(46, 1, 2, 'Samsung Galaxy Note 3', 10000.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 0, 'second', 'samsung galxaxy note 3 neo'),
	(47, 8, 6, 'dfasdfasdfs', 48.00, 0.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 1, 'second', 'dfsdfasdfasdfa'),
	(48, 8, 6, 'អាវដៃវេង', 30.99, 10.00, '<h4>Product details</h4>\r\n\r\n<p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>\r\n\r\n<h4>Material &amp; care</h4>\r\n\r\n<ul>\r\n	<li>Polyester</li>\r\n	<li>Machine wash</li>\r\n</ul>\r\n\r\n<h4>Size &amp; Fit</h4>\r\n\r\n<ul>\r\n	<li>Regular fit</li>\r\n	<li>The model (height 5\'8&quot; and chest 33&quot;) is wearing a size S</li>\r\n</ul>\r\n\r\n<blockquote>\r\n<p><em>Define style this season with Armani\'s new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>\r\n</blockquote>', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', '5b08f31f7b869_1527313140_large(1).jpg', 1, 'new', 'អាវស្រីទាន់សម័យ,លក់ដាច់ជាងគេ');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.tblproduct
CREATE TABLE IF NOT EXISTS `tblproduct` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` double(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table ci_ecom.tblproduct: ~3 rows (approximately)
/*!40000 ALTER TABLE `tblproduct` DISABLE KEYS */;
INSERT INTO `tblproduct` (`id`, `name`, `code`, `image`, `price`) VALUES
	(1, '3D Camera', '3DcAM01', 'product-images/camera.jpg', 1500.00),
	(2, 'External Hard Drive', 'USB02', 'product-images/external-hard-drive.jpg', 800.00),
	(3, 'Wrist Watch', 'wristWear03', 'product-images/watch.jpg', 300.00);
/*!40000 ALTER TABLE `tblproduct` ENABLE KEYS */;

-- Dumping structure for table ci_ecom.user_info
CREATE TABLE IF NOT EXISTS `user_info` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `address1` varchar(300) NOT NULL,
  `address2` varchar(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table ci_ecom.user_info: ~3 rows (approximately)
/*!40000 ALTER TABLE `user_info` DISABLE KEYS */;
INSERT INTO `user_info` (`user_id`, `first_name`, `last_name`, `email`, `password`, `mobile`, `address1`, `address2`) VALUES
	(1, 'Rizwan', 'Khan', 'rizwankhan.august16@gmail.com', '25f9e794323b453885f5181f1b624d0b', '8389080183', 'Rahmat Nagar Burnpur Asansol', 'Asansol'),
	(2, 'Rizwan', 'Khan', 'rizwankhan.august16@yahoo.com', '25f9e794323b453885f5181f1b624d0b', '8389080183', 'Rahmat Nagar Burnpur Asansol', 'Asa'),
	(3, 'Seng', 'Sourng', 'sengsourng@gmail.com', '25f9e794323b453885f5181f1b624d0b', '1234567899', 'Road6', 'Siem Reap');
/*!40000 ALTER TABLE `user_info` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
