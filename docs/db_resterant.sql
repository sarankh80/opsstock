/*
SQLyog Community v11.51 (64 bit)
MySQL - 5.6.17 : Database - db_resterant
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_resterant` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_resterant`;

/*Table structure for table `tb_currency` */

DROP TABLE IF EXISTS `tb_currency`;

CREATE TABLE `tb_currency` (
  `cu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cu_name_en` varchar(100) DEFAULT NULL,
  `cu_name_km` varchar(100) DEFAULT NULL,
  `rate` float(18,3) DEFAULT NULL COMMENT 'promary dollar',
  `icon` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tb_currency` */

insert  into `tb_currency`(`cu_id`,`cu_name_en`,`cu_name_km`,`rate`,`icon`,`status`,`date`,`user_id`) values (1,'Dollar','ដុល្លារ',4000.000,'$',1,'2015-05-13',1),(2,'Riel','រៀល',4000.000,'៛',1,'2015-05-22',1);

/*Table structure for table `tb_language` */

DROP TABLE IF EXISTS `tb_language`;

CREATE TABLE `tb_language` (
  `id` int(10) DEFAULT NULL,
  `language` varchar(150) DEFAULT NULL,
  `language_short` varchar(120) DEFAULT NULL,
  `icon` varchar(765) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `create_date` varchar(150) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_language` */

insert  into `tb_language`(`id`,`language`,`language_short`,`icon`,`user_id`,`create_date`,`status`) values (1,'Englishs','en','Englishs.gif',1,'9 Apr 2015 08:14:00',1),(2,'Khmer','km','Khmer.gif',1,'9 Apr 2015 08:16:53',1);

/*Table structure for table `tb_order_reciept` */

DROP TABLE IF EXISTS `tb_order_reciept`;

CREATE TABLE `tb_order_reciept` (
  `or_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `so_id` int(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` float(18,3) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`or_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_order_reciept` */

/*Table structure for table `tb_order_reciept_detail` */

DROP TABLE IF EXISTS `tb_order_reciept_detail`;

CREATE TABLE `tb_order_reciept_detail` (
  `ord_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `or_id` int(10) DEFAULT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `unit_price` float(18,3) DEFAULT NULL,
  `amount` float(18,3) DEFAULT NULL,
  PRIMARY KEY (`ord_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_order_reciept_detail` */

/*Table structure for table `tb_pro_category` */

DROP TABLE IF EXISTS `tb_pro_category`;

CREATE TABLE `tb_pro_category` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name_en` varchar(255) DEFAULT NULL,
  `cat_name_km` varchar(300) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `tb_pro_category` */

insert  into `tb_pro_category`(`cat_id`,`cat_name_en`,`cat_name_km`,`parent_id`,`status`,`user_id`,`date`,`icon`) values (1,'Steamed Noodles','គុយទាវ',NULL,1,1,'2015-05-06','Steamed_Noodles.jpg'),(2,'Rice ','បាយ',NULL,1,3,'2015-05-17','RICE.jpg'),(3,'Coffe','កាហ្វេ',NULL,1,1,'2015-05-06','Coffe.jpg'),(4,'Beverage','ភេសជ្ជៈ',NULL,1,3,'2015-05-17','Beverage.jpg'),(5,'Other','ផ្សេងៗ',NULL,1,1,'2015-05-24','Other.jpg');

/*Table structure for table `tb_product` */

DROP TABLE IF EXISTS `tb_product`;

CREATE TABLE `tb_product` (
  `pro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `pro_code` varchar(20) DEFAULT NULL,
  `pro_name_en` varchar(255) DEFAULT NULL,
  `pro_name_km` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `cu_id` int(11) DEFAULT NULL,
  `price_in` float(18,3) DEFAULT NULL,
  `price_out` float(18,3) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `description` text,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `tb_product` */

insert  into `tb_product`(`pro_id`,`cat_id`,`pro_code`,`pro_name_en`,`pro_name_km`,`icon`,`cu_id`,`price_in`,`price_out`,`status`,`description`,`user_id`,`date`) values (1,1,'PC-00001','Steamed Noodles','គុយទាវ​','Steamed_Noodles.jpg',2,NULL,12000.000,1,'Steamed Noodles',1,'2015-05-24'),(2,2,'PC-00002','Rice white','បាយស','Rice_white.jpg',2,NULL,2000.000,1,'Rice white',1,'2015-05-24'),(3,3,'PC-00003','Black Coffe ','កាហ្វេ​ខ្មៅ','Black_Coffe_.jpg',2,NULL,2000.000,1,'Black Coffe ',1,'2015-05-24'),(4,4,'PC-00004','Pepsi','ប៉ិបស៊ី','Pepsi.png',2,NULL,2000.000,1,'Pepsi',1,'2015-05-24'),(5,2,'PC-00007','Rice Pig','បាយសាច់ជ្រូក','Rice_Pig.png',2,NULL,5000.000,1,'Rice Pig',1,'2015-05-24'),(6,2,'PC-00006','Rice Chicken','បាយសាច់មាន់','Rice_Chicken.png',2,NULL,10000.000,1,'Rice Chicken',1,'2015-05-24'),(7,4,'PC-00007','CoCa','កូកា','CoCa.png',2,NULL,2000.000,1,'CoCa',1,'2015-05-24');

/*Table structure for table `tb_sale_order` */

DROP TABLE IF EXISTS `tb_sale_order`;

CREATE TABLE `tb_sale_order` (
  `so_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) DEFAULT NULL,
  `tab_id` int(11) DEFAULT NULL COMMENT 'table id',
  `user_id` int(11) DEFAULT NULL,
  `total_dollar` float(18,2) DEFAULT NULL,
  `total_reil` float(18,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`so_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `tb_sale_order` */

insert  into `tb_sale_order`(`so_id`,`order_no`,`tab_id`,`user_id`,`total_dollar`,`total_reil`,`date`,`status`) values (1,'OR-00001',1,1,12.75,51000.00,'2015-05-24',1),(2,'OR-00002',2,1,4.25,17000.00,'2015-05-24',1),(3,'OR-00003',1,3,11.75,47000.00,'2015-05-25',1);

/*Table structure for table `tb_sale_order_detail` */

DROP TABLE IF EXISTS `tb_sale_order_detail`;

CREATE TABLE `tb_sale_order_detail` (
  `sod_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `so_id` int(11) DEFAULT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `unit_price` float(18,3) DEFAULT NULL,
  `amount` float(18,3) DEFAULT NULL,
  PRIMARY KEY (`sod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

/*Data for the table `tb_sale_order_detail` */

insert  into `tb_sale_order_detail`(`sod_id`,`so_id`,`pro_id`,`qty`,`unit_price`,`amount`) values (18,1,1,1,12000.000,12000.000),(19,1,2,1,2000.000,2000.000),(20,1,5,1,5000.000,5000.000),(21,1,6,1,10000.000,10000.000),(22,1,3,1,2000.000,2000.000),(23,1,7,10,2000.000,20000.000),(24,2,1,1,12000.000,12000.000),(25,2,5,1,5000.000,5000.000),(40,3,5,3,5000.000,15000.000),(41,3,2,2,2000.000,4000.000),(42,3,3,1,2000.000,2000.000),(43,3,4,1,2000.000,2000.000),(44,3,7,1,2000.000,2000.000),(45,3,6,1,10000.000,10000.000),(46,3,1,1,12000.000,12000.000);

/*Table structure for table `tb_stie_languages` */

DROP TABLE IF EXISTS `tb_stie_languages`;

CREATE TABLE `tb_stie_languages` (
  `id` int(10) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_stie_languages` */

insert  into `tb_stie_languages`(`id`,`language_id`,`active`,`status`) values (1,1,1,1),(2,2,0,1);

/*Table structure for table `tb_table` */

DROP TABLE IF EXISTS `tb_table`;

CREATE TABLE `tb_table` (
  `tab_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `name_km` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `description` text,
  `status` tinyint(4) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`tab_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tb_table` */

insert  into `tb_table`(`tab_id`,`code`,`name_en`,`name_km`,`icon`,`description`,`status`,`user_id`,`date`) values (1,'TB-001','Table 001','តុលេខ ០០១','Tb-001.jpg','Tb-001',1,1,'2015-05-24'),(2,'TB-002','Table 002','តុលេខ ០០២','Tb-002.jpg','Tb-002',1,1,'2015-05-24');

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` int(11) DEFAULT NULL,
  `user_code` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `photo` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tb_user` */

insert  into `tb_user`(`user_id`,`user_type`,`user_code`,`name`,`user_name`,`password`,`email`,`address`,`photo`,`status`,`date`) values (1,1,'ND-001','ខាន់​ ​សារ៉ាន់','administrator','21232f297a57a5a743894a0e4a801fc3','admin@gmail.com','admin','admin.jpg',1,'2015-05-22'),(2,1,'ND-002','អ្នកគិតលុយ','admin','2208639860dda3f5c6bf627bbe3657c7','​cashier@gmail.com',NULL,'saran.jpg',1,'2015-05-06');

/*Table structure for table `tb_user_type` */

DROP TABLE IF EXISTS `tb_user_type`;

CREATE TABLE `tb_user_type` (
  `user_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `tb_user_type` */

insert  into `tb_user_type`(`user_type_id`,`user_type`,`status`,`date`) values (1,'Administrator',1,'2015-05-04');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
