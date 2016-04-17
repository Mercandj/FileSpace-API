SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema filespace
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `filespace` ;
CREATE SCHEMA IF NOT EXISTS `filespace` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `filespace` ;

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
  `id_file_profile_picture` INT NULL,
  `description` VARCHAR(999) NULL,  
  `language` VARCHAR(50) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  `android_apk_version` VARCHAR(45) NULL,
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
  `id_user` INT NOT NULL,
  `title` VARCHAR(500) NULL,
  `date_creation` DATETIME NULL,
  `type` VARCHAR(60) NULL,
  `content` VARCHAR(9999) NULL,  
  `description` VARCHAR(999) NULL,
  `request_uri` VARCHAR(9999) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `succeed` TINYINT NOT NULL DEFAULT 0,
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
  `is_apk_update` TINYINT NOT NULL DEFAULT 0,
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
-- Table `Support_Comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Support_Comment` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Support_Comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_device` VARCHAR(500) NULL,
  `is_dev_response` TINYINT NOT NULL DEFAULT 0,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `content` VARCHAR(999) NULL,
  `description` VARCHAR(999) NULL,
  `language` VARCHAR(50) NULL,
  `longitude` VARCHAR(80) NULL,
  `latitude` VARCHAR(80) NULL,
  `email` VARCHAR(199) NULL,
  `android_app_version_code` VARCHAR(45) NULL,
  `android_app_version_name` VARCHAR(45) NULL,
  `android_app_package` VARCHAR(80) NULL,
  `android_app_notification_id` VARCHAR(600) NULL,
  `android_device_model` VARCHAR(100) NULL,
  `android_device_manufacturer` VARCHAR(200) NULL,
  `android_device_version_os` VARCHAR(100) NULL,
  `android_device_display` VARCHAR(100) NULL,
  `android_device_bootloader` VARCHAR(100) NULL,
  `android_device_display_language` VARCHAR(100) NULL,
  `android_device_country` VARCHAR(190) NULL,
  `android_device_radio_version` VARCHAR(100) NULL,
  `android_device_version_sdk` VARCHAR(60) NULL,
  `android_device_version_incremental` VARCHAR(100) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Device` (notification)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Device` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Device` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `location` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `date_update` DATETIME NULL,
  `content` VARCHAR(999) NULL,
  `description` VARCHAR(999) NULL,
  `platform` VARCHAR(200) NULL,
  `android_app_gcm_id` VARCHAR(600) NULL,
  `android_app_version_code` VARCHAR(45) NULL,
  `android_app_version_name` VARCHAR(45) NULL,
  `android_app_package` VARCHAR(200) NULL,
  `android_device_model` VARCHAR(100) NULL,
  `android_device_manufacturer` VARCHAR(200) NULL,
  `android_device_version_os` VARCHAR(100) NULL,
  `android_device_display` VARCHAR(100) NULL,
  `android_device_bootloader` VARCHAR(100) NULL,
  `android_device_display_language` VARCHAR(100) NULL,
  `android_device_country` VARCHAR(190) NULL,
  `android_device_radio_version` VARCHAR(100) NULL,
  `android_device_version_sdk` VARCHAR(60) NULL,
  `android_device_version_incremental` VARCHAR(100) NULL,
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

-- -----------------------------------------------------
-- Table `Genealogy_Person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Genealogy_Person` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Genealogy_Person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name_1` VARCHAR(500) NULL,
  `first_name_2` VARCHAR(500) NULL,
  `first_name_3` VARCHAR(500) NULL,
  `last_name` VARCHAR(500) NULL,
  `is_man` TINYINT(1) NULL,
  `date_death` VARCHAR(500) NULL,
  `date_birth` VARCHAR(500) NULL,
  `birth_location` VARCHAR(500) NULL,
  `death_location` VARCHAR(500) NULL,
  `life_location` VARCHAR(500) NULL,
  `profession` VARCHAR(500) NULL,
  `id_father` INT NULL,
  `id_mother` INT NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `content` VARCHAR(999) NULL,
  `description` VARCHAR(999) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `Genealogy_Marriage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Genealogy_Marriage` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `Genealogy_Marriage` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_person_husband` INT NULL,
  `id_person_wife` INT NULL,
  `date` DATETIME NULL,
  `location` VARCHAR(500) NULL,
  `visibility` TINYINT NOT NULL DEFAULT 1,
  `public` TINYINT NOT NULL DEFAULT 0,
  `date_creation` DATETIME NULL,
  `content` VARCHAR(999) NULL,
  `description` VARCHAR(999) NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
