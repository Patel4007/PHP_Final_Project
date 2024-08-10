-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2024 at 01:03 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grocery_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `grocery_items`
--

CREATE TABLE `grocery_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grocery_items`
--

INSERT INTO `grocery_items` (`id`, `name`, `price`) VALUES
(1, 'Apples', 3.5);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `expiry` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `type`, `price`, `quantity`, `expiry`) VALUES
(1, 'Apple', 'Fruit', 1.9, 3, '2024-07-27'),
(2, 'Bread', 'Grocery', 3.9, 16, '2024-07-25'),
(3, 'Bay Leaf', 'Grocery', 1.4, 15, '2024-07-31'),
(4, 'Rice', 'Grocery', 19.99, 14, '2024-07-26'),
(6, 'Pear', 'Fruit', 0.7, 9, '2024-09-20'),
(7, 'Pineapple', 'Fruit', 1.2, 80, '2024-08-25'),
(8, 'Watermelon', 'Fruit', 3, 70, '2024-08-15'),
(9, 'Zucchini', 'Vegetable', 0.8, 1, '2024-09-10'),
(10, 'Radish', 'Vegetable', 0.6, 110, '2024-08-30'),
(11, 'Asparagus', 'Vegetable', 1.5, 71, '2024-09-05'),
(12, 'Almond Milk', 'Dairy', 2, 50, '2024-08-25'),
(13, 'Soy Cheese', 'Dairy', 3, 40, '2024-09-12'),
(14, 'Coconut Yogurt', 'Dairy', 2.5, 60, '2024-07-15'),
(15, 'Turkey Bacon', 'Meat', 6, 30, '2024-10-08'),
(16, 'Lamb Chops', 'Meat', 9, 25, '2024-08-20'),
(17, 'Duck Breast', 'Meat', 8, 35, '2024-09-28'),
(18, 'Cod', 'Seafood', 12, 20, '2024-09-02'),
(19, 'Haddock', 'Seafood', 10, 15, '2024-11-14'),
(20, 'Oyster', 'Seafood', 15, 10, '2024-07-12'),
(21, 'Whole Wheat Bread', 'Bakery', 1.2, 100, '2024-10-30'),
(22, 'Danish Pastry', 'Bakery', 1.8, 70, '2024-08-18'),
(23, 'Pretzel', 'Bakery', 1.5, 80, '2024-09-22'),
(24, 'Quinoa', 'Grain', 3, 200, '2024-08-15'),
(25, 'Barley', 'Grain', 2.5, 150, '2024-09-05'),
(26, 'Arugula', 'Vegetable', 1.1, 90, '2024-02-15'),
(27, 'Kale', 'Vegetable', 1.3, 80, '2024-05-22'),
(28, 'Cauliflower', 'Vegetable', 1.2, 60, '2024-08-11'),
(29, 'Bell Peppers', 'Vegetable', 1.5, 70, '2024-10-03'),
(30, 'Portobello Mushrooms', 'Vegetable', 2.2, 40, '2024-07-09'),
(31, 'Whipped Cream', 'Dairy', 1.9, 60, '2024-11-26'),
(32, 'Ghee', 'Dairy', 2.8, 45, '2024-09-14'),
(33, 'Organic Eggs', 'Dairy', 3, 100, '2024-06-01'),
(34, 'Venison', 'Meat', 10, 25, '2024-04-27'),
(35, 'Duck', 'Meat', 9.5, 20, '2024-12-07'),
(36, 'Mango', 'Fruit', 45, 120, '2024-07-25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL,
  `payment` varchar(100) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(9, 'Jay Patel', '$2y$10$xbhxKeJFnmITs8lQvZIlUO/3ahakmFHBIapjn3d6AGfxHZT9taJRu', 'patel41100@gmail.com', 'Admin'),
(10, 'kennedy', '$2y$10$gBu0LtBE7xXJxtHye9lale6NZ60v0Rkr3G6vKdASzN./CSc5An8Aa', 'patel411200@gmail.com', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grocery_items`
--
ALTER TABLE `grocery_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
