CREATE DATABASE  IF NOT EXISTS `ludus` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ludus`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: ludus
-- ------------------------------------------------------
-- Server version	8.3.0

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'ação'),(13,'arcade'),(2,'aventura'),(21,'combate'),(15,'construção'),(25,'cooperativo'),(19,'estratégia'),(8,'furtivo'),(18,'luta'),(10,'metroidvania'),(16,'mundo aberto'),(5,'musical'),(3,'narrativa'),(24,'pixelado'),(22,'plataforma'),(6,'puzzle'),(7,'retro'),(14,'roguelike'),(23,'roguelite'),(11,'rpg'),(12,'simulação'),(17,'sobrevivência'),(9,'tático'),(4,'terror');
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo`
--

LOCK TABLES `jogo` WRITE;
/*!40000 ALTER TABLE `jogo` DISABLE KEYS */;
INSERT INTO `jogo` VALUES (1,'A Lenda do Herói','Dumativa','Dumativa','A Lenda do Herói é um jogo de plataforma 2D com visual retrô, onde o personagem atravessa fases cheias de inimigos e obstáculos para salvar a princesa, tudo acompanhado por uma trilha sonora dinâmica criada pelos Castro Brothers que canta cada movimento em tempo real.','img/jogos/lenda-do-heroi.webp','2020-04-30',1),(2,'Enigma do Medo','Dumativa','Dumativa','Enigma do Medo é um jogo de investigação e terror com visual em pixel art e cenários 3D, inspirado na obra Ordem Paranormal criada por Cellbit. A protagonista Mia parte em busca do pai desaparecido ao lado de seu cão Lupi, explorando uma região tomada por fenômenos sobrenaturais e resolvendo enigmas pelo caminho.','img/jogos/enigma-do-medo.webp','2024-09-28',1),(3,'Shieldmaiden','Dumativa','Dumativa','Shieldmaiden é um jogo de ação e plataforma 2D com visual pixel art e clima cyberpunk. Asta, a protagonista, embarca em uma jornada por uma cidade devastada, enfrentando máquinas e usando um escudo energético para superar desafios.','img/jogos/shieldmaiden.jpg','2020-03-12',1),(4,'Dandara','Long Hat House','Long Hat House','Metroidvania brasileiro com arte pixelada e mecânicas de gravidade únicas.','img/jogos/dandara.jpg','2022-02-06',1),(5,'9Kings','Sad Socket','Sad Socket','Construtor de reinos roguelike com batalhas épicas e mecânicas de cartas.','img/jogos/9-kings.webp','2025-05-23',1),(6,'Gods of Sand','Sad Socket','Achenar Studios','Gerencie gladiadores em combates táticos por turnos em uma arena brutal.','img/jogos/gods-of-sand.jpeg','2022-02-07',1),(7,'Ending Tau','Sad Socket','Extra Small, Sad Socket','Roguelite de ação inspirado no folclore sul-americano, com sistema de maldições e transformação corporal.','img/jogos/ending-tau.jpg','2026-01-01',1),(8,'Seraphs\'s Last Stand','Sad Socket','Sad Socket','Roguelike arcade onde você cria builds insanas para enfrentar ondas infinitas de inimigos.','img/jogos/seraphs-last-stand.jpeg','2022-03-23',1),(9,'Blazing Chrome','JoyMasher','JoyMasher','Ação retrô estilo Contra e Metal Slug com coop local. Os protagonistas são Mavra e Doyle, lutando contra uma dominação robótica.','img/jogos/blazing-chrome.jpeg','2019-07-11',1),(10,'Vengeful Guardian: Moonrider','JoyMasher','JoyMasher','Plataforma cyberpunk com estética 16-bit. O personagem principal é Moonrider, um supersoldado rebelde que desafia o regime opressor.','img/jogos/vengeful-guardian-moonrider.jpg','2023-01-12',1),(11,'Odallus: The Dark Call','JoyMasher','JoyMasher','Metroidvania sombrio com ambientação gótica. O herói Haggis enfrenta criaturas demoníacas para salvar seu filho e sua vila.','img/jogos/odallus-the-dark-call.jpg','2015-07-15',1),(12,'Oniken','JoyMasher','JoyMasher','Ação estilo Ninja Gaiden. O protagonista Zaku luta contra uma corporação militar que domina o mundo pós-apocalíptico.','img/jogos/oniken.webp','2012-06-14',1),(13,'Adore','Cadabra Games','Cadabra Games','ARPG com invocação de criaturas. O protagonista Lukha canaliza espíritos para lutar contra forças corrompidas e restaurar o equilíbrio.','img/jogos/adore.webp','2023-08-03',1),(14,'Red Ronin','Wired Dreams Studio','Wired Dreams Studio','Jogo tático de ação. Red é uma samurai cibernética em busca de vingança contra seu antigo grupo, enfrentando dilemas morais e inimigos brutais.','img/jogos/red-ronin.jpg','2021-03-17',1),(15,'Upaon: A Snake\'s Journey','Black River Studios','Black River Studios','Puzzle inspirado em Snake. Upaon é uma cobra que precisa restaurar o equilíbrio da natureza em cenários inspirados na cultura brasileira.','img/jogos/upaon-a-snakes-journey.webp','2023-06-15',1),(16,'Raven\'s Hike','Wired Dreams Studio','Wired Dreams Studio','Plataforma de precisão. Raven é uma exploradora que usa um gancho para atravessar cavernas perigosas sem pular, desafiando a gravidade.','img/jogos/ravens-hike.jpeg','2022-04-20',1),(17,'Undergrave','Wired Dreams Studio','Wired Dreams Studio','Roguelike tático. Um guerreiro revive em um mundo sombrio e precisa usar habilidades estratégicas para sobreviver a cada sala.','img/jogos/undergrave.jpeg','2022-09-01',1),(18,'FROGUE','Wired Dreams Studio','Wired Dreams Studio','Bullet-time roguelike. Um sapo ninja enfrenta inimigos em câmera lenta, usando teletransporte e reflexos para vencer arenas desafiadoras.','img/jogos/frogue.jpg','2023-08-14',1),(19,'Kaze and the Wild Masks','SOEDESCO','PixelHive','Jogo de plataforma inspirado nos clássicos dos anos 90, com máscaras que concedem poderes especiais.','img/jogos/kaze-and-the-wild-masks.webp','2021-03-26',1),(20,'Guns N\' Runs','Statera Studio','Statera Studio','Jogo de ação e plataforma com dashs frenéticos e 200 desafios, inspirado em Mega Man e Metal Slug.','img/jogos/guns-n-runs.png','2021-03-30',1),(21,'Pocket Bravery','Statera Studio','Statera Studio','Jogo de luta 2D em pixel art com estética chibi e inspirado em clássicos como Street Fighter e KOF.','img/jogos/pocket-bravery.webp','2023-08-31',1),(22,'Arashi Gaiden','Nuntius Games','Statera Studio, Wired Dreams Studio','Jogo de ação por turnos estilo dash and slash, ambientado no Japão, com combate estratégico.','img/jogos/arashi-gaiden.jpg','2025-07-14',1),(23,'Super Chicken Jumper','Sewer Cat','Sewer Cat, Heavy Sheep','Runner/shooter onde uma galinha espiã salva o mundo com ajuda de garotas anime e cogumelos perigosos.','img/jogos/super-chicken-jumper.jpeg','2021-09-24',1),(24,'Sand Bullets','Duaik','Duaik, Reload Studios','Shooter top-down ambientado em mundo pós-apocalíptico onde se luta por água em um parque abandonado.','img/jogos/sand-bullets.jpg','2022-10-22',1),(25,'Aritana e a pena da harpia','Duaik Entretenimento','Duaik Entretenimento','Plataforma 2.5D com mecânica de postura e combos, inspirado na cultura indígena brasileira.','img/jogos/aritana-e-a-pena-da-harpia.jpg','2014-08-15',1),(26,'Aritana and the Twin Masks','Duaik Entretenimento','Duaik Entretenimento','Plataforma 3D com exploração e combate, onde Aritana busca salvar a Árvore da Vida com arco e flechas.','img/jogos/aritana-and-the-twin-masks.jpeg','2019-08-16',1),(27,'Bad Rats: The Rats\' Revenge','Invent4 Entertainment','Invent4 Entertainment','Jogo de quebra-cabeça baseado em física onde ratos se vingam de gatos com armadilhas criativas.','img/jogos/bad-rats.jpg','2009-07-20',1),(28,'Razor2: Hidden Skies','Invent4 Entertainment','Invent4 Entertainment','Shoot \'em up brasileiro com trilha orquestrada e combates intensos contra chefes.','img/jogos/razor-2-hidden-skies.webp','2010-07-19',1),(29,'Bridge Project','THQ Nordic','Halycon Media GmbH & Co. KG','Simulador de construção de pontes com física realista e diversos tipos de estruturas.','img/jogos/bridge-project.webp','2013-03-28',1),(30,'Fat Rat Pinball','Invent4 Entertainment','Invent4 Entertainment','Um jogo de Pinball onde um rato gordo é lançado, o jogo possui varios personagens inclusive uma bola branca, que podem ser desbloqueados com rubis coletados durante o jogo.','img/jogos/fat-rat-pinball.jpg','2017-06-29',1),(31,'Gaucho and the Grassland','Epopeia Games','Epopeia Games','Simulador de fazenda com elementos de aventura e folclore gaúcho, incluindo criaturas místicas.','img/jogos/gaucho-and-the-grassland.webp','2025-07-16',1),(32,'Goroons','Epopeia Games','Epopeia Games','Jogo cooperativo de quebra-cabeça com criaturas metamórficas e foco em trabalho em equipe.','img/jogos/goroons.jpg','2018-05-17',1),(33,'IIN','Epopeia Games','Epopeia Games','Jogo de plataforma minimalista com foco em física e cooperação entre formas geométricas.','img/jogos/iin.jpg','2023-04-06',1),(34,'Mullet MadJack','Epopeia Games','Hammer95 Studios','FPS roguelike inspirado em animes dos anos 80, com ação frenética e estética retrô.','img/jogos/mullet-madjack.jpg','2024-05-15',1),(35,'Heavy Bullets','Devolver Digital','Terri Vellmann','FPS roguelike com munição reciclável e estética neon, onde cada morte reinicia o progresso.','img/jogos/heavy-bullets.webp','2014-09-18',1),(36,'Mundo Lixo',NULL,'Terri Vellmann','Um jogo em primeira pessoa sem objetivos onde se pode explorar um mundo imerso em lixo que não tem fim, e sair na hora que quiser.','img/jogos/mundo-lixo.jpg','2016-03-20',1),(37,'High Hell','Devolver Digital','Terri Vellmann, Doseone','FPS arcade com estética neon e missões absurdas contra um cartel criminoso.','img/jogos/high-hell.jpg','2017-10-23',1),(38,'SLUDGE LIFE','Devolver Digital','Terri Vellmann, Doseone','Simulador de vandalismo em mundo aberto com humor ácido e crítica à sociedade corporativa.','img/jogos/sludge-life.webp','2020-05-28',1),(39,'SLUDGE LIFE: The BIG MUD SESSIONS','Devolver Digital','Terri Vellmann, Doseone','Interlúdio gratuito entre os dois Sludge Life, com missão curta e estética surreal.','img/jogos/sludge-life-the-big-mud-sessions.jpg','2023-08-02',1),(40,'SLUDGE LIFE 2','Devolver Digital','Terri Vellmann, Doseone','Continuação do simulador de vandalismo, com novos itens, personagens e trilhas sonoras.','img/jogos/sludge-life-2.png','2023-06-27',1),(41,'GUNCHO','Arnold Rauers','Arnold Rauers, Terri Vellmann, Sam Webster','Roguelike tático ambientado no Velho Oeste com mecânica de tiro baseada em cilindro de revólver.','img/jogos/guncho.avif','2024-06-25',1),(42,'TELEFORUM','Monumental Collab','Monumental Collab','Jogo de terror analógico com narrativa investigativa sobre um caso misterioso envolvendo mídia.','img/jogos/teleforum.jpg','2023-10-19',1),(43,'Soulstone Survivors','Game Smithing','Game Smithing','Um jogo onde você elimina hordas de inimigos, luta contra chefes colossais e trilha seu caminho para um poder divino. Como um Caçador do Vazio, domine habilidades devastadoras, crie armas únicas e desbloqueie sinergias revolucionárias em sua busca por poder.','img/jogos/soulstone-survivors.jpg','2025-06-17',1);
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
INSERT INTO `jogo_possui_genero` VALUES (3,1),(4,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(16,1),(17,1),(18,1),(19,1),(20,1),(22,1),(23,1),(24,1),(28,1),(34,1),(35,1),(37,1),(41,1),(43,1),(1,2),(2,2),(3,2),(4,2),(7,2),(10,2),(11,2),(14,2),(15,2),(16,2),(19,2),(25,2),(26,2),(31,2),(32,2),(38,2),(1,3),(2,3),(3,3),(7,3),(42,3),(2,4),(23,4),(42,4),(1,5),(1,6),(2,6),(4,6),(11,6),(14,6),(15,6),(16,6),(27,6),(29,6),(32,6),(33,6),(1,7),(3,7),(4,7),(8,7),(9,7),(10,7),(11,7),(12,7),(15,7),(19,7),(21,7),(34,7),(2,8),(20,8),(35,8),(38,8),(39,8),(40,8),(5,9),(6,9),(7,9),(14,9),(17,9),(18,9),(41,9),(4,10),(11,10),(19,10),(25,10),(26,10),(4,11),(5,11),(6,11),(7,11),(8,11),(13,11),(5,12),(6,12),(29,12),(36,12),(38,12),(39,12),(40,12),(8,13),(9,13),(10,13),(12,13),(14,13),(16,13),(17,13),(18,13),(20,13),(23,13),(28,13),(30,13),(34,13),(37,13),(41,13),(5,14),(8,14),(17,14),(18,14),(34,14),(35,14),(41,14),(29,15),(24,16),(31,16),(36,16),(38,16),(39,16),(40,16),(24,17),(31,17),(21,18),(22,18),(43,18),(14,19),(16,19),(27,19),(29,19),(30,19),(6,21),(13,21),(22,21),(28,21),(43,21),(10,22),(11,22),(12,22),(15,22),(16,22),(19,22),(20,22),(33,22),(7,23),(43,23),(1,24),(2,24),(3,24),(4,24),(21,24),(32,25);
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
INSERT INTO `jogo_possui_plataforma` VALUES (41,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2),(41,2),(42,2),(43,2),(4,3),(9,3),(10,3),(22,3),(23,3),(32,3),(33,3),(34,3),(43,3),(4,4),(9,4),(10,4),(11,4),(12,4),(22,4),(23,4),(32,4),(43,4),(4,5),(13,5),(14,5),(15,5),(16,5),(17,5),(18,5),(23,5),(30,5),(41,5),(4,6),(13,6),(14,6),(15,6),(16,6),(17,6),(18,6),(30,6),(41,6),(1,7),(3,7),(4,7),(8,7),(9,7),(10,7),(11,7),(12,7),(13,7),(14,7),(15,7),(16,7),(17,7),(18,7),(19,7),(20,7),(21,7),(22,7),(23,7),(32,7),(33,7),(38,7),(3,8),(4,8),(9,8),(10,8),(19,8),(20,8),(21,8),(22,8),(23,8),(25,8),(26,8),(32,8),(34,8),(4,9),(9,9),(10,9),(11,9),(12,9),(19,9),(20,9),(21,9),(22,9),(23,9),(26,9),(32,9),(1,10),(4,10),(25,10),(32,10),(33,10),(35,10),(36,10),(37,10),(41,10),(42,10),(43,10),(1,11),(4,11),(6,11),(7,11),(8,11),(32,11),(33,11),(35,11),(36,11),(43,11),(1,12),(2,12),(3,12),(4,12),(5,12),(6,12),(7,12),(8,12),(9,12),(10,12),(11,12),(12,12),(13,12),(14,12),(15,12),(16,12),(17,12),(18,12),(19,12),(20,12),(21,12),(22,12),(23,12),(24,12),(25,12),(26,12),(27,12),(28,12),(29,12),(31,12),(32,12),(33,12),(34,12),(35,12),(36,12),(37,12),(38,12),(39,12),(40,12),(41,12),(42,12),(43,12);
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'dev_teste','dev@email.com','desenvolvedor','1234senha_hashada','img/usuarios/default.png','2025-06-13 11:07:37'),(2,'illpirox','pierrehenriquedemirandaneppel@gmail.com','desenvolvedor','$2y$10$LAxsD3Ixd34bq2pmt6w.ZeNyE/jto2GmCUtOWZ6vnWdjNj3.l.qTe','img/usuarios/gatofofo.jpg','2025-09-11 17:11:43');
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

-- Dump completed on 2025-09-18 20:04:29
