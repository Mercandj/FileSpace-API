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
  `date_creation` DATETIME NULL,
  `date_last_connection` DATETIME NULL,
  `first_name` VARCHAR(100) NULL,
  `last_name` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL,
  `age` TINYINT NULL,
  `sexe` TINYINT(1) NULL,
  `admin` TINYINT(1) NOT NULL DEFAULT '0',
  `id_file_picture` INT NULL,
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
-- Table `User_Group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User_Group` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `User_Group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_user` INT NOT NULL,
  `id_user_recipient` INT NOT NULL,
  `description` VARCHAR(999) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `User_Connection`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User_Connection` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `User_Connection` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NULL,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_user` INT NOT NULL,
  `description` VARCHAR(999) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Conversation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Conversation` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Conversation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT NOT NULL,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `to_yourself` TINYINT NOT NULL DEFAULT 0,
  `to_all` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_file_picture` INT NULL,
  `description` VARCHAR(999) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Conversation_User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Conversation_User` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Conversation_User` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_conversation` INT NOT NULL,
  `id_user` INT NOT NULL,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_file_picture` INT NULL,
  `description` VARCHAR(999) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Conversation_Message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Conversation_Message` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Conversation_Message` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_user` INT NOT NULL,
  `id_conversation` INT NOT NULL,
  `id_file` INT NULL,
  `description` VARCHAR(999) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
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
  `name` VARCHAR(500) NOT NULL,
  `size` INT UNSIGNED NOT NULL DEFAULT 0,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `number_read` INT UNSIGNED NULL,
  `number_download` INT UNSIGNED NULL,
  `directory` TINYINT NOT NULL DEFAULT 0,
  `id_user` INT NOT NULL,
  `id_file_parent` INT NOT NULL DEFAULT -1,
  `description` VARCHAR(999) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `File_Download`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `File_Download` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `File_Download` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_user` INT NOT NULL,
  `id_file` INT NOT NULL,
  `description` VARCHAR(999) NULL,  
  `size` INT UNSIGNED NOT NULL DEFAULT 0,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `File_Upload`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `File_Upload` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `File_Upload` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_user` INT NOT NULL,
  `id_file` INT NOT NULL,
  `description` VARCHAR(999) NULL,  
  `size` INT UNSIGNED NOT NULL DEFAULT 0,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Server_Daemon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Server_Daemon` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Server_Daemon` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `running` TINYINT NOT NULL DEFAULT 0,
  `activate` TINYINT NOT NULL DEFAULT 0,
  `id_server_daemon` INT NOT NULL DEFAULT -1,
  `sleep_second` BIGINT NOT NULL DEFAULT 3600,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `id_user` INT NULL,
  `id_file` INT NULL,
  `description` VARCHAR(999) NULL,  
  `size` INT UNSIGNED NOT NULL DEFAULT 0,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Server_Daemon_Ping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Server_Daemon_Ping` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Server_Daemon_Ping` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_server_daemon` INT NOT NULL,
  `title` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,
  `description` VARCHAR(999) NULL,  
  `size` INT UNSIGNED NOT NULL DEFAULT 0,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
