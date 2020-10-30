-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema app_coches
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema app_coches
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `app_coches` DEFAULT CHARACTER SET utf8 ;
USE `app_coches` ;

-- -----------------------------------------------------
-- Table `app_coches`.`Personas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_coches`.`Personas` (
  `idPersona` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellidos` VARCHAR(45) NOT NULL,
  `dni` VARCHAR(9) NOT NULL,
  PRIMARY KEY (`idPersona`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `app_coches`.`Vendedores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_coches`.`Vendedores` (
  `idVendedor` INT NOT NULL AUTO_INCREMENT,
  `Personas_idPersona` INT NOT NULL,
  PRIMARY KEY (`idVendedor`),
  INDEX `fk_Vendedores_Personas1_idx` (`Personas_idPersona` ASC),
  CONSTRAINT `fk_Vendedores_Personas1`
    FOREIGN KEY (`Personas_idPersona`)
    REFERENCES `app_coches`.`Personas` (`idPersona`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `app_coches`.`Coches`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_coches`.`Coches` (
  `idCoche` INT NOT NULL AUTO_INCREMENT,
  `Vendedores_idVendedor` INT NOT NULL,
  `matricula` VARCHAR(45) NOT NULL,
  `marca` VARCHAR(30) NOT NULL,
  `modelo` VARCHAR(45) NOT NULL,
  `combustible` VARCHAR(20) NOT NULL,
  `color` VARCHAR(20) NOT NULL,
  `precio` INT(7) NOT NULL,
  PRIMARY KEY (`idCoche`),
  INDEX `fk_Coches_Vendedores1_idx` (`Vendedores_idVendedor` ASC),
  CONSTRAINT `fk_Coches_Vendedores1`
    FOREIGN KEY (`Vendedores_idVendedor`)
    REFERENCES `app_coches`.`Vendedores` (`idVendedor`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `app_coches`.`Compradores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_coches`.`Compradores` (
  `idComprador` INT NOT NULL AUTO_INCREMENT,
  `Personas_idPersona` INT NOT NULL,
  PRIMARY KEY (`idComprador`),
  INDEX `fk_Compradores_Personas_idx` (`Personas_idPersona` ASC),
  CONSTRAINT `fk_Compradores_Personas`
    FOREIGN KEY (`Personas_idPersona`)
    REFERENCES `app_coches`.`Personas` (`idPersona`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `app_coches`.`Transacciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_coches`.`Transacciones` (
  `idTransaccion` INT NOT NULL AUTO_INCREMENT,
  `Vendedores_idVendedor` INT NOT NULL,
  `Compradores_idComprador` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`idTransaccion`),
  INDEX `fk_Transacciones_Vendedores1_idx` (`Vendedores_idVendedor` ASC),
  INDEX `fk_Transacciones_Compradores1_idx` (`Compradores_idComprador` ASC),
  CONSTRAINT `fk_Transacciones_Vendedores1`
    FOREIGN KEY (`Vendedores_idVendedor`)
    REFERENCES `app_coches`.`Vendedores` (`idVendedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Transacciones_Compradores1`
    FOREIGN KEY (`Compradores_idComprador`)
    REFERENCES `app_coches`.`Compradores` (`idComprador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `app_coches`.`Transacciones_has_Coches`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `app_coches`.`Transacciones_has_Coches` (
  `Transacciones_idTransaccion` INT NOT NULL,
  `Coches_idCoche` INT NOT NULL,
  PRIMARY KEY (`Transacciones_idTransaccion`, `Coches_idCoche`),
  INDEX `fk_Transacciones_has_Coches_Coches1_idx` (`Coches_idCoche` ASC),
  INDEX `fk_Transacciones_has_Coches_Transacciones1_idx` (`Transacciones_idTransaccion` ASC),
  CONSTRAINT `fk_Transacciones_has_Coches_Transacciones1`
    FOREIGN KEY (`Transacciones_idTransaccion`)
    REFERENCES `app_coches`.`Transacciones` (`idTransaccion`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Transacciones_has_Coches_Coches1`
    FOREIGN KEY (`Coches_idCoche`)
    REFERENCES `app_coches`.`Coches` (`idCoche`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
