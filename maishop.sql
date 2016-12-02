/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : maishop

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-12-02 17:25:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理者ID',
  `name` varchar(40) NOT NULL COMMENT '管理者名',
  `login_id` varchar(40) NOT NULL COMMENT 'ログインID',
  `password` varchar(255) NOT NULL COMMENT 'パスワード',
  `admin_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:ambassador,1:admin',
  `disable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created` int(11) DEFAULT NULL COMMENT '作成日',
  `updated` int(11) DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`login_id`),
  KEY `disable` (`disable`),
  KEY `admin_type` (`admin_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='ユーザテーブル';

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', 'AnhMH', 'anhmh', 'iTJ6h6xDs1W4dzslL9-O-XU1Z3BFZXJyaUNUcTJVX1Q2X0hlWGNodFV4SmN4TWxOenRxUHh6dWlTU1U', '1', '0', '1477366927', '1477379816');

-- ----------------------------
-- Table structure for attributes
-- ----------------------------
DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `disable` tinyint(4) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of attributes
-- ----------------------------

-- ----------------------------
-- Table structure for authenticates
-- ----------------------------
DROP TABLE IF EXISTS `authenticates`;
CREATE TABLE `authenticates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(11) NOT NULL COMMENT '`user_id/admin_id base on type',
  `token` varchar(255) NOT NULL COMMENT 'トークン',
  `expire_date` int(11) NOT NULL COMMENT 'トークンの期限',
  `regist_type` varchar(20) NOT NULL COMMENT 'user/admin',
  `created` int(11) DEFAULT NULL COMMENT '作成日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of authenticates
-- ----------------------------
INSERT INTO `authenticates` VALUES ('1', '1', '7f03f8b9a0d615e549a571a065cd705a8096267ce6e95a5f757ffd7fab3716fdbef0b1c3b252427813d1af0830c4344e89089b9187f51e7afafbac1cb2e18a70', '1482647285', 'admin', '1477368345');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `disable` tinyint(4) DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', 'Guong', 'http://front.maishop.localhost/img/Chair-60x50.png', '1', '0', '1480408427', '1480408427');
INSERT INTO `categories` VALUES ('2', 'Ban trang diem', 'http://front.maishop.localhost/img/Light-60x50.png', '2', '0', '1480408444', '1480408444');
INSERT INTO `categories` VALUES ('8', 'Goi', 'http://img.maishop.localhost/2016/11/29/f1c9130197ac1b435e01004153028e9b.jpg', '2', '1', '1480412540', '1480412540');

-- ----------------------------
-- Table structure for districts
-- ----------------------------
DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts` (
  `districtid` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL,
  `provinceid` varchar(5) NOT NULL,
  PRIMARY KEY (`districtid`),
  KEY `provinceid` (`provinceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of districts
-- ----------------------------

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) DEFAULT NULL,
  `note` text,
  `address` varchar(255) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `is_paid` tinyint(4) DEFAULT '0',
  `is_cancel` tinyint(4) DEFAULT '0',
  `disable` tinyint(4) DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_deposit` tinyint(4) DEFAULT NULL,
  `deposit_money` float DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `district_id` (`district_id`),
  KEY `province_id` (`province_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES ('1', '123', 'asd asd asd ', 'aaa', '123', '123', '23131', '1', '1', '0', '1478847398', '1478847398', '1', '1', '233', 'aaaa');

-- ----------------------------
-- Table structure for order_products
-- ----------------------------
DROP TABLE IF EXISTS `order_products`;
CREATE TABLE `order_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order_products
-- ----------------------------
INSERT INTO `order_products` VALUES ('1', '1', '1', '1478851343', '1478851343', '3');
INSERT INTO `order_products` VALUES ('2', '1', '7', '1479438177', '1479438177', '2');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `is_feature` tinyint(4) DEFAULT '0',
  `disable` tinyint(4) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', '1', '120', '1', '0', '1474007579', '1474007579', '10');
INSERT INTO `products` VALUES ('7', '1', '200', null, '0', '1477388727', '1477388727', '1');

-- ----------------------------
-- Table structure for product_attributes
-- ----------------------------
DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_attributes
-- ----------------------------

-- ----------------------------
-- Table structure for product_images
-- ----------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_default` tinyint(4) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `disable` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_images
-- ----------------------------
INSERT INTO `product_images` VALUES ('1', '1', 'http://admin.maishop.localhost/img/logo.png', '1', '1477380580', '1477380580', '0');
INSERT INTO `product_images` VALUES ('2', '1', 'http://admin.maishop.localhost/img/logo.png', '0', '1477463499', '1477463499', '0');
INSERT INTO `product_images` VALUES ('3', '1', 'http://admin.maishop.localhost/img/logo.png', '0', '1477463515', '1477463515', '0');
INSERT INTO `product_images` VALUES ('4', '1', 'http://admin.maishop.localhost/img/logo.png', null, '1477463524', '1477463524', '0');

