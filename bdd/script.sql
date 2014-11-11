SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema jarvis
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `jarvis` ;
CREATE SCHEMA IF NOT EXISTS `jarvis` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `jarvis` ;

-- -----------------------------------------------------
-- Table `User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `token` VARCHAR(500) NULL,
  `date_creation` DATETIME NULL,
  `date_last_connection` DATETIME NULL,
  `first_name` VARCHAR(100) NULL,
  `last_name` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL,
  `age` TINYINT NULL,
  `sexe` TINYINT(1) NULL,
  `admin` TINYINT(1) NULL,
  `url_image_profil` VARCHAR(100) NULL,
  `description` VARCHAR(999) NULL,  
  `language` VARCHAR(50) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  `android_jarvis_version` VARCHAR(45) NULL,
  `android_sdk` VARCHAR(60) NULL,
  `android_id` VARCHAR(600) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `File`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `File` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `File` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `url` VARCHAR(500) NOT NULL,
  `size` INT UNSIGNED NULL,
  `visibility` TINYINT NULL,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `number_read` INT UNSIGNED NULL,
  `number_download` INT UNSIGNED NULL,
  `id_User` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
