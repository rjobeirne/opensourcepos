-- phpMyAdmin SQL Dump
-- version 4.6.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2017 at 06:25 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos.asadjaved`
--

-- --------------------------------------------------------

--
-- Table structure for table `phs_expenses`
--


CREATE TABLE `phs_expense_categories` (
  `expense_category_id` int(121) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `phs_expense_categories` (`expense_category_id`, `category_name`, `description`) VALUES
(1, 'tea', 'staff tea'),
(2, 'Meals', 'Stuff Meals'),
(4, 'bills', 'All type of bils');

CREATE TABLE `phs_expenses` (
  `expense_id` int(10) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(15,2) NOT NULL,
  `expense_category_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `employee_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phs_expenses`
--

INSERT INTO `phs_expenses` (`expense_id`, `date`, `amount`, `expense_category_id`, `description`, `employee_id`) VALUES
(2, '2017-03-05 01:37:41', '100.00', 1, 'Stuff Meals', 1),
(11, '2017-03-11 20:20:00', '200.00', 2, 'hh', 1),
(14, '2017-03-13 20:45:00', '1000.00', 4, 'telephone billl', 1),
(15, '2017-03-27 15:50:00', '200.00', 4, 'bill', 1),
(16, '2017-04-20 07:00:00', '1000.00', 1, 'expense tea', 1);

--
-- Indexes for dumped tables
--
ALTER TABLE `phs_expense_categories`
  ADD PRIMARY KEY (`expense_category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);
--
-- Indexes for table `phs_expenses`
--
ALTER TABLE `phs_expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `expense_category_id` (`expense_category_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--
ALTER TABLE `phs_expense_categories`
  MODIFY `expense_category_id` int(121) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `phs_expenses`
--
ALTER TABLE `phs_expenses`
  MODIFY `expense_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `phs_expenses`
--
ALTER TABLE `phs_expenses`
  ADD CONSTRAINT `phs_expenses_ibfk_1` FOREIGN KEY (`expense_category_id`) REFERENCES `phs_expense_categories` (`expense_category_id`),
  ADD CONSTRAINT `phs_expenses_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `phs_employees` (`person_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
