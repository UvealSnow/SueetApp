-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2016 at 10:01 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sueet`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_sessions`
--

DROP TABLE IF EXISTS `active_sessions`;
CREATE TABLE IF NOT EXISTS `active_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(5) NOT NULL,
  `iat` int(10) NOT NULL,
  `exp` int(10) NOT NULL,
  `jti` varchar(64) COLLATE utf8_bin NOT NULL,
  `state` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `active_sessions`
--

INSERT INTO `active_sessions` (`id`, `uid`, `iat`, `exp`, `jti`, `state`) VALUES
(2, 1, 1468779299, 1468782899, '75a1db3f956a64ab74a7292fbdd36c987bd5bfce292add4d16df2498e1b1563d', 1);

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

DROP TABLE IF EXISTS `amenities`;
CREATE TABLE IF NOT EXISTS `amenities` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `towerId` int(5) NOT NULL,
  `managerId` int(5) NOT NULL,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `opens` varchar(5) COLLATE utf8_bin NOT NULL,
  `closes` varchar(5) COLLATE utf8_bin NOT NULL,
  `reservable` tinyint(1) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `towerId`, `managerId`, `name`, `opens`, `closes`, `reservable`, `status`) VALUES
(1, 3, 1, 'Alberca', '07:00', '20:00', 0, 1),
(4, 2, 2, 'Cancha de Tennis', '07:00', '22:00', 1, 1),
(5, 3, 3, 'Cancha de Fútbol', '10:00', '22:00', 1, 1),
(6, 4, 2, 'SPA', '05:00', '16:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ownerId` int(5) NOT NULL,
  `brand` varchar(50) COLLATE utf8_bin NOT NULL,
  `model` varchar(50) COLLATE utf8_bin NOT NULL,
  `year` varchar(4) COLLATE utf8_bin NOT NULL,
  `color` varchar(5) COLLATE utf8_bin NOT NULL,
  `plates` varchar(20) COLLATE utf8_bin NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `ownerId`, `brand`, `model`, `year`, `color`, `plates`, `createdAt`) VALUES
(1, 1, 'Chevrolet', 'Colorado', '2006', 'WHT', 'XXX587X', '2016-07-02 15:40:34'),
(2, 3, 'Nissan', 'TIIDA', '2008', 'WHT', 'XXX1254X', '2016-07-02 15:42:18'),
(3, 2, 'Volkswagen', 'Volkswagen Tipo 1', '1994', 'WHT', 'XXX2365X', '2016-07-02 15:42:18');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ownerId` int(5) NOT NULL,
  `recieverId` int(5) NOT NULL,
  `groupId` int(5) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastMessage` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `towerId` int(5) NOT NULL,
  `shareId` int(5) DEFAULT NULL,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `createdAt` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `towerId`, `shareId`, `name`, `createdAt`) VALUES
