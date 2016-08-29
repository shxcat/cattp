# ************************************************************
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: think_admin_sys
# Generation Time: 2016-07-27 10:10:09 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tp_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tp_admins`;

CREATE TABLE `tp_admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码MD5',
  `salt` char(6) NOT NULL DEFAULT '',
  `realname` varchar(60) DEFAULT '' COMMENT '联系人',
  `avatar` varchar(200) DEFAULT '' COMMENT '头像',
  `gender` tinyint(1) DEFAULT '0' COMMENT '性别, 0保密, 1男 2女',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机',
  `email` varchar(200) DEFAULT '' COMMENT '邮件',
  `last_ip` varchar(15) DEFAULT '' COMMENT '最后登录IP',
  `login_ip` varchar(15) DEFAULT '' COMMENT '登录IP',
  `last_time` int(10) DEFAULT '0' COMMENT '最后登录时间',
  `login_time` int(10) DEFAULT '0' COMMENT '登录时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态, 0 锁定, 1 正常',
  `remark` varchar(400) DEFAULT '' COMMENT '锁定说明',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `ip` (`last_ip`,`login_ip`),
  KEY `time` (`add_time`,`last_time`,`login_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员';

LOCK TABLES `tp_admins` WRITE;
/*!40000 ALTER TABLE `tp_admins` DISABLE KEYS */;

INSERT INTO `tp_admins` (`id`, `username`, `password`, `salt`, `realname`, `avatar`, `gender`, `mobile`, `email`, `last_ip`, `login_ip`, `last_time`, `login_time`, `status`, `remark`, `add_time`)
VALUES
  (1,'admin','d45e845aa4bdf6e32aa39b70274d259c','kmPaTp','Admin','',0,'12345678901','cbwfree@163.com','','',0,0,1,'',1471428121);

/*!40000 ALTER TABLE `tp_admins` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table tp_setting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tp_setting`;

CREATE TABLE `tp_setting` (
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '设置名',
  `value` text NOT NULL COMMENT '设置值(JSON数据)',
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
