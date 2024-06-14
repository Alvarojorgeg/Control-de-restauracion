-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: localhost    Database: proyecto
-- ------------------------------------------------------
-- Server version	8.0.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `cod_mesareservada` int(11) NOT NULL,
  `cod_cliente` char(9) NOT NULL,
  PRIMARY KEY (`cod_cliente`,`cod_mesareservada`),
  KEY `cod_mesareservada` (`cod_mesareservada`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cod_mesareservada`) REFERENCES `mesareservada` (`cod_mesareservada`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comida`
--

DROP TABLE IF EXISTS `comida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comida` (
  `cod_comida` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `ingredientes` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_comida`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comida`
--

LOCK TABLES `comida` WRITE;
/*!40000 ALTER TABLE `comida` DISABLE KEYS */;
INSERT INTO `comida` VALUES (21,'Hamburguesa normal','Plato principal','1 Carne',12.00,'menu/hamburguesa.png'),(22,'Hamburguesa doble','Plato principal','2 Carnes',15.00,'menu/doble hamburguesa.png'),(24,'Cocacola','Entrante','Gaseosa',2.00,'menu/coca.png'),(25,'Burrito','Plato principal','Pollo',8.00,'menu/burrito.png'),(26,'Tequeños','Entrante','Queso',6.00,'menu/teq.png');
/*!40000 ALTER TABLE `comida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_mesas`
--

DROP TABLE IF EXISTS `configuracion_mesas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion_mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_mesas` int(11) DEFAULT NULL,
  `config_mesas` json DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_mesas`
--

LOCK TABLES `configuracion_mesas` WRITE;
/*!40000 ALTER TABLE `configuracion_mesas` DISABLE KEYS */;
INSERT INTO `configuracion_mesas` VALUES (1,6,'{\"1\": 5, \"2\": 2, \"3\": 3, \"4\": 3, \"5\": 6, \"6\": 0}','2024-06-12 20:11:23');
/*!40000 ALTER TABLE `configuracion_mesas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dia_libre`
--

DROP TABLE IF EXISTS `dia_libre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dia_libre` (
  `dni` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `concedido_sino` char(2) NOT NULL,
  PRIMARY KEY (`dni`,`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dia_libre`
--

LOCK TABLES `dia_libre` WRITE;
/*!40000 ALTER TABLE `dia_libre` DISABLE KEYS */;
INSERT INTO `dia_libre` VALUES ('0894934K','2024-06-18','No'),('0894934K','2024-08-24','No'),('45234223S','2024-08-09','No'),('5345432U','2024-06-17','No'),('5345432U','2024-08-04','No');
/*!40000 ALTER TABLE `dia_libre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `pin` char(6) NOT NULL,
  `fechanac` date NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `horario` varchar(20) NOT NULL,
  `nacionalidad` varchar(100) NOT NULL,
  `inicio_contrato` date NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `trabajando` char(2) NOT NULL,
  `nomina` varchar(50) NOT NULL,
  PRIMARY KEY (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES ('0894934K','Jose','Manuel','123456','1989-08-14','Calle Salesianos Atocha','josemanuel@gmail.com','669494932','Tarde','Española','2024-03-14','Jefe de cocina','No',''),('45234223S','Samuel','Redes','123456','1967-01-01','Calle las Rozas 5','samuelredes@gmail.com','654646654','Noche','Española','2019-07-12','Cocinero','Si',''),('5324324Y','Alvaro','Jorge','123456','2003-07-11','Calle de la Rubia','alvaro@gmail.com','674848484','noche','Española','2024-06-14','Gerente','Si',''),('5345432U','Carlos','Contreras','123456','1964-04-04','Calle Dominguez Rariz 5','carloscontreras@gmail.com','56453665','Mañana','Española','2023-02-24','Camarero','Si',''),('6435644S','Olivio','Perez','123456','1978-05-03','Calle rambo 30','fdsffds@gmail.com','43243432','noche','Cubana','2024-05-18','Limpieza','Si','');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadodemesas`
--

DROP TABLE IF EXISTS `estadodemesas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estadodemesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_mesa` int(11) NOT NULL,
  `ultima_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ocupada` enum('si','no') DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `numero_mesa` (`numero_mesa`),
  CONSTRAINT `estadodemesas_ibfk_1` FOREIGN KEY (`numero_mesa`) REFERENCES `configuracion_mesas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadodemesas`
--

LOCK TABLES `estadodemesas` WRITE;
/*!40000 ALTER TABLE `estadodemesas` DISABLE KEYS */;
/*!40000 ALTER TABLE `estadodemesas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura`
--

DROP TABLE IF EXISTS `factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura` (
  `codigo_ticket` varchar(50) NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `numero_mesa` int(11) NOT NULL,
  PRIMARY KEY (`codigo_ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura`
--

LOCK TABLES `factura` WRITE;
/*!40000 ALTER TABLE `factura` DISABLE KEYS */;
INSERT INTO `factura` VALUES ('ticket_666a02a303c00','2024-06-12 20:18:43',48.00,4),('ticket_666a02b471294','2024-06-12 20:19:00',62.00,3),('ticket_666a02c26b1e3','2024-06-12 20:19:14',34.00,5),('ticket_666a02ca1b76c','2024-06-12 20:19:22',122.00,1),('ticket_666a02f01b76b','2024-06-12 20:20:00',180.00,3),('ticket_666a02f1efee9','2024-06-12 20:20:01',117.00,4),('ticket_666a0310c1f4d','2024-06-12 20:20:32',24.00,3),('ticket_666a0311d8199','2024-06-12 20:20:33',4.00,5),('ticket_666a031798ebe','2024-06-12 20:20:39',100.00,1),('ticket_666a0318ab031','2024-06-12 20:20:40',24.00,2),('ticket_666a031a9209f','2024-06-12 20:20:42',70.00,4),('ticket_666a031fcbb77','2024-06-12 20:20:47',28.00,1),('ticket_666a03445764d','2024-06-12 20:21:24',47.00,2),('ticket_666a03629392a','2024-06-12 20:21:54',27.00,2);
/*!40000 ALTER TABLE `factura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mesareservada`
--

DROP TABLE IF EXISTS `mesareservada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mesareservada` (
  `cod_mesareservada` int(11) NOT NULL AUTO_INCREMENT,
  `cod_cliente` char(9) DEFAULT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `personas` int(11) DEFAULT NULL,
  `intolerancias` varchar(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`cod_mesareservada`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mesareservada`
--

LOCK TABLES `mesareservada` WRITE;
/*!40000 ALTER TABLE `mesareservada` DISABLE KEYS */;
INSERT INTO `mesareservada` VALUES (87,NULL,'Eric','Cubilot',5,'Celiaca','2024-06-14','14:30'),(88,NULL,'Elena','Canobas',2,'No','2024-06-13','13:00'),(89,NULL,'Pepe','Ramirez',1,'Queso','2024-06-22','22:00'),(90,NULL,'Francisco','Gutierrez',1,'No','2024-06-17','21:00'),(91,NULL,'Yesenia','Diaz',4,'No','2024-06-14','21:30');
/*!40000 ALTER TABLE `mesareservada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_mesa` int(11) DEFAULT NULL,
  `comida_id` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `comida_id` (`comida_id`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`comida_id`) REFERENCES `comida` (`cod_comida`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-12 22:39:37
