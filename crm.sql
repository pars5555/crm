/*
SQLyog Ultimate v11.52 (64 bit)
MySQL - 5.6.21 : Database - crm
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `currencies` */

CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iso` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_char` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol_position` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'left or right',
  `order_index` tinyint(4) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `currencies` */

LOCK TABLES `currencies` WRITE;

insert  into `currencies`(`id`,`iso`,`name`,`template_char`,`symbol_position`,`order_index`,`active`) values (1,'USD','United States Dollar','$','left',0,1);
insert  into `currencies`(`id`,`iso`,`name`,`template_char`,`symbol_position`,`order_index`,`active`) values (2,'AMD','Armenian Drams','Դր','right',1,1);

UNLOCK TABLES;

/*Table structure for table `manufacturers` */

CREATE TABLE `manufacturers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link` tinytext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `manufacturers` */

LOCK TABLES `manufacturers` WRITE;

insert  into `manufacturers`(`id`,`name`,`link`) values (1,'Acer','');
insert  into `manufacturers`(`id`,`name`,`link`) values (2,'Dell','');
insert  into `manufacturers`(`id`,`name`,`link`) values (3,'Toshiba','');
insert  into `manufacturers`(`id`,`name`,`link`) values (4,'Lenovo','');

UNLOCK TABLES;

/*Table structure for table `partners` */

CREATE TABLE `partners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` tinytext COLLATE utf8_unicode_ci,
  `create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `partners` */

LOCK TABLES `partners` WRITE;

insert  into `partners`(`id`,`name`,`email`,`address`,`create_date`) values (1,'partner1','aa@aa.aa','address',NULL);
insert  into `partners`(`id`,`name`,`email`,`address`,`create_date`) values (2,'aaa','dd@dd.dd','dfsdf',NULL);
insert  into `partners`(`id`,`name`,`email`,`address`,`create_date`) values (3,'bbb','bbb@bbb.bb','addd','2015-07-25 13:23:38');
insert  into `partners`(`id`,`name`,`email`,`address`,`create_date`) values (4,'sadfasd','bbb@bbb.bbb','111','2015-07-25 13:27:14');
insert  into `partners`(`id`,`name`,`email`,`address`,`create_date`) values (5,'sdfsdf','sdfsd@sdf.sdfs','sdsf','2015-07-25 13:28:14');
insert  into `partners`(`id`,`name`,`email`,`address`,`create_date`) values (6,'dfdfgh','dfgh@sdf.sdf','','2015-07-26 20:27:36');

UNLOCK TABLES;

/*Table structure for table `payment_methods` */

CREATE TABLE `payment_methods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `translation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `payment_methods` */

LOCK TABLES `payment_methods` WRITE;

insert  into `payment_methods`(`id`,`name`,`active`,`translation_id`) values (1,'cash',1,7);
insert  into `payment_methods`(`id`,`name`,`active`,`translation_id`) values (2,'bank_transfer',1,8);
insert  into `payment_methods`(`id`,`name`,`active`,`translation_id`) values (3,'credit_card',1,9);

UNLOCK TABLES;

/*Table structure for table `payment_transactions` */

CREATE TABLE `payment_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `partner_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `cancelled` tinyint(1) DEFAULT '0',
  `cancel_note` tinytext COLLATE utf8_unicode_ci,
  `note` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `payment_transactions` */

LOCK TABLES `payment_transactions` WRITE;

insert  into `payment_transactions`(`id`,`date`,`payment_method_id`,`partner_id`,`currency_id`,`amount`,`cancelled`,`cancel_note`,`note`) values (1,'2015-07-19 00:16:01',3,1,2,'0.02',1,'222',NULL);
insert  into `payment_transactions`(`id`,`date`,`payment_method_id`,`partner_id`,`currency_id`,`amount`,`cancelled`,`cancel_note`,`note`) values (2,'2015-07-19 00:16:02',3,1,1,'0.03',0,NULL,NULL);
insert  into `payment_transactions`(`id`,`date`,`payment_method_id`,`partner_id`,`currency_id`,`amount`,`cancelled`,`cancel_note`,`note`) values (3,'2015-07-19 00:16:00',3,1,1,'0.04',0,NULL,NULL);
insert  into `payment_transactions`(`id`,`date`,`payment_method_id`,`partner_id`,`currency_id`,`amount`,`cancelled`,`cancel_note`,`note`) values (4,'2015-07-26 13:54:00',1,5,1,'0.01',0,NULL,'ttgtgtg');
insert  into `payment_transactions`(`id`,`date`,`payment_method_id`,`partner_id`,`currency_id`,`amount`,`cancelled`,`cancel_note`,`note`) values (5,'2015-07-26 14:14:00',2,2,1,'15.00',1,'','wertgwert');

UNLOCK TABLES;

/*Table structure for table `pol_serial_numbers` */

