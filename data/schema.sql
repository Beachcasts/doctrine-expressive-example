
--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `id` char(36) NOT NULL,
  `sort` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='news/announcements to display on client dashboard';

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;

CREATE TABLE `branches` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NULL,
  `city` varchar(50) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `zip` varchar(15) NOT NULL,
  `email` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `bank_id` (`bank_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='branches are assigned to banks';

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;

INSERT INTO `branches` VALUES (1,1,'Branch #1','555 test st','suite 5','Wellington',18,'33437','customerservices@branch1.com','555-555-5555',1,'2011-06-05 17:22:36','2011-11-01 05:17:09'),(2,2,'branch #2','555 test st','suite 5','Boynton Beach',18,'33437','test@branch2.com','555-555-5555',1,'2011-06-06 00:31:06','2011-06-07 18:25:04'),(3,2,'Branch #3','555 test st','suite 5','Boynton Beach',18,'33437','test@branch3.com','555-555-5555',1,'2011-06-06 00:33:01','2011-06-06 00:33:01');

UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;

CREATE TABLE `banks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `address1` varchar(250) NOT NULL,
  `address2` varchar(250) NULL,
  `city` varchar(50) NOT NULL,
  `zone_id` int(11) unsigned NOT NULL,
  `zip` varchar(15) NOT NULL,
  `product_1_price` decimal(8,2) NOT NULL,
  `product_2_price` decimal(8,2) NOT NULL,
  `allow_email_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `zone_id` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='highest level, but could be assigned to another';

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;

INSERT INTO `banks` VALUES (1,0,'Bank #1','555-555-5555','666-666-6666','55 Any Street','Suite 100','Wellington',18,'33496',0.00,0.00,1,1,'2011-06-04 00:26:43','2011-12-29 14:19:29'),(2,1,'Bank #2','555-555-5555','666-666-6666','55 Any Street','Suite 100','Boca Raton',18,'33496',0.00,0.00,0,1,'2011-06-05 14:33:30','2012-04-03 16:25:40');

UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;

CREATE TABLE `zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='states in USA, since state is a reserved word we use zone';

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;

INSERT INTO `zones` VALUES (1,'AL','Alabama'),(2,'AK','Alaska'),(3,'AS','American Samoa'),(4,'AZ','Arizona'),(5,'AR','Arkansas'),(6,'AF','Armed Forces Africa'),(7,'AA','Armed Forces Americas'),(8,'AC','Armed Forces Canada'),(9,'AE','Armed Forces Europe'),(10,'AM','Armed Forces Middle East'),(11,'AP','Armed Forces Pacific'),(12,'CA','California'),(13,'CO','Colorado'),(14,'CT','Connecticut'),(15,'DE','Delaware'),(16,'DC','District of Columbia'),(17,'FM','Federated States Of Micronesia'),(18,'FL','Florida'),(19,'GA','Georgia'),(20,'GU','Guam'),(21,'HI','Hawaii'),(22,'ID','Idaho'),(23,'IL','Illinois'),(24,'IN','Indiana'),(25,'IA','Iowa'),(26,'KS','Kansas'),(27,'KY','Kentucky'),(28,'LA','Louisiana'),(29,'ME','Maine'),(30,'MH','Marshall Islands'),(31,'MD','Maryland'),(32,'MA','Massachusetts'),(33,'MI','Michigan'),(34,'MN','Minnesota'),(35,'MS','Mississippi'),(36,'MO','Missouri'),(37,'MT','Montana'),(38,'NE','Nebraska'),(39,'NV','Nevada'),(40,'NH','New Hampshire'),(41,'NJ','New Jersey'),(42,'NM','New Mexico'),(43,'NY','New York'),(44,'NC','North Carolina'),(45,'ND','North Dakota'),(46,'MP','Northern Mariana Islands'),(47,'OH','Ohio'),(48,'OK','Oklahoma'),(49,'OR','Oregon'),(50,'PW','Palau'),(51,'PA','Pennsylvania'),(52,'PR','Puerto Rico'),(53,'RI','Rhode Island'),(54,'SC','South Carolina'),(55,'SD','South Dakota'),(56,'TN','Tennessee'),(57,'TX','Texas'),(58,'UT','Utah'),(59,'VT','Vermont'),(60,'VI','Virgin Islands'),(61,'VA','Virginia'),(62,'WA','Washington'),(63,'WV','West Virginia'),(64,'WI','Wisconsin'),(65,'WY','Wyoming');

UNLOCK TABLES;
