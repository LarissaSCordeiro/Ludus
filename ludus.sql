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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (3,'Ação'),(5,'Aventura'),(8,'Cooperativo'),(9,'Corrida'),(11,'Esporte'),(7,'Estratégia'),(2,'Plataforma'),(4,'Puzzle'),(6,'RPG'),(12,'Tático'),(1,'Terror'),(10,'Tiro');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo`
--

LOCK TABLES `jogo` WRITE;
/*!40000 ALTER TABLE `jogo` DISABLE KEYS */;
INSERT INTO `jogo` VALUES (1,'Enigma do Medo','Estúdio BR Games','Lucas Andrade','Um jogo de terror psicológico ambientado no interior do Brasil.','img/jogos/enigma_med.jpg','2023-10-01',1),(2,'Sombras de Dandara','Indie Flame','Ana Beatriz','Plataforma 2D com movimentos inspirados na resistência quilombola.','img/jogos/default.png','2024-02-15',1),(3,'Adore','QUByte Interactive','Cadabra Games','Adore é um action RPG onde você invoca e controla criaturas em tempo real, com foco em estratégia e personalização do estilo de combate.','img/jogos/Adore.jpeg','2023-08-03',1),(4,'GROBO','Lucas Molina','Lucas Molina','Uma narrativa sutil sobre crescimento e superação, usando apenas formas e mecânicas simples.','img/jogos/grobo.png','2020-08-01',1),(5,'No Place for Bravery','Ysbryd Games','Glitch Factory','Uma história intensa sobre coragem, perda e redenção, ambientada em um mundo marcado pela guerra.','img/jogos/No Place for Bravery.jpg','2022-09-22',1),(6,'Unsighted','Studio Pixel Punk','Studio Pixel Punk','Em uma cidade tomada por robôs conscientes, cada segundo conta para impedir a escuridão.','img/jogos/Unsighted.avif','2021-09-30',1),(7,'Horizon Chase Turbo','Aquiris','Aquiris','Uma celebração acelerada aos clássicos dos anos 90, com curvas perfeitas e trilha empolgante.','img/jogos/Horizon Chase Turbo.webp','2018-05-15',1),(8,'ARIDA: Backland\'s Awakening','Aoca Game Lab','Aoca Game Lab','Explore a realidade do sertão e descubra a força de sobreviver em um lugar onde o tempo é mais severo que o clima.','img/jogos/ARIDA.jpg','2019-08-15',1),(9,'Kaze and the Wild Masks','PixelHive','PixelHive','Viaje por mundos vibrantes e enfrente desafios usando os poderes de antigas entidades mascaradas.','img/jogos/Kaze and the Wild Masks.jpeg','2021-03-26',1),(10,'Blazing Chrome','The Arcade Crew','JoyMasher','Parta em uma missão explosiva contra máquinas hostis em uma missão para recuperar o planeta.','img/jogos/Blazing Chrome.jpeg','2019-07-11',1),(11,'Elderand','Graffiti Games','Mantra / Sinergia Games','Em um mundo esquecido pelos deuses, encare monstros grotescos e paisagens arrepiantes em busca de glória.','img/jogos/Elderand.jpg','2023-02-16',1),(12,'Chroma Squad','Behold Studios','Behold Studios','Gerencie uma equipe de dublês que decide filmar sua própria série de ação colorida, enfrentando desafios dentro e fora das câmeras.','img/jogos/Chroma Squad.jpg','2015-04-30',1),(13,'Celeste','Matt Makes Games','Matt Thorson + Studio MiniBoss','Acompanhe a jornada de uma jovem que decide escalar uma montanha misteriosa — e seus próprios limites.','img/jogos/Celeste.png','2018-01-25',1),(14,'Out of Space','Behold Studios','Behold Studios','Uma mudança de casa vira um caos intergaláctico quando visitantes indesejados ocupam seu novo lar.','img/jogos/Out of Space.webp','2020-02-26',1),(15,'Dodgeball Academia','Pocket Trap','Pocket Trap','Entre em uma escola onde queimada é mais do que um esporte — é o centro de amizades, rivalidades e aventuras.','img/jogos/Dodgeball Academia.jpg','2021-08-05',1),(16,'Red Ronin','Wired Dreams Studio','Thiago Oliveira','Um combate frio e calculado acompanha a vingança de uma assassina que deixou de seguir ordens.','img/jogos/Red Ronin.jpg','2020-10-13',1),(17,'Tetragon','Cafundo Creative Studio','Cafundo Creative Studio','Mude a gravidade e molde o mundo ao seu redor enquanto persegue a verdade escondida entre dimensões.','img/jogos/Tetragon.jpg','2021-08-12',1),(18,'Spacelines from the Far Out','Coffeenauts','Coffeenauts','Transforme voos caóticos em experiências memoráveis enquanto administra sua empresa nas estrelas.','img/jogos/Spacelines from the Far Out.jpg','2022-06-07',1),(19,'Fobia: St. Dinfna Hotel','Maximum Games','Pulsatrix Studios','Você entra para investigar... mas logo percebe que o hotel esconde memórias e algo mais.','img/jogos/Fobia.png','2022-06-28',1),(20,'Hellclock','Wulkan Studios','Wulkan Studios','Um conflito ancestral conhecido como Guerra da Eternidade ecoa pelas noites silenciosas de uma cidade esquecida. Você acorda sem memória, com um relógio amaldiçoado preso ao pulso, e precisa atravessar batalhas fantasmagóricas entre dimensões para entender seu papel nesse ciclo sem fim.','img/jogos/Hellclock.webp','2022-10-18',1);
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
INSERT INTO `jogo_possui_genero` VALUES (1,1),(19,1),(20,1),(2,2),(4,2),(9,2),(13,2),(2,3),(3,3),(5,3),(6,3),(9,3),(10,3),(11,3),(20,3),(4,4),(16,4),(17,4),(3,5),(6,5),(8,5),(17,5),(5,6),(11,6),(12,6),(15,6),(12,7),(14,7),(18,7),(14,8),(18,8),(7,9),(10,10),(15,11),(16,12);
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
  KEY `fk_jogo_plataforma_plataforma` (`id_plataforma`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo_possui_plataforma`
