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
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records for `dh_user`
-- ----------------------------
INSERT INTO `dh_user` VALUES ('1', '内部渠道', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736930626', '1736930626', null);
INSERT INTO `dh_user` VALUES ('82', '北方导航', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386685', '1747386685', null);
INSERT INTO `dh_user` VALUES ('83', '101', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386700', '1747386700', null);
INSERT INTO `dh_user` VALUES ('84', '八万导航', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386775', '1747386775', null);
INSERT INTO `dh_user` VALUES ('85', '七星导航', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386804', '1747386804', null);
INSERT INTO `dh_user` VALUES ('86', '种子', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386833', '1747386833', null);
INSERT INTO `dh_user` VALUES ('87', '东京', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386847', '1747386847', null);
INSERT INTO `dh_user` VALUES ('88', '东方', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386873', '1747386873', null);
INSERT INTO `dh_user` VALUES ('89', '顺风', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386893', '1747386893', null);
INSERT INTO `dh_user` VALUES ('90', '巴黎', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386915', '1747386915', null);
INSERT INTO `dh_user` VALUES ('91', '午夜', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386929', '1747386929', null);
INSERT INTO `dh_user` VALUES ('92', '性天堂', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386943', '1747386943', null);
INSERT INTO `dh_user` VALUES ('93', '南方', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386967', '1747386967', null);
INSERT INTO `dh_user` VALUES ('94', '红中', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386985', '1747386985', null);
INSERT INTO `dh_user` VALUES ('95', '福利', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747386997', '1747386997', null);
INSERT INTO `dh_user` VALUES ('96', '一同', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747387034', '1747387034', null);
INSERT INTO `dh_user` VALUES ('97', '顺水', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747387053', '1747387053', null);
INSERT INTO `dh_user` VALUES ('98', '天上', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1747387076', '1747387076', null);
-- over---------------------

