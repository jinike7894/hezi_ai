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
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records for `dh_user`
-- ----------------------------
INSERT INTO `dh_user` VALUES ('1', '内部渠道', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736930626', '1736930626', null);
INSERT INTO `dh_user` VALUES ('2', '夜行者101', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946158', '1736946158', null);
INSERT INTO `dh_user` VALUES ('3', '片源102', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '片源102', '1736946203', '1743671382', null);
INSERT INTO `dh_user` VALUES ('4', '高德103', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946235', '1736946235', null);
INSERT INTO `dh_user` VALUES ('5', '金手指104', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946267', '1736946267', null);
INSERT INTO `dh_user` VALUES ('6', '聚宝盆105', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946327', '1736946327', null);
INSERT INTO `dh_user` VALUES ('7', '嘉士伯106', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946356', '1736946356', null);
INSERT INTO `dh_user` VALUES ('8', '顺水107', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946411', '1736946411', null);
INSERT INTO `dh_user` VALUES ('9', '红灯区108', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946443', '1736946443', null);
INSERT INTO `dh_user` VALUES ('10', '红中109', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '红中109', '1736946576', '1741941762', null);
INSERT INTO `dh_user` VALUES ('11', '逍遥阁110', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736946609', '1736946609', null);
INSERT INTO `dh_user` VALUES ('12', '雪花111', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '雪花111', '1736946634', '1739786958', null);
INSERT INTO `dh_user` VALUES ('13', '蜜恋112', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1736955381', '1736955381', null);
INSERT INTO `dh_user` VALUES ('14', '一筒114', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '一筒114', '1737835534', '1737835714', null);
INSERT INTO `dh_user` VALUES ('15', '八万115', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '八万115', '1737835561', '1737835726', null);
INSERT INTO `dh_user` VALUES ('16', '北方116', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '北方116', '1737835774', '1738918553', null);
INSERT INTO `dh_user` VALUES ('17', '福利117', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '福利117', '1737835815', '1740244664', null);
INSERT INTO `dh_user` VALUES ('18', '五星118', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '五星118', '1737835843', '1743156804', null);
INSERT INTO `dh_user` VALUES ('19', '顺风119', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '顺风119', '1737835905', '1740556286', null);
INSERT INTO `dh_user` VALUES ('20', '勇闯120', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '勇闯120', '1737835933', '1740643841', null);
INSERT INTO `dh_user` VALUES ('21', '青岛121', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '青岛121', '1737835962', '1740645279', null);
INSERT INTO `dh_user` VALUES ('22', '星辰122', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '星辰122', '1737835985', '1741076488', null);
INSERT INTO `dh_user` VALUES ('23', '百威123', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '百威123', '1737836024', '1741168895', null);
INSERT INTO `dh_user` VALUES ('24', '喜力124', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '喜力124', '1737836049', '1741185378', null);
INSERT INTO `dh_user` VALUES ('25', '南方125', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '南方125', '1737836096', '1741195837', null);
INSERT INTO `dh_user` VALUES ('26', '许仙126', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836164', '1737836164', null);
INSERT INTO `dh_user` VALUES ('27', '大萱127', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836195', '1737836195', null);
INSERT INTO `dh_user` VALUES ('28', '福利社128', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '福利社128', '1737836256', '1741195857', null);
INSERT INTO `dh_user` VALUES ('29', '色界129', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '色界129', '1737836292', '1741694656', null);
INSERT INTO `dh_user` VALUES ('30', '草莓130', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '草莓130', '1737836322', '1741694719', null);
INSERT INTO `dh_user` VALUES ('31', '夜生活131', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '夜生活131', '1737836365', '1742404056', null);
INSERT INTO `dh_user` VALUES ('32', '明道132', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '明道132', '1737836393', '1743680406', null);
INSERT INTO `dh_user` VALUES ('33', '明道133', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '明道133', '1737836420', '1743680423', null);
INSERT INTO `dh_user` VALUES ('34', 'momo134', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836446', '1737836446', null);
INSERT INTO `dh_user` VALUES ('35', 'momo135', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836471', '1737836471', null);
INSERT INTO `dh_user` VALUES ('36', 'momo136', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836496', '1737836496', null);
INSERT INTO `dh_user` VALUES ('37', 'momo137', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836527', '1737836527', null);
INSERT INTO `dh_user` VALUES ('38', 'momo138', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836555', '1737836555', null);
INSERT INTO `dh_user` VALUES ('39', 'momo139', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836597', '1737836597', null);
INSERT INTO `dh_user` VALUES ('40', 'momo140', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836623', '1737836623', null);
INSERT INTO `dh_user` VALUES ('41', 'momo141', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737836670', '1737836670', null);
INSERT INTO `dh_user` VALUES ('42', 'momo142', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879473', '1737879473', null);
INSERT INTO `dh_user` VALUES ('43', 'momo143', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879510', '1737879510', null);
INSERT INTO `dh_user` VALUES ('44', 'momo144', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879536', '1737879536', null);
INSERT INTO `dh_user` VALUES ('45', 'momo145', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, 'momo145', '1737879558', '1739462028', null);
INSERT INTO `dh_user` VALUES ('46', 'momo146', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879588', '1737879588', null);
INSERT INTO `dh_user` VALUES ('47', 'momo147', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879612', '1737879612', null);
INSERT INTO `dh_user` VALUES ('48', '娜娜148', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879646', '1737879646', null);
INSERT INTO `dh_user` VALUES ('49', '娜娜149', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879668', '1737879668', null);
INSERT INTO `dh_user` VALUES ('50', '娜娜150', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, '娜娜150', '1737879692', '1739515519', null);
INSERT INTO `dh_user` VALUES ('51', '娜娜151', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879777', '1737879777', null);
INSERT INTO `dh_user` VALUES ('52', '娜娜152', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879804', '1737879804', null);
INSERT INTO `dh_user` VALUES ('53', '娜娜153', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879859', '1737879859', null);
INSERT INTO `dh_user` VALUES ('54', '娜娜154', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879896', '1737879896', null);
INSERT INTO `dh_user` VALUES ('55', '娜娜155', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879920', '1737879920', null);
INSERT INTO `dh_user` VALUES ('56', '娜娜156', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879967', '1737879967', null);
INSERT INTO `dh_user` VALUES ('57', '娜娜157', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737879992', '1737879992', null);
INSERT INTO `dh_user` VALUES ('58', '娜娜158', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880022', '1737880022', null);
INSERT INTO `dh_user` VALUES ('59', '娜娜159', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880050', '1737880050', null);
INSERT INTO `dh_user` VALUES ('60', '娜娜160', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880078', '1737880078', null);
INSERT INTO `dh_user` VALUES ('61', '娜娜161', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880134', '1737880134', null);
INSERT INTO `dh_user` VALUES ('62', '娜娜162', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880157', '1737880157', null);
INSERT INTO `dh_user` VALUES ('63', '娜娜163', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880183', '1737880183', null);
INSERT INTO `dh_user` VALUES ('64', '娜娜164', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880207', '1737880207', null);
INSERT INTO `dh_user` VALUES ('65', '娜娜165', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1737880231', '1737880231', null);
INSERT INTO `dh_user` VALUES ('66', 'ceshi19031', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1740325188', '1740325188', null);
INSERT INTO `dh_user` VALUES ('67', '娜娜166', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447170', '1741447170', null);
INSERT INTO `dh_user` VALUES ('68', '娜娜167', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447205', '1741447205', null);
INSERT INTO `dh_user` VALUES ('69', '娜娜168', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447267', '1741447267', null);
INSERT INTO `dh_user` VALUES ('70', '娜娜169', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447295', '1741447295', null);
INSERT INTO `dh_user` VALUES ('71', '娜娜170', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447321', '1741447321', null);
INSERT INTO `dh_user` VALUES ('72', '娜娜171', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447350', '1741447350', null);
INSERT INTO `dh_user` VALUES ('73', '娜娜172', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741447379', '1741447379', null);
INSERT INTO `dh_user` VALUES ('74', '娜娜173', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741785600', '1741785600', null);
INSERT INTO `dh_user` VALUES ('75', '娜娜174', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741785764', '1741785764', null);
INSERT INTO `dh_user` VALUES ('76', '娜娜175', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1741785788', '1741785788', null);
INSERT INTO `dh_user` VALUES ('77', '娜娜176', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1742464022', '1742464022', null);
INSERT INTO `dh_user` VALUES ('78', '娜娜177', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1742464052', '1742464052', null);
INSERT INTO `dh_user` VALUES ('79', '娜娜178', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1742464424', '1742464424', null);
INSERT INTO `dh_user` VALUES ('80', '娜娜179', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1742464454', '1742464454', null);
INSERT INTO `dh_user` VALUES ('81', '娜娜180', 'ed696eb5bba1f7460585cc6975e6cf9bf24903dd', null, '1', null, null, '1742464480', '1742464480', null);
-- over---------------------