--

LOCK TABLES `jogo_possui_plataforma` WRITE;
/*!40000 ALTER TABLE `jogo_possui_plataforma` DISABLE KEYS */;
INSERT INTO `jogo_possui_plataforma` VALUES (1,2),(2,1),(3,2),(3,3),(3,4),(3,7),(4,5),(4,6),(5,2),(5,7),(6,2),(6,7),(6,8),(6,9),(7,1),(7,2),(7,3),(7,4),(7,7),(8,2),(8,5),(8,6),(9,2),(9,3),(9,4),(9,7),(10,2),(10,7),(10,8),(10,9),(11,2),(11,7),(11,8),(11,9),(12,1),(12,2),(12,5),(12,6),(12,7),(12,8),(12,9),(13,1),(13,7),(13,8),(13,9),(13,10),(13,11),(14,2),(14,7),(14,8),(14,9),(15,2),(15,3),(15,4),(15,7),(16,1),(16,2),(16,3),(16,4),(16,7),(17,2),(17,3),(17,4),(17,7),(18,2),(18,3),(19,2),(19,3),(19,4),(20,2);
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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plataforma`
--

LOCK TABLES `plataforma` WRITE;
/*!40000 ALTER TABLE `plataforma` DISABLE KEYS */;
INSERT INTO `plataforma` VALUES (2,'PC'),(1,'Web'),(3,'Xbox'),(4,'Playstation'),(5,'Android'),(6,'iOS'),(7,'Nintendo Switch'),(8,'Xbox One'),(9,'Playstation 4'),(10,'macOS'),(11,'Linux');
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

--
-- Dumping events for database 'ludus'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-19 17:00:39
