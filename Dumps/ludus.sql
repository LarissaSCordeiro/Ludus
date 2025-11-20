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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
INSERT INTO `avaliacao` VALUES (1,5.0,'2025-09-24 12:26:45',NULL,1,13),(5,5.0,'2025-11-12 09:13:17',NULL,2,13),(6,5.0,'2025-11-13 20:32:29',NULL,2,20),(7,5.0,'2025-11-17 19:56:45',NULL,2,21),(8,5.0,'2025-11-18 19:56:49',NULL,2,22);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentario`
--

LOCK TABLES `comentario` WRITE;
/*!40000 ALTER TABLE `comentario` DISABLE KEYS */;
INSERT INTO `comentario` VALUES (1,'2025-09-24 12:26:58','Esse jogo é de fato muito bom!',13,1),(5,'2025-11-17 19:56:55','Jogo muito bom!!!',21,7),(6,'2025-11-18 19:57:02','Jogo muito legal de verdade!!!!',22,8);
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'Ação'),(13,'Arcade'),(2,'Aventura'),(26,'Casual'),(21,'Combate'),(15,'Construção'),(25,'Cooperativo'),(19,'Estratégia'),(8,'Furtivo'),(18,'Luta'),(10,'Metroidvania'),(16,'Mundo aberto'),(5,'Musical'),(3,'Narrativa'),(24,'Pixelado'),(22,'Plataforma'),(6,'Puzzle'),(7,'Retro'),(14,'Roguelike'),(23,'Roguelite'),(11,'RPG'),(12,'Simulação'),(17,'Sobrevivência'),(9,'Tático'),(4,'Terror');
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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jogo`
--

