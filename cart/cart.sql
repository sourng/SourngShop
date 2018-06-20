-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2017 at 03:01 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cart`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `address` varchar(80) COLLATE latin1_general_ci NOT NULL,
  `phone` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `address`, `phone`) VALUES
(1, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801'),
(2, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801'),
(3, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801'),
(4, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801'),
(5, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801'),
(6, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801'),
(7, 'Arunkumar M', 'arunkumarpsp1@gmail.com', 'Thee Thi Appavu Street, 15/10', '8951519801');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customerid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `date`, `customerid`) VALUES
(1, '2017-03-29', 1),
(2, '2017-03-29', 2),
(3, '2017-03-29', 3),
(4, '2017-03-29', 4),
(5, '2017-03-29', 5),
(6, '2017-03-29', 6),
(7, '2017-03-30', 7);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `orderid`, `productid`, `quantity`, `price`) VALUES
(1, 1, 3, 1, 350),
(2, 1, 2, 1, 300),
(3, 1, 4, 1, 400),
(4, 2, 3, 1, 350),
(5, 2, 2, 1, 300),
(6, 2, 4, 1, 400),
(7, 3, 3, 1, 350),
(8, 3, 2, 1, 300),
(9, 3, 4, 1, 400),
(10, 4, 3, 1, 350),
(11, 4, 2, 1, 300),
(12, 4, 4, 1, 400),
(13, 5, 3, 1, 350),
(14, 5, 2, 1, 300),
(15, 5, 4, 1, 400),
(16, 6, 3, 1, 350),
(17, 6, 2, 1, 300),
(18, 6, 4, 1, 400),
(19, 7, 2, 1, 300),
(20, 7, 3, 3, 350),
(21, 7, 4, 1, 400);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `price` varchar(250) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`) VALUES
(1, 'product 1', '250', '1.jpg'),
(2, 'product 2', '300', '2.jpg'),
(3, 'product 3', '350', '3.jpg'),
(4, 'product 4', '400', '4.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
