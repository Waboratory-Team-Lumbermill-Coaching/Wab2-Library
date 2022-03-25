DROP DATABASE IF EXISTS `softuni_library`;
CREATE DATABASE `softuni_library`;

CREATE TABLE `softuni_library`.`users` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `username` VARCHAR(255) NULL ,
 `password` VARCHAR(255) NOT NULL ,
 `full_name` VARCHAR(255) NOT NULL ,
 `born_on` DATE NULL ,

 PRIMARY KEY (`id`),
 UNIQUE (`username`)
) ENGINE = InnoDB;

CREATE TABLE `softuni_library`.`genres` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `name` VARCHAR(50) NULL ,

 PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `softuni_library`.`books` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(50) NULL ,
  `author` VARCHAR(50) NULL ,
  `description` TEXT NULL ,
  `image` VARCHAR(255) NULL ,
  `genre_id` INT NULL ,
  `user_id` INT NULL ,
  `added_on` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,

   PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `softuni_library`.`genres` (`id`, `name`)
VALUES
(NULL, 'Drama'),
(NULL, 'Educational'),
(NULL, 'Adventure')