-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: oficina_mecanica
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` (`id_cliente`, `nome`, `telefone`, `email`) VALUES (1,'Carlos Eduardo','(11) 9999-8888','carlos@email.com'),(2,'Mariana Souza','(11) 7777-6666','mariana@email.com');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mecanicos`
--

DROP TABLE IF EXISTS `mecanicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mecanicos` (
  `id_mecanico` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_mecanico`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mecanicos`
--

LOCK TABLES `mecanicos` WRITE;
/*!40000 ALTER TABLE `mecanicos` DISABLE KEYS */;
INSERT INTO `mecanicos` (`id_mecanico`, `nome`, `especialidade`) VALUES (1,'João Silva','Motor e Transmissão'),(2,'Maria Santos','Suspensão e Freios');
/*!40000 ALTER TABLE `mecanicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordens_servico`
--

DROP TABLE IF EXISTS `ordens_servico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordens_servico` (
  `id_os` int NOT NULL AUTO_INCREMENT,
  `id_veiculo` int NOT NULL,
  `data_abertura` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Aberta',
  `observacoes` text,
  PRIMARY KEY (`id_os`),
  KEY `id_veiculo` (`id_veiculo`),
  CONSTRAINT `ordens_servico_ibfk_1` FOREIGN KEY (`id_veiculo`) REFERENCES `veiculos` (`id_veiculo`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordens_servico`
--

LOCK TABLES `ordens_servico` WRITE;
/*!40000 ALTER TABLE `ordens_servico` DISABLE KEYS */;
INSERT INTO `ordens_servico` (`id_os`, `id_veiculo`, `data_abertura`, `status`, `observacoes`) VALUES (1,1,'2024-01-15','Concluída','Troca de óleo realizada'),(2,2,'2024-01-16','Em Andamento','Revisão de freios');
/*!40000 ALTER TABLE `ordens_servico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_mecanicos`
--

DROP TABLE IF EXISTS `os_mecanicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_mecanicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_os` int NOT NULL,
  `id_mecanico` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_os` (`id_os`),
  KEY `id_mecanico` (`id_mecanico`),
  CONSTRAINT `os_mecanicos_ibfk_1` FOREIGN KEY (`id_os`) REFERENCES `ordens_servico` (`id_os`) ON DELETE CASCADE,
  CONSTRAINT `os_mecanicos_ibfk_2` FOREIGN KEY (`id_mecanico`) REFERENCES `mecanicos` (`id_mecanico`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_mecanicos`
--

LOCK TABLES `os_mecanicos` WRITE;
/*!40000 ALTER TABLE `os_mecanicos` DISABLE KEYS */;
INSERT INTO `os_mecanicos` (`id`, `id_os`, `id_mecanico`) VALUES (1,1,1),(2,2,2);
/*!40000 ALTER TABLE `os_mecanicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_pecas`
--

DROP TABLE IF EXISTS `os_pecas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_pecas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_os` int NOT NULL,
  `id_peca` int NOT NULL,
  `quantidade` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_os` (`id_os`),
  KEY `id_peca` (`id_peca`),
  CONSTRAINT `os_pecas_ibfk_1` FOREIGN KEY (`id_os`) REFERENCES `ordens_servico` (`id_os`) ON DELETE CASCADE,
  CONSTRAINT `os_pecas_ibfk_2` FOREIGN KEY (`id_peca`) REFERENCES `pecas` (`id_peca`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_pecas`
--

LOCK TABLES `os_pecas` WRITE;
/*!40000 ALTER TABLE `os_pecas` DISABLE KEYS */;
INSERT INTO `os_pecas` (`id`, `id_os`, `id_peca`, `quantidade`) VALUES (1,1,1,1),(2,1,2,4);
/*!40000 ALTER TABLE `os_pecas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `os_servicos`
--

DROP TABLE IF EXISTS `os_servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `os_servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_os` int NOT NULL,
  `id_servico` int NOT NULL,
  `observacoes` text,
  PRIMARY KEY (`id`),
  KEY `id_os` (`id_os`),
  KEY `id_servico` (`id_servico`),
  CONSTRAINT `os_servicos_ibfk_1` FOREIGN KEY (`id_os`) REFERENCES `ordens_servico` (`id_os`) ON DELETE CASCADE,
  CONSTRAINT `os_servicos_ibfk_2` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id_servico`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `os_servicos`
--

LOCK TABLES `os_servicos` WRITE;
/*!40000 ALTER TABLE `os_servicos` DISABLE KEYS */;
INSERT INTO `os_servicos` (`id`, `id_os`, `id_servico`, `observacoes`) VALUES (1,1,1,NULL),(2,2,3,NULL);
/*!40000 ALTER TABLE `os_servicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pecas`
--

DROP TABLE IF EXISTS `pecas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pecas` (
  `id_peca` int NOT NULL AUTO_INCREMENT,
  `nome_peca` varchar(100) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `quantidade_estoque` int DEFAULT '0',
  PRIMARY KEY (`id_peca`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pecas`
--

LOCK TABLES `pecas` WRITE;
/*!40000 ALTER TABLE `pecas` DISABLE KEYS */;
INSERT INTO `pecas` (`id_peca`, `nome_peca`, `preco_unitario`, `quantidade_estoque`) VALUES (1,'Filtro de Óleo',25.00,50),(2,'Óleo Motor 5W30',45.00,100);
/*!40000 ALTER TABLE `pecas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicos`
--

DROP TABLE IF EXISTS `servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicos` (
  `id_servico` int NOT NULL AUTO_INCREMENT,
  `nome_servico` varchar(100) NOT NULL,
  `descricao` text,
  `preco_mao_obra` decimal(10,2) NOT NULL,
  `tempo_estimado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_servico`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicos`
--

LOCK TABLES `servicos` WRITE;
/*!40000 ALTER TABLE `servicos` DISABLE KEYS */;
INSERT INTO `servicos` (`id_servico`, `nome_servico`, `descricao`, `preco_mao_obra`, `tempo_estimado`) VALUES (1,'Troca de Óleo e Filtro','Troca completa de óleo do motor e filtro de óleo',80.00,'1 hora'),(2,'Alinhamento e Balanceamento','Alinhamento da direção e balanceamento das rodas',120.00,'1.5 horas'),(3,'Revisão de Freios','Troca de pastilhas, discos e fluido de freio',200.00,'2 horas');
/*!40000 ALTER TABLE `servicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `veiculos`
--

DROP TABLE IF EXISTS `veiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `veiculos` (
  `id_veiculo` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `ano` int DEFAULT NULL,
  `placa` varchar(10) NOT NULL,
  PRIMARY KEY (`id_veiculo`),
  UNIQUE KEY `placa` (`placa`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `veiculos`
--

LOCK TABLES `veiculos` WRITE;
/*!40000 ALTER TABLE `veiculos` DISABLE KEYS */;
INSERT INTO `veiculos` (`id_veiculo`, `id_cliente`, `marca`, `modelo`, `ano`, `placa`) VALUES (1,1,'Volkswagen','Gol',2020,'ABC1D23'),(2,2,'Fiat','Uno',2018,'DEF4G56');
/*!40000 ALTER TABLE `veiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'oficina_mecanica'
--

--
-- Dumping routines for database 'oficina_mecanica'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-05 16:06:49
