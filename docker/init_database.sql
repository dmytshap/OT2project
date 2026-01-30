SET @SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema PROJECTS
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `PROJECTS` ;

-- -----------------------------------------------------
-- Schema PROJECTS
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `PROJECTS` DEFAULT CHARACTER SET utf8 ;
USE `PROJECTS` ;

-- -----------------------------------------------------
-- Table `PROJECTS`.`PROJECT_DATA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `PROJECTS`.`PROJECT_DATA` ;

CREATE TABLE IF NOT EXISTS `PROJECTS`.`PROJECT_DATA` (
  `PROJECT_ID` INT NOT NULL AUTO_INCREMENT,
  `SHORT_DESC` VARCHAR(45) NOT NULL,
  `PHONE` VARCHAR(45) NOT NULL,
  `EMAIL` VARCHAR(45) NOT NULL,
  `COMPANY` VARCHAR(45) NOT NULL,
  `CONTACT_TIME` DATETIME NOT NULL,
  `RESPOND_BY` DATETIME,
  `LONG_DESC` LONGTEXT NOT NULL,
  PRIMARY KEY (`PROJECT_ID`)
  );


-- -----------------------------------------------------
-- Insert fake data
-- -----------------------------------------------------
INSERT INTO `PROJECTS`.`PROJECT_DATA`(
  SHORT_DESC,
  PHONE,
  EMAIL,
  COMPANY,
  CONTACT_TIME,
  RESPOND_BY,
  LONG_DESC
) VALUES (
  "Nettisivu koodausideoiden keräämiseksi"
  , "0401234567"
  , "notmymail@hotmail.com"
  , "NotMyCompany"
  , NOW()
  , NULL
  , "Nettisivun tarkoituksena on kerätä ideoita ja helpottaa opettajan työtä. Lorem ipsum dolores magnificum..."
), (
  "Tämä toinen idea on vielä parempi"
  , "0507654321"
  , "yeaah@yeaah.com"
  , "Yaah Company"
  , NOW()
  , NULL
  , "Mitähän ihmettä tänne kannattaisi kirjoittaa? Tuleeko hyviä ideoita? Tämän tekstin pitäisi olla tarpeeksi pitkä, jotta sen avulla voidaan testata nettisivun ulkoasua. Pystyykö nettisivu näyttämään pitkän tekstin oikein ja huolehtimaan esimerkiksi rivinvaihdosta?"
);
