-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 04 月 04 日 11:47
-- 服务器版本: 5.5.30
-- PHP 版本: 5.3.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `kunggcom_renrenpage`
--

-- --------------------------------------------------------

--
-- 表的结构 `bug`
--

DROP TABLE IF EXISTS `bug`;
CREATE TABLE IF NOT EXISTS `bug` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `bug` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- 表的结构 `lost`
--

DROP TABLE IF EXISTS `lost`;
CREATE TABLE IF NOT EXISTS `lost` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `lost` varchar(20) COLLATE utf8_bin NOT NULL,
  `name` varchar(16) COLLATE utf8_bin NOT NULL,
  `detail` text COLLATE utf8_bin,
  `contact` tinyint(1) DEFAULT '0',
  `number` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `renren_xyh`
--

DROP TABLE IF EXISTS `renren_xyh`;
CREATE TABLE IF NOT EXISTS `renren_xyh` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `name` varchar(24) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `uid` (`uid`),
  KEY `id` (`id`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1816 ;

-- --------------------------------------------------------

--
-- 表的结构 `rrrest`
--

DROP TABLE IF EXISTS `rrrest`;
CREATE TABLE IF NOT EXISTS `rrrest` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `uid` smallint(4) DEFAULT NULL,
  `rid` bigint(11) NOT NULL,
  `dir` char(80) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=80 ;

-- --------------------------------------------------------

--
-- 表的结构 `rrrest_dir`
--

DROP TABLE IF EXISTS `rrrest_dir`;
CREATE TABLE IF NOT EXISTS `rrrest_dir` (
  `did` mediumint(4) NOT NULL AUTO_INCREMENT COMMENT '目录id',
  `fid` mediumint(4) NOT NULL COMMENT '父类目录id',
  `isHide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为影藏目录',
  `name` char(80) COLLATE utf8_bin NOT NULL COMMENT '控制器名',
  `content` char(80) COLLATE utf8_bin DEFAULT NULL COMMENT '中文名字',
  `instruction` text COLLATE utf8_bin COMMENT '详细说明',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `isConnect` tinyint(1) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `access_token` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `refresh_token` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `renren_id` bigint(15) DEFAULT NULL,
  `email` varchar(80) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `weixin_id` char(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`,`access_token`,`refresh_token`,`renren_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=453 ;

-- --------------------------------------------------------

--
-- 表的结构 `wxrest`
--

DROP TABLE IF EXISTS `wxrest`;
CREATE TABLE IF NOT EXISTS `wxrest` (
  `weixin_id` char(40) COLLATE utf8_bin NOT NULL COMMENT '微信ID',
  `uid` smallint(4) DEFAULT '0' COMMENT '本站ID',
  `dir` char(80) COLLATE utf8_bin DEFAULT '0' COMMENT '目录位置',
  `createtime` bigint(15) NOT NULL COMMENT '注册时间',
  `lasttime` bigint(15) NOT NULL COMMENT '最后一次操作时间',
  PRIMARY KEY (`weixin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
