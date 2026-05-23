-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27-Maio-2023 às 16:44
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `consult`
--
CREATE DATABASE IF NOT EXISTS `consult` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `consult`;


CREATE TABLE IF NOT EXISTS `departaments` (
  `id_departaments` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_departaments`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=UTF8MB4_UNICODE_CI;

CREATE TABLE `users` (
  `id_users` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_departament` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `type_user` int(11) NOT NULL DEFAULT 1,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_id_departament_foreign` (`id_departament`),
  CONSTRAINT `users_id_departament_foreign` FOREIGN KEY (`id_departament`) REFERENCES `departaments` (`id_departaments`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `consults` (
  `id_consults` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_users` bigint(20) unsigned NOT NULL,
  `id_departament` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
   status ENUM('pendente', 'confirmada', 'cancelada', 'realizada') DEFAULT 'pendente',
  `description` text DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_consults`),
  KEY `consults_id_users_foreign` (`id_users`),
  CONSTRAINT `consults_id_users_foreign` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY `consults_id_departament_foreign` (`id_departament`),
  CONSTRAINT `consults_id_departament_foreign` FOREIGN KEY (`id_departament`) REFERENCES `departaments` (`id_departaments`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