-- ----------------------------
-- Table structure for product_informations
-- ----------------------------
DROP TABLE IF EXISTS `product_informations`;
CREATE TABLE `product_informations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `detail` text,
  `language_type` int(11) DEFAULT '1',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_informations
-- ----------------------------
INSERT INTO `product_informations` VALUES ('1', '1', 'product 1 updated', 'aasda sad asd Công ty TNHH Nội Thất ABC tự hào là một trong những công ty hàng đầu về tư vấn thiết kế và thi công các công trình nội thất. Công ty đã có một bề dày kinh nghiệm chuyên về thiết kế kiến trúc - nội thấ sad a123', '<strong>sadasd asdas </strong><u><em>sadasda sad<img alt=\"\" src=\"http://img.maishop.localhost/images/Koala.jpg\" style=\"width: 100px; height: 75px;\" /><br />\r\nsadas<br />\r\n&nbsp;as<br />\r\nd as<br />\r\ndas&nbsp;</em></u><br />\r\n&nbsp;Công ty TNHH Nội Thất ABC tự hào là một trong những công ty hàng đầu về tư vấn thiết kế và thi công các công trình nội thất. Công ty đã có một bề dày kinh nghiệm chuyên về thiết kế kiến trúc - nội thấ 123 sa da 1 2', '1', '1477380304', '1477380304');
INSERT INTO `product_informations` VALUES ('3', '6', 'product 2', null, null, null, '1477387057', '1477387057');
INSERT INTO `product_informations` VALUES ('4', '7', 'Product 3', 'abc as asdCông ty TNHH Nội Thất ABC tự hào là một trong những công ty hàng đầu về tư vấn thiết kế và thi công các công trình nội thất. Công ty đã có một bề dày kinh nghiệm chuyên về thiết kế kiến trúc - nội thấ asd asd 21 sad213', '213213Công ty TNHH Nội Thất ABC tự hào là một trong những công ty hàng đầu về tư vấn thiết kế và thi công các công trình nội thất. Công ty đã có một bề dày kinh nghiệm chuyên về thiết kế kiến trúc - nội thấ sad asd as', '1', '1477388727', '1477388727');

-- ----------------------------
-- Table structure for provinces
-- ----------------------------
DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces` (
  `provinceid` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`provinceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of provinces
-- ----------------------------

-- ----------------------------
-- Table structure for sub_categories
-- ----------------------------
DROP TABLE IF EXISTS `sub_categories`;
CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `cate_id` int(11) DEFAULT NULL,
  `disable` tinyint(4) DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sub_categories
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `district_id` varchar(5) DEFAULT NULL,
  `province_id` varchar(5) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_admin` tinyint(4) DEFAULT '0',
  `count_product` int(11) DEFAULT NULL,
  `disable` tinyint(4) DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------

-- ----------------------------
-- Table structure for user_products
-- ----------------------------
DROP TABLE IF EXISTS `user_products`;
CREATE TABLE `user_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_products
-- ----------------------------

-- ----------------------------
-- Table structure for wards
-- ----------------------------
DROP TABLE IF EXISTS `wards`;
CREATE TABLE `wards` (
  `wardid` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `location` varchar(30) NOT NULL,
  `districtid` varchar(5) NOT NULL,
  PRIMARY KEY (`wardid`),
  KEY `districtid` (`districtid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wards
-- ----------------------------
DROP TRIGGER IF EXISTS `before_insert_admins`;
DELIMITER ;;
CREATE TRIGGER `before_insert_admins` BEFORE INSERT ON `admins` FOR EACH ROW SET
	new.created = UNIX_TIMESTAMP(),
	new.updated = UNIX_TIMESTAMP()
;
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_update_admins`;
DELIMITER ;;
CREATE TRIGGER `before_update_admins` BEFORE UPDATE ON `admins` FOR EACH ROW SET 
	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_attributes`;
DELIMITER ;;
CREATE TRIGGER `before_insert_attributes` BEFORE INSERT ON `attributes` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_authenticates`;
DELIMITER ;;
CREATE TRIGGER `before_insert_authenticates` BEFORE INSERT ON `authenticates` FOR EACH ROW SET 
	new.created = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_categories`;
DELIMITER ;;
CREATE TRIGGER `before_insert_categories` BEFORE INSERT ON `categories` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_orders`;
DELIMITER ;;
CREATE TRIGGER `before_insert_orders` BEFORE INSERT ON `orders` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_order_products`;
DELIMITER ;;
CREATE TRIGGER `before_insert_order_products` BEFORE INSERT ON `order_products` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_products`;
DELIMITER ;;
CREATE TRIGGER `before_insert_products` BEFORE INSERT ON `products` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_product_attributes`;
DELIMITER ;;
CREATE TRIGGER `before_insert_product_attributes` BEFORE INSERT ON `product_attributes` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_product_images`;
DELIMITER ;;
CREATE TRIGGER `before_insert_product_images` BEFORE INSERT ON `product_images` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_product_informations`;
DELIMITER ;;
CREATE TRIGGER `before_insert_product_informations` BEFORE INSERT ON `product_informations` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_sub_categories`;
DELIMITER ;;
CREATE TRIGGER `before_insert_sub_categories` BEFORE INSERT ON `sub_categories` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_users`;
DELIMITER ;;
CREATE TRIGGER `before_insert_users` BEFORE INSERT ON `users` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `before_insert_user_products`;
DELIMITER ;;
CREATE TRIGGER `before_insert_user_products` BEFORE INSERT ON `user_products` FOR EACH ROW SET 

	new.created = UNIX_TIMESTAMP(),

	new.updated = UNIX_TIMESTAMP()
;;
DELIMITER ;
