-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 05/05/2026 às 13:23
-- Versão do servidor: 11.8.6-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u719349953_ADMIN`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 'gui', '$2y$10$w8Tl0iwSBmcHFprigSNVJeRMXfKMm6YSSpDyL2LZyaG//aKr7USoS', 'guineife@hotmail.com', '2025-09-02 19:37:52', '2025-09-05 20:42:10');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'teste', 'test_user_123456@testuser.com', '11949294591', 'Avenida Cipriano Rodrigues,875', '2025-09-02 19:53:43', '2025-09-05 14:04:39'),
(2, 'Guilherme Neife', 'guilherme.neife@hotmail.com', '11949294591', '', '2025-09-03 14:57:09', '2025-09-03 14:57:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `client_logins`
--

CREATE TABLE `client_logins` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `client_logins`
--

INSERT INTO `client_logins` (`id`, `client_id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 2, 'neife', '$2y$10$1WNtnMOIZKYgkDScL5LkP.ADHGc.vy5I4KY4Wy4DpAGQVbl4Xn18K', '2025-09-03 14:57:09', '2025-09-03 14:57:09'),
(2, 1, 'teste', '$2y$10$1WNtnMOIZKYgkDScL5LkP.ADHGc.vy5I4KY4Wy4DpAGQVbl4Xn18K', '2025-09-03 14:57:09', '2025-09-03 14:57:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `client_payments`
--

CREATE TABLE `client_payments` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `gateway_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `client_payments`
--

INSERT INTO `client_payments` (`id`, `client_id`, `subscription_id`, `payment_id`, `payment_method`, `amount`, `status`, `gateway_response`, `created_at`, `updated_at`) VALUES
(1, 1, 18, '1324656554', 'unknown', 139.90, 'approved', 'false', '2025-09-04 20:23:42', '2025-09-04 20:23:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `client_sites`
--

CREATE TABLE `client_sites` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `db_servername` varchar(255) NOT NULL,
  `db_username` varchar(255) NOT NULL,
  `db_password` varchar(255) NOT NULL,
  `db_name` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `client_sites`
--

INSERT INTO `client_sites` (`id`, `client_id`, `site_name`, `site_url`, `db_servername`, `db_username`, `db_password`, `db_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'testes', 'https://hotpink-ibex-280696.hostingersite.com/', 'localhost', 'u719349953_SITE_HOSTINGER', '@Guilhermeneife1', 'u719349953_TESTE_SITE', 1, '2025-09-02 19:55:27', '2025-09-05 16:06:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `client_subscriptions`
--

CREATE TABLE `client_subscriptions` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `plan_id` varchar(50) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('pending','active','cancelled','expired') DEFAULT 'pending',
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `client_subscriptions`
--

INSERT INTO `client_subscriptions` (`id`, `client_id`, `plan_id`, `plan_name`, `price`, `status`, `payment_id`, `payment_status`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(18, 1, 'mensal_8_profissionais', 'Plano Mensal - 8 Profissionais', 139.90, 'active', '1324656554', NULL, '2025-09-04', '2025-10-04', '2025-09-04 20:13:32', '2025-09-05 15:10:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_site_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue','cancelled') DEFAULT 'pending',
  `plan_details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_customizations`
--

CREATE TABLE `site_customizations` (
  `id` int(11) NOT NULL,
  `client_site_id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `client_logins`
--
ALTER TABLE `client_logins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Índices de tabela `client_payments`
--
ALTER TABLE `client_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `subscription_id` (`subscription_id`);

--
-- Índices de tabela `client_sites`
--
ALTER TABLE `client_sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_url` (`site_url`),
  ADD KEY `client_id` (`client_id`);

--
-- Índices de tabela `client_subscriptions`
--
ALTER TABLE `client_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Índices de tabela `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `client_site_id` (`client_site_id`);

--
-- Índices de tabela `site_customizations`
--
ALTER TABLE `site_customizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_site_id` (`client_site_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `client_logins`
--
ALTER TABLE `client_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `client_payments`
--
ALTER TABLE `client_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `client_sites`
--
ALTER TABLE `client_sites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `client_subscriptions`
--
ALTER TABLE `client_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `site_customizations`
--
ALTER TABLE `site_customizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `client_logins`
--
ALTER TABLE `client_logins`
  ADD CONSTRAINT `client_logins_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `client_payments`
--
ALTER TABLE `client_payments`
  ADD CONSTRAINT `client_payments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_payments_ibfk_2` FOREIGN KEY (`subscription_id`) REFERENCES `client_subscriptions` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `client_sites`
--
ALTER TABLE `client_sites`
  ADD CONSTRAINT `client_sites_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `client_subscriptions`
--
ALTER TABLE `client_subscriptions`
  ADD CONSTRAINT `client_subscriptions_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`client_site_id`) REFERENCES `client_sites` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `site_customizations`
--
ALTER TABLE `site_customizations`
  ADD CONSTRAINT `site_customizations_ibfk_1` FOREIGN KEY (`client_site_id`) REFERENCES `client_sites` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
