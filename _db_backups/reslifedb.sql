-- MySQL dump 10.11
--
-- Host: grid50mysql3165.secureserver.net    Database: reslifedb
-- ------------------------------------------------------
-- Server version	5.0.92-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CATEGORY`
--

DROP TABLE IF EXISTS `CATEGORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CATEGORY` (
  `CATEGORY_ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(20) NOT NULL,
  `PERMISSIONS` int(11) NOT NULL default '1',
  `checkout_length` int(2) default NULL,
  PRIMARY KEY  (`CATEGORY_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CATEGORY`
--

LOCK TABLES `CATEGORY` WRITE;
/*!40000 ALTER TABLE `CATEGORY` DISABLE KEYS */;
INSERT INTO `CATEGORY` VALUES (2,'Board Games',0,0),(3,'Cleaning Supplies',0,NULL),(4,'DVD',1,NULL),(5,'Equipment',11,NULL),(6,'Game Equipment',0,1),(7,'Nintendo Games',2,NULL),(8,'Playstation Games',3,NULL),(9,'Rooms',20,1),(10,'VHS',4,NULL),(11,'Video Game Hardware',10,NULL),(12,'Wii Games',5,NULL),(13,'Xbox Games',6,NULL),(14,'Toys',0,2);
/*!40000 ALTER TABLE `CATEGORY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CHECKOUT`
--

DROP TABLE IF EXISTS `CHECKOUT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CHECKOUT` (
  `ORDER_NUMBER` int(11) NOT NULL auto_increment,
  `STUDENT_ID` int(11) default NULL,
  `NUM_ITEMS` int(11) NOT NULL,
  `DATE_CHECKED_OUT` datetime default NULL,
  `DUE_DATE` datetime default NULL,
  `DATE_CHECKED_IN` datetime default NULL,
  `CHECKOUT_TYPE` varchar(11) default NULL,
  PRIMARY KEY  (`ORDER_NUMBER`),
  KEY `FK_STUDENT_ID` (`STUDENT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CHECKOUT`
--

LOCK TABLES `CHECKOUT` WRITE;
/*!40000 ALTER TABLE `CHECKOUT` DISABLE KEYS */;
/*!40000 ALTER TABLE `CHECKOUT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ITEM`
--

DROP TABLE IF EXISTS `ITEM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ITEM` (
  `ITEM_ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(50) default NULL,
  `QUANTITY` int(11) default NULL,
  `AVAILABLE` int(11) default NULL,
  `HALL_ID` int(11) default NULL,
  `CATEGORY_ID` int(11) default NULL,
  `CHECKED_OUT_COUNT` int(11) NOT NULL default '0',
  `PACKAGE_ID` int(11) NOT NULL default '1',
  PRIMARY KEY  (`ITEM_ID`),
  KEY `FK_CATEGORY_ID` (`CATEGORY_ID`),
  KEY `FK_RESIDENCE_HALL` (`HALL_ID`),
  FULLTEXT KEY `NAME` (`NAME`)
) ENGINE=MyISAM AUTO_INCREMENT=1130331 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ITEM`
--

LOCK TABLES `ITEM` WRITE;
/*!40000 ALTER TABLE `ITEM` DISABLE KEYS */;
INSERT INTO `ITEM` VALUES (1010001,'Adverteasing',2,2,1,2,0,1),(1020002,'Apples to Apples',2,2,1,2,0,1),(1020003,'Backgammon',1,1,1,2,0,1),(1020004,'Balderdash',1,1,1,2,0,1),(1020006,'Beyond Balderdash',1,1,1,2,0,1),(1020007,'Bingo',1,1,1,2,0,1),(1020008,'Box of Bunco',1,1,1,2,0,1),(1020009,'Camp',1,1,1,2,0,1),(1020010,'Chess',1,1,1,2,0,1),(1020011,'Coda',1,1,1,2,0,1),(1020012,'Connect Four',1,1,1,2,0,1),(1020013,'Cranium',1,1,1,2,0,1),(1020014,'Cribbage',1,1,1,2,0,1),(1020015,'Dirty Minds',1,1,1,2,0,1),(1020016,'Encore',1,1,1,2,0,1),(1020017,'Going Nutz',1,1,1,2,0,1),(1020018,'Guesstures',1,1,1,2,0,1),(1020019,'Jenga',1,1,1,2,0,1),(1020020,'Knowbody Knows',1,1,1,2,0,1),(1020021,'Life',1,1,1,2,0,1),(1020022,'Master Mind',2,2,1,2,0,1),(1020023,'Monopoly',1,1,1,2,0,1),(1020024,'Monopoly: Star Wars Edition',1,1,1,2,0,1),(1020025,'Parcheesi',1,1,1,2,0,1),(1020026,'Party Pooper',1,1,1,2,0,1),(1020027,'Password',1,1,1,2,0,1),(1020028,'Payday',1,1,1,2,0,1),(1020029,'Perfect Timing',1,1,1,2,0,1),(1020031,'Risk',1,1,1,2,0,1),(1020032,'Scattergories',1,1,1,2,0,1),(1020033,'Scotland Yard',1,1,1,2,0,1),(1020034,'Strategy',1,1,1,2,0,1),(1020035,'Taboo',1,1,1,2,0,1),(1020036,'Take 5',1,1,1,2,0,1),(1020037,'Therapy',1,1,1,2,0,1),(1020038,'Trivial Pursuit: Genus 5',1,1,1,2,0,1),(1020039,'Trivial Pursuit: Genus IV',1,1,1,2,0,1),(1020040,'Trivial Pursuit: Lord of the Rings Edition',1,1,1,2,0,1),(1020041,'Trivial Pursuit: Star Wars',1,1,1,2,0,1),(1020042,'True Colors',1,1,1,2,0,1),(1020043,'Twister',1,1,1,2,0,1),(1020044,'Win Lose or Draw',1,1,1,2,0,1),(1020046,'Yahtzee',2,2,1,2,0,1),(1020047,'You Might Be a Redneck If…',1,1,1,2,0,1),(1020048,'Vaccuum',1,1,1,3,0,1),(1030049,'28 Days Later',1,1,1,4,0,1),(1040050,'A Clockwork Orange',1,1,1,4,0,1),(1040051,'A League of Their Own',1,1,1,4,0,1),(1040052,'American Gangster',1,1,1,4,0,1),(1040053,'American Pie 2',1,1,1,4,0,1),(1040054,'Anchorman',1,1,1,4,0,1),(1040055,'Animal House',1,1,1,4,0,1),(1040056,'Any Given Sunday',1,1,1,4,0,1),(1040057,'Bio-Dome',1,1,1,4,0,1),(1040058,'Black Hawk Down',1,1,1,4,0,1),(1040059,'Boondock Saints, The',1,1,1,4,0,1),(1040060,'Borne Trilogy, The',1,1,1,4,0,1),(1040061,'Can\'t Hardly Wait',1,1,1,4,0,1),(1040062,'Cat in the Hat, The',1,1,1,4,0,1),(1040063,'Cellular',1,1,1,4,0,1),(1040064,'Chicago',1,1,1,4,0,1),(1040065,'Chronicles of Narnia, The',1,1,1,4,0,1),(1040066,'Cool Hand Luke',1,1,1,4,0,1),(1040067,'Corpse Bride',1,1,1,4,0,1),(1040068,'Curious Case of Benjamin Buttons, The',1,1,1,4,0,1),(1040069,'Dark Knight, The',1,1,1,4,0,1),(1040070,'Darkness Falls',1,1,1,4,0,1),(1040071,'Death Race',1,1,1,4,0,1),(1040072,'Descent',1,1,1,4,0,1),(1040073,'Dirty Dancing',1,1,1,4,0,1),(1040074,'District 9',1,1,1,4,0,1),(1040075,'Disturbia',1,1,1,4,0,1),(1040076,'Dr. Strangelove',1,1,1,4,0,1),(1040077,'Elf',1,1,1,4,0,1),(1040078,'Exit Wounds',1,1,1,4,0,1),(1040079,'Fast & Furious',1,1,1,4,0,1),(1040080,'Fear and Loathing in Las Vegas',1,1,1,4,0,1),(1040081,'Finding Nemo',1,1,1,4,0,1),(1040082,'Forbidden Kingdom, The',1,1,1,4,0,1),(1040083,'Forgetting Sarah Marshall',1,1,1,4,0,1),(1040084,'GoodFellas',1,1,1,4,0,1),(1040085,'Grease',1,1,1,4,0,1),(1040086,'Great Escape, The',1,1,1,4,0,1),(1040087,'Hairspray',1,1,1,4,0,1),(1040088,'Hangover, The',1,1,1,4,0,1),(1040089,'Hannah Montana: The Movie',1,1,1,4,0,1),(1040090,'Harry Potter and the Chamber of Secrets',1,1,1,4,0,1),(1040091,'Harry Potter and The Prisoner of Azkaban',1,1,1,4,0,1),(1040092,'Harry Potter and the Sorcerer\'s Stone',1,1,1,4,0,1),(1040093,'Hero',1,1,1,4,0,1),(1040094,'How to Eat Fried Worms',1,1,1,4,0,1),(1040095,'How to Lose a Guy in 10 Days',1,1,1,4,0,1),(1040096,'I Heart Huckabees',1,1,1,4,0,1),(1040097,'I Now Pronounce You Chuck and Larry',2,2,1,4,0,1),(1040099,'I, Robot',1,1,1,4,0,1),(1040100,'Incredibles, The',1,1,1,4,0,1),(1040101,'John Q.',1,1,1,4,0,1),(1040102,'King Kong',1,1,1,4,0,1),(1040103,'Knocked Up',1,1,1,4,0,1),(1040104,'Last Samurai, The',1,1,1,4,0,1),(1040105,'Lizzie McGuire Movie, The',1,1,1,4,0,1),(1040106,'Lord of the Rings: Fellowship of the Ring',1,1,1,4,0,1),(1040107,'Lord of the Rings: Return of the King',1,1,1,4,0,1),(1040108,'Lord of the Rings: The Two Towers',1,1,1,4,0,1),(1040109,'Lucky # Slevin',1,1,1,4,0,1),(1040110,'Man of Fire',1,1,1,4,0,1),(1040111,'Moulin Rouge',1,1,1,4,0,1),(1040112,'Mummy Returns, The',1,1,1,4,0,1),(1040113,'Mummy, The',1,1,1,4,0,1),(1040114,'Music Man, The',1,1,1,4,0,1),(1040115,'My Big Fat Greek Wedding',1,1,1,4,0,1),(1040116,'National Treasure',1,1,1,4,0,1),(1040117,'Natural Born Killers',1,1,1,4,0,1),(1040118,'Office Space',1,1,1,4,0,1),(1040119,'Old School',1,1,1,4,0,1),(1040120,'One Flew Over the Cuckoo\'s Nest',1,1,1,4,0,1),(1040121,'Over the Hedge',1,1,1,4,0,1),(1040122,'Phantom of the Opera, The',1,1,1,4,0,1),(1040123,'Police Academy',1,1,1,4,0,1),(1040124,'Pride & Prejudice',1,1,1,4,0,1),(1040125,'Protector, The',1,1,1,4,0,1),(1040126,'Rain Man',1,1,1,4,0,1),(1040127,'Real Bruce Lee, The',1,1,1,4,0,1),(1040128,'Rear Window',1,1,1,4,0,1),(1040129,'Road Trip',1,1,1,4,0,1),(1040130,'Rush Hour 2',1,1,1,4,0,1),(1040131,'Saving Private Ryan',1,1,1,4,0,1),(1040132,'Semi-Pro',1,1,1,4,0,1),(1040133,'Shaun of the Dead',1,1,1,4,0,1),(1040134,'Shrek',1,1,1,4,0,1),(1040135,'Sixth Sense, The',1,1,1,4,0,1),(1040136,'Snatch',1,1,1,4,0,1),(1040137,'Sound of Music, the',1,1,1,4,0,1),(1040138,'Spaceballs',1,1,1,4,0,1),(1040139,'Star Trek',1,1,1,4,0,1),(1040140,'Star Wars Trilogy',1,1,1,4,0,1),(1040141,'Star Wars: Attack of the Clones (Episode 2)',1,1,1,4,0,1),(1040142,'Star Wars: Revenge of the Sith (Episode 3)',1,1,1,4,0,1),(1040143,'Star Wars: The Phantom Menace (Episode 1)',1,1,1,4,0,1),(1040144,'Superbad',1,1,1,4,0,1),(1040145,'Taken',1,1,1,4,0,1),(1040146,'Texas Chainsaw Massacre, The',1,1,1,4,0,1),(1040147,'Thin Red Line, The',1,1,1,4,0,1),(1040148,'Top Gun',1,1,1,4,0,1),(1040149,'Tropic Thunder',1,1,1,4,0,1),(1040150,'True Romance',1,1,1,4,0,1),(1040151,'Up',1,1,1,4,0,1),(1040152,'Up on One',1,1,1,4,0,1),(1040153,'View from the Top',1,1,1,4,0,1),(1040154,'Wallace and Gromit: The Curse of the Were-Rabbit',1,1,1,4,0,1),(1040155,'Wall-E',1,1,1,4,0,1),(1040156,'Wedding Crashers',1,1,1,4,0,1),(1040157,'Wheelies',1,1,1,4,0,1),(1040158,'When Harry Met Sally',1,1,1,4,0,1),(1040159,'Wizard of Oz',1,1,1,4,0,1),(1040160,' Projector',1,1,1,5,0,1),(1050161,'DVD Player',1,1,1,5,0,1),(1050163,'VCR',2,2,1,5,0,1),(1050164,'Foosball set',1,1,1,6,0,1),(1060168,'Ping Pong set (Two paddles)',4,4,1,6,0,1),(1060171,'Pool Set (Two cues & one set of pool balls)',3,3,1,6,0,1),(1060172,'Baseball',1,1,1,7,0,1),(1070173,'Castlevania III: Dracula\'s Curse',1,1,1,7,0,1),(1070174,'Crystalis',1,1,1,7,0,1),(1070175,'Dragon Warrior',1,1,1,7,0,1),(1070176,'Faxanadu',1,1,1,7,0,1),(1070177,'Iron Sword',1,1,1,7,0,1),(1070178,'Iron Tank',1,1,1,7,0,1),(1070179,'Simon\'s Quest',1,1,1,7,0,1),(1070180,'Solstice',1,1,1,7,0,1),(1070181,'Super C',1,1,1,7,0,1),(1070182,'Super Mario Bros./Duck Hunt',1,1,1,7,0,1),(1070183,'Xevious',1,1,1,7,0,1),(1070184,'2 Xtreme',1,1,1,8,0,1),(1080185,'Air Combat',1,1,1,8,0,1),(1080186,'Crash Bandicoot 2',1,1,1,8,0,1),(1080187,'Croc',1,1,1,8,0,1),(1080188,'Frogger',1,1,1,8,0,1),(1080189,'Jet Moto 2',1,1,1,8,0,1),(1080190,'Namco Museum: Volume 3',1,1,1,8,0,1),(1080191,'NFL Gameday 98',1,1,1,8,0,1),(1080192,'Oddworld',1,1,1,8,0,1),(1080193,'Resident Evil 2',1,1,1,8,0,1),(1080194,'1st Floor Kitchen',1,1,1,9,0,1),(1090195,'Practice Room M10',1,1,1,9,0,1),(1090196,'Practice Room M11',1,1,1,9,0,1),(1090197,'Practice Room M12',1,1,1,9,0,1),(1090198,'Practice Room M14',1,1,1,9,0,1),(1090199,'007: Tomorrow Never Dies',1,1,1,10,0,1),(1100200,'28 Days',1,1,1,10,0,1),(1100201,'Air Force One',1,1,1,10,0,1),(1100202,'Along for the Ride',1,1,1,10,0,1),(1100203,'American Beauty',1,1,1,10,0,1),(1100204,'American Pie',1,1,1,10,0,1),(1100205,'Amityville 1992',1,1,1,10,0,1),(1100206,'Apollo 13',1,1,1,10,0,1),(1100207,'Armageddon',1,1,1,10,0,1),(1100208,'Austin Powers: The Spy Who Shagged Me',1,1,1,10,0,1),(1100209,'Bad Boys',1,1,1,10,0,1),(1100210,'Bicentennial Man',1,1,1,10,0,1),(1100211,'Biloxi Blues',1,1,1,10,0,1),(1100212,'Bone Collector, The',1,1,1,10,0,1),(1100213,'Braveheart',1,1,1,10,0,1),(1100214,'Bruce Almighty',1,1,1,10,0,1),(1100215,'Central Station',1,1,1,10,0,1),(1100216,'Charlie\'s Angels',1,1,1,10,0,1),(1100217,'Cider House Rules',1,1,1,10,0,1),(1100218,'Clueless',1,1,1,10,0,1),(1100219,'Cradle 2 the Grave',1,1,1,10,0,1),(1100220,'Cruel Intentions',1,1,1,10,0,1),(1100221,'Derailed',1,1,1,10,0,1),(1100222,'Down Periscope',1,1,1,10,0,1),(1100223,'Erin Brockovich',1,1,1,10,0,1),(1100224,'Ferris Bueller\'s Day Off',1,1,1,10,0,1),(1100225,'Fifth Element, The',1,1,1,10,0,1),(1100226,'Fight Club',1,1,1,10,0,1),(1100227,'Final Destination 2',1,1,1,10,0,1),(1100228,'Galaxy Quest',1,1,1,10,0,1),(1100229,'Ghost and the Darkness, The',1,1,1,10,0,1),(1100230,'Ghost Ship',1,1,1,10,0,1),(1100231,'Gladiator',1,1,1,10,0,1),(1100232,'Goonies, The',1,1,1,10,0,1),(1100233,'Grease',1,1,1,10,0,1),(1100234,'Green Mile, The',1,1,1,10,0,1),(1100235,'Half Past Dead',1,1,1,10,0,1),(1100236,'Harry Potter and the Sorcerer\'s Stone',1,1,1,10,0,1),(1100237,'How to Deal',1,1,1,10,0,1),(1100238,'Independence Day',1,1,1,10,0,1),(1100239,'Indiana Jones',1,1,1,10,0,1),(1100240,'Jerry Maguire',1,1,1,10,0,1),(1100241,'Knight Club',1,1,1,10,0,1),(1100242,'La Bamba',1,1,1,10,0,1),(1100243,'Labyrinth',1,1,1,10,0,1),(1100244,'Lethal Weapon',1,1,1,10,0,1),(1100245,'Lethal Weapon 2',1,1,1,10,0,1),(1100246,'Liar Liar',1,1,1,10,0,1),(1100247,'Lost in Space',1,1,1,10,0,1),(1100248,'Lost World, The',1,1,1,10,0,1),(1100249,'Love Potion #9',1,1,1,10,0,1),(1100250,'Mallrats',1,1,1,10,0,1),(1100251,'Man in the Iron Mask, The',1,1,1,10,0,1),(1100252,'Me, Myself & Irene',1,1,1,10,0,1),(1100253,'Meet Joe Black',1,1,1,10,0,1),(1100254,'Men in Black',1,1,1,10,0,1),(1100255,'Men of Honor',1,1,1,10,0,1),(1100256,'Mission: Impossible',1,1,1,10,0,1),(1100257,'Mission: Impossible 2',1,1,1,10,0,1),(1100258,'Mood Squad, The',1,1,1,10,0,1),(1100259,'Next Friday',1,1,1,10,0,1),(1100260,'Ocean\'s Eleven',1,1,1,10,0,1),(1100261,'One, The',1,1,1,10,0,1),(1100262,'Pretty Woman',1,1,1,10,0,1),(1100263,'Princess Bride, The',1,1,1,10,0,1),(1100264,'Rad',1,1,1,10,0,1),(1100265,'Rainmaker, The',1,1,1,10,0,1),(1100266,'Raise Your Voice',1,1,1,10,0,1),(1100267,'Redlined',1,1,1,10,0,1),(1100268,'Remember the Titans',1,1,1,10,0,1),(1100269,'Road Trip',1,1,1,10,0,1),(1100270,'Robin Hood',1,1,1,10,0,1),(1100271,'Romeo and Juliet',1,1,1,10,0,1),(1100272,'Scorpion King, The',1,1,1,10,0,1),(1100273,'Secondhand Lions',1,1,1,10,0,1),(1100274,'Shanghai Knights',1,1,1,10,0,1),(1100275,'Shrek',1,1,1,10,0,1),(1100276,'Signs',1,1,1,10,0,1),(1100277,'Silence of the Lambs, The',1,1,1,10,0,1),(1100278,'South Park: Bigger, Longer and Uncut',1,1,1,10,0,1),(1100279,'Star Trek: The Final Frontier',1,1,1,10,0,1),(1100280,'Star Trek: The Motion Picture',1,1,1,10,0,1),(1100281,'Star Trek: The Undiscovered Country',1,1,1,10,0,1),(1100282,'Star Trek: The Voyage Home',1,1,1,10,0,1),(1100283,'Star Trek: The Wrath of Khan',1,1,1,10,0,1),(1100284,'Thunderbirds',1,1,1,10,0,1),(1100285,'Tommy Boy',1,1,1,10,0,1),(1100286,'Top Gun',1,1,1,10,0,1),(1100287,'Toy Story 2',1,1,1,10,0,1),(1100288,'Tremors',1,1,1,10,0,1),(1100289,'Truman Show, The',1,1,1,10,0,1),(1100290,'Turner & Hooch',1,1,1,10,0,1),(1100291,'Under Siege 2',1,1,1,10,0,1),(1100292,'Vanilla Sky',1,1,1,10,0,1),(1100293,'War Games',1,1,1,10,0,1),(1100294,'Wedding Singer, The',1,1,1,10,0,1),(1100295,'Nintendo Entertainment System',2,2,1,11,0,1),(1110297,'Nintendo Wii',1,1,1,11,0,1),(1110298,'Playstation',1,1,1,11,0,1),(1110299,'X-Box',1,1,1,11,0,1),(1110300,'Mario Party 8',1,1,1,12,0,1),(1120301,'Super Mario Kart',1,1,1,12,0,1),(1120302,'Super Smash Bros.',1,1,1,12,0,1),(1120303,'Wii Sports',1,1,1,12,0,1),(1120304,'Madden 07',1,1,1,13,0,1),(1130305,'Mortal Kombat Deception',1,1,1,13,0,1),(1130306,'NBA Street v3',1,1,1,13,0,1),(1130307,'NCAA Football 2005/Top Spin Tennis',1,1,1,13,0,1),(1130308,'Rainbow Six 3',1,1,1,13,0,1),(1130309,'Tony Hawk 4',1,1,1,13,0,1),(1130313,'Windex',4,4,1,3,0,1),(1130317,'Cleaning rag',20,20,1,3,0,1),(1130327,'Gina Test',1,1,1,2,0,1),(1130322,'AAA',3,2,1,2,0,1),(1130325,'test',1,1,1,3,0,1),(1130330,'AA',2,2,1,2,0,1);
/*!40000 ALTER TABLE `ITEM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ITEMS_DAMAGED`
--

DROP TABLE IF EXISTS `ITEMS_DAMAGED`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ITEMS_DAMAGED` (
  `ITEM_ID` int(11) NOT NULL,
  `NAME` varchar(50) default NULL,
  `HALL_ID` int(11) default NULL,
  `CATEGORY_ID` int(11) default NULL,
  `STUDENT_ID` int(11) default NULL,
  PRIMARY KEY  (`ITEM_ID`),
  KEY `FK_CATEGORY_ID` (`CATEGORY_ID`),
  KEY `FK_RESIDENCE_HALL` (`HALL_ID`),
  KEY `FK_STUDENT_ID` (`STUDENT_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ITEMS_DAMAGED`
--

LOCK TABLES `ITEMS_DAMAGED` WRITE;
/*!40000 ALTER TABLE `ITEMS_DAMAGED` DISABLE KEYS */;
/*!40000 ALTER TABLE `ITEMS_DAMAGED` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LINEITEM`
--

DROP TABLE IF EXISTS `LINEITEM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LINEITEM` (
  `ORDER_NUMBER` int(11) NOT NULL,
  `ITEM_ID` int(11) NOT NULL,
  PRIMARY KEY  (`ORDER_NUMBER`,`ITEM_ID`),
  KEY `FK_ITEM_ID` (`ITEM_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LINEITEM`
--

LOCK TABLES `LINEITEM` WRITE;
/*!40000 ALTER TABLE `LINEITEM` DISABLE KEYS */;
/*!40000 ALTER TABLE `LINEITEM` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PACKAGE`
--

DROP TABLE IF EXISTS `PACKAGE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PACKAGE` (
  `package_id` int(11) NOT NULL auto_increment,
  `package_name` varchar(15) NOT NULL,
  PRIMARY KEY  (`package_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PACKAGE`
--

LOCK TABLES `PACKAGE` WRITE;
/*!40000 ALTER TABLE `PACKAGE` DISABLE KEYS */;
INSERT INTO `PACKAGE` VALUES (1,'Default'),(2,'DVD Player'),(3,'VHS Player'),(4,'Ping Pong'),(5,'Pool Table'),(6,'Kitchen'),(7,'XBox'),(8,'Wii'),(9,'NES');
/*!40000 ALTER TABLE `PACKAGE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PACKAGE_ENTRY`
--

DROP TABLE IF EXISTS `PACKAGE_ENTRY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PACKAGE_ENTRY` (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(20) NOT NULL,
  `CATEGORY_ID` int(11) NOT NULL,
  `PACKAGE_ID` int(11) NOT NULL,
  `QUANTITY` int(3) NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  KEY `FK_CATEGORY_ID` (`CATEGORY_ID`),
  KEY `FK_PACKAGE_ID` (`PACKAGE_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PACKAGE_ENTRY`
--

LOCK TABLES `PACKAGE_ENTRY` WRITE;
/*!40000 ALTER TABLE `PACKAGE_ENTRY` DISABLE KEYS */;
INSERT INTO `PACKAGE_ENTRY` VALUES (5,'Cleaning Supplies',3,6,1),(4,'DVD',4,2,1);
/*!40000 ALTER TABLE `PACKAGE_ENTRY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RESIDENCE_HALL`
--

DROP TABLE IF EXISTS `RESIDENCE_HALL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RESIDENCE_HALL` (
  `HALL_ID` int(11) NOT NULL,
  `NAME` varchar(10) NOT NULL,
  PRIMARY KEY  (`HALL_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RESIDENCE_HALL`
--

LOCK TABLES `RESIDENCE_HALL` WRITE;
/*!40000 ALTER TABLE `RESIDENCE_HALL` DISABLE KEYS */;
INSERT INTO `RESIDENCE_HALL` VALUES (1,'MORRISON');
/*!40000 ALTER TABLE `RESIDENCE_HALL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USER`
--

DROP TABLE IF EXISTS `USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER` (
  `STUDENT_ID` int(11) NOT NULL,
  `F_NAME` varchar(50) NOT NULL,
  `L_NAME` varchar(50) NOT NULL,
  `EMAIL` varchar(100) default NULL,
  `encryptedPassword` varchar(32) NOT NULL,
  `admin` tinyint(4) NOT NULL default '0',
  `ROOM_NUMBER` int(11) default NULL,
  `HALL_ID` int(11) default NULL,
  `AVAILABLE_CO` tinyint(1) NOT NULL default '1',
  `transaction_count` int(11) NOT NULL default '0',
  PRIMARY KEY  (`STUDENT_ID`),
  KEY `FK_STU_RESIDENCE_HALL` (`HALL_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USER`
--

LOCK TABLES `USER` WRITE;
/*!40000 ALTER TABLE `USER` DISABLE KEYS */;
INSERT INTO `USER` VALUES (123456,'Ray','FitzGerald','ray13eezy@gmail.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',1,987,1,1,0),(123457,'Jon','Roster','jroster76@gmail.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',1,44,1,1,0),(456370,'Gina','Korn','gina.korn@gmail.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',1,860,1,1,0),(123459,'Benjamin','Bennett','benjabenn@gmail.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',1,210,1,1,0),(123444,'Jane','Doe','j_pettit99@hotmail.com','b7fadc7ed0244f4c90922650a97c6682',0,21,1,1,0),(123461,'James','Pettit','jpettit99@gmail.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',1,336,1,1,0),(123462,'Zack','Ranck','zranck25@ewu.edu','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',0,454,1,1,0),(12345,'Corey','Turner','turnercj65@gmail.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',1,789,1,1,0),(999999,'John','Smith','john@smith.com','3fd5f2e1a9eb5a3eb4e96b4fd05171f8',0,212,1,1,0),(654321,'John','Doe','john@doe.com','49cbf7795f538109eafa9a2c693cbd4c',0,321,1,1,0),(11223345,'allison','stillmaker','ally@gmail.com','ffbdd8682413ab2a71c5eada42cb3cb4',0,555,1,1,0),(99900099,'Mike','Bowers','mbowers@ewu.edu','14d8a164f23bc5202a1848eef4d44581',1,0,1,1,0);
/*!40000 ALTER TABLE `USER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `all_items_reserved`
--

DROP TABLE IF EXISTS `all_items_reserved`;
/*!50001 DROP VIEW IF EXISTS `all_items_reserved`*/;
/*!50001 CREATE TABLE `all_items_reserved` (
  `ITEM_ID` int(11)
) ENGINE=MyISAM */;

--
-- Temporary table structure for view `checked_out_items`
--

DROP TABLE IF EXISTS `checked_out_items`;
/*!50001 DROP VIEW IF EXISTS `checked_out_items`*/;
/*!50001 CREATE TABLE `checked_out_items` (
  `Order Number` int(11),
  `Checked out to` varchar(101),
  `name` varchar(50),
  `Due Date` datetime,
  `Past Due` varchar(3)
) ENGINE=MyISAM */;

--
-- Temporary table structure for view `items_available`
--

DROP TABLE IF EXISTS `items_available`;
/*!50001 DROP VIEW IF EXISTS `items_available`*/;
/*!50001 CREATE TABLE `items_available` (
  `ITEM_ID` int(11),
  `ITEM NAME` varchar(50),
  `QUANTITY` int(11),
  `AVAILABLE` int(11),
  `HALL_ID` int(11),
  `CATEGORY NAME` varchar(20),
  `CATEGORY_ID` int(11)
) ENGINE=MyISAM */;

--
-- Temporary table structure for view `items_reserved_next_24h`
--

DROP TABLE IF EXISTS `items_reserved_next_24h`;
/*!50001 DROP VIEW IF EXISTS `items_reserved_next_24h`*/;
/*!50001 CREATE TABLE `items_reserved_next_24h` (
  `ITEM_ID` int(11)
) ENGINE=MyISAM */;

--
-- Table structure for table `old_transactions`
--

DROP TABLE IF EXISTS `old_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `old_transactions` (
  `order_num` int(8) NOT NULL,
  `student_id` int(8) NOT NULL,
  `date_checked_in` datetime NOT NULL,
  PRIMARY KEY  (`order_num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `old_transactions`
--

LOCK TABLES `old_transactions` WRITE;
/*!40000 ALTER TABLE `old_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `old_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `past_due_report`
--

DROP TABLE IF EXISTS `past_due_report`;
/*!50001 DROP VIEW IF EXISTS `past_due_report`*/;
/*!50001 CREATE TABLE `past_due_report` (
  `Order number` int(11),
  `Student ID` int(11),
  `Name` varchar(101),
  `Item name` varchar(50),
  `Due Date` datetime,
  `Current Date` datetime
) ENGINE=MyISAM */;

--
-- Temporary table structure for view `past_due_test`
--

DROP TABLE IF EXISTS `past_due_test`;
/*!50001 DROP VIEW IF EXISTS `past_due_test`*/;
/*!50001 CREATE TABLE `past_due_test` (
  `Order number` int(11),
  `Student ID` int(11),
  `concat(USER.f_name, " ", USER.l_name)` varchar(101),
  `name` varchar(50),
  `Due date` datetime,
  `Current date` datetime
) ENGINE=MyISAM */;

--
-- Temporary table structure for view `past_view_test`
--

DROP TABLE IF EXISTS `past_view_test`;
/*!50001 DROP VIEW IF EXISTS `past_view_test`*/;
/*!50001 CREATE TABLE `past_view_test` (
  `Order number` int(11),
  `Student ID` int(11),
  `Name` varchar(101),
  `Item name` varchar(50),
  `Due Date` datetime,
  `Current Date` datetime
) ENGINE=MyISAM */;

--
-- Temporary table structure for view `popular_categories`
--

DROP TABLE IF EXISTS `popular_categories`;
/*!50001 DROP VIEW IF EXISTS `popular_categories`*/;
/*!50001 CREATE TABLE `popular_categories` (
  `name` varchar(20),
  `times_checked_out` decimal(32,0)
) ENGINE=MyISAM */;

--
-- Final view structure for view `all_items_reserved`
--

/*!50001 DROP TABLE `all_items_reserved`*/;
/*!50001 DROP VIEW IF EXISTS `all_items_reserved`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `all_items_reserved` AS select `LINEITEM`.`ITEM_ID` AS `ITEM_ID` from (`CHECKOUT` join `LINEITEM` on((`CHECKOUT`.`ORDER_NUMBER` = `LINEITEM`.`ORDER_NUMBER`))) where (`CHECKOUT`.`DATE_CHECKED_OUT` > now()) */;

--
-- Final view structure for view `checked_out_items`
--

/*!50001 DROP TABLE `checked_out_items`*/;
/*!50001 DROP VIEW IF EXISTS `checked_out_items`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `checked_out_items` AS select `LINEITEM`.`ORDER_NUMBER` AS `Order Number`,concat(`USER`.`F_NAME`,_utf8' ',`USER`.`L_NAME`) AS `Checked out to`,`ITEM`.`NAME` AS `name`,`CHECKOUT`.`DUE_DATE` AS `Due Date`,if((`CHECKOUT`.`DUE_DATE` < now()),_utf8'Yes',_utf8'No') AS `Past Due` from (((`LINEITEM` join `CHECKOUT`) join `USER`) join `ITEM`) where ((`LINEITEM`.`ORDER_NUMBER` = `CHECKOUT`.`ORDER_NUMBER`) and (`CHECKOUT`.`STUDENT_ID` = `USER`.`STUDENT_ID`) and (`LINEITEM`.`ITEM_ID` = `ITEM`.`ITEM_ID`) and isnull(`CHECKOUT`.`DATE_CHECKED_IN`)) */;

--
-- Final view structure for view `items_available`
--

/*!50001 DROP TABLE `items_available`*/;
/*!50001 DROP VIEW IF EXISTS `items_available`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `items_available` AS select `ITEM`.`ITEM_ID` AS `ITEM_ID`,`ITEM`.`NAME` AS `ITEM NAME`,`ITEM`.`QUANTITY` AS `QUANTITY`,`ITEM`.`AVAILABLE` AS `AVAILABLE`,`ITEM`.`HALL_ID` AS `HALL_ID`,`CATEGORY`.`NAME` AS `CATEGORY NAME`,`ITEM`.`CATEGORY_ID` AS `CATEGORY_ID` from (`ITEM` join `CATEGORY` on((`ITEM`.`CATEGORY_ID` = `CATEGORY`.`CATEGORY_ID`))) where ((`ITEM`.`AVAILABLE` > 0) and (not(`ITEM`.`ITEM_ID` in (select `items_reserved_next_24h`.`ITEM_ID` AS `ITEM_ID` from `items_reserved_next_24h`)))) */;

--
-- Final view structure for view `items_reserved_next_24h`
--

/*!50001 DROP TABLE `items_reserved_next_24h`*/;
/*!50001 DROP VIEW IF EXISTS `items_reserved_next_24h`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `items_reserved_next_24h` AS select `LINEITEM`.`ITEM_ID` AS `ITEM_ID` from (`CHECKOUT` join `LINEITEM` on((`CHECKOUT`.`ORDER_NUMBER` = `LINEITEM`.`ORDER_NUMBER`))) where ((`CHECKOUT`.`DATE_CHECKED_OUT` > now()) and (`CHECKOUT`.`DATE_CHECKED_OUT` < (now() + interval 1 day))) */;

--
-- Final view structure for view `past_due_report`
--

/*!50001 DROP TABLE `past_due_report`*/;
/*!50001 DROP VIEW IF EXISTS `past_due_report`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `past_due_report` AS select `CHECKOUT`.`ORDER_NUMBER` AS `Order number`,`CHECKOUT`.`STUDENT_ID` AS `Student ID`,concat(`USER`.`F_NAME`,_utf8' ',`USER`.`L_NAME`) AS `Name`,`ITEM`.`NAME` AS `Item name`,`CHECKOUT`.`DUE_DATE` AS `Due Date`,now() AS `Current Date` from (((`CHECKOUT` join `USER`) join `LINEITEM`) join `ITEM`) where ((`CHECKOUT`.`STUDENT_ID` = `USER`.`STUDENT_ID`) and (`CHECKOUT`.`ORDER_NUMBER` = `LINEITEM`.`ORDER_NUMBER`) and (`LINEITEM`.`ITEM_ID` = `ITEM`.`ITEM_ID`) and (`CHECKOUT`.`DUE_DATE` < now())) */;

--
-- Final view structure for view `past_due_test`
--

/*!50001 DROP TABLE `past_due_test`*/;
/*!50001 DROP VIEW IF EXISTS `past_due_test`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `past_due_test` AS select `CHECKOUT`.`ORDER_NUMBER` AS `Order number`,`CHECKOUT`.`STUDENT_ID` AS `Student ID`,concat(`USER`.`F_NAME`,_utf8' ',`USER`.`L_NAME`) AS `concat(USER.f_name, " ", USER.l_name)`,`ITEM`.`NAME` AS `name`,`CHECKOUT`.`DUE_DATE` AS `Due date`,now() AS `Current date` from (((`CHECKOUT` join `USER`) join `LINEITEM`) join `ITEM`) where ((`CHECKOUT`.`STUDENT_ID` = `USER`.`STUDENT_ID`) and (`CHECKOUT`.`ORDER_NUMBER` = `LINEITEM`.`ORDER_NUMBER`) and (`LINEITEM`.`ITEM_ID` = `ITEM`.`ITEM_ID`) and (`CHECKOUT`.`DUE_DATE` < now())) */;

--
-- Final view structure for view `past_view_test`
--

/*!50001 DROP TABLE `past_view_test`*/;
/*!50001 DROP VIEW IF EXISTS `past_view_test`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `past_view_test` AS select `CHECKOUT`.`ORDER_NUMBER` AS `Order number`,`CHECKOUT`.`STUDENT_ID` AS `Student ID`,concat(`USER`.`F_NAME`,_utf8' ',`USER`.`L_NAME`) AS `Name`,`ITEM`.`NAME` AS `Item name`,`CHECKOUT`.`DUE_DATE` AS `Due Date`,now() AS `Current Date` from (((`CHECKOUT` join `USER`) join `LINEITEM`) join `ITEM`) where ((`CHECKOUT`.`STUDENT_ID` = `USER`.`STUDENT_ID`) and (`CHECKOUT`.`ORDER_NUMBER` = `LINEITEM`.`ORDER_NUMBER`) and (`LINEITEM`.`ITEM_ID` = `ITEM`.`ITEM_ID`) and (`CHECKOUT`.`DUE_DATE` < now())) */;

--
-- Final view structure for view `popular_categories`
--

/*!50001 DROP TABLE `popular_categories`*/;
/*!50001 DROP VIEW IF EXISTS `popular_categories`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`reslifedb`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `popular_categories` AS select `CATEGORY`.`NAME` AS `name`,sum(`ITEM`.`CHECKED_OUT_COUNT`) AS `times_checked_out` from (`CATEGORY` join `ITEM` on((`CATEGORY`.`CATEGORY_ID` = `ITEM`.`CATEGORY_ID`))) group by `CATEGORY`.`CATEGORY_ID` having (`times_checked_out` > 0) order by sum(`ITEM`.`CHECKED_OUT_COUNT`) desc */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-04-21 19:09:53
