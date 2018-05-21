-- ----------------------------
-- Table structure for uk_member
-- ----------------------------
DROP TABLE IF EXISTS `uk_member`;
CREATE TABLE `uk_member` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `groupid` int(3)  NOT NULL DEFAULT '1',
  `account` varchar(30) NOT NULL,
  `openid` varchar(32) NOT NULL  NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL,
  `integral` int(8) NOT NULL DEFAULT '0' COMMENT '积分',
  `nickname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `telephone` varchar(12) NOT NULL DEFAULT '',
  `headpic` int(10) NOT NULL DEFAULT '0' COMMENT '头像',
  `register_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '注册IP',
  `last_login_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '登录IP',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uk_member
-- ----------------------------

-- ----------------------------
-- Table structure for uk_member_group
-- ----------------------------
DROP TABLE IF EXISTS `uk_member_group`;
CREATE TABLE `uk_member_group` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `menu_ids` text NOT NULL COMMENT '菜单权限',
  `conf` varchar(1000) DEFAULT '' COMMENT '分组参数配置',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uk_member_group
-- ----------------------------
INSERT INTO `uk_member_group` VALUES ('1', '普通会员', '1,2,3,4','',1496641096,1496641096,1);

-- ----------------------------
-- Table structure for uk_member_menu
-- ----------------------------
DROP TABLE IF EXISTS `uk_member_menu`;
CREATE TABLE `uk_member_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `url_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '链接类型1内部、2外链',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(10) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(4) NOT NULL DEFAULT '100' COMMENT '排序',
  `ifvisible` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员菜单表';
-- ----------------------------
-- Records of uk_member_menu
-- ----------------------------
INSERT INTO `uk_member_menu` VALUES ('1', '0', '投稿管理', '1', '', '_self', '1511679739', '1511679739', '100', '1');
INSERT INTO `uk_member_menu` VALUES ('3', '1', '添加内容', '1', 'content/add', '_self', '1511679739', '1511679739', '100', '0');
INSERT INTO `uk_member_menu` VALUES ('4', '1', '编辑内容', '1', 'content/edit', '_self', '1511679739', '1511679739', '100', '0');
INSERT INTO `uk_member_menu` VALUES ('5', '1', '删除内容', '1', 'content/delete', '_self', '1511679739', '1511679739', '100', '0');
INSERT INTO `uk_member_menu` VALUES ('6', '1', '移动内容栏目', '1', 'content/move', '_self', '1511679739', '1511679739', '100', '0');
INSERT INTO `uk_member_menu` VALUES ('2', '0', '站内信管理', '1', '', '_self', '1511679739', '1511679739', '100', '1');