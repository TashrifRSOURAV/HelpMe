-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 03:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood_donors`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(10) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `mobile_number` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `location` enum('Badda','Natun Bazar','Mirpur') NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`first_name`, `last_name`, `gender`, `blood_group`, `mobile_number`, `email`, `location`, `password`) VALUES
('Faisal', 'Ahmed', '', 'A+', 123, '0', 'Natun Bazar', '123'),
('Jannatul', 'tajrian', 'Female', 'B+', 1234, '0', 'Mirpur', '1234'),
('Nahi', 'Mehedi', 'Male', 'A+', 12345, '0', 'Natun Bazar', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `sender_mobile` int(11) DEFAULT NULL,
  `receiver_mobile` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `sender_mobile`, `receiver_mobile`, `message`, `timestamp`) VALUES
(113, 888, 1234, 'hello', '2024-05-30 10:21:30'),
(118, 1755404808, 1760060543, 'Hello', '2024-06-02 23:04:03'),
(119, 1760060543, 1755404808, 'hey', '2024-06-02 23:04:58'),
(120, 1755404808, 1760060543, 'ki obosta?', '2024-06-02 23:05:13'),
(121, 1760060543, 1755404808, 'Alhamdulillah ', '2024-06-03 02:15:15'),
(122, 1760060543, 123, 'hello', '2024-06-03 03:58:46'),
(123, 123, 1760060543, 'hi', '2024-06-03 03:59:21'),
(124, 1760060543, 123, 'huhyo', '2024-06-03 04:09:56'),
(125, 123400044, 123, 'hi', '2024-06-03 04:20:30'),
(126, 123400044, 123, 'hello', '2024-06-03 04:31:44'),
(127, 123400044, 123, 'hi', '2024-06-03 05:34:23'),
(128, 123400044, 123, 'HI', '2024-06-03 07:07:27'),
(129, 123, 123400044, 'HELLO', '2024-06-03 07:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `complain`
--

CREATE TABLE `complain` (
  `first_name` varchar(30) NOT NULL,
  `mobile_number` varchar(11) NOT NULL,
  `complaints` varchar(2500) NOT NULL,
  `cimage` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complain`
--

INSERT INTO `complain` (`first_name`, `mobile_number`, `complaints`, `cimage`) VALUES
('ahmed', '123400044', 'complain 1\r\n', '');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(10) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `mobile_number` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `last_donation_date` date NOT NULL,
  `location` enum('Badda','Natun Bazar','Mirpur') NOT NULL,
  `password` varchar(500) NOT NULL,
  `reward` int(11) NOT NULL,
  `profilepicture` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`first_name`, `last_name`, `gender`, `blood_group`, `mobile_number`, `email`, `last_donation_date`, `location`, `password`, `reward`, `profilepicture`) VALUES
('SRK', 'badsha', 'Male', 'A+', 74, 's@k', '2024-05-18', 'Badda', '15', 0, ''),
('Salman', 'Khan', 'Male', 'A+', 89, 's@k', '0000-00-00', 'Natun Bazar', '852', 0, ''),
('faisal', 'ahmed', 'Male', 'A+', 123, 'faisalahmed@gmail.com', '2000-09-01', 'Badda', '123', 0, 0x75706c6f6164732f363635643434306564393639302e706e67),
('Hrittik', 'Roshan', 'Male', 'O+', 888, 'H@R', '2024-05-30', 'Mirpur', '888', 0, ''),
('anuara', 'manuar', 'Female', 'B+', 1234, 'a@m', '2009-10-30', 'Badda', '56', 5, 0x75706c6f6164732f363635316665303536623630392e6a666966),
('ahmed', 'faisal', 'Male', 'B+', 123400044, 'ahmedfaisal@gmail.com', '1999-01-01', 'Mirpur', '1234', 0, 0x75706c6f6164732f363635643434366630346431642e6a7067),
('Khokon ', 'Patwari', 'Male', 'A+', 1755404808, 'k@p', '2003-01-07', 'Badda', '808', 3, 0x75706c6f6164732f363635643433623262633130322e6a7067),
('Tashrif Rashid', 'Sourav', 'Male', 'B+', 1760060543, 's@w', '2024-06-10', 'Badda', '1234', 6, 0x75706c6f6164732f363635643432633035623465332e706e67);

-- --------------------------------------------------------

