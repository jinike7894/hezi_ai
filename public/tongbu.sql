-- ----------- ----------------------------
-- Table structure for `dh_user`
-- ----------------------------
DROP TABLE IF EXISTS `dh_user`;
CREATE TABLE `dh_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `googleauth` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ip` varchar(32) DEFAULT NULL,
  `nickname` varchar(32) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
<<<<<<< HEAD
  UNIQUE KEY `username` (`username`) USING BTREE
=======
  UNIQUE KEY `username` (`username`)
>>>>>>> 005bd6ff3092db198642ebf01b7b713a24a69bda
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for `dh_channelcode`
-- ----------------------------
DROP TABLE IF EXISTS `dh_channelcode`;
CREATE TABLE `dh_channelcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `channelCode` varchar(20) DEFAULT NULL,
  `mininum` int(11) NOT NULL DEFAULT '0' COMMENT '保底',
  `ratio` int(11) NOT NULL DEFAULT '0' COMMENT '扣量比例',
  `time_range` varchar(100) DEFAULT '' COMMENT '指定时间段展示x内容',
  `coefficient` float NOT NULL DEFAULT '0.62',
  `price` float unsigned NOT NULL DEFAULT '3',
  `autoc` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `backjumpstatus` tinyint(4) NOT NULL DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL,
  `statistics_code` varchar(32) DEFAULT '' COMMENT '百度统计代码',
  `cnzz_code` varchar(255) DEFAULT NULL COMMENT 'cnzz统计代码',
  `51la_code` varchar(255) DEFAULT NULL COMMENT '51la统计代码',
  `try_yp` varchar(255) DEFAULT '' COMMENT '约炮测试广告地址',
  `try_home` varchar(255) DEFAULT '' COMMENT '首页测试广告地址',
  `try_zb` varchar(255) DEFAULT '' COMMENT '直播测试广告地址',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
<<<<<<< HEAD
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `channelCode` (`channelCode`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=177 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
=======
  PRIMARY KEY (`id`),
  UNIQUE KEY `channelCode` (`channelCode`)
) ENGINE=MyISAM AUTO_INCREMENT=176 DEFAULT CHARSET=utf8;
>>>>>>> 005bd6ff3092db198642ebf01b7b713a24a69bda

-- ----------------------------
-- Table structure for `dh_qdtongji`
-- ----------------------------
DROP TABLE IF EXISTS `dh_qdtongji`;
CREATE TABLE `dh_qdtongji` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channelCode` varchar(20) DEFAULT NULL,
  `sj_num` int(11) NOT NULL DEFAULT '0' COMMENT '实际数据',
  `sum` int(11) DEFAULT '0',
  `date` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
<<<<<<< HEAD
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `channelCode` (`channelCode`,`date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
=======
  PRIMARY KEY (`id`),
  UNIQUE KEY `channelCode` (`channelCode`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
>>>>>>> 005bd6ff3092db198642ebf01b7b713a24a69bda

-- ----------------------------
-- Records for `dh_user`
-- ----------------------------
INSERT INTO `dh_user` VALUES ('1', '内部渠道', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736930626', '1736930626', null);
-- ----------------------------
-- Records for `dh_channelcode`
-- ----------------------------
INSERT INTO `dh_channelcode` VALUES ('1', '1', '1', '1', '1', '', '0.62', '3', '0', '1', '1', '', '111', '111', '111', '', '', '', '1736930626', '1736930626', null);
<<<<<<< HEAD
INSERT INTO `dh_channelcode` VALUES ('176', '0', '232', '0', '0', '', '0.62', '3', '0', '1', '1', null, '', null, null, '', '', '', '1746876277', '1746876277', null);
=======
>>>>>>> 005bd6ff3092db198642ebf01b7b713a24a69bda
-- ----------------------------
-- Records for `dh_qdtongji`
-- ----------------------------
INSERT INTO `dh_qdtongji` VALUES ('1', '1', '2', '2', '2025-04-16', '0', '1744817389', '1744817389', null);
INSERT INTO `dh_qdtongji` VALUES ('2', '1', '1', '1', '2025-04-17', '0', '1744822517', '1744822517', null);
<<<<<<< HEAD
INSERT INTO `dh_qdtongji` VALUES ('3', '1', '1', '1', '2025-04-30', '0', '1745946981', '1745946981', null);
INSERT INTO `dh_qdtongji` VALUES ('4', '1', '1', '1', '2025-05-02', '0', '1746180970', '1746180970', null);
INSERT INTO `dh_qdtongji` VALUES ('5', '1', '1', '1', '2025-05-05', '0', '1746443636', '1746443636', null);
INSERT INTO `dh_qdtongji` VALUES ('6', '1', '1', '1', '2025-05-09', '0', '1746783652', '1746783652', null);
INSERT INTO `dh_qdtongji` VALUES ('7', '1', '2', '2', '2025-05-10', '0', '1746874664', '1746874664', null);
INSERT INTO `dh_qdtongji` VALUES ('8', '232', '1', '1', '2025-05-10', '0', '1746876277', '1746876277', null);
INSERT INTO `dh_qdtongji` VALUES ('9', '1', '2', '2', '2025-05-15', '0', '1747293521', '1747293521', null);
=======
>>>>>>> 005bd6ff3092db198642ebf01b7b713a24a69bda
-- over---------------------