LOCK TABLES `jogo` WRITE;
/*!40000 ALTER TABLE `jogo` DISABLE KEYS */;
INSERT INTO `jogo` VALUES (1,'A Lenda do Herói','Dumativa','Dumativa','A Lenda do Herói é um jogo de plataforma 2D com visual retrô, onde o personagem atravessa fases cheias de inimigos e obstáculos para salvar a princesa, tudo acompanhado por uma trilha sonora dinâmica criada pelos Castro Brothers que canta cada movimento em tempo real.','img/jogos/lenda-do-heroi.webp','2020-04-30',1),(2,'Enigma do Medo','Dumativa','Dumativa','Enigma do Medo é um jogo de investigação e terror com visual em pixel art e cenários 3D, inspirado na obra Ordem Paranormal criada por Cellbit. A protagonista Mia parte em busca do pai desaparecido ao lado de seu cão Lupi, explorando uma região tomada por fenômenos sobrenaturais e resolvendo enigmas pelo caminho.','img/jogos/enigma-do-medo.webp','2024-09-28',1),(3,'Shieldmaiden','Dumativa','Dumativa','Shieldmaiden é um jogo de ação e plataforma 2D com visual pixel art e clima cyberpunk. Asta, a protagonista, embarca em uma jornada por uma cidade devastada, enfrentando máquinas e usando um escudo energético para superar desafios.','img/jogos/shieldmaiden.jpg','2020-03-12',1),(4,'Dandara','Long Hat House','Long Hat House','Metroidvania brasileiro com arte pixelada e mecânicas de gravidade únicas.','img/jogos/dandara.jpg','2022-02-06',1),(5,'9Kings','Sad Socket','Sad Socket','Construtor de reinos roguelike com batalhas épicas e mecânicas de cartas.','img/jogos/9-kings.webp','2025-05-23',1),(6,'Gods of Sand','Sad Socket','Achenar Studios','Gerencie gladiadores em combates táticos por turnos em uma arena brutal.','img/jogos/gods-of-sand.jpeg','2022-02-07',1),(7,'Ending Tau','Sad Socket','Extra Small, Sad Socket','Roguelite de ação inspirado no folclore sul-americano, com sistema de maldições e transformação corporal.','img/jogos/ending-tau.jpg','2026-01-01',1),(8,'Seraphs\'s Last Stand','Sad Socket','Sad Socket','Roguelike arcade onde você cria builds insanas para enfrentar ondas infinitas de inimigos.','img/jogos/seraphs-last-stand.jpeg','2022-03-23',1),(9,'Blazing Chrome','JoyMasher','JoyMasher','Ação retrô estilo Contra e Metal Slug com coop local. Os protagonistas são Mavra e Doyle, lutando contra uma dominação robótica.','img/jogos/blazing-chrome.jpeg','2019-07-11',1),(10,'Vengeful Guardian: Moonrider','JoyMasher','JoyMasher','Plataforma cyberpunk com estética 16-bit. O personagem principal é Moonrider, um supersoldado rebelde que desafia o regime opressor.','img/jogos/vengeful-guardian-moonrider.jpg','2023-01-12',1),(11,'Odallus: The Dark Call','JoyMasher','JoyMasher','Metroidvania sombrio com ambientação gótica. O herói Haggis enfrenta criaturas demoníacas para salvar seu filho e sua vila.','img/jogos/odallus-the-dark-call.jpg','2015-07-15',1),(12,'Oniken','JoyMasher','JoyMasher','Ação estilo Ninja Gaiden. O protagonista Zaku luta contra uma corporação militar que domina o mundo pós-apocalíptico.','img/jogos/oniken.webp','2012-06-14',1),(13,'Adore','Cadabra Games','Cadabra Games','ARPG com invocação de criaturas. O protagonista Lukha canaliza espíritos para lutar contra forças corrompidas e restaurar o equilíbrio.','img/jogos/adore.webp','2023-08-03',1),(14,'Red Ronin','Wired Dreams Studio','Wired Dreams Studio','Jogo tático de ação. Red é uma samurai cibernética em busca de vingança contra seu antigo grupo, enfrentando dilemas morais e inimigos brutais.','img/jogos/red-ronin.jpg','2021-03-17',1),(15,'Upaon: A Snake\'s Journey','Black River Studios','Black River Studios','Puzzle inspirado em Snake. Upaon é uma cobra que precisa restaurar o equilíbrio da natureza em cenários inspirados na cultura brasileira.','img/jogos/upaon-a-snakes-journey.webp','2023-06-15',1),(16,'Raven\'s Hike','Wired Dreams Studio','Wired Dreams Studio','Plataforma de precisão. Raven é uma exploradora que usa um gancho para atravessar cavernas perigosas sem pular, desafiando a gravidade.','img/jogos/ravens-hike.jpeg','2022-04-20',1),(17,'Undergrave','Wired Dreams Studio','Wired Dreams Studio','Roguelike tático. Um guerreiro revive em um mundo sombrio e precisa usar habilidades estratégicas para sobreviver a cada sala.','img/jogos/undergrave.jpeg','2022-09-01',1),(18,'FROGUE','Wired Dreams Studio','Wired Dreams Studio','Bullet-time roguelike. Um sapo ninja enfrenta inimigos em câmera lenta, usando teletransporte e reflexos para vencer arenas desafiadoras.','img/jogos/frogue.jpg','2023-08-14',1),(19,'Kaze and the Wild Masks','SOEDESCO','PixelHive','Jogo de plataforma inspirado nos clássicos dos anos 90, com máscaras que concedem poderes especiais.','img/jogos/kaze-and-the-wild-masks.webp','2021-03-26',1),(20,'Guns N\' Runs','Statera Studio','Statera Studio','Jogo de ação e plataforma com dashs frenéticos e 200 desafios, inspirado em Mega Man e Metal Slug.','img/jogos/guns-n-runs.png','2021-03-30',1),(21,'Pocket Bravery','Statera Studio','Statera Studio','Jogo de luta 2D em pixel art com estética chibi e inspirado em clássicos como Street Fighter e KOF.','img/jogos/pocket-bravery.webp','2023-08-31',1),(22,'Arashi Gaiden','Nuntius Games','Statera Studio, Wired Dreams Studio','Jogo de ação por turnos estilo dash and slash, ambientado no Japão, com combate estratégico.','img/jogos/arashi-gaiden.jpg','2025-07-14',1),(23,'Super Chicken Jumper','Sewer Cat','Sewer Cat, Heavy Sheep','Runner/shooter onde uma galinha espiã salva o mundo com ajuda de garotas anime e cogumelos perigosos.','img/jogos/super-chicken-jumper.jpeg','2021-09-24',1),(24,'Sand Bullets','Duaik','Duaik, Reload Studios','Shooter top-down ambientado em mundo pós-apocalíptico onde se luta por água em um parque abandonado.','img/jogos/sand-bullets.jpg','2022-10-22',1),(25,'Aritana e a pena da harpia','Duaik Entretenimento','Duaik Entretenimento','Plataforma 2.5D com mecânica de postura e combos, inspirado na cultura indígena brasileira.','img/jogos/aritana-e-a-pena-da-harpia.jpg','2014-08-15',1),(26,'Aritana and the Twin Masks','Duaik Entretenimento','Duaik Entretenimento','Plataforma 3D com exploração e combate, onde Aritana busca salvar a Árvore da Vida com arco e flechas.','img/jogos/aritana-and-the-twin-masks.jpeg','2019-08-16',1),(27,'Bad Rats: The Rats\' Revenge','Invent4 Entertainment','Invent4 Entertainment','Jogo de quebra-cabeça baseado em física onde ratos se vingam de gatos com armadilhas criativas.','img/jogos/bad-rats.jpg','2009-07-20',1),(28,'Razor2: Hidden Skies','Invent4 Entertainment','Invent4 Entertainment','Shoot \'em up brasileiro com trilha orquestrada e combates intensos contra chefes.','img/jogos/razor-2-hidden-skies.webp','2010-07-19',1),(29,'Bridge Project','THQ Nordic','Halycon Media GmbH & Co. KG','Simulador de construção de pontes com física realista e diversos tipos de estruturas.','img/jogos/bridge-project.webp','2013-03-28',1),(30,'Fat Rat Pinball','Invent4 Entertainment','Invent4 Entertainment','Um jogo de Pinball onde um rato gordo é lançado, o jogo possui varios personagens inclusive uma bola branca, que podem ser desbloqueados com rubis coletados durante o jogo.','img/jogos/fat-rat-pinball.jpg','2017-06-29',1),(31,'Gaucho and the Grassland','Epopeia Games','Epopeia Games','Simulador de fazenda com elementos de aventura e folclore gaúcho, incluindo criaturas místicas.','img/jogos/gaucho-and-the-grassland.webp','2025-07-16',1),(32,'Goroons','Epopeia Games','Epopeia Games','Jogo cooperativo de quebra-cabeça com criaturas metamórficas e foco em trabalho em equipe.','img/jogos/goroons.jpg','2018-05-17',1),(33,'IIN','Epopeia Games','Epopeia Games','Jogo de plataforma minimalista com foco em física e cooperação entre formas geométricas.','img/jogos/iin.jpg','2023-04-06',1),(34,'Mullet MadJack','Epopeia Games','Hammer95 Studios','FPS roguelike inspirado em animes dos anos 80, com ação frenética e estética retrô.','img/jogos/mullet-madjack.jpg','2024-05-15',1),(35,'Heavy Bullets','Devolver Digital','Terri Vellmann','FPS roguelike com munição reciclável e estética neon, onde cada morte reinicia o progresso.','img/jogos/heavy-bullets.webp','2014-09-18',1),(36,'Mundo Lixo',NULL,'Terri Vellmann','Um jogo em primeira pessoa sem objetivos onde se pode explorar um mundo imerso em lixo que não tem fim, e sair na hora que quiser.','img/jogos/mundo-lixo.jpg','2016-03-20',1),(37,'High Hell','Devolver Digital','Terri Vellmann, Doseone','FPS arcade com estética neon e missões absurdas contra um cartel criminoso.','img/jogos/high-hell.jpg','2017-10-23',1),(38,'SLUDGE LIFE','Devolver Digital','Terri Vellmann, Doseone','Simulador de vandalismo em mundo aberto com humor ácido e crítica à sociedade corporativa.','img/jogos/sludge-life.webp','2020-05-28',1),(39,'SLUDGE LIFE: The BIG MUD SESSIONS','Devolver Digital','Terri Vellmann, Doseone','Interlúdio gratuito entre os dois Sludge Life, com missão curta e estética surreal.','img/jogos/sludge-life-the-big-mud-sessions.jpg','2023-08-02',1),(40,'SLUDGE LIFE 2','Devolver Digital','Terri Vellmann, Doseone','Continuação do simulador de vandalismo, com novos itens, personagens e trilhas sonoras.','img/jogos/sludge-life-2.png','2023-06-27',1),(41,'GUNCHO','Arnold Rauers','Arnold Rauers, Terri Vellmann, Sam Webster','Roguelike tático ambientado no Velho Oeste com mecânica de tiro baseada em cilindro de revólver.','img/jogos/guncho.avif','2024-06-25',1),(42,'TELEFORUM','Monumental Collab','Monumental Collab','Jogo de terror analógico com narrativa investigativa sobre um caso misterioso envolvendo mídia.','img/jogos/teleforum.jpg','2023-10-19',1),(43,'Soulstone Survivors','Game Smithing','Game Smithing','Um jogo onde você elimina hordas de inimigos, luta contra chefes colossais e trilha seu caminho para um poder divino. Como um Caçador do Vazio, domine habilidades devastadoras, crie armas únicas e desbloqueie sinergias revolucionárias em sua busca por poder.','img/jogos/soulstone-survivors.jpg','2025-06-17',1),(44,'Wishing Sarah','Asteristic Game Studio','Asteristic Game Studio','Depois de ficar em coma, Sarah finalmente acorda e percebe algo estranho sobre o mundo em que vive agora. Ela não consegue entender muito bem o que está acontecendo, e você deve ajudá-la a atravessar esse novo mundo — cheio de personagens e eventos intrigantes.','img/jogos/wishing-sarah.webp','2019-10-29',1),(45,'Dandy & Randy DX','Ratalaika Games','Asteristic Game Studio','Dandy and Randy DX é um jogo arcade 2D, top-down, ao estilo clássico, estrelado por dois dos piores arqueólogos que já pisaram na Terra. Ajude-os em suas aventuras encontrando tesouros, derrotando inimigos, enfrentando chefes e descobrindo segredos! Será que eles conseguirão pagar suas dívidas? Cabe a você descobrir!','img/jogos/dandyrandydx.webp','2022-04-29',1),(46,'Awakening Sarah','Asteristic Game Studio','Asteristic Game Studio','Awakening Sarah é um jogo de plataforma 2D com ênfase em exploração. Descubra os sonhos e realidades de Sarah, uma garota em recuperação após o coma, enquanto a ajuda a entender melhor o mundo ao seu redor e a si mesma.','img/jogos/awakening-sarah.webp','2025-01-24',1),(47,'Au Revoir','Nuntius Games','Invisible Studio','Assuma o papel de Tristan e resolva puzzles para recuperar consciências humanas em 2071. Investigue ambientes, descubra segredos e tome decisões que afetam o final. Explore um mundo neon-noir com estética low poly inspirada nos anos 90.','img/jogos/au-revoir.webp','2025-01-30',1),(48,'Bob Help Them','Gagonfe','Gagonfe','Bob Help Them é um jogo de tempo, onde seu objetivo é ajudar todos os NPC\'s antes que o tempo acabe.','img/jogos/bob-help-them.avif','2020-11-25',1),(49,'Colorful Colore','Gagonfe','Gagonfe','Colorful Colore é um jogo de quebra-cabeça em um labirinto, onde seu objetivo é chegar na saída, combinando com as cores certas da sala e interagindo com diferentes mecânicas.','img/jogos/coloful.jpeg','2020-10-24',1),(50,'Dungeon Color','Gagonfe','Gagonfe','Dungeon Color é um jogo puzzle top-down, onde você troca de chamas para chegar na enorme chama colorida de cada sala. Interaja com obstáculos especiais e derreta seu cérebro para achar a saída. Fofo, colorido, difícil e divertido.','img/jogos/dungeon-color.avif','2021-10-12',1),(51,'Gemini','Gagonfe','Gagonfe','Gemini é um jogo de ação em tower defense, onde seu objetivo é proteger o cristal e ficar vivo.','img/jogos/gemini.jpg','2020-09-23',1),(52,'Toroom','Gagonfe','Gagonfe','Toroom é um dungeon-crawler roguelike top-down shooter, onde o protagonista só quer voltar para o seu quarto após acordar em um mundo de fantasia. Lute para atravessar múltiplos biomas, limpe salas cheias de inimigos, pegue todo o tesouro possível e derrote os chefões em seu caminho.','img/jogos/toroom.jpg','2021-07-17',1),(53,'Dreaming Sarah','Asteristic Game Studio','Asteristic Game Studio','Dreaming Sarah é um adventure de plataforma onde você joga com Sarah, uma garota que está em coma. Explore o mundo ao seu redor e ajude-a a acordar!','img/jogos/dreaming.avif','2015-03-12',1),(54,'Doomed to Hell','Gagonfe','Gagonfe','Doomed to Hell é um shooter roguelike estilo top-down onde você joga como Rose, um humano no inferno que terá que lutar contra todos os monstros de lá em rodadas de inimigos, derrotar bosses, e adquirir novas armas para reconquistar sua vida na terra.','img/jogos/doomed-to-hell.webp','2022-03-25',1),(55,'Colorful Recolor','Gagonfe','Gagonfe','Colorful Recolor é um jogo em ritmo acelerado com uma coleção caleidoscópica de quebra-cabeças. Jogue através de 100 leveis com mecânicas engajantes, biomas atraentes, mais de 10 elementos distintos, e uma abundância de cores. Pinte seu caminho neste mundo colorido!','img/jogos/recolor.webp','2022-08-10',1),(56,'Archaeogem','Gagonfe','Gagonfe','Chapéus e chicotes a postos; os mistérios da natureza o aguardam! Explore biomas exuberantes, use seu chicote para extrair poderes místicos de flores, e colecione gemas raras neste jogo de plataforma de precisão. Desvende maravilhas arqueológicas e se torne uma lenda!','img/jogos/archaeogem.jpg','2024-05-17',1),(57,'Rocket Rats','Gagonfe','Gagonfe','Rocket Rats é um jogo survivors-like onde você derrota corpos celestes à base de queijo no espaço – como um rato! Limpe ondas de inimigos, colete queijo, melhore seu equipamento, e conquiste a lua!','img/jogos/rocket-rats.jpg','2024-12-17',1),(58,'Aspire: Ina\'s Tale','Untold Tales','Wondernaut Studio','Embarque em uma aventura mística de autodescoberta, ambientada nos fantásticos cenários da Torre. Conheça os habitantes de lá e ouça as histórias que têm para contar. Manipule espíritos para resolver enigmas e guie Ina para alcançar a liberdade.','img/jogos/aspire.avif','2021-12-17',1),(59,'Deathbound','Tate Multimedia','Trialforge Studio','Deathbound é um Soulslike único. Forme um grupo de quatro personagens e alterne instantaneamente entre eles dentro e fora do combate. Combine suas habilidades para realizar ataques devastadores e derrotar aqueles que se colocarem diante de você.','img/jogos/deathbound.jpg','2024-08-08',1);
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
INSERT INTO `jogo_possui_genero` VALUES (2,1),(3,1),(4,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(16,1),(17,1),(18,1),(19,1),(20,1),(22,1),(23,1),(24,1),(28,1),(34,1),(35,1),(37,1),(41,1),(43,1),(45,1),(50,1),(51,1),(52,1),(54,1),(57,1),(59,1),(2,2),(3,2),(4,2),(7,2),(10,2),(11,2),(14,2),(15,2),(16,2),(19,2),(25,2),(26,2),(31,2),(32,2),(38,2),(44,2),(45,2),(46,2),(47,2),(50,2),(51,2),(52,2),(53,2),(54,2),(56,2),(58,2),(59,2),(3,3),(7,3),(42,3),(23,4),(42,4),(4,6),(11,6),(14,6),(15,6),(16,6),(27,6),(29,6),(32,6),(33,6),(3,7),(4,7),(8,7),(9,7),(10,7),(11,7),(12,7),(15,7),(19,7),(21,7),(34,7),(20,8),(35,8),(38,8),(39,8),(40,8),(5,9),(6,9),(7,9),(14,9),(17,9),(18,9),(41,9),(4,10),(11,10),(19,10),(25,10),(26,10),(4,11),(5,11),(6,11),(7,11),(8,11),(13,11),(59,11),(5,12),(6,12),(29,12),(36,12),(38,12),(39,12),(40,12),(8,13),(9,13),(10,13),(12,13),(14,13),(16,13),(17,13),(18,13),(20,13),(23,13),(28,13),(30,13),(34,13),(37,13),(41,13),(5,14),(8,14),(17,14),(18,14),(34,14),(35,14),(41,14),(29,15),(24,16),(31,16),(36,16),(38,16),(39,16),(40,16),(24,17),(31,17),(21,18),(22,18),(43,18),(14,19),(16,19),(27,19),(29,19),(30,19),(48,19),(49,19),(50,19),(51,19),(55,19),(6,21),(13,21),(22,21),(28,21),(43,21),(10,22),(11,22),(12,22),(15,22),(16,22),(19,22),(20,22),(33,22),(7,23),(43,23),(3,24),(4,24),(21,24),(32,25),(45,26),(46,26),(48,26),(49,26),(50,26),(51,26),(53,26),(55,26),(57,26),(58,26);
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
INSERT INTO `jogo_possui_plataforma` VALUES (41,1),(44,1),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2),(41,2),(42,2),(43,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(4,3),(9,3),(10,3),(22,3),(23,3),(32,3),(33,3),(34,3),(43,3),(4,4),(9,4),(10,4),(11,4),(12,4),(22,4),(23,4),(32,4),(43,4),(4,5),(13,5),(14,5),(15,5),(16,5),(17,5),(18,5),(23,5),(30,5),(41,5),(4,6),(13,6),(14,6),(15,6),(16,6),(17,6),(18,6),(30,6),(41,6),(3,7),(4,7),(8,7),(9,7),(10,7),(11,7),(12,7),(13,7),(14,7),(15,7),(16,7),(17,7),(18,7),(19,7),(20,7),(21,7),(22,7),(23,7),(32,7),(33,7),(38,7),(45,7),(48,7),(49,7),(50,7),(51,7),(52,7),(53,7),(54,7),(58,7),(3,8),(4,8),(9,8),(10,8),(19,8),(20,8),(21,8),(22,8),(23,8),(25,8),(26,8),(32,8),(34,8),(45,8),(49,8),(50,8),(53,8),(54,8),(55,8),(57,8),(58,8),(4,9),(9,9),(10,9),(11,9),(12,9),(19,9),(20,9),(21,9),(22,9),(23,9),(26,9),(32,9),(45,9),(49,9),(50,9),(53,9),(54,9),(55,9),(57,9),(58,9),(4,10),(25,10),(32,10),(33,10),(35,10),(36,10),(37,10),(41,10),(42,10),(43,10),(53,10),(4,11),(6,11),(7,11),(8,11),(32,11),(33,11),(35,11),(36,11),(43,11),(46,11),(53,11),(2,12),(3,12),(4,12),(5,12),(6,12),(7,12),(8,12),(9,12),(10,12),(11,12),(12,12),(13,12),(14,12),(15,12),(16,12),(17,12),(18,12),(19,12),(20,12),(21,12),(22,12),(23,12),(24,12),(25,12),(26,12),(27,12),(28,12),(29,12),(31,12),(32,12),(33,12),(34,12),(35,12),(36,12),(37,12),(38,12),(39,12),(40,12),(41,12),(42,12),(43,12),(44,12),(45,12),(46,12),(48,12),(49,12),(50,12),(51,12),(52,12),(53,12),(54,12),(55,12),(56,12),(57,12),(58,12),(59,12),(45,13),(49,13),(50,13),(53,13),(55,13),(59,13),(45,14),(49,14),(50,14),(53,14),(54,14),(55,14),(57,14),(58,14),(59,14),(58,15),(59,15);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plataforma`
--

LOCK TABLES `plataforma` WRITE;
/*!40000 ALTER TABLE `plataforma` DISABLE KEYS */;
INSERT INTO `plataforma` VALUES (5,'Android'),(15,'GeForce Now'),(6,'iOS'),(11,'Linux'),(10,'macOS'),(7,'Nintendo Switch'),(2,'PC'),(4,'Playstation'),(9,'Playstation 4'),(13,'Playstation 5'),(1,'Web'),(12,'Windows'),(3,'Xbox'),(8,'Xbox One'),(14,'Xbox Series X|S');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'dev_teste','dev@email.com','desenvolvedor','1234senha_hashada','img/usuarios/default.png','2025-06-13 11:07:37'),(13,'pierrehneppel','pierrehenrique@gmail.com','administrador','$2y$10$RB3NQ8FTNguuVxaPvSCsNe4x8qRa3k5gSs31OMM6ixQurKGLCnrNu','img/usuarios/user_13.jpg','2025-09-24 12:26:02'),(17,'Dev2wow','xirek20391@fermiro.com','desenvolvedor','$2y$10$IFwdNUMs5ZnBYz3TNWNylejl9l89xegMrmpD1m1eYzJxBsem2hQHW','img/usuarios/default.png','2025-11-12 11:00:01'),(18,'PedrinhoDev','rearea@gmail.com','desenvolvedor','$2y$10$ZjDbuG6NQxVIztr1UO0b2uWXdN/2s/LE7G0XzdNOOX.WoU3lSLOcG','img/usuarios/default.png','2025-11-12 11:21:58'),(20,'Ludus','ludus@gmail.com','desenvolvedor','$2y$10$Wtua5pBYWyfgnTnqV56rF.q3zIOaxetm3qhq6gRKW1pyRQXJkR0lK','img/usuarios/user_20.jpg','2025-11-12 16:16:37'),(21,'LudusOficial','ludus2@gmail.com','desenvolvedor','$2y$10$ylp6sIMrMGYDA32aTzWAwOB9/E0hqtmqsqt9xnUZUd3XAYom5t8Qa','img/usuarios/user_21.jpg','2025-11-17 19:56:08'),(22,'LudusOMG','ludus3@gmail.com','desenvolvedor','$2y$10$VMl6xoObinozRCqKqCB8LOUphmr.dom0fPHQSLCKSWKVco3u4enh6','img/usuarios/user_22.jpg','2025-11-18 19:55:22');
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
INSERT INTO `usuario_favorita_jogo` VALUES (13,1),(20,2);
/*!40000 ALTER TABLE `usuario_favorita_jogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verificacao_desenvolvedor`
--

DROP TABLE IF EXISTS `verificacao_desenvolvedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verificacao_desenvolvedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motivo` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pendente','aprovado','rejeitado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendente',
  `data_solicitacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_expiracao` timestamp NOT NULL,
  `data_verificacao` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_id_usuario` (`id_usuario`),
  KEY `idx_status` (`status`),
  KEY `idx_data_expiracao` (`data_expiracao`),
  CONSTRAINT `verificacao_desenvolvedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verificacao_desenvolvedor`
--

LOCK TABLES `verificacao_desenvolvedor` WRITE;
/*!40000 ALTER TABLE `verificacao_desenvolvedor` DISABLE KEYS */;
INSERT INTO `verificacao_desenvolvedor` VALUES (5,17,'xirek20391@fermiro.com','dad197a6f4f84fa3772154dbe062fa6ae1261a6627e93258593b072607c64d05','Criar uma plataforma acessível e intuitiva que valorize os jogos independentes brasileiros. A proposta é oferecer um espaço para que desenvolvedores divulguem seus jogos e jogadores descubram novas experiências, contribuindo com avaliações, comentários e engajamento.','','2025-11-12 14:00:19','2025-11-14 17:00:19','2025-11-12 14:18:06','2025-11-12 14:00:19','2025-11-12 14:18:06'),(8,18,'rearea@gmail.com','0299310599db17f4fa60c80b7757fd28332a6f7d92d6ac72e220e22203286cdc','fasffewfwefwefewfwefewf','','2025-11-12 14:52:14','2025-11-14 17:52:14','2025-11-12 14:52:17','2025-11-12 14:52:14','2025-11-12 14:52:17'),(9,20,'ludus@gmail.com','a11daee64147f0ac3ae12979ba5b00dbf317f491216d919a69aedde5d24bfa4f','ggggggggggggggggggggggggggggggggggggg','','2025-11-12 19:34:10','2025-11-14 22:34:10','2025-11-12 19:34:13','2025-11-12 19:34:10','2025-11-12 19:34:13'),(10,21,'ludus2@gmail.com','040517a229201206d0351e8b75e568411ebe1b3c1c461323486a47978ec6bd3d','Criei um jogo muito bom ano passado...','','2025-11-17 22:58:09','2025-11-20 01:58:09','2025-11-17 22:58:13','2025-11-17 22:58:09','2025-11-17 22:58:13'),(11,22,'ludus3@gmail.com','e3fede263e5745b75691f755db60c6593365804660224b9891dd8050bec9176c','Sou um desenvolvedor de longa data e criei aquele jogo lá...','','2025-11-18 22:57:50','2025-11-21 01:57:50','2025-11-18 22:57:53','2025-11-18 22:57:50','2025-11-18 22:57:53');
/*!40000 ALTER TABLE `verificacao_desenvolvedor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-20 13:59:30
