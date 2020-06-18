-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema acervo_digital_pessoal
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema acervo_digital_pessoal
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `acervo_digital_pessoal` DEFAULT CHARACTER SET utf8mb4 ;
USE `acervo_digital_pessoal` ;

-- -----------------------------------------------------
-- Table `acervo_digital_pessoal`.`disciplina`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `acervo_digital_pessoal`.`disciplina` ;

CREATE TABLE IF NOT EXISTS `acervo_digital_pessoal`.`disciplina` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` CHAR(250) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_nome` (`nome` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `acervo_digital_pessoal`.`conteudo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `acervo_digital_pessoal`.`conteudo` ;

CREATE TABLE IF NOT EXISTS `acervo_digital_pessoal`.`conteudo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_disciplina` INT(11) NOT NULL,
  `titulo` CHAR(250) NULL DEFAULT NULL,
  `texto` LONGTEXT NOT NULL,
  `arquivo` CHAR(250) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT INDEX `idx_texto` (`texto`),
  INDEX `fk_conteudo_disciplina_idx` (`id_disciplina` ASC),
  INDEX `idx_titulo` (`titulo` ASC),
  CONSTRAINT `fk_conteudo_disciplina`
    FOREIGN KEY (`id_disciplina`)
    REFERENCES `acervo_digital_pessoal`.`disciplina` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
