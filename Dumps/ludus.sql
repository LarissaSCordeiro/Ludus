-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: ludus
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nota` decimal(2,1) NOT NULL,
  `data_avaliacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `texto` text,
  `id_jogo` int NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_avaliacao_jogo` (`id_jogo`),
  KEY `fk_avaliacao_usuario` (`id_usuario`),
  CONSTRAINT `fk_avaliacao_jogo` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id`),
  CONSTRAINT `fk_avaliacao_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  CONSTRAINT `avaliacao_chk_1` CHECK ((`nota` between 0 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentario`
--

DROP TABLE IF EXISTS `comentario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_comentario` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `texto` text NOT NULL,
  `id_usuario` int NOT NULL,
  `id_avaliacao` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comentario_usuario` (`id_usuario`),
  KEY `fk_comentario_avaliacao` (`id_avaliacao`),
  CONSTRAINT `fk_comentario_avaliacao` FOREIGN KEY (`id_avaliacao`) REFERENCES `avaliacao` (`id`),
  CONSTRAINT `fk_comentario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
/*!40000 ALTER TABLE `comentario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'ação'),(13,'arcade'),(2,'aventura'),(8,'furtivo'),(10,'metroidvania'),(5,'musical'),(3,'narrativa'),(6,'puzzle'),(7,'retro'),(11,'rpg'),(12,'simulação'),(9,'tático'),(4,'terror');
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jogo`
--

DROP TABLE IF EXISTS `jogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `estudio` varchar(100) DEFAULT NULL,
  `desenvolvedor` varchar(100) DEFAULT NULL,
  `descricao` text,
  `imagem` varchar(255) DEFAULT 'img/jogos/default.png',
  `data_lancamento` date DEFAULT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jogo_usuario` (`id_usuario`),
  CONSTRAINT `fk_jogo_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo`
--

LOCK TABLES `jogo` WRITE;
/*!40000 ALTER TABLE `jogo` DISABLE KEYS */;
INSERT INTO `jogo` VALUES (1,'A Lenda do Herói','Dumativa','Dumativa','A Lenda do Herói é um jogo de plataforma 2D com visual retrô, onde o personagem atravessa fases cheias de inimigos e obstáculos para salvar a princesa, tudo acompanhado por uma trilha sonora dinâmica criada pelos Castro Brothers que canta cada movimento em tempo real.','img/jogos/lenda-do-heroi.webp','2020-04-30',1),(2,'Enigma do Medo','Dumativa','Dumativa','Enigma do Medo é um jogo de investigação e terror com visual em pixel art e cenários 3D, inspirado na obra Ordem Paranormal criada por Cellbit. A protagonista Mia parte em busca do pai desaparecido ao lado de seu cão Lupi, explorando uma região tomada por fenômenos sobrenaturais e resolvendo enigmas pelo caminho.','img/jogos/enigma-do-medo.webp','2024-09-28',1),(3,'Shieldmaiden','Dumativa','Dumativa','Shieldmaiden é um jogo de ação e plataforma 2D com visual pixel art e clima cyberpunk. Asta, a protagonista, embarca em uma jornada por uma cidade devastada, enfrentando máquinas e usando um escudo energético para superar desafios.','img/jogos/shieldmaiden.jpg','2020-03-12',1),(4,'Dandara','Long Hat House','Long Hat House','Metroidvania brasileiro com arte pixelada e mecânicas de gravidade únicas.','img/jogos/dandara.jpg','2022-02-06',1),(5,'9Kings','Sad Socket','Sad Socket','Construtor de reinos roguelike com batalhas épicas e mecânicas de cartas.','img/jogos/9-kings.webp','2025-05-23',1),(6,'Gods of Sand','Sad Socket','Achenar Studios','Gerencie gladiadores em combates táticos por turnos em uma arena brutal.','img/jogos/gods-of-sand.jpeg','2022-02-07',1),(7,'Ending Tau','Sad Socket','Extra Small, Sad Socket','Roguelite de ação inspirado no folclore sul-americano, com sistema de maldições e transformação corporal.','img/jogos/ending-tau.jpg','2026-01-01',1),(8,'Seraphs\'s Last Stand','Sad Socket','Sad Socket','Roguelike arcade onde você cria builds insanas para enfrentar ondas infinitas de inimigos.','img/jogos/seraphs-last-stand.jpeg','2022-03-23',1);
/*!40000 ALTER TABLE `jogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jogo_possui_genero`
--

DROP TABLE IF EXISTS `jogo_possui_genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jogo_possui_genero` (
  `id_jogo` int NOT NULL,
  `id_genero` int NOT NULL,
  PRIMARY KEY (`id_jogo`,`id_genero`),
  KEY `fk_jogo_genero_genero` (`id_genero`),
  CONSTRAINT `fk_jogo_genero_genero` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id`),
  CONSTRAINT `fk_jogo_genero_jogo` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo_possui_genero`
--

LOCK TABLES `jogo_possui_genero` WRITE;
/*!40000 ALTER TABLE `jogo_possui_genero` DISABLE KEYS */;
INSERT INTO `jogo_possui_genero` VALUES (3,1),(4,1),(7,1),(8,1),(1,2),(2,2),(3,2),(4,2),(7,2),(1,3),(2,3),(3,3),(7,3),(2,4),(1,5),(1,6),(2,6),(4,6),(1,7),(3,7),(4,7),(8,7),(2,8),(5,9),(6,9),(7,9),(4,10),(4,11),(5,11),(6,11),(7,11),(8,11),(5,12),(6,12),(8,13);
/*!40000 ALTER TABLE `jogo_possui_genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jogo_possui_plataforma`
--

DROP TABLE IF EXISTS `jogo_possui_plataforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jogo_possui_plataforma` (
  `id_jogo` int NOT NULL,
  `id_plataforma` int NOT NULL,
  PRIMARY KEY (`id_jogo`,`id_plataforma`),
  KEY `fk_jogo_plataforma_plataforma` (`id_plataforma`),
  CONSTRAINT `fk_jogo_plataforma_jogo` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id`),
  CONSTRAINT `fk_jogo_plataforma_plataforma` FOREIGN KEY (`id_plataforma`) REFERENCES `plataforma` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo_possui_plataforma`
--

LOCK TABLES `jogo_possui_plataforma` WRITE;
/*!40000 ALTER TABLE `jogo_possui_plataforma` DISABLE KEYS */;
INSERT INTO `jogo_possui_plataforma` VALUES (1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(4,3),(4,4),(4,5),(4,6),(1,7),(3,7),(4,7),(8,7),(3,8),(4,8),(4,9),(1,10),(4,10),(1,11),(4,11),(6,11),(7,11),(8,11),(1,12),(2,12),(3,12),(4,12),(5,12),(6,12),(7,12),(8,12);
/*!40000 ALTER TABLE `jogo_possui_plataforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plataforma`
--

DROP TABLE IF EXISTS `plataforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plataforma` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plataforma`
--

LOCK TABLES `plataforma` WRITE;
/*!40000 ALTER TABLE `plataforma` DISABLE KEYS */;
INSERT INTO `plataforma` VALUES (5,'Android'),(6,'iOS'),(11,'Linux'),(10,'macOS'),(7,'Nintendo Switch'),(2,'PC'),(4,'Playstation'),(9,'Playstation 4'),(1,'Web'),(12,'Windows'),(3,'Xbox'),(8,'Xbox One');
/*!40000 ALTER TABLE `plataforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tipo` enum('jogador','desenvolvedor','administrador') NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT 'img/usuarios/default.png',
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'dev_teste','dev@email.com','desenvolvedor','1234senha_hashada','img/usuarios/default.png','2025-06-13 11:07:37');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_curte_avaliacao`
--

DROP TABLE IF EXISTS `usuario_curte_avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_curte_avaliacao` (
  `id_usuario` int NOT NULL,
  `id_avaliacao` int NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_avaliacao`),
  KEY `fk_curte_avaliacao` (`id_avaliacao`),
  CONSTRAINT `fk_curte_avaliacao` FOREIGN KEY (`id_avaliacao`) REFERENCES `avaliacao` (`id`),
  CONSTRAINT `fk_curte_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_curte_avaliacao`
--

LOCK TABLES `usuario_curte_avaliacao` WRITE;
/*!40000 ALTER TABLE `usuario_curte_avaliacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_curte_avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_favorita_jogo`
--

DROP TABLE IF EXISTS `usuario_favorita_jogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_favorita_jogo` (
  `id_usuario` int NOT NULL,
  `id_jogo` int NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_jogo`),
  KEY `fk_favorita_jogo` (`id_jogo`),
  CONSTRAINT `fk_favorita_jogo` FOREIGN KEY (`id_jogo`) REFERENCES `jogo` (`id`),
  CONSTRAINT `fk_favorita_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_favorita_jogo`
--

LOCK TABLES `usuario_favorita_jogo` WRITE;
/*!40000 ALTER TABLE `usuario_favorita_jogo` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_favorita_jogo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-17 18:28:00
