
DROP TABLE IF EXISTS `tbldosageplanner`;
CREATE TABLE `tbldosageplanner`
(
  `plan_id` int NOT NULL AUTO_INCREMENT,
  `medicine_id` int NOT NULL,
  `date_taken` varchar
(20) NOT NULL,
  `time_taken` varchar
(10) NOT NULL,
  `user_id` int NOT NULL,
  `date_inputted` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY
(`plan_id`),
  KEY `FK_Plan_Medicine`
(`medicine_id`),
  KEY `FK_Plan_User`
(`user_id`),
  CONSTRAINT `FK_Plan_Medicine` FOREIGN KEY
(`medicine_id`) REFERENCES `tblmedicine`
(`medicine_id`) ON
DELETE CASCADE ON
UPDATE CASCADE,
  CONSTRAINT `FK_Plan_User` FOREIGN KEY
(`user_id`) REFERENCES `userregister`
(`UserID`) ON
DELETE CASCADE ON
UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET
=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `tbldosageplanner` WRITE;
UNLOCK TABLES;


DROP TABLE IF EXISTS `tblmedicine`;
CREATE TABLE `tblmedicine`
(
  `medicine_id` int NOT NULL AUTO_INCREMENT,
  `medicine_name` varchar
(45) NOT NULL,
  `dosage_qty` int NOT NULL,
  `dosage_unit` varchar
(45) NOT NULL,
  `grams` int NOT NULL,
  `grams_unit` varchar
(45) NOT NULL DEFAULT 'Mg',
  `frequency_qty` int NOT NULL,
  `frequency_unit` varchar
(45) NOT NULL,
  `UserId` int NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY
(`medicine_id`),
  KEY `FK_Medicine_UserID`
(`UserId`),
  CONSTRAINT `FK_Medicine_UserID` FOREIGN KEY
(`UserId`) REFERENCES `userregister`
(`UserID`) ON
DELETE CASCADE ON
UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET
=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `userregister`;
CREATE TABLE `userregister`
(
  `UserID` int NOT NULL AUTO_INCREMENT,
  `UserName` varchar
(50) NOT NULL,
  `UserPassword` varchar
(200) NOT NULL,
  `FullName` varchar
(100) NOT NULL,
  `Email` varchar
(150) NOT NULL,
  `RegistrationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY
(`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;



