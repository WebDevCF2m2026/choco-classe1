-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema choco
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `choco` ;

-- -----------------------------------------------------
-- Schema choco
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `choco` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `choco` ;

-- -----------------------------------------------------
-- Table `choco`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `choco`.`user` ;

CREATE TABLE IF NOT EXISTS `choco`.`user` (
                                              `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                              `user_login` VARCHAR(50) NOT NULL,
                                              `user_pwd` VARCHAR(255) NOT NULL,
                                              `user_full_name` VARCHAR(100) NULL,
                                              `user_email` VARCHAR(100) NOT NULL,
                                              `user_role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
                                              PRIMARY KEY (`user_id`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_login_UNIQUE` ON `choco`.`user` (`user_login` ASC) VISIBLE;


-- -----------------------------------------------------
-- Table `choco`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `choco`.`article` ;

CREATE TABLE IF NOT EXISTS `choco`.`article` (
                                                 `article_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                                 `article_title` VARCHAR(180) NOT NULL,
                                                 `article_slug` VARCHAR(184) NOT NULL,
                                                 `article_text` TEXT NOT NULL,
                                                 `article_create_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                                                 `article_validate_at` DATETIME NULL,
                                                 `article_status` ENUM('publié', 'en attente', 'désactivé') NULL DEFAULT 'publié',
                                                 `user_user_id` INT UNSIGNED NOT NULL,
                                                 PRIMARY KEY (`article_id`),
                                                 CONSTRAINT `fk_article_user`
                                                     FOREIGN KEY (`user_user_id`)
                                                         REFERENCES `choco`.`user` (`user_id`)
                                                         ON DELETE NO ACTION
                                                         ON UPDATE NO ACTION)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `article_slug_UNIQUE` ON `choco`.`article` (`article_slug` ASC) VISIBLE;

CREATE INDEX `fk_article_user_idx` ON `choco`.`article` (`user_user_id` ASC) VISIBLE;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
