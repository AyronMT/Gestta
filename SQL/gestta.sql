-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2025 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestta`
--

-- --------------------------------------------------------

--
-- Table structure for table `estoque`
--

CREATE TABLE `estoque` (
  `codigo` int(5) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `quantidade` int(50) NOT NULL,
  `preco` float(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estoque`
--

INSERT INTO `estoque` (`codigo`, `nome`, `quantidade`, `preco`) VALUES
(1, 'Chá Mate', 2, 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `servicos`
--

CREATE TABLE `servicos` (
  `NumeroOrdem` int(5) NOT NULL,
  `NomeCli` varchar(50) DEFAULT NULL,
  `Descricao` varchar(500) DEFAULT NULL,
  `datadeabertura` date DEFAULT NULL,
  `situacao` varchar(12) DEFAULT NULL,
  `produtos` text DEFAULT NULL,
  `Custo` float(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `servicos`
--

INSERT INTO `servicos` (`NumeroOrdem`, `NomeCli`, `Descricao`, `datadeabertura`, `situacao`, `produtos`, `Custo`) VALUES
(3, 'Miguel', 'A', '2025-09-20', 'Em andamento', '[{\"id\":\"1\",\"nome\":\"Ch\\u00e1 Mate\",\"quantidade\":3}]', 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `token_redefinicao` varchar(255) DEFAULT NULL,
  `token_expiracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `senha`, `nome`, `token_redefinicao`, `token_expiracao`) VALUES
(1, 'teste', '$2y$10$thQV5Ml737/VkIEUOTBHD.Y6bbLMzlR4s7wA0EIOMpgug1cdJl2Te', '', NULL, NULL),
(2, 'ayronmt@gmail.com', '$2y$10$tqBDZHgYSfFhFG38HtLMquBADLpi00d9prJYhX5tO/z4gHJS5Gfcm', 'Ayron Felype Dias da Silva', NULL, NULL),
(4, 'Daniel@email.com', '$2y$10$otl5AbJN6nGSStuzXSgIlervVGqrlS6bvxfAJ0/jyOAud6EScAvLy', 'Daniel Mercante Delphino de Oliveira', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`NumeroOrdem`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `estoque`
--
ALTER TABLE `estoque`
  MODIFY `codigo` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `servicos`
--
ALTER TABLE `servicos`
  MODIFY `NumeroOrdem` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