CREATE TABLE `pol_serial_numbers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `line_id` int(11) DEFAULT NULL,
  `serial_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pol_serial_numbers` */

LOCK TABLES `pol_serial_numbers` WRITE;

UNLOCK TABLES;

/*Table structure for table `products` */

CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_unicode_ci,
  `model` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT '1' COMMENT 'Unit of Measurement',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `products` */

LOCK TABLES `products` WRITE;

insert  into `products`(`id`,`name`,`model`,`manufacturer_id`,`uom_id`) values (4,'dfgh','dfghd',1,1);
insert  into `products`(`id`,`name`,`model`,`manufacturer_id`,`uom_id`) values (5,'hhh','ggg',2,1);
insert  into `products`(`id`,`name`,`model`,`manufacturer_id`,`uom_id`) values (6,'fffff','ffff',1,1);

UNLOCK TABLES;

/*Table structure for table `purchase_order_lines` */

CREATE TABLE `purchase_order_lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` tinytext COLLATE utf8_unicode_ci,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `purchase_order_lines` */

LOCK TABLES `purchase_order_lines` WRITE;

UNLOCK TABLES;

/*Table structure for table `purchase_orders` */

CREATE TABLE `purchase_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_date` datetime DEFAULT NULL,
  `partner_id` int(11) DEFAULT NULL COMMENT 'Supplier',
  `note` tinytext COLLATE utf8_unicode_ci,
  `cancelled` tinyint(1) DEFAULT '0',
  `cancel_note` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `purchase_orders` */

LOCK TABLES `purchase_orders` WRITE;

UNLOCK TABLES;

/*Table structure for table `sale_order_lines` */

CREATE TABLE `sale_order_lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `currency` (`currency_id`),
  KEY `sale_order_id` (`sale_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sale_order_lines` */

LOCK TABLES `sale_order_lines` WRITE;

insert  into `sale_order_lines`(`id`,`sale_order_id`,`product_id`,`product_name`,`quantity`,`unit_price`,`currency_id`) values (35,4,5,'','0.30','0.01',1);
insert  into `sale_order_lines`(`id`,`sale_order_id`,`product_id`,`product_name`,`quantity`,`unit_price`,`currency_id`) values (36,4,4,'','0.10','0.02',2);
insert  into `sale_order_lines`(`id`,`sale_order_id`,`product_id`,`product_name`,`quantity`,`unit_price`,`currency_id`) values (37,4,4,'','0.20','0.02',2);
insert  into `sale_order_lines`(`id`,`sale_order_id`,`product_id`,`product_name`,`quantity`,`unit_price`,`currency_id`) values (38,9,4,'','0.20','0.02',2);
insert  into `sale_order_lines`(`id`,`sale_order_id`,`product_id`,`product_name`,`quantity`,`unit_price`,`currency_id`) values (39,1,4,'','1.00','0.04',1);

UNLOCK TABLES;

/*Table structure for table `sale_orders` */

CREATE TABLE `sale_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_date` datetime DEFAULT NULL,
  `partner_id` int(11) DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `cancelled` tinyint(1) DEFAULT '0',
  `cancel_note` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sale_orders` */

LOCK TABLES `sale_orders` WRITE;

insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (1,'2015-07-26 00:01:00',2,NULL,0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (2,'2015-07-26 00:03:00',2,NULL,0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (3,'2015-07-26 11:04:00',3,NULL,0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (4,'2015-07-26 11:05:00',5,NULL,0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (5,'2015-07-26 13:07:00',2,NULL,0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (6,'2015-07-26 13:07:00',5,NULL,0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (7,'2015-07-26 16:08:00',2,'',0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (8,'2015-07-26 16:08:00',2,'',0,NULL);
insert  into `sale_orders`(`id`,`order_date`,`partner_id`,`note`,`cancelled`,`cancel_note`) values (9,'2015-07-26 20:27:00',4,'',0,NULL);

UNLOCK TABLES;

/*Table structure for table `settings` */

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `var` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `settings` */

LOCK TABLES `settings` WRITE;

insert  into `settings`(`id`,`var`,`value`) values (1,'default_currency_id','1');
insert  into `settings`(`id`,`var`,`value`) values (2,'default_payment_method_id','1');
insert  into `settings`(`id`,`var`,`value`) values (3,'default_uom_id','1');

UNLOCK TABLES;

/*Table structure for table `sol_serial_numbers` */

CREATE TABLE `sol_serial_numbers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `line_id` int(11) DEFAULT NULL,
  `serial_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sol_serial_numbers` */

LOCK TABLES `sol_serial_numbers` WRITE;

UNLOCK TABLES;

/*Table structure for table `translations` */

CREATE TABLE `translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phrase_en` mediumtext,
  `phrase_am` mediumtext,
  `phrase_ru` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=682 DEFAULT CHARSET=utf8;

/*Data for the table `translations` */

LOCK TABLES `translations` WRITE;

insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (1,'Login','Մուտք','Вход');
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (3,'Email','Էլ. փոստ','Эл. почта');
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (4,'Password','Գաղտնաբառ','Пароль');
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (5,'Registration','Գրանցվել','Регистрация');
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (6,'Forgot password?','Մոռացե՞լ եք գաղտնաբառը','Забыли пароль');
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (7,'Cash',NULL,NULL);
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (8,'Bank Transfer',NULL,NULL);
insert  into `translations`(`id`,`phrase_en`,`phrase_am`,`phrase_ru`) values (9,'Credit Card',NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `uom` */

CREATE TABLE `uom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `uom` */

LOCK TABLES `uom` WRITE;

insert  into `uom`(`id`,`name`) values (1,'unit');
insert  into `uom`(`id`,`name`) values (2,'gram');
insert  into `uom`(`id`,`name`) values (3,'kg');
insert  into `uom`(`id`,`name`) values (4,'meter');
insert  into `uom`(`id`,`name`) values (5,'cm');
insert  into `uom`(`id`,`name`) values (6,'inch');
insert  into `uom`(`id`,`name`) values (7,'liter');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
