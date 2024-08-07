-- MySQL Script generated by MySQL Workbench
-- Sat Dec  3 09:24:53 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema db_bagshop01
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `db_bagshop01` ;

-- -----------------------------------------------------
-- Schema db_bagshop01
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_bagshop01` DEFAULT CHARACTER SET utf8 ;
SHOW WARNINGS;
USE `db_bagshop01` ;

-- -----------------------------------------------------
-- Table `tb_addproduct`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_addproduct` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_addproduct` (
  `addproduct_id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `addproduct_qty` INT NULL,
  `addproduct_status` INT NULL,
  `addproduct_datetime` DATETIME NULL,
  PRIMARY KEY (`addproduct_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_admin` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `admin_fullname` VARCHAR(50) NULL,
  `admin_email` VARCHAR(50) NULL,
  `admin_tel` VARCHAR(10) NULL,
  `admin_username` VARCHAR(50) NULL,
  `admin_password` VARCHAR(50) NULL,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_bank`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_bank` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_bank` (
  `bank_id` INT NOT NULL,
  `bank_type` VARCHAR(50) NULL,
  `bank_name` VARCHAR(50) NULL,
  `bank_bankname` VARCHAR(50) NULL,
  `bank_accountno` VARCHAR(50) NULL,
  `bank_status` INT NULL,
  `admin_id` INT NOT NULL,
  PRIMARY KEY (`bank_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_member` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_member` (
  `member_id` INT NOT NULL AUTO_INCREMENT,
  `member_fullname` VARCHAR(50) NULL,
  `member_address` VARCHAR(255) NULL,
  `member_tel` VARCHAR(10) NULL,
  `member_email` VARCHAR(50) NULL,
  `member_username` VARCHAR(50) NULL,
  `member_password` VARCHAR(50) NULL,
  `member_status` INT NULL,
  `member_datetime` DATETIME NULL,
  PRIMARY KEY (`member_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_order` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_order` (
  `order_id` INT NOT NULL,
  `order_no` VARCHAR(45) NULL,
  `order_price` FLOAT NULL,
  `order_status` INT NULL,
  `order_datetime` DATETIME NULL,
  PRIMARY KEY (`order_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_orderdetail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_orderdetail` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_orderdetail` (
  `orderdetail_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `orderdetail_qty` INT NULL,
  `member_id` INT NOT NULL,
  `orderdetail_datetime` DATETIME NULL,
  `orderdetail_status` VARCHAR(45) NULL,
  PRIMARY KEY (`orderdetail_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_paymeny`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_paymeny` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_paymeny` (
  `paymeny_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `paymeny_no` VARCHAR(10) NULL,
  `paymeny_by` VARCHAR(50) NULL,
  `bank_id` INT NOT NULL,
  `paymeny_price` FLOAT NULL,
  `member_id` INT NOT NULL,
  `paymeny_date` DATETIME NULL,
  `paymeny_status` INT NULL,
  PRIMARY KEY (`paymeny_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_preorder`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_preorder` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_preorder` (
  `preorder_id` INT NOT NULL AUTO_INCREMENT,
  `preorder_no` VARCHAR(50) NULL,
  `preorder_price` FLOAT NULL,
  `member_id` INT NOT NULL,
  `preorder_status` INT NULL,
  `preorder_datetime` DATETIME NULL,
  PRIMARY KEY (`preorder_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_preorderdetial`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_preorderdetial` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_preorderdetial` (
  `preorderdetial_id` INT NOT NULL AUTO_INCREMENT,
  `preorder_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `member_id` INT NOT NULL,
  `preorderdetial_qty` INT NULL,
  `preorderdetial_price` FLOAT NULL,
  `preorderdetial_status` INT NULL,
  `preorderdetial_datetime` DATETIME NULL,
  PRIMARY KEY (`preorderdetial_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_product` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_product` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `product_serial_no` VARCHAR(50) NULL,
  `type_id` INT NOT NULL,
  `product_name` VARCHAR(50) NULL,
  `product_detail` TEXT NULL,
  `product_qty` INT NULL,
  `product_min_qty` VARCHAR(45) NULL,
  `product_price` FLOAT NOT NULL,
  `product_wieght` FLOAT NULL,
  `product_pic` VARCHAR(255) NULL,
  `product_status` INT NULL,
  `product_date` DATETIME NULL,
  `admin_id` INT NOT NULL,
  PRIMARY KEY (`product_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_rateshipping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_rateshipping` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_rateshipping` (
  `rateshipping_id` INT NOT NULL AUTO_INCREMENT,
  `rateshipping_weight` FLOAT NULL,
  `rateshipping_province` TEXT NULL,
  `rateshipping_rates` VARCHAR(45) NULL,
  `rateshipping_status` INT NULL,
  `rateshipping_datetime` DATETIME NULL,
  `admin_id` INT NOT NULL,
  PRIMARY KEY (`rateshipping_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_return`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_return` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_return` (
  `return_id` INT NOT NULL AUTO_INCREMENT,
  `return_no` VARCHAR(45) NULL,
  `return_remark` TEXT NULL,
  `product_id` INT NOT NULL,
  `return_qty` INT NULL,
  `member_id` INT NOT NULL,
  `return_status` INT NULL,
  `return_datetime` DATETIME NULL,
  PRIMARY KEY (`return_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_shipping`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_shipping` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_shipping` (
  `shipping_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `shipping_trackingno` VARCHAR(50) NULL,
  `shipping_address` TEXT NULL,
  `shipping_district` VARCHAR(50) NULL,
  `shipping_subdistrict` VARCHAR(50) NULL,
  `shipping_province` VARCHAR(50) NULL,
  `shipping_postcode` VARCHAR(10) NULL,
  `shipping_by` VARCHAR(45) NULL,
  `shipping_cost` VARCHAR(45) NULL,
  `member_id` INT NOT NULL,
  `shipping_status` INT NULL,
  `shipping_datetime` DATETIME NULL,
  PRIMARY KEY (`shipping_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `tb_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tb_type` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `tb_type` (
  `type_id` INT NOT NULL AUTO_INCREMENT,
  `type_name` VARCHAR(50) NULL,
  PRIMARY KEY (`type_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