(1, 3, NULL, 'ReglasEdificio.jpg', '2016-07-06 22:44:49'),
(2, 3, NULL, 'HorarioAlberca.png', '2016-07-06 22:45:53'),
(3, 3, NULL, 'AvisoReservaciones.pdf', '2016-07-06 22:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `doc_shares`
--

DROP TABLE IF EXISTS `doc_shares`;
CREATE TABLE IF NOT EXISTS `doc_shares` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `userId` int(5) NOT NULL,
  `groupId` int(5) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `flats`
--

DROP TABLE IF EXISTS `flats`;
CREATE TABLE IF NOT EXISTS `flats` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `towerId` int(5) NOT NULL,
  `ownerId` int(5) DEFAULT NULL,
  `number` int(5) NOT NULL,
  `maxResidents` int(2) NOT NULL DEFAULT '2',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `flats`
--

INSERT INTO `flats` (`id`, `towerId`, `ownerId`, `number`, `maxResidents`, `status`) VALUES
(1, 1, 3, 11, 2, 1),
(2, 1, NULL, 12, 2, 1),
(3, 1, NULL, 13, 2, 1),
(4, 1, NULL, 14, 2, 1),
(5, 2, NULL, 11, 2, 1),
(6, 2, NULL, 12, 2, 1),
(7, 2, NULL, 13, 2, 1),
(8, 2, NULL, 14, 2, 1),
(9, 16, NULL, 1, 2, 1),
(10, 16, NULL, 2, 2, 1),
(11, 16, NULL, 3, 2, 1),
(12, 16, NULL, 4, 2, 1),
(13, 16, NULL, 5, 2, 1),
(14, 16, NULL, 6, 2, 1),
(15, 16, NULL, 7, 2, 1),
(16, 16, NULL, 8, 2, 1),
(17, 17, NULL, 1, 2, 1),
(18, 17, NULL, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `managerId` int(5) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `createdAt` timestamp NOT NULL,
  `lastMember` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `senderId` int(5) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `parking_boxes`
--

DROP TABLE IF EXISTS `parking_boxes`;
CREATE TABLE IF NOT EXISTS `parking_boxes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `towerId` int(5) NOT NULL,
  `ownerId` int(5) NOT NULL,
  `assignedAt` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `dashboard` tinyint(1) NOT NULL,
  `units` tinyint(1) NOT NULL,
  `comms` tinyint(1) NOT NULL,
  `messages` tinyint(1) NOT NULL,
  `requests` tinyint(1) NOT NULL,
  `amenities` tinyint(1) NOT NULL,
  `workers` tinyint(1) NOT NULL,
  `documents` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `dashboard`, `units`, `comms`, `messages`, `requests`, `amenities`, `workers`, `documents`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 0, 1, 1, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `apptId` int(5) NOT NULL,
  `ownerId` int(5) NOT NULL,
  `areaId` int(5) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `amenityId` int(5) NOT NULL,
  `ownerId` int(5) NOT NULL,
  `starts` datetime NOT NULL,
  `ends` datetime NOT NULL,
  `reservedAt` timestamp NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `amenityId`, `ownerId`, `starts`, `ends`, `reservedAt`, `status`) VALUES
(1, 4, 1, '2016-08-24 10:00:00', '2016-08-26 11:00:00', '2016-07-06 02:03:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `permissionId` int(5) NOT NULL,
  `areaId` int(5) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `permissionId`, `areaId`, `name`) VALUES
(1, 1, 1, 'Admin'),
(2, 2, 2, 'Mantenimiento');

-- --------------------------------------------------------

--
-- Table structure for table `towers`
--

DROP TABLE IF EXISTS `towers`;
CREATE TABLE IF NOT EXISTS `towers` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `unitId` int(5) NOT NULL,
  `managerId` int(5) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `towers`
--

INSERT INTO `towers` (`id`, `unitId`, `managerId`, `name`, `status`) VALUES
(1, 3, 3, 'Torre Este', 1),
(2, 3, 3, 'Torre Oeste', 1),
(3, 1, 1, 'Torre 1', 1),
(4, 2, 2, 'Torre 1', 1),
(17, 21, 1, 'El Zaguan', 1),
(16, 21, 1, 'Nuva', 1);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `userId` int(5) NOT NULL,
  `type` varchar(3) COLLATE utf8_bin NOT NULL,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `locLan` varchar(20) COLLATE utf8_bin NOT NULL,
  `locLat` varchar(20) COLLATE utf8_bin NOT NULL,
  `street` varchar(150) COLLATE utf8_bin NOT NULL,
  `district` varchar(150) COLLATE utf8_bin NOT NULL,
  `city` varchar(100) COLLATE utf8_bin NOT NULL,
  `state` varchar(100) COLLATE utf8_bin NOT NULL,
  `zip` int(6) NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `createdAt` timestamp NOT NULL,
  `lastChange` timestamp NOT NULL,
  `img` varchar(60) COLLATE utf8_bin NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `userId`, `type`, `name`, `locLan`, `locLat`, `street`, `district`, `city`, `state`, `zip`, `description`, `createdAt`, `lastChange`, `img`, `status`) VALUES
(1, 1, 'res', 'Casa de Kevin', '22.135345458984375', '-101.038', 'Rincón del Parque #153', 'Rinconada de los Andes', 'San Luis Potosí', 'San Luis Potosí', 78218, 'Esta es la casa de Kevin, tieene tres pisos, hecha en un estilo cubista y la verdad está genial.', '2016-06-30 04:08:52', '2016-06-30 04:08:52', '75130d49c463334f805ee075786ee60d.jpg', 1),
(2, 2, 'res', 'Casa de Enrique', '22.134862899780273', '-101.041', 'Rincón de Trentino', 'Rinconada de los Andes', 'San Luis Potosí', 'San Luis Potosí', 78218, 'Esta es la cas de Enrique, es un poco más pequeña, pero en ella vive la misma cantidad de gente y un perro.', '2016-06-30 04:08:52', '2016-06-30 04:08:52', '', 1),
(3, 3, 'res', 'Complejo de departamentos Las Luces', '22.136266708374023', '-101.036', 'Cerro Las Trojes 113', 'Lomas 4ta', 'San Luis Potosí', 'San Luis Potosí', 78217, 'Complejo con 15 departamentos y vista a la montaña', '2016-07-02 15:05:54', '2016-07-02 15:05:54', '', 1),
(4, 3, 'res', 'Complejo Habitacional Arakán', '22.137968063354492', '-101.034', 'Av Cordillera Arakan 720', 'Lomas 4ta', 'San Luis Potosí', 'San Luis Potosí', 78217, 'Complejo con 5 departamentos', '2016-07-02 15:05:54', '2016-07-02 15:05:54', '', 1),
(21, 1, 'res', 'Nuva', '22.151236979837577', '-100.99120733642576', 'General Mariano Arista #1452', 'Centro', 'San Luis Potosi', 'SLP', 78001, 'Aqui trabajo', '2016-07-18 23:41:18', '2016-07-18 23:41:18', '75130d49c463334f805ee075786ee60f.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `flatId` int(5) DEFAULT NULL,
  `firstName` varchar(100) COLLATE utf8_bin NOT NULL,
  `lastName` varchar(100) COLLATE utf8_bin NOT NULL,
  `birthDay` int(2) NOT NULL,
  `birthMonth` int(2) NOT NULL,
  `birthYear` int(4) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `pass` varchar(60) COLLATE utf8_bin NOT NULL,
  `landLine` varchar(20) COLLATE utf8_bin NOT NULL,
  `cellPhone` varchar(20) COLLATE utf8_bin NOT NULL,
  `lastLogin` timestamp NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `flatId`, `firstName`, `lastName`, `birthDay`, `birthMonth`, `birthYear`, `sex`, `email`, `pass`, `landLine`, `cellPhone`, `lastLogin`, `status`) VALUES
(1, NULL, 'Kevin', 'Avila', 10, 11, 1992, 1, 'kevin@getsueet.com', '$2y$10$A2Imk5ldVxbNgk74CK4XkOhbBLE.K7P6u/9OLDlXaSLavhOa.k37a', '+524441982929', '+524448370848', '2016-06-30 03:56:59', 1),
(2, NULL, 'Enrique', 'Caballero', 12, 12, 1992, 1, 'enrique@getsueet.com', '$2y$10$erNjQJUylUEBEWG/QAoFHub3uBZSHRZqjn55Nk3LRwfoL2/P10oHK', '+524448252881', '+524441987845', '2016-06-30 04:03:30', 1),
(3, NULL, 'Seonyeong', 'Kim', 20, 11, 1992, 0, 'seon@telebyte.mx', '$2y$10$AmUJMrnvXUjJp1K7bgT8bu1aN9a5Zoid2aSxCruv9T1GdDiCLuwW6', '+524441982929', '+524448370848', '2016-06-30 04:14:33', 1),
(4, NULL, 'Juancho', 'Reparatodo', 15, 1, 1982, 1, 'juancho@getsueet.com', '$2y$10$RkJHgQkGYzOlg59zqY7J.eW9exkKZo9nN0IHDCcGYWe1uwOjI4ZCO', '524448579623', '524441253874', '2016-07-06 02:43:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

DROP TABLE IF EXISTS `workers`;
CREATE TABLE IF NOT EXISTS `workers` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `userId` int(5) NOT NULL,
  `unitId` int(5) NOT NULL,
  `roleId` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `userId`, `unitId`, `roleId`) VALUES
(1, 4, 1, 2),
(2, 1, 1, 1),
(3, 2, 2, 1),
(4, 3, 3, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