--
-- Table structure for table `helpline`
--

CREATE TABLE `helpline` (
  `Area` enum('Badda','Natun Bazar','Mirpur') NOT NULL,
  `Service` enum('Police','Ambulance','Fire Brigade') NOT NULL,
  `phone` int(12) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helpline`
--

INSERT INTO `helpline` (`Area`, `Service`, `phone`, `email`) VALUES
('Mirpur', 'Ambulance', 787, 'm@a'),
('Mirpur', 'Police', 4592, 'm@p'),
('Badda', 'Fire Brigade', 8255, 'bd@fire'),
('Badda', 'Ambulance', 123456, 'badd@am'),
('Natun Bazar', 'Ambulance', 839892, 'n@a'),
('Mirpur', 'Fire Brigade', 39399292, 'm@f'),
('Badda', 'Police', 1713373173, ' ocvatara@dmp.gov.bd'),
('Mirpur', 'Police', 1713373179, ''),
('Natun Bazar', 'Police', 1769058055, '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(6) UNSIGNED NOT NULL,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `first_name` varchar(30) NOT NULL,
  `notices` varchar(1500) NOT NULL,
  `images` longblob NOT NULL,
  `location` enum('Badda','Natun Bazar','Mirpur') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`first_name`, `notices`, `images`, `location`) VALUES
('01871035821', 'Do no go out side without any reason.', 0x363633306136313531393461665f686561742e6a706567, 'Natun Bazar'),
('01871035821', 'People are requested  if they want to come mirpur,be aware.Its a huge jam near Mirpur 11', 0x363633306233656362653465385f6a616d2e6a706567, 'Mirpur'),
('123', 'Heavy trafic in badda', 0x363635643362616438616435355f6261646464612e6a706567, 'Badda');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `user_id` varchar(255) NOT NULL,
  `content` varchar(250) NOT NULL,
  `location` enum('Natun Bazar','Badda','Mirpur') NOT NULL,
  `post_image` longblob NOT NULL,
  `thumbsup` int(11) NOT NULL,
  `timestamp_column` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mobile_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`user_id`, `content`, `location`, `post_image`, `thumbsup`, `timestamp_column`, `mobile_number`) VALUES
('Tashrif Rashid', 'Severe rainfall has resulted in significant flooding on the streets of Dhaka. Those planning to visit Mirpur should exercise caution as the roads have been compromised due to the heavy rain.', 'Mirpur', 0x363635636636313065656238392e6a7067, 0, '2024-06-02 22:45:36', 1760060543),
('Khokon ', 'Due to severe traffic congestion, it is advisable to choose alternative routes if traveling to Badda. Please avoid this area to prevent delays.', 'Badda', 0x363635636638613363363730612e6a7067, 0, '2024-06-02 22:56:35', 1755404808),
('faisal', 'heavey traffic mirpur\r\n', 'Mirpur', 0x363635643364666261363461392e6a7067, 0, '2024-06-03 03:52:27', 123);

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `ride_from` varchar(255) NOT NULL,
  `ride_to` varchar(255) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`id`, `service`, `amount`, `ride_from`, `ride_to`, `vehicle_number`, `first_name`, `mobile_number`) VALUES
(11, 'Uber', 80.00, 'Mirpur', 'Badda', 'Jhi45402', '', '1234'),
(12, 'Pathau', 80.00, 'Natunbazar', 'Badda', 'ere232', '', '1234'),
(13, 'Uber', 100.00, 'Mirpur', 'Badda', 'ffr454', '', '1234'),
(14, 'Pathau', 170.00, 'Badda', 'Natunbazar', 'vahoe', '', '1234'),
(15, 'Pathau', 85.00, 'Natunbazar', 'Mirpur', 'abc456', '', '74'),
(17, 'Uber', 50.00, 'Natunbazar', 'Mirpur', 'a12345', '', '01755404808'),
(18, 'Uber', 50.00, 'Mirpur', 'Natunbazar', '12345', '', '123400044');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`mobile_number`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_mobile` (`sender_mobile`),
  ADD KEY `receiver_mobile` (`receiver_mobile`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`mobile_number`);

--
-- Indexes for table `helpline`
--
ALTER TABLE `helpline`
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`sender_mobile`) REFERENCES `donors` (`mobile_number`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`receiver_mobile`) REFERENCES `donors` (`mobile_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
