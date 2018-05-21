/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : ukcms

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-03-19 13:43:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for uk_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `uk_admin_menu`;
CREATE TABLE `uk_admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '链接类型1模块、2外链',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(10) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(4) NOT NULL DEFAULT '100' COMMENT '排序',
  `ifsystem` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统菜单',
  `ifvisible` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of uk_admin_menu
-- ----------------------------
INSERT INTO `uk_admin_menu` VALUES ('1', '0', '基础设置', 'ion-android-share-alt', '1', '', '_self', '0', '1514013151', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('2', '35', '结构管理', 'fa fa-code-fork', '1', '', '_self', '0', '1514013308', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('3', '1', '后台首页', 'fa fa-home', '1', 'admin/index/index', '_self', '0', '1511679739', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('4', '1', '系统管理', 'fa fa-recycle', '1', '', '_self', '0', '1511679739', '3', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('5', '4', '后台菜单', 'fa fa-newspaper-o', '1', 'admin/menu/index', '_self', '0', '1514013197', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('6', '1', '个人设置', 'fa fa-user-o', '1', 'admin/index/userInfo', '_self', '0', '1511679739', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('7', '4', '数据库管理', 'fa fa-database', '1', 'admin/database/index', '_self', '0', '1514013197', '2', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('8', '7', '数据库备份', 'fa fa-folder', '1', 'admin/database/export', '_self', '0', '0', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('9', '7', '数据库还原', 'fa fa-exchange', '1', 'admin/database/import', '_self', '0', '0', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('10', '7', '优化表', 'fa fa-gear', '1', 'admin/database/optimize', '_self', '0', '0', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('11', '7', '修复表', 'fa fa-wrench', '1', 'admin/database/repair', '_self', '0', '0', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('12', '7', '删除备份', 'fa fa-close', '1', 'admin/database/delete', '_self', '0', '0', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('13', '5', '添加菜单', 'fa fa-plus-circle', '1', 'admin/menu/add', '_self', '0', '0', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('14', '5', '菜单编辑', 'fa fa-pencil', '1', 'admin/menu/edit', '_self', '0', '0', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('15', '5', '菜单显隐', 'fa fa-eye', '1', 'admin/menu/visible', '_self', '0', '0', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('16', '5', '删除菜单', 'fa fa-times-circle', '1', 'admin/menu/delete', '_self', '0', '1495952539', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('17', '1', '后台权限', 'fa fa-unlock-alt', '1', '', '_self', '0', '1511679739', '4', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('18', '17', '用户管理', 'fa fa-group', '1', 'admin/user/index', '_self', '0', '0', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('19', '17', '角色管理', 'fa fa-address-card', '1', 'admin/role/index', '_self', '0', '0', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('20', '18', '添加用户', 'fa fa-user-plus', '1', 'admin/user/add', '_self', '0', '0', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('21', '18', '编辑用户', 'fa fa-user-circle-o', '1', 'admin/user/edit', '_self', '0', '0', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('22', '18', '用户状态设置', 'fa fa-user-circle', '1', 'admin/user/setstate', '_self', '0', '1495594779', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('23', '18', '删除用户', 'fa fa-user-times', '1', 'admin/user/delete', '_self', '0', '0', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('24', '19', '添加角色', 'fa fa-user-plus', '1', 'admin/role/add', '_self', '0', '0', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('25', '19', '角色编辑', 'fa fa-user-circle-o', '1', 'admin/role/edit', '_self', '0', '0', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('26', '19', '角色删除', 'fa fa-user-times', '1', 'admin/role/delete', '_self', '0', '0', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('27', '19', '角色状态设置', 'fa fa-user-secret', '1', 'admin/role/setstate', '_self', '0', '1495594763', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('28', '5', '菜单排序', 'fa fa-sort-numeric-asc', '1', 'admin/menu/changeorder', '_self', '0', '0', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('29', '131', '参数设置', 'fa fa-gear', '1', 'admin/config/index', '_self', '0', '1511680982', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('30', '4', '附件管理', 'fa fa-folder-open', '1', 'admin/filemanage/index', '_self', '0', '1514013197', '3', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('31', '30', '上传', 'fa fa-cloud-upload', '1', 'admin/filemanage/upload', '_self', '0', '1511835076', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('32', '30', '查看文件夹文件', 'fa fa-folder', '1', 'admin/filemanage/getfiles', '_self', '0', '1511835076', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('33', '30', '附件状态设置', 'fa fa-file', '1', 'admin/filemanage/setstate', '_self', '0', '1511835076', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('34', '30', '删除附件', 'fa fa-times', '1', 'admin/filemanage/delete', '_self', '0', '1511835076', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('35', '0', '结构内容', 'fa fa-file-text-o', '1', '', '_self', '0', '1506914018', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('36', '35', '模型管理', 'fa fa-cube', '1', 'admin/model/index', '_self', '0', '1514013308', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('37', '2', '栏目管理', 'fa fa-sitemap', '1', 'admin/column/index', '_self', '0', '1497771172', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('38', '36', '添加模型', 'fa fa-plus-square-o', '1', 'admin/model/add', '_self', '0', '1510206728', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('39', '36', '编辑模型', 'fa fa-pencil-square-o', '1', 'admin/model/edit', '_self', '0', '1510206728', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('40', '36', '删除模型', 'fa fa-close', '1', 'admin/model/delete', '_self', '0', '1510206728', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('41', '36', '模型字段', 'fa fa-bars', '1', 'admin/field/index', '_self', '1495592456', '1510206728', '5', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('42', '36', '添加字段', 'fa fa-plus-square', '1', 'admin/field/add', '_self', '1495592525', '1510206728', '6', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('43', '36', '编辑字段', 'fa fa-edit', '1', 'admin/field/edit', '_self', '1495592647', '1510206728', '7', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('44', '36', '字段状态设置', 'fa fa-toggle-off', '1', 'admin/field/setstate', '_self', '1495592746', '1510206728', '10', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('45', '36', '字段隐显设置', 'fa fa-eye', '1', 'admin/field/setvisible', '_self', '1495592852', '1510206728', '11', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('46', '36', '删除字段', 'fa fa-close', '1', 'admin/field/delete', '_self', '1495850874', '1510206728', '9', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('47', '37', '添加栏目', 'fa fa-plus-circle', '1', 'admin/column/add', '_self', '1496037441', '1503901646', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('48', '37', '编辑栏目', 'fa fa-edit', '1', 'admin/column/edit', '_self', '1496037554', '1503901646', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('49', '37', '删除栏目', 'fa fa-close', '1', 'admin/column/delete', '_self', '1496037649', '1503901646', '7', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('50', '37', '栏目排序', 'fa fa-sort-alpha-asc', '1', 'admin/column/changeorder', '_self', '1496037727', '1503901646', '8', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('51', '37', '批量添加栏目', 'fa fa-plus-square', '1', 'admin/column/addAll', '_self', '1496041478', '1503901646', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('55', '37', '栏目状态设置', 'fa fa-eye', '1', 'admin/column/setstate', '_self', '1496129249', '1503901646', '9', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('56', '37', '栏目拓展内容', 'fa fa-tasks', '1', 'admin/column/extfields', '_self', '1496300432', '1503901646', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('57', '35', '栏目内容', 'fa fa-clone', '1', '', '_self', '1496640683', '1514013717', '2', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('58', '37', '栏目批量移动', 'fa fa-reply', '1', 'admin/column/move', '_self', '1496742824', '1503901646', '10', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('59', '37', '栏目批量编辑', 'fa fa-file-text', '1', 'admin/column/editAll', '_self', '1496742900', '1503901646', '11', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('60', '57', '添加模型内容', 'fa fa-plus', '1', 'admin/content/add', '_self', '1496992960', '1523772682', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('61', '57', '编辑模型内容', 'fa fa-edit', '1', 'admin/content/edit', '_self', '1496993528', '1523772682', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('62', '57', '删除模型内容', 'fa fa-close', '1', 'admin/content/delete', '_self', '1496993730', '1523772682', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('63', '57', '设置模型内容状态', 'fa fa-toggle-on', '1', 'admin/content/setstate', '_self', '1496993816', '1523772682', '8', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('64', '57', '模型内容排序', 'fa fa-sort-numeric-desc', '1', 'admin/content/changeorder', '_self', '1496993920', '1523772682', '9', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('65', '2', '推荐位管理', 'fa fa-dot-circle-o', '1', 'admin/place/index', '_self', '1497768076', '1497771172', '2', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('66', '65', '添加推荐位', 'fa fa-plus', '1', 'admin/place/add', '_self', '1497768219', '1497774456', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('67', '65', '编辑推荐位', 'fa fa-edit', '1', 'admin/place/edit', '_self', '1497768262', '1497774468', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('68', '65', '删除推荐位', 'fa fa-remove', '1', 'admin/place/delete', '_self', '1497768361', '1497774482', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('69', '35', '广告管理', 'fa fa-photo', '1', '', '_self', '1497771040', '1514013308', '4', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('70', '69', '链接管理', 'fa fa-link', '1', 'admin/link/index', '_self', '1497771084', '1498009133', '1', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('71', '69', '分组管理', 'fa fa-window-maximize', '1', 'admin/linkgroup/index', '_self', '1497771145', '1514013906', '0', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('72', '71', '添加分组', 'fa fa-plus', '1', 'admin/linkgroup/add', '_self', '1498008871', '1498009140', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('73', '71', '编辑分组', 'fa fa-edit', '1', 'admin/linkgroup/edit', '_self', '1498008936', '1498009140', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('74', '71', '删除分组', 'fa fa-times-circle-o', '1', 'admin/linkgroup/delete', '_self', '1498009007', '1498009140', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('75', '70', '添加链接', 'fa fa-plus', '1', 'admin/link/add', '_self', '1498009187', '1498009187', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('76', '70', '编辑链接', 'fa fa-edit', '1', 'admin/link/edit', '_self', '1498009222', '1498009222', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('77', '70', '删除链接', 'fa fa-times-circle-o', '1', 'admin/link/delete', '_self', '1498009266', '1498009266', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('78', '70', '设置链接状态', 'fa fa-toggle-on', '1', 'admin/link/setstate', '_self', '1498009327', '1498009327', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('79', '70', '设置链接排序', 'fa fa-sort-numeric-desc', '1', 'admin/link/changeorder', '_self', '1498009393', '1498009393', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('80', '36', '模型状态设置', 'fa fa-toggle-on', '1', 'admin/model/setstate', '_self', '1498894295', '1510206728', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('81', '57', '模型内容移动栏目', 'fa fa-reply-all', '1', 'admin/content/move', '_self', '1498895686', '1523772682', '6', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('82', '36', '模型是否可投稿', 'fa fa-circle-o', '1', 'admin/model/setsub', '_self', '1499416148', '1510206728', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('83', '36', '导入模型内容', 'fa fa-exchange', '1', 'admin/model/importdata', '_self', '1501653553', '1523772682', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('84', '36', '导入格式下载', 'fa fa-file-text-o', '1', 'admin/model/importexample', '_self', '1501739963', '1523772682', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('85', '3', '清空缓存', 'fa fa-trash-o', '1', 'admin/index/clear', '_self', '1501837809', '1501837809', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('86', '2', 'TAG标签管理', 'fa fa-tags', '1', 'admin/tag/index', '_self', '1502153759', '1502153759', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('87', '86', '添加TAG', 'fa fa-plus', '1', 'admin/tag/add', '_self', '1502153780', '1511144430', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('88', '86', '编辑TAG', 'fa fa-edit', '1', 'admin/tag/edit', '_self', '1502153799', '1511144430', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('89', '86', '导入TAG', 'fa fa-plus-circle', '1', 'admin/tag/importdata', '_self', '1502153821', '1511144430', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('90', '86', 'AJAX设置TAG值', 'fa fa-toggle-on', '1', 'admin/tag/setvalue', '_self', '1502154055', '1511144430', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('91', '86', '删除TAG', 'fa fa-close', '1', 'admin/tag/delete', '_self', '1502154076', '1511144430', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('92', '131', '参数管理', 'fa fa-wrench', '1', 'admin/configset/index', '_self', '1503461284', '1511680969', '0', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('93', '92', '新增参数', 'fa fa-plus', '1', 'admin/configset/add', '_self', '1503461342', '1511680999', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('94', '92', '编辑参数', 'fa fa-edit', '1', 'admin/configset/edit', '_self', '1503461434', '1511681009', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('95', '92', '删除参数', 'fa fa-times', '1', 'admin/configset/delete', '_blank', '1503461464', '1511681027', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('96', '92', '参数排序', 'fa fa-sort-numeric-asc', '1', 'admin/configset/changeorder', '_self', '1503461488', '1511681036', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('97', '92', '参数状态', 'fa fa-eye', '1', 'admin/configset/setstate', '_self', '1503461504', '1511681043', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('98', '37', '列表栏目筛选条件选项字段', 'fa fa-arrows-v', '1', 'admin/column/getoptionfield', '_self', '1503901633', '1503901646', '12', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('99', '36', '字段排序', 'fa fa-sort-numeric-asc', '1', 'admin/field/changeorder', '_self', '1504449938', '1510206728', '8', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('100', '35', '独立模型内容', 'fa fa-suitcase', '1', '', '_self', '1506914011', '1511681732', '3', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('101', '1', '插件管理', 'fa fa-code', '1', '', '_self', '1506914011', '1511679739', '6', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('102', '1', '应用管理', 'fa fa-hdd-o', '1', 'admin/app/index', '_self', '1506914336', '1511679739', '5', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('103', '102', '应用状态', 'fa fa-toggle-on', '1', 'admin/app/setstate', '_self', '1506914560', '1509439223', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('104', '102', '应用安装', 'fa fa-plus', '1', 'admin/app/install', '_self', '1506915151', '1509439223', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('105', '102', '应用卸载', 'fa fa-close', '1', 'admin/app/uninstall', '_self', '1506915217', '1509439223', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('113', '35', 'SEO优化', 'fa fa-paw', '1', '', '_self', '1509086034', '1514013308', '5', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('114', '113', 'SiteMap', 'fa fa-map', '1', 'admin/sitemap/index', '_self', '1509086579', '1509086579', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('115', '36', '字段必填设置', 'fa fa-dot-circle-o', '1', 'admin/field/setrequire', '_self', '1509352076', '1510206728', '12', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('116', '101', '钩子管理', 'fa fa-anchor', '1', 'admin/hook/index', '_self', '1509439663', '1509439663', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('117', '101', '行为管理', 'fa fa-plane', '1', 'admin/behavior/index', '_self', '1509440005', '1509591321', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('118', '116', '添加钩子', 'fa fa-plus', '1', 'admin/hook/add', '_self', '1509591455', '1509763021', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('119', '116', '编辑钩子', 'fa fa-edit', '1', 'admin/hook/edit', '_self', '1509591550', '1509763021', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('120', '116', '钩子排序', 'fa fa-long-arrow-down', '1', 'admin/hook/changeorder', '_self', '1509591630', '1509763021', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('121', '116', '删除钩子', 'fa fa-times', '1', 'admin/hook/delete', '_self', '1509591695', '1509763021', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('122', '117', '行为安装', 'fa fa-wrench', '1', 'admin/behavior/install', '_self', '1509592668', '1509687874', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('123', '117', '行为卸载', 'fa fa-close', '1', 'admin/behavior/uninstall', '_self', '1509592887', '1509687874', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('124', '117', '行为排序', 'fa fa-long-arrow-down', '1', 'admin/behavior/edichangeorder', '_self', '1509593073', '1509687874', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('125', '117', '添加行为', 'fa fa-plus', '1', 'admin/behavior/add', '_self', '1509687746', '1509687874', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('126', '117', '删除行为', 'fa fa-times', '1', 'admin/behavior/delete', '_self', '1509687833', '1509687874', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('127', '116', '钩子状态', 'fa fa-toggle-on', '1', 'admin/hook/setstate', '_self', '1509763006', '1509763021', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('128', '36', '字段设置搜索', 'fa fa-search', '1', 'admin/field/setsearch', '_self', '1510206709', '1510206728', '13', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('129', '30', '图片本地化', 'fa fa-arrow-circle-down', '1', 'admin/filemanage/geturlfile', '_self', '1510889582', '1511835076', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('130', '86', '替换内容TAG添加链接', 'fa fa-link', '1', 'admin/tag/gettaglink', '_self', '1511144419', '1511144430', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('131', '1', '系统参数', 'fa fa-cogs', '1', '', '_self', '1511679696', '1511680945', '2', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('132', '4', '域名管理', 'fa fa-globe', '1', 'admin/domain/index', '_self', '1511685612', '1514013197', '0', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('133', '132', '添加域名', 'fa fa-plus', '1', 'admin/domain/add', '_self', '1511685861', '1511685861', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('134', '132', '编辑域名', 'fa fa-edit', '1', 'admin/domain/edit', '_self', '1511685926', '1511685926', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('135', '132', '删除域名', 'fa fa-close', '1', 'admin/domain/delete', '_self', '1511686015', '1511686015', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('136', '132', '域名状态设置', 'fa fa-toggle-on', '1', 'admin/domain/setstate', '_self', '1511686423', '1511686423', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('137', '30', 'ajax通过ID获取附件信息', 'fa fa-file-archive-o', '1', 'admin/filemanage/ajaxgetfileinfo', '_self', '1511835064', '1515915377', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('138', '57', '复制模型内容', 'fa fa-copy', '1', 'admin/content/copy', '_self', '1512630859', '1523772682', '7', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('139', '30', 'ajax通过类型获取附件列表', 'fa fa-reorder', '1', 'admin/filemanage/showfilelist', '_self', '1515914039', '1515915509', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('140', '36', '导出模型内容', 'fa fa-mail-reply-all', '1', 'admin/model/exportdata', '_self', '1523772657', '1523772682', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('141', '57', '关于我们', 'fa fa-sticky-note', '1', 'admin/content/aboutus', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('142', '57', 'WEB知识', 'fa fa-sticky-note', '1', 'admin/content/web', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('143', '142', '前端知识', 'fa fa-sticky-note', '1', 'admin/content/front', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('144', '143', '前端基础', 'fa fa-sticky-note', '1', 'admin/content/frontbase', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('145', '143', '前端框架', 'fa fa-sticky-note', '1', 'admin/content/frontframe', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('146', '145', '主流框架', 'fa fa-sticky-note', '1', 'admin/content/mainframe', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('147', '145', '最新框架', 'fa fa-sticky-note', '1', 'admin/content/newframe', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('148', '142', 'PHP介绍', 'fa fa-sticky-note', '1', 'admin/content/php', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('149', '142', 'UKcms常见问题', 'fa fa-sticky-note', '1', 'admin/content/ukcms', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('150', '57', '传奇影星', 'fa fa-sticky-note', '1', 'admin/content/legend', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('151', '57', '产品演示', 'fa fa-sticky-note', '1', 'admin/content/product', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('152', '57', '留言本', 'fa fa-sticky-note', '1', 'admin/content/guestbook', '_self', '1500017712', '1523772682', '100', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('153', '100', '添加独立内容', 'fa fa-plus', '1', 'admin/independent/add', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('154', '100', '编辑独立内容', 'fa fa-edit', '1', 'admin/independent/edit', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('155', '100', '删除独立内容', 'fa fa-close', '1', 'admin/independent/delete', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('156', '100', '独立内容状态设置', 'fa fa-toggle-on', '1', 'admin/independent/setstate', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('157', '100', '独立模型内容排序', 'fa fa-long-arrow-down', '1', 'admin/independent/changeorder', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('158', '100', '内容评论', 'fa fa-sticky-note-o', '1', 'admin/independent/comment', '_self', '1500017712', '1523772682', '100', '1', '1');
-- ----------------------------
-- Table structure for uk_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `uk_admin_role`;
CREATE TABLE `uk_admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分组id',
  `path` varchar(255) NOT NULL COMMENT '层级路径',
  `names` varchar(36) NOT NULL DEFAULT '' COMMENT '分组名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_ids` text NOT NULL COMMENT '菜单权限',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否能登陆',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of uk_admin_role
-- ----------------------------
INSERT INTO `uk_admin_role` VALUES ('1', '0,', '超级管理员', '拥有所有权限', '-1', '0', '0', '200', '1');
INSERT INTO `uk_admin_role` VALUES ('2', '0,1,', '后台编辑', '编辑角色演示', '1,6,4,30,31,32,129,33,34,137,35,2,36,38,39,80,82,40,41,42,43,99,46,44,45,115,128,37,47,48,56,51,49,50,55,58,59,98,65,66,67,68,86,87,88,89,90,91,130,57,60,83,84,61,62,81,138,63,64,107,108,109,110,111,112,69,71,72,73,74,70,75,76,77,78,79', '1507340909', '1513323088', '100', '1');

-- ----------------------------
-- Table structure for uk_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `uk_admin_user`;
CREATE TABLE `uk_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `username` varchar(30) NOT NULL,
  `realname` varchar(90) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL,
  `head_pic` int(6) NOT NULL DEFAULT '0' COMMENT '头像',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(64) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` char(15) NOT NULL DEFAULT '' COMMENT '上次登录IP',
  `orders` int(4) NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usename` (`username`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员信息表';

-- ----------------------------
-- Records of uk_admin_user
-- ----------------------------
INSERT INTO `uk_admin_user` VALUES ('1', '1', '<account>', '路过', '<password>', '1', '13638816362', '404133748@qq.com', '1491880778', '1509441186', '0.0.0.0', '0', '1');
-- ----------------------------
-- Table structure for uk_app
-- ----------------------------
DROP TABLE IF EXISTS `uk_app`;
CREATE TABLE `uk_app` (
  `name` varchar(60) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `installstate` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用管理表';

-- ----------------------------
-- Records of uk_app
-- ----------------------------
INSERT INTO `uk_app` VALUES ('member', '0', '0');
INSERT INTO `uk_app` VALUES ('wechat', '0', '0');

-- ----------------------------
-- Table structure for uk_article
-- ----------------------------
DROP TABLE IF EXISTS `uk_article`;
CREATE TABLE `uk_article` (
  `id` int(11) unsigned AUTO_INCREMENT COMMENT '文档id',
  `cname` varchar(64) DEFAULT '' COMMENT '栏目标识',
  `ifextend` tinyint(2) DEFAULT '0' COMMENT '是否栏目拓展',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `places` varchar(64) DEFAULT '' COMMENT '推荐位',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `orders` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned DEFAULT '0' COMMENT '状态',
  `hits` int(11) unsigned DEFAULT '0' COMMENT '点击量',
  `source` varchar(128) DEFAULT '' COMMENT '文章来源',
  `description` varchar(3000) DEFAULT '' COMMENT 'SEO摘要',
  `cover` int(5) unsigned DEFAULT '0' COMMENT '封面图',
  `keywords` varchar(256) DEFAULT '' COMMENT 'SEO关键词',
  `color` varchar(7) DEFAULT '' COMMENT '标题颜色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章模型表';

-- ----------------------------
-- Records of uk_article
-- ----------------------------
INSERT INTO `uk_article` VALUES ('1', 'frontbase', '0', '1', ',', '什么是HTML5', '1500101771', '1502097944', '100', '1', '0', '原创', 'HTML5是万维网的核心语言、标准通用标记语言下的一个应用超文本标记语言（HTML）的第五次重大修改', '3', 'Canvas,W3C', '');
INSERT INTO `uk_article` VALUES ('2', 'frontbase', '0', '1', '', 'JavaScript语言简介', '1500103276', '1521436693', '100', '1', '0', '原创', 'JavaScript是一种属于网络的脚本语言,已经被广泛用于Web应用开发', '4', '直译式脚本语言,跨平台性', '#de3131');
INSERT INTO `uk_article` VALUES ('3', 'mainframe', '0', '1', ',', 'BootStrap', '1500104104', '1502096842', '100', '1', '0', '原创', 'Bootstrap，来自 Twitter，是目前很受欢迎的前端框架', '5', 'Twitter,最流行,网格系统', '');
INSERT INTO `uk_article` VALUES ('4', 'mainframe', '0', '1', ',', 'Semantic', '1500105735', '1502096808', '100', '1', '0', '原创', 'Semantic UI 是一款语义化设计的前端开源框架，其功能强大，使用简单，为设计师和开发师提供可复用的完美设计方案', '6', '功能强大,使用简单', '');
INSERT INTO `uk_article` VALUES ('5', 'newframe', '0', '1', ',', 'Vuejs', '1500106878', '1502096624', '100', '1', '0', '原创', 'Vue 从根本上采用最小成本、渐进增量(incrementally adoptable)的设计', '7', '渐进式框架,最小成本,渐进增量', '');
INSERT INTO `uk_article` VALUES ('6', 'newframe', '0', '1', '', 'React', '1500107255', '1521438087', '100', '1', '0', '原创', 'React 起源于 Facebook 的内部项目，用来架设 Instagram 的网站', '8', '虚拟DOM,组件化,独立封装', '#1a9c4e');
INSERT INTO `uk_article` VALUES ('7', 'php', '0', '1', ',', 'PHP简介', '1500108476', '1502095271', '100', '1', '0', '原创', 'PHP（外文名:PHP: Hypertext Preprocessor，中文名：“超文本预处理器”）是一种通用开源脚本语言。语法吸收了C语言、Java和Perl的特点，利于学习，使用广泛，主要适用于Web开发领域', '0', '最流行,跨平台性,免费性', '');
INSERT INTO `uk_article` VALUES ('8', 'php', '0', '1', ',1,', 'PHP7打破一切', '1500109352', '1502095237', '100', '1', '0', '原创', 'PHP7比PHP5.6性能提升了两倍', '0', '两倍性能,高度尊重的语言', '');
INSERT INTO `uk_article` VALUES ('9', 'php', '0', '1', ',1,', 'PHP国产开发利器ThinkPHP5', '1500109953', '1502095003', '100', '1', '0', '原创', 'ThinkPHP V5.0是一个颠覆和重构版本', '0', '适合国情,Apache2,支持API', '');
INSERT INTO `uk_article` VALUES ('10', 'php', '0', '1', ',1,', 'Laravel国际范主流PHP框架', '1500110354', '1502094952', '100', '1', '0', '原创', 'Laravel是一套简洁、优雅的PHP Web开发框架', '0', '简洁开发,富于表达力,访问单例', '');
INSERT INTO `uk_article` VALUES ('11', 'php', '0', '1', '', 'Symfony老牌的php框架', '1500110783', '1521438112', '100', '1', '0', '原创', 'Symfony是一个基于MVC模式的面向对象的PHP5框架', '0', '解决方案,团体支持,成熟的前端', '#2faac9');
INSERT INTO `uk_article` VALUES ('12', 'ukcms', '0', '1', ',1,', 'UKcms需要什么运行环境？', '1500111695', '1502094764', '100', '1', '0', '原创', '', '0', '运行环境,浏览器', '');
INSERT INTO `uk_article` VALUES ('13', 'ukcms', '0', '1', ',1,', '使用UKcms需要收费么？', '1500112500', '1502094679', '100', '1', '0', '原创', '程序用于非盈利用途，只能使用免费版程序', '0', 'ukcms,版权', '');
INSERT INTO `uk_article` VALUES ('14', 'ukcms', '0', '1', ',1,', '授权用户提供哪些服务？', '1500166819', '1502094661', '100', '1', '0', '原创', '提供单独的授权版系统，授权费可以抵扣定制费用', '0', '授权费用,系统定制,解决方案', '');

-- ----------------------------
-- Table structure for uk_article_data
-- ----------------------------
DROP TABLE IF EXISTS `uk_article_data`;
CREATE TABLE `uk_article_data` (
  `did` int(11) unsigned DEFAULT '0' COMMENT '文档id',
  `content` text COMMENT '文章内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章模型扩展表';

-- ----------------------------
-- Records of uk_article_data
-- ----------------------------
INSERT INTO `uk_article_data` VALUES ('1', '<p>HTML5是万维网的核心语言、标准通用标记语言下的一个应用超文本标记语言（HTML）的第五次重大修改。<br/></p><p><strong>语义特性（Class：Semantic）</strong></p><p>HTML5赋予网页更好的意义和结构。更加丰富的标签将随着对RDFa的，微数据与微格式等方面的支持，构建对程序、对用户都更有价值的数据驱动的Web。</p><p>本地存储特性（Class: OFFLINE &amp; STORAGE）</p><p>基于HTML5开发的网页APP拥有更短的启动时间，更快的联网速度，这些全得益于HTML5 APP Cache，以及本地存储功能。Indexed DB（html5本地存储最重要的技术之一）和API说明文档。</p><p><strong>设备兼容特性 (Class: DEVICE ACCESS)</strong></p><p>从Geolocation功能的API文档公开以来，HTML5为网页应用开发者们提供了更多功能上的优化选择，带来了更多体验功能的优势。HTML5提供了前所未有的数据与应用接入开放接口。使外部应用可以直接与浏览器内部的数据直接相连，例如视频影音可直接与microphones及摄像头相联。</p><p><strong>连接特性（Class: CONNECTIVITY）</strong></p><p>更有效的连接工作效率，使得基于页面的实时聊天，更快速的网页游戏体验，更优化的在线交流得到了实现。HTML5拥有更有效的服务器推送技术，Server-Sent Event和WebSockets就是其中的两个特性，这两个特性能够帮助我们实现服务器将数据“推送”到客户端的功能。</p><p><strong>网页多媒体特性(Class: MULTIMEDIA)</strong></p><p>支持网页端的Audio、Video等多媒体功能， 与网站自带的APPS，摄像头，影音功能相得益彰。</p><p><strong>三维、图形及特效特性（Class: 3D, Graphics &amp; Effects）</strong></p><p>基于SVG、Canvas、WebGL及CSS3的3D功能，用户会惊叹于在浏览器中，所呈现的惊人视觉效果。</p><p><strong>性能与集成特性（Class: Performance &amp; Integration）</strong></p><p>没有用户会永远等待你的Loading——HTML5会通过XMLHttpRequest2等技术，解决以前的跨域等问题，帮助您的Web应用和网站在多样化的环境中更快速的工作。</p><p><strong>CSS3特性(Class: CSS3)</strong></p><p>在不牺牲性能和语义结构的前提下，CSS3中提供了更多的风格和更强的效果。此外，较之以前的Web排版，Web的开放字体格式（WOFF）也提供了更高的灵活性和控制性。</p><p><strong>改革</strong></p><p>HTML5提供了一些新的元素和属性，例如</p><nav>（网站导航块）和。这种标签将有利于搜索引擎的索引整理，同时更好的帮助小屏幕装置和视障人士使用，除此之外，还为其他浏览要素提供了新的功能，如</nav><p><br/></p><p>1、取消了一些过时的HTML4标记</p><p>其中包括纯粹显示效果的标记，如<span style=\"\">和</span>，它们已经被CSS取代。</p><p><br/></p><p>HTML5 吸取了XHTML2 一些建议，包括一些用来改善文档结构的功能，比如，新的HTML 标签 header, footer, dialog, aside, figure 等的使用，将使内容创作者更加语义地创建文档，之前的开发者在实现这些功能时一般都是使用div。</p><p>2、将内容和展示分离</p><p>b 和 i 标签依然保留，但它们的意义已经和之前有所不同，这些标签的意义只是为了将一段文字标识出来，而不是为了为它们设置粗体或斜体式样。u，font，center，strike 这些标签则被完全去掉了。</p><p>3、一些全新的表单输入对象</p><p>包括日期，URL，Email 地址，其它的对象则增加了对非拉丁字符的支持。HTML5 还引入了微数据，这一使用机器可以识别的标签标注内容的方法，使语义Web 的处理更为简单。总的来说，这些与结构有关的改进使内容创建者可以创建更干净，更容易管理的网页，这样的网页对搜索引擎，对读屏软件等更为友好。</p><p>4、全新的，更合理的Tag</p><p>多媒体对象将不再全部绑定在object或 embed Tag 中，而是视频有视频的Tag，音频有音频的 Tag。</p><p>5、本地数据库</p><p>这个功能将内嵌一个本地的SQL 数据库，以加速交互式搜索，缓存以及索引功能。同时，那些离线Web 程序也将因此获益匪浅。不需要插件的丰富动画。</p><p>6、Canvas 对象</p><p>将给浏览器带来直接在上面绘制矢量图的能力，这意味着用户可以脱离Flash 和Silverlight，直接在浏览器中显示图形或动画。</p><p>7、浏览器中的真正程序</p><p>将提供 API 实现浏览器内的编辑，拖放，以及各种图形用户界面的能力。内容修饰Tag 将被剔除，而使用CSS。</p><p>8、Html5取代Flash在移动设备的地位。</p><p>9、其突出的特点就是强化了web页的表现性,追加了本地数据库,</p><p>规范</p><p>HTML5和Canvas 2D规范的制定已经完成，尽管还不能算是W3C标准，但是这些规范已经功能完整，企业和开发人员有了一个稳定的执行和规划目标。</p><p>W3C首席执行官Jeff Jaffe表示：“从今天起，企业用户可以清楚地知道，他们能够在未来依赖HTML5。”HTML5是开放Web标准的基石，它是一个完整的编程环境，适用于跨平台应用程序、视频和动画、图形、风格、排版和其它数字内容发布工具、广泛的网络功能等等。</p><p>为了减少浏览器碎片、实现于所有HTML工具的应用，W3C从今天开始着手W3C标准化的互操作性和测试。和之前宣布的规划一样，W3C计划在2014年完成HTML5标准。</p><p>HTML工作组还发布了HTML5.1、HTML Canvas 2D Context、Level 2以及主要元素的草案，让开发人员能提前预览下一轮标准。</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('2', '&lt;p&gt;JavaScript一种直译式脚本语言，是一种动态类型、弱类型、基于原型的语言，内置支持类型。它的解释器被称为JavaScript引擎，为浏览器的一部分，广泛用于客户端的脚本语言，最早是在HTML（标准通用标记语言下的一个应用）网页上使用，用来给HTML网页增加动态功能。&lt;/p&gt;&lt;p&gt;在1995年时，由Netscape公司的Brendan Eich，在网景导航者浏览器上首次设计实现而成。因为Netscape与Sun合作，Netscape管理层希望它外观看起来像Java，因此取名为JavaScript。但实际上它的语法风格与Self及Scheme较为接近。&lt;/p&gt;&lt;p&gt;为了取得技术优势，微软推出了JScript，CEnvi推出ScriptEase，与JavaScript同样可在浏览器上运行。为了统一规格，因为JavaScript兼容于ECMA标准，因此也称为ECMAScript。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;JavaScript是一种属于网络的脚本语言,已经被广泛用于Web应用开发,常用来为网页添加各式各样的动态功能,为用户提供更流畅美观的浏览效果。通常JavaScript脚本是通过嵌入在HTML中来实现自身的功能的。&lt;/p&gt;&lt;p&gt;是一种解释性脚本语言（代码不进行预编译）。&lt;/p&gt;&lt;p&gt;主要用来向HTML（标准通用标记语言下的一个应用）页面添加交互行为。&amp;nbsp;&lt;/p&gt;&lt;p&gt;可以直接嵌入HTML页面，但写成单独的js文件有利于结构和行为的分离。&amp;nbsp;&lt;/p&gt;&lt;p&gt;跨平台特性，在绝大多数浏览器的支持下，可以在多种平台下运行（如Windows、Linux、Mac、Android、iOS等）。&lt;/p&gt;&lt;p&gt;Javascript脚本语言同其他语言一样，有它自身的基本数据类型，表达式和算术运算符及程序的基本程序框架。Javascript提供了四种基本的数据类型和两种特殊数据类型用来处理数据和文字。而变量提供存放信息的地方，表达式则可以完成较复杂的信息处理。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;JavaScript脚本语言具有以下特点:&lt;/p&gt;&lt;p&gt;(1)脚本语言。JavaScript是一种解释型的脚本语言,C、C++等语言先编译后执行,而JavaScript是在程序的运行过程中逐行进行解释。&lt;/p&gt;&lt;p&gt;(2)基于对象。JavaScript是一种基于对象的脚本语言,它不仅可以创建对象,也能使用现有的对象。&lt;/p&gt;&lt;p&gt;(3)简单。JavaScript语言中采用的是弱类型的变量类型,对使用的数据类型未做出严格的要求,是基于Java基本语句和控制的脚本语言,其设计简单紧凑。&lt;/p&gt;&lt;p&gt;(4)动态性。JavaScript是一种采用事件驱动的脚本语言,它不需要经过Web服务器就可以对用户的输入做出响应。在访问一个网页时,鼠标在网页中进行鼠标点击或上下移、窗口移动等操作JavaScript都可直接对这些事件给出相应的响应。&lt;/p&gt;&lt;p&gt;(5)跨平台性。JavaScript脚本语言不依赖于操作系统,仅需要浏览器的支持。因此一个JavaScript脚本在编写后可以带到任意机器上使用,前提上机器上的浏览器支 持JavaScript脚本语言,目前JavaScript已被大多数的浏览器所支持。&lt;/p&gt;&lt;p&gt;不同于服务器端脚本语言，例如PHP与ASP，JavaScript主要被作为客户端脚本语言在用户的浏览器上运行，不需要服务器的支持。所以在早期程序员比较青睐于JavaScript以减少对服务器的负担，而与此同时也带来另一个问题：安全性。&lt;/p&gt;&lt;p&gt;而随着服务器的强壮，虽然程序员更喜欢运行于服务端的脚本以保证安全，但JavaScript仍然以其跨平台、容易上手等优势大行其道。同时，有些特殊功能（如AJAX）必须依赖Javascript在客户端进行支持。随着引擎如V8和框架如Node.js的发展，及其事件驱动及异步IO等特性，JavaScript逐渐被用来编写服务器端程序。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;');
INSERT INTO `uk_article_data` VALUES ('3', '<p>Bootstrap，来自 Twitter，是目前很受欢迎的前端框架。Bootstrap 是基于 HTML、CSS、JAVASCRIPT 的，它简洁灵活，使得 Web 开发更加快捷。它由Twitter的设计师Mark Otto和Jacob Thornton合作开发，是一个CSS/HTML框架。Bootstrap提供了优雅的HTML和CSS规范，它即是由动态CSS语言Less写成。Bootstrap一经推出后颇受欢迎，一直是GitHub上的热门开源项目，包括NASA的MSNBC（微软全国广播公司）的Breaking News都使用了该项目。国内一些移动开发者较为熟悉的框架，如WeX5前端开源框架等，也是基于Bootstrap源码进行性能优化而来。</p><p><br/></p><p>基本结构：Bootstrap 提供了一个带有网格系统、链接样式、背景的基本结构。这将在 Bootstrap 基本结构 部分详细讲解。</p><p>CSS：Bootstrap 自带以下特性：全局的 CSS 设置、定义基本的 HTML 元素样式、可扩展的 class，以及一个先进的网格系统。</p><p>组件：Bootstrap 包含了十几个可重用的组件，用于创建图像、下拉菜单、导航、警告框、弹出框等等。</p><p>JavaScript 插件：Bootstrap 包含了十几个自定义的 jQuery 插件。您可以直接包含所有的插件，也可以逐个包含这些插件。</p><p>定制：您可以定制 Bootstrap 的组件、LESS 变量和 jQuery 插件来得到您自己的版本。</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('4', '<p>Semantic UI 是一款语义化设计的前端开源框架，其功能强大，使用简单，为设计师和开发师提供可复用的完美设计方案。</p><p>Semantic 的约定围绕 常见用法 而不是自定配方。句法借鉴于自然语言中的有用原则例如复数、时态和词序，让你的代码能够自我解释。</p><p>Semantic UI 对单词和类以可交换的概念处理。classes使用类似名词/修饰词关系的自然语言语法，对语序，多连接有直观概念。</p><p>Semantic 设计为完全使用em 这令响应式尺寸轻而易举。元素内嵌入的设计变量让你决定内容如何为平板和移动设备调整。</p><p>Semantic 拥有与 Angular, Meteor, Ember 以及很多其它框架的集成，帮助你一同组织 UI 层与应用逻辑。</p><p>直观的JavaScript：Semantic 用简单的短语来触发功能。在组件中任意设计都是作为一个设置，开发者可以修改。</p><p>Semantic 给予了完全的设计自由。高层次变量与直观的集成系统让你用仅仅几行代码改变你的部件的外观与感觉。</p><p>定义不仅仅局限于页面上的按钮。Semantic 的部件提供了几种不同类型的定义：元素，组合，视图，模块与行为，这些囊括了界面设计的各个方面。</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('5', '<p>Vue.js（读音 /vjuː/，类似于 view 的读音）是一套构建用户界面(user interface)的渐进式框架。与其他重量级框架不同的是，Vue 从根本上采用最小成本、渐进增量(incrementally adoptable)的设计。Vue 的核心库只专注于视图层，并且很容易与其他第三方库或现有项目集成。另一方面，当与单文件组件和 Vue 生态系统支持的库结合使用时，Vue 也完全能够为复杂的单页应用程序提供有力驱动。</p><p>组件系统是 Vue 的一个重要概念，因为它是一种抽象，可以让我们使用小型、自包含和通常可复用的组件，把这些组合来构建大型应用程序。仔细想想，几乎任意类型的应用程序界面，都可以抽象为一个组件树。</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('6', '&lt;p&gt;React 起源于 Facebook 的内部项目，因为该公司对市场上所有 JavaScript MVC 框架，都不满意，就决定自己写一套，用来架设 Instagram 的网站。做出来以后，发现这套东西很好用，就在2013年5月开源了。由于 React 的设计思想极其独特，属于革命性创新，性能出众，代码逻辑却非常简单。所以，越来越多的人开始关注和使用，认为它可能是将来 Web 开发的主流工具。&lt;/p&gt;&lt;p&gt;&lt;strong&gt;1、ReactJS的背景和原理&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;在Web开发中，我们总需要将变化的数据实时反应到UI上，这时就需要对DOM进行操作。而复杂或频繁的DOM操作通常是性能瓶颈产生的原因（如何进行高性能的复杂DOM操作通常是衡量一个前端开发人员技能的重要指标）。React为此引入了虚拟DOM（Virtual DOM）的机制：在浏览器端用Javascript实现了一套DOM API。基于React进行开发时所有的DOM构造都是通过虚拟DOM进行，每当数据变化时，React都会重新构建整个DOM树，然后React将当前整个DOM树和上一次的DOM树进行对比，得到DOM结构的区别，然后仅仅将需要变化的部分进行实际的浏览器DOM更新。而且React能够批处理虚拟DOM的刷新，在一个事件循环（Event Loop）内的两次数据变化会被合并，例如你连续的先将节点内容从A变成B，然后又从B变成A，React会认为UI不发生任何变化，而如果通过手动控制，这种逻辑通常是极其复杂的。尽管每一次都需要构造完整的虚拟DOM树，但是因为虚拟DOM是内存数据，性能是极高的，而对实际DOM进行操作的仅仅是Diff部分，因而能达到提高性能的目的。这样，在保证性能的同时，开发者将不再需要关注某个数据的变化如何更新到一个或多个具体的DOM元素，而只需要关心在任意一个数据状态下，整个界面是如何Render的。&lt;/p&gt;&lt;p&gt;&lt;strong&gt;2、组件化&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;虚拟DOM(virtual-dom)不仅带来了简单的UI开发逻辑，同时也带来了组件化开发的思想，所谓组件，即封装起来的具有独立功能的UI部件。React推荐以组件的方式去重新思考UI构成，将UI上每一个功能相对独立的模块定义成组件，然后将小的组件通过组合或者嵌套的方式构成大的组件，最终完成整体UI的构建。例如，Facebook的instagram.com整站都采用了React来开发，整个页面就是一个大的组件，其中包含了嵌套的大量其它组件，大家有兴趣可以看下它背后的代码。&lt;/p&gt;&lt;p&gt;如果说MVC的思想让你做到视图-数据-控制器的分离，那么组件化的思考方式则是带来了UI功能模块之间的分离。我们通过一个典型的Blog评论界面来看MVC和组件化开发思路的区别。&lt;/p&gt;&lt;p&gt;对于MVC开发模式来说，开发者将三者定义成不同的类，实现了表现，数据，控制的分离。开发者更多的是从技术的角度来对UI进行拆分，实现松耦合。&lt;/p&gt;&lt;p&gt;对于React而言，则完全是一个新的思路，开发者从功能的角度出发，将UI分成不同的组件，每个组件都独立封装。&lt;/p&gt;&lt;p&gt;在React中，你按照界面模块自然划分的方式来组织和编写你的代码，对于评论界面而言，整个UI是一个通过小组件构成的大组件，每个组件只关心自己部分的逻辑，彼此独立。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;');
INSERT INTO `uk_article_data` VALUES ('7', '<p>PHP（外文名:PHP: Hypertext Preprocessor，中文名：“超文本预处理器”）是一种通用开源脚本语言。语法吸收了C语言、Java和Perl的特点，利于学习，使用广泛，主要适用于Web开发领域。PHP 独特的语法混合了C、Java、Perl以及PHP自创的语法。它可以比CGI或者Perl更快速地执行动态网页。用PHP做出的动态页面与其他的编程语言相比，PHP是将程序嵌入到HTML（标准通用标记语言下的一个应用）文档中去执行，执行效率比完全生成HTML标记的CGI要高许多；PHP还可以执行编译后代码，编译可以达到加密和优化代码运行，使代码运行更快。</p><p>PHP原始为Personal Home Page的缩写，已经正式更名为 &quot;PHP: Hypertext Preprocessor&quot;。注意不是“Hypertext Preprocessor”的缩写，这种将名称放到定义中的写法被称作递归缩写。PHP于1994年由Rasmus Lerdorf创建，刚刚开始是Rasmus Lerdorf为了要维护个人网页而制作的一个简单的用Perl语言编写的程序。这些工具程序用来显示 Rasmus Lerdorf 的个人履历，以及统计网页流量。后来又用C语言重新编写，包括可以访问数据库。他将这些程序和一些表单直译器整合起来，称为 PHP/FI。PHP/FI 可以和数据库连接，产生简单的动态网页程序。</p><p>在1995年以Personal Home Page Tools (PHP Tools) 开始对外发表第一个版本，Lerdorf写了一些介绍此程序的文档。并且发布了PHP1.0！在这的版本中，提供了访客留言本、访客计数器等简单的功能。以后越来越多的网站使用了PHP，并且强烈要求增加一些特性。比如循环语句和数组变量等等；在新的成员加入开发行列之后，Rasmus Lerdorf 在1995年6月8日将 PHP/FI 公开发布，希望可以透过社群来加速程序开发与寻找错误。这个发布的版本命名为 PHP 2，已经有 PHP 的一些雏型，像是类似 Perl的变量命名方式、表单处理功能、以及嵌入到 HTML 中执行的能力。程序语法上也类似 Perl，有较多的限制，不过更简单、更有弹性。PHP/FI加入了对MySQL的支持，从此建立了PHP在动态网页开发上的地位。到了1996年底，有15000个网站使用 PHP/FI。</p><p><br/></p><p><strong>PHP的特性包括</strong>：</p><p>1. PHP 独特的语法混合了 C、Java、Perl 以及 PHP 自创新的语法。</p><p>2. PHP可以比CGI或者Perl更快速的执行动态网页——动态页面方面，与其他的编程语言相比，</p><p>PHP是将程序嵌入到HTML文档中去执行，执行效率比完全生成htmL标记的CGI要高许多；</p><p>PHP具有非常强大的功能，所有的CGI的功能PHP都能实现。</p><p>3. PHP支持几乎所有流行的数据库以及操作系统。</p><p>4. 最重要的是PHP可以用C、C++进行程序的扩展</p><p><br/></p><p><strong>开放源代码</strong></p><p>所有的PHP源代码事实上都可以得到。</p><p><strong>免费性</strong></p><p>和其它技术相比，PHP本身免费且是开源代码。</p><p><strong>快捷性</strong></p><p>程序开发快，运行快，技术本身学习快。嵌入于HTML：因为PHP可以被嵌入于HTML语言，它相对于其他语言。编辑简单，实用性强，更适合初学者。</p><p><strong>跨平台性强</strong></p><p>由于PHP是运行在服务器端的脚本，可以运行在UNIX、LINUX、WINDOWS、Mac OS、Android等平台</p><p><strong>效率高</strong></p><p>PHP消耗相当少的系统资源。</p><p><strong>图像处理</strong></p><p>用PHP动态创建图像,PHP图像处理默认使用GD2。且也可以配置为使用image magick进行图像处理。</p><p><strong>面向对象</strong></p><p>在php4,php5 中，面向对象方面都有了很大的改进，php完全可以用来开发大型商业程序。</p><p><br/></p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('8', '<p style=\"white-space: normal;\"><strong>PHP7要打破一切</strong>。 PHP开发人员应该接受打破版本之间向下兼容的定律。只要不允许大量的向后兼容，PHP7将是一个高度尊重的语言。</p><p style=\"white-space: normal;\">　　1、创建一个具体的核心语言 删除所有库方法，并保持在对象集中的核心方法。 您应该能够编写无需任何外部库或扩展PHP7和对基本输入/输出，字符串处理和数学一个很好的完整的语言。库以外的任何应该通过批准扩展。</p><p style=\"white-space: normal;\">　　2、 一切都当作一个对象 以从Ruby，Smalltalk和(主要)的Java对象，并把它一切当作对象。 整数是对象，字符串是对象，他们每个人都可以操作的方法， 我不相信PHP需要的Ruby和Smalltalk在对象之间传递彼此讯息的观念，而调用对象的方法才是最好的。</p><p style=\"white-space: normal;\">　　3、一致的命名方法和类 由于PHP的最大的抱怨之一是不断要检查，(needle,haystack) 或(haystack, needle)，或some_function()，或function_some()，或someFunction()，一个一致的格式需要制定。</p><p style=\"white-space: normal;\">　　4、让事情严格尝试传递到一个方法浮动字符串? 这是一个警告。</p><p style=\"white-space: normal;\">　　5、 一切是Unicode 在PHP6中的所有字符串都是Unicode，这很好，我主张PHP7也应该保持。</p><p style=\"white-space: normal;\">　　6、中央启动点 创建一个主类或初始化，所有代码执行源于此。</p><p style=\"white-space: normal;\">　　7、清理C代码我不是一个C的专家，但如果你比较了解Ruby的C代码到PHP的C代码，可以很容易地了解了PHP与Ruby的内部。 我非常熟悉PHP，所以我自己的写扩展更容易。</p><p style=\"white-space: normal;\">　　8、摆脱eval() eval()是邪恶的。 如果你正在使用它，那么这是一个错的主意：这将打破PHPUnit，抛弃它从现在开始。</p><p style=\"white-space: normal;\">　　9、支持操作符重载 因为一切都是对象，开发者只需掌握操作对象的方法即可。</p><p style=\"white-space: normal;\">　　10、允许的方法签名</p><p style=\"white-space: normal;\"><strong>性能提升：</strong></p><p style=\"white-space: normal;\">PHP7比PHP5.6性能提升了两倍。 Improved performance: PHP 7 is up to twice as fast as PHP 5.6</p><p style=\"white-space: normal;\">全面一致的64位支持。 Consistent 64-bit support</p><p style=\"white-space: normal;\">以前的许多致命错误，现在改成抛出异常。Many fatal errors are now Exceptions</p><p style=\"white-space: normal;\">移除了一些老的不在支持的SAPI（服务器端应用编程端口）和扩展。Removal of old and unsupported SAPIs and extensions</p><p style=\"white-space: normal;\">新增了空接合操作符。The null coalescing operator (??)</p><p style=\"white-space: normal;\">新增加了结合比较运算符。Combined comparison Operator (&lt;=&gt;)<!--=--><!--=--></p><p style=\"white-space: normal;\">新增加了函数的返回类型声明。Return Type Declarations</p><p style=\"white-space: normal;\">新增加了标量类型声明。Scalar Type Declarations</p><p style=\"white-space: normal;\">新增加匿名类。Anonymous Classes</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('9', '<p>ThinkPHP是一个免费开源的，快速、简单的面向对象的轻量级PHP开发框架，是为了敏捷WEB应用开发和简化企业应用开发而诞生的。ThinkPHP从诞生以来一直秉承简洁实用的设计原则，在保持出色的性能和至简的代码的同时，也注重易用性。遵循Apache2开源许可协议发布，意味着你可以免费使用ThinkPHP，甚至允许把你基于ThinkPHP开发的应用开源或商业产品发布/销售。</p><p><br/></p><p>ThinkPHP V5.0是一个为API开发而设计的高性能框架——是一个颠覆和重构版本，采用全新的架构思想，引入了很多的PHP新特性，优化了核心，减少了依赖，实现了真正的惰性加载，支持composer，并针对API开发做了大量的优化。 ThinkPHP5是一个全新的里程碑版本，包括路由、日志、异常、模型、数据库、模板引擎和验证等模块都已经重构，不适合原有3.2项目的升级，请慎重考虑商业项目升级，但绝对是新项目的首选（无论是WEB还是API开发），而且最好是忘记3.2版本的思维习惯，重新理解TP5。</p><p><br/></p><p><strong>规范</strong>：遵循PSR-2、PSR-4规范，Composer及单元测试支持；</p><p><strong>严谨</strong>：异常严谨的错误检测和安全机制，详细的日志信息，为你的开发保驾护航；</p><p><strong>灵活</strong>：减少核心依赖，扩展更灵活、方便，支持命令行指令扩展；</p><p><strong>API友好</strong>：出色的性能和REST支持、远程调试，更好的支持API开发；</p><p><strong>高效</strong>：惰性加载，及路由、配置和自动加载的缓存机制；</p><p><strong>ORM</strong>：重构的数据库、模型及关联，MongoDb支持；</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('10', '<p>Laravel是一套简洁、优雅的PHP Web开发框架(PHP Web Framework)。它可以让你从面条一样杂乱的代码中解脱出来；它可以帮你构建一个完美的网络APP，而且每行代码都可以简洁、富于表达力。</p><p>在Laravel中已经具有了一套高级的PHP ActiveRecord实现 -- Eloquent ORM。它能方便的将“约束（constraints）”应用到关系的双方，这样你就具有了对数据的完全控制，而且享受到ActiveRecord的所有便利。Eloquent原生支持Fluent中查询构造器（query-builder）的所有方法。</p><p><br/></p><p>1、Bundle是Laravel的扩展包组织形式或称呼。Laravel的扩展包仓库已经相当成熟了，可以很容易的帮你把扩展包（bundle）安装到你的应用中。你可以选择下载一个扩展包（bundle）然后拷贝到bundles目录，或者通过命令行工具“Artisan”自动安装。</p><p>2、应用逻辑（Application Logic）可以在控制器（controllers）中实现，也可以直接集成到路由（route）声明中，并且语法和Sinatra框架类似。Laravel的设计理念是：给开发者以最大的灵活性，既能创建非常小的网站也能构建大型的企业应用。</p><p>3、反向路由（Reverse Routing）赋予你通过路由（routes）名称创建链接（URI)的能力。只需使用路由名称（route name），Laravel就会自动帮你创建正确的URI。这样你就可以随时改变你的路由（routes），Laravel会帮你自动更新所有相关的链接。</p><p>4、Restful控制器（Restful Controllers）是一项区分GET和POST请求逻辑的可选方式。比如在一个用户登陆逻辑中，你声明了一个get_login()的动作（action）来处理获取登陆页面的服务；同时也声明了一个post_login()动作（action）来校验表单POST过来的数据，并且在验证之后，做出重新转向（redirect）到登陆页面还是转向控制台的决定。</p><p>5、自动加载类（Class Auto-loading）简化了类（class）的加载工作，以后就可以不用去维护自动加载配置表和非必须的组件加载工作了。当你想加载任何库（library）或模型（model）时，立即使用就行了，Laravel会自动帮你加载需要的文件。</p><p>6、视图组装器（View Composers）本质上就是一段代码，这段代码在视图（View）加载时会自动执行。最好的例子就是博客中的侧边随机文章推荐，“视图组装器”中包含了加载随机文章推荐的逻辑，这样，你只需要加载内容区域的视图（view）就行了，其它的事情Laravel会帮你自动完成。</p><p>7、反向控制容器（IoC container）提供了生成新对象、随时实例化对象、访问单例（singleton）对象的便捷方式。反向控制（IoC）意味着你几乎不需要特意去加载外部的库（libraries），就可以在代码中的任意位置访问这些对象，并且不需要忍受繁杂、冗余的代码结构。</p><p>8、迁移（Migrations）就像是版本控制（version control）工具，不过，它管理的是数据库范式，并且直接集成在了Laravel中。你可以使用“Artisan”命令行工具生成、执行“迁移”指令。当你的小组成员改变了数据库范式的时候，你就可以轻松的通过版本控制工具更新当前工程，然后执行“迁移&quot;指令即可，好了，你的数据库已经是最新的了！</p><p>9、单元测试（Unit-Testing）是Laravel中很重要的部分。Laravel自身就包含数以百计的测试用例，以保障任何一处的修改不会影响其它部分的功能，这就是为什么在业内Laravel被认为是最稳版本的原因之一。Laravel也提供了方便的功能，让你自己的代码容易的进行单元测试。通过Artisan命令行工具就可以运行所有的测试用例。</p><p>10、自动分页（Automatic Pagination）功能避免了在你的业务逻辑中混入大量无关分页配置代码。方便的是不需要记住当前页，只要从数据库中获取总的条目数量，然后使用limit/offset获取选定的数据，最后调用‘paginate’方法，让Laravel将各页链接输出到指定的视图（View)中即可，Laravel会替你自动完成所有工作。Laravel的自动分页系统被设计为容易实现、易于修改。虽然Laravel可以自动处理这些工作，但是不要忘了调用相应方法和手动配置分页系统哦！</p><p><br/></p>');
INSERT INTO `uk_article_data` VALUES ('11', '&lt;p&gt;Symfony是一个基于MVC模式的面向对象的PHP5框架。Symfony允许在一个web应用中分离事务控制，服务逻辑和表示层。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;简单的模板功能symfony是一个开源的PHP Web框架。基于最佳Web开发实践，已经有多个网站完全采用此框架开发，symfony的目的是加速Web应用的创建与维护。同时，它还包含了很多工具和类用以缩短开发复杂的网络应用的时间。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;它的特点如下：&lt;/p&gt;&lt;p&gt;简单的模板功能&lt;/p&gt;&lt;p&gt;缓存管理&lt;/p&gt;&lt;p&gt;自定义URLs&lt;/p&gt;&lt;p&gt;搭建了一些基础模块&lt;/p&gt;&lt;p&gt;多语言与I18N支持&lt;/p&gt;&lt;p&gt;采用对象模型与MVC分离&lt;/p&gt;&lt;p&gt;Ajax支持&lt;/p&gt;&lt;p&gt;适用于企业应用开发&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;无论你是一个php5的专家还是一个在网络应用编程方面的新手都没有问题，影响你决定的最主要因素是你项目的大小。&lt;/p&gt;&lt;p&gt;如果你只是想要开发一个简易的5-10页的站点，只需要有限的访问数据库和几乎不考虑性能、可用性或文档，那么你只需要单独使用PHP。你将不会从网络应用的框架结构的特征中获的太多的益处，使用面向对象或MVC模式只会使你的开发变慢。Symfony运行在一个只有CGI支持的共享主机上将不会高效优异的运行。&lt;/p&gt;&lt;p&gt;另一方面，如果你开发大型的web应用，其中有大量的事务逻辑，那么单独使用PHP是不够的。如果你计划将来维护和扩展你的应用，你需要编写轻量级、易读的和高性能的的代码。如果你想直接使用最友好的用户交互界面（AJAX），你不能只写上数百行的Javascrīpt代码。如果你想享受并且快速的开发，那么单独使用PHP将是令人失望的。介于上述这些原因，symfony非常适合你。&lt;/p&gt;&lt;p&gt;当然，如果你是一个专业的web开发人员，你已经知道了web应用框架的所有优点，并且你需要一个成熟的，具有详细文档和一个大的团体支持。那么不要再犹豫，symfony就是你的解决方案。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;');
INSERT INTO `uk_article_data` VALUES ('12', '<p>服务器环境：php 5.6+ &amp; Mysql&nbsp;&amp; Apache/Nginx/IIS</p><p>浏览器：Versions FF, Chrome, IE (aka 10+)</p>');
INSERT INTO `uk_article_data` VALUES ('13', '<p>满足以下两个条件就可免费使用UKcms：</p><p>1、程序用于非盈利用途，注意：任何将系统用于盈利目的的行为我们将保留追责权利。</p><p>2、只能使用免费版程序：免费版内部含有版权信息：Powered by UKcms，在功能和安全设置上与正式授权版有所不同。</p>');
INSERT INTO `uk_article_data` VALUES ('14', '<p>1、提供单独的授权版系统</p><p>2、提供售后服务，包含：安装、升级和迁移指导；在任何由于UKcms系统自身问题导致网站无法正常运行的情况下提供解决方案</p><p>3、授权费可以抵扣定制费用</p>');

-- ----------------------------
-- Table structure for uk_attachment
-- ----------------------------
DROP TABLE IF EXISTS `uk_attachment`;
CREATE TABLE `uk_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(64) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of uk_attachment
-- ----------------------------
INSERT INTO `uk_attachment` VALUES ('1', '1', 'demo1.jpg', 'admin', 'images/20170614/3301b085279f819c91906763f343273a.jpg', '', '', 'image/jpeg', 'jpg', '2170', '4bd3b079d4fd99d0bcfe4b51c441ce96', 'bca848acb3d828908b90d1e38a39dd570fd74a49', 'local', '0', '1497421718', '1497421718', '100', '1');
INSERT INTO `uk_attachment` VALUES ('2', '1', 'demo2.jpg', 'admin', 'images/20170715/1b18e5d089de85c470558331212b5a6b.png', '', '', 'image/jpeg', 'jpg', '6172', 'afb9f77547cb29396a96353ee6e27da3', '5b08b57a2d266443e9441cefb1b1039474fb0458', 'local', '0', '1500085487', '1500085487', '100', '1');
INSERT INTO `uk_attachment` VALUES ('3', '1', 'demo3.jpg', 'admin', 'images/20170715/6f51ffa8c3c7eacedef551e69616aeed.jpg', '', '', 'image/jpeg', 'jpg', '5388', '8909929348c8c297de64c5add9d05bac', '88f2f7eb982c3ea11fcc846092dcfce1636c63d6', 'local', '0', '1500101761', '1500101761', '100', '1');
INSERT INTO `uk_attachment` VALUES ('4', '1', 'demo4.png', 'admin', 'images/20170715/0e6bfb19c98b183142c9e53a3fa34487.png', '', '', 'image/png', 'png', '24363', '9f0458f97a240ec6e41de43d1ec11bc1', '5b31388a2447fb47fc5272474d04f16552300b1c', 'local', '0', '1500103834', '1500103834', '100', '1');
INSERT INTO `uk_attachment` VALUES ('5', '1', 'demo5.jpg', 'admin', 'images/20170715/bdb7aff8b8421ac471270ba5d84d7ad4.jpg', '', '', 'image/jpeg', 'jpg', '4397', '39f516b595fce54ff0a0be8e57ec12bc', '60e7bed775f10f0b27fe9776e79bd3994c52252d', 'local', '0', '1500104762', '1500104762', '100', '1');
INSERT INTO `uk_attachment` VALUES ('6', '1', 'demo6.jpg', 'admin', 'images/20170715/29011516a47f95ed7e8e50575aebdafc.jpg', '', '', 'image/jpeg', 'jpg', '6520', '4fce14c5dc68dd3e05ca939d988ed1a0', '292f643438a42fc8e804e33550ba8b5b8b1ad32c', 'local', '0', '1500106065', '1500106065', '100', '1');
INSERT INTO `uk_attachment` VALUES ('7', '1', 'demo7t.jpg', 'admin', 'images/20170715/d2a30e8107f5137ca99c7a52b1635421.jpg', '', '', 'image/jpeg', 'jpg', '4821', '6e825ae70a888145a20c9ef10aa61dfd', 'd45c8a5eeb03b56b7312e3e3b46689e7ef7f4967', 'local', '0', '1500106612', '1500106612', '100', '1');
INSERT INTO `uk_attachment` VALUES ('8', '1', 'demo8.JPG', 'admin', 'images/20170715/e672b350015bf23c1a86be88c194e8c3.JPG', '', '', 'image/jpeg', 'JPG', '7490', 'fe5b3ecc2997408bace8e9aa43b7e3b5', '854247f723f6bb6df11e043e4943de63cf421fc9', 'local', '0', '1500107464', '1500107464', '100', '1');
INSERT INTO `uk_attachment` VALUES ('9', '1', 'watermark.png', 'admin', 'images/20170716/85ab14f70024491b8d49a2192563e361.png', '', '', 'image/png', 'png', '2396', 'b4625444ee3c0554089c2ad625ebbcdc', 'a5eb0e9c97a4e36cedabca14775058a208b712f6', 'local', '0', '1500182195', '1500182195', '100', '1');
INSERT INTO `uk_attachment` VALUES ('10', '1', 'g1.jpg', 'admin', 'images/20170716/383e22b40d82c9efec6af8a6d948a896.jpg', 'images/20170716/thumb/383e22b40d82c9efec6af8a6d948a896.jpg', '', 'image/jpeg', 'jpg', '37297', '997457cac9a441e80ddfafecd9ae19a2', '5c704965df9c66628a0e07758c2ff923958c2128', 'local', '0', '1500182291', '1500182291', '100', '1');
INSERT INTO `uk_attachment` VALUES ('11', '1', 'g3.jpg', 'admin', 'images/20170716/2467d58feed579f18854119c268365d1.jpg', 'images/20170716/thumb/2467d58feed579f18854119c268365d1.jpg', '', 'image/jpeg', 'jpg', '88276', '4c63654b390e8b6101709351f7ff86a9', '539208f8d1441b3b6c656684ef18c10c7b6f26b7', 'local', '0', '1500182292', '1500182292', '100', '1');
INSERT INTO `uk_attachment` VALUES ('12', '1', 'g4.jpg', 'admin', 'images/20170716/af5110625ee67f1d4f37da1dc33f2a23.jpg', 'images/20170716/thumb/af5110625ee67f1d4f37da1dc33f2a23.jpg', '', 'image/jpeg', 'jpg', '98397', '4855506c81c6e7ee1a4788168f10bb3b', '101631470631fa1d6995d76d3b6cb7627c85536b', 'local', '0', '1500182292', '1500182292', '100', '1');
INSERT INTO `uk_attachment` VALUES ('13', '1', 'g2.jpg', 'admin', 'images/20170716/4257f7a9c674c9efff24f278521370a4.jpg', 'images/20170716/thumb/4257f7a9c674c9efff24f278521370a4.jpg', '', 'image/jpeg', 'jpg', '188886', '4ce43ffeaa0356cff407ddb84de2f7e8', 'aa4a6d34ada598743b466f9b1b9daafbc462c188', 'local', '0', '1500182292', '1500182292', '100', '1');
INSERT INTO `uk_attachment` VALUES ('14', '1', 'g12.jpeg', 'admin', 'images/20170716/5db1dee06042595f1bf20cf8a78ba8be.jpeg', 'images/20170716/thumb/5db1dee06042595f1bf20cf8a78ba8be.jpeg', '', 'image/jpeg', 'jpeg', '151640', 'c90142a84a8a0f655143f65f13549767', '9b99b61487c6fd7bcbfb3c5c7088517f8e60be4f', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('15', '1', 'g11.jpeg', 'admin', 'images/20170716/9772eb6068193c68163e82c1c04e1fd4.jpeg', 'images/20170716/thumb/9772eb6068193c68163e82c1c04e1fd4.jpeg', '', 'image/jpeg', 'jpeg', '182597', '00bc4c6a2f07965274e38d0a4765b0fc', '6c5f05d80ed5ea955bb4289c3ae940f67299333c', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('16', '1', 'g13.jpg', 'admin', 'images/20170716/555a802803c4baa99e8d8d12c97a7cca.jpg', 'images/20170716/thumb/555a802803c4baa99e8d8d12c97a7cca.jpg', '', 'image/jpeg', 'jpg', '100688', 'ed105dc56b67480753c4305213ece9d4', '493f8459da3c188bdd790e67a32e35d23d7d2450', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('17', '1', 'g14.jpeg', 'admin', 'images/20170716/a610754f8b6d5d24ea40ae463c932d44.jpeg', 'images/20170716/thumb/a610754f8b6d5d24ea40ae463c932d44.jpeg', '', 'image/jpeg', 'jpeg', '78001', 'f9ee59c0ed86ac0c9fe0c9c8ec0c96c1', '09d97f7602da854a2bd1e3abbfea0c938c173b09', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('18', '1', 'g22.jpg', 'admin', 'images/20170716/c4e251b96f30762f9cd80359f2458e4f.jpg', 'images/20170716/thumb/c4e251b96f30762f9cd80359f2458e4f.jpg', '', 'image/jpeg', 'jpg', '95273', '1c32d3b71a0ce69ecdd618d3deaa6a3e', '99310f6b76e6679a8b2c17a1cceeaff8490f5939', 'local', '0', '1500188953', '1500188953', '100', '1');
INSERT INTO `uk_attachment` VALUES ('19', '1', 'g23.jpg', 'admin', 'images/20170716/d2b986b271d0622a3f96c5211053254b.jpg', 'images/20170716/thumb/d2b986b271d0622a3f96c5211053254b.jpg', '', 'image/jpeg', 'jpg', '154456', '7df7de7259e55310c5857f423ae78a29', 'bc04a012e872b67e969d30369897eaf054bc7e16', 'local', '0', '1500188954', '1500188954', '100', '1');
INSERT INTO `uk_attachment` VALUES ('20', '1', 'g24.jpeg', 'admin', 'images/20170716/e2cb292e32e91758f802a78003924194.jpeg', 'images/20170716/thumb/e2cb292e32e91758f802a78003924194.jpeg', '', 'image/jpeg', 'jpeg', '117863', 'd74744c492edd17fb374fd09f30d3ec1', '17d3c6e86e06beb06371cf07b1df5e05f0de34e1', 'local', '0', '1500188954', '1500188954', '100', '1');
INSERT INTO `uk_attachment` VALUES ('21', '1', 'g21.jpg', 'admin', 'images/20170716/9fa9ad6c22dee3dbef1a9806a64d3081.jpg', 'images/20170716/thumb/9fa9ad6c22dee3dbef1a9806a64d3081.jpg', '', 'image/jpeg', 'jpg', '109602', 'f06b9b1bb0aeddbc7903db26cd1f0159', 'a7b2b7fb296cb9862d5eb9c7605922f4201d4efb', 'local', '0', '1500188954', '1500188954', '100', '1');
INSERT INTO `uk_attachment` VALUES ('22', '1', 'm2.jpg', 'admin', 'images/20170716/8b256640cef0b5b46b4c400faf5adc51.jpg', 'images/20170716/thumb/8b256640cef0b5b46b4c400faf5adc51.jpg', '', 'image/jpeg', 'jpg', '86709', '956f86876872d51d5bcad50791202e9d', 'c866b893c03af75de227307b2fb4d1d657407e9c', 'local', '0', '1500190272', '1500190272', '100', '1');
INSERT INTO `uk_attachment` VALUES ('23', '1', 'm1.jpg', 'admin', 'images/20170716/d1077afda0fdec8f2b89a29a9ed40f98.jpg', 'images/20170716/thumb/d1077afda0fdec8f2b89a29a9ed40f98.jpg', '', 'image/jpeg', 'jpg', '91997', 'bba6c384d420afae0ee6cd60f7a422f3', '9f2d10a6d098c1e0e4a213b93d0d721e5be4946a', 'local', '0', '1500190272', '1500190272', '100', '1');
INSERT INTO `uk_attachment` VALUES ('24', '1', 'm3.jpg', 'admin', 'images/20170716/7de372105b92ffd05e0b790c4984283d.jpg', 'images/20170716/thumb/7de372105b92ffd05e0b790c4984283d.jpg', '', 'image/jpeg', 'jpg', '38435', '6b2537f45c671d68e6a5d957f0f7e925', '01640a3cf98268b7633cd848b7966133724fb0ef', 'local', '0', '1500190273', '1500190273', '100', '1');
INSERT INTO `uk_attachment` VALUES ('25', '1', 'm4.jpg', 'admin', 'images/20170716/2338da35a64f8c4e999ef79cae27b96e.jpg', 'images/20170716/thumb/2338da35a64f8c4e999ef79cae27b96e.jpg', '', 'image/jpeg', 'jpg', '75118', 'd0879f046db399d9b696fbc46767ff3e', '7ad0838f827cac02c920fd9a23fdef8bda7018f2', 'local', '0', '1500190273', '1500190273', '100', '1');
INSERT INTO `uk_attachment` VALUES ('26', '1', 'm11.jpg', 'admin', 'images/20170716/b64deae22e7e42050d91af2a246d83da.jpg', 'images/20170716/thumb/b64deae22e7e42050d91af2a246d83da.jpg', '', 'image/jpeg', 'jpg', '128565', 'ab74dd3b1909a19b143bca13e7ba23ee', 'cae92f88de4f0b3edf4e8db2bb60448425ec447f', 'local', '0', '1500191099', '1500191099', '100', '1');
INSERT INTO `uk_attachment` VALUES ('27', '1', 'm33.jpeg', 'admin', 'images/20170716/c600b04e424d49715cc28055a8469da6.jpeg', 'images/20170716/thumb/c600b04e424d49715cc28055a8469da6.jpeg', '', 'image/jpeg', 'jpeg', '115133', '9ee0ae16dc5d7b3774083a22fdd30c71', '022fa3c71879eb18acab05965c8ae2291bc0958a', 'local', '0', '1500191099', '1500191099', '100', '1');
INSERT INTO `uk_attachment` VALUES ('28', '1', 'm44.jpg', 'admin', 'images/20170716/ffefd6b7bae7d84c792d1752e4a13af5.jpg', 'images/20170716/thumb/ffefd6b7bae7d84c792d1752e4a13af5.jpg', '', 'image/jpeg', 'jpg', '119906', '7a559e68d16e255e64143910d3f5e13e', '418ee49a9fbecc2af15ad7cc80bc21e6a1067ee7', 'local', '0', '1500191099', '1500191099', '100', '1');
INSERT INTO `uk_attachment` VALUES ('29', '1', 'm22.jpg', 'admin', 'images/20170716/62ba52ceb37a1c4f1544c99c42b38bd4.jpg', 'images/20170716/thumb/62ba52ceb37a1c4f1544c99c42b38bd4.jpg', '', 'image/jpeg', 'jpg', '96091', 'd69068ae28f37983e0559362cf0b857b', '22523727f533e256acbabdb34fee6121b6384a5d', 'local', '0', '1500191100', '1500191100', '100', '1');
INSERT INTO `uk_attachment` VALUES ('30', '1', 'mm.jpeg', 'admin', 'images/20170716/cd19ad73e71aaf5d80e8080cb058fb92.jpeg', 'images/20170716/thumb/cd19ad73e71aaf5d80e8080cb058fb92.jpeg', '', 'image/jpeg', 'jpeg', '92243', '1a56e536e2ef1380375b11da4b5c8566', '130a3a80d2b550dcb2dee633191b21cec54ff923', 'local', '0', '1500191748', '1500191748', '100', '1');
INSERT INTO `uk_attachment` VALUES ('31', '1', 'mm2.jpeg', 'admin', 'images/20170716/a8fc65126ed9e8d4e882cd10cc13913f.jpeg', 'images/20170716/thumb/a8fc65126ed9e8d4e882cd10cc13913f.jpeg', '', 'image/jpeg', 'jpeg', '106100', 'ac27cda0db960026ea0c63d71fcdba43', '58ab01a5c4c753d0a46fd96f0823c6a959dfd237', 'local', '0', '1500191748', '1500191748', '100', '1');
INSERT INTO `uk_attachment` VALUES ('32', '1', 'mm3.jpeg', 'admin', 'images/20170716/799ea8dd132cfe68d66fcb1627029b62.jpeg', 'images/20170716/thumb/799ea8dd132cfe68d66fcb1627029b62.jpeg', '', 'image/jpeg', 'jpeg', '131670', '2d7de305736d018a0daa0a7ce47812d4', 'dbb22bebb1b4ec6da2404c5dfe4837f3895b0da0', 'local', '0', '1500191748', '1500191748', '100', '1');
INSERT INTO `uk_attachment` VALUES ('33', '1', 'mm1.jpeg', 'admin', 'images/20170716/76760e64b7ec481d1a5e29433c94ca79.jpeg', 'images/20170716/thumb/76760e64b7ec481d1a5e29433c94ca79.jpeg', '', 'image/jpeg', 'jpeg', '118089', '653e9a1fbc7b33e1c113df714daeeba4', '0349e7ab4d60d0aee694d845f5bf514c6b4a659f', 'local', '0', '1500191749', '1500191749', '100', '1');
INSERT INTO `uk_attachment` VALUES ('34', '1', 'banner.jpg', 'admin', 'images/20170717/dd8e22b3eb3f590d0e7e2bddf500e25a.jpg', '', '', 'image/jpeg', 'jpg', '65591', 'b1266e9a918fb6aeb762a1613a236ac8', 'f949030143e7802ae437c962d3f6129f769e4485', 'local', '0', '1500262078', '1500262078', '100', '1');
INSERT INTO `uk_attachment` VALUES ('35', '1', 'banner2.jpg', 'admin', 'images/20170719/135493c869d5ab63ac746ae143681f19.jpg', '', '', 'image/jpeg', 'jpg', '61214', '9880a8adaca5832701cf4828563d1ed5', '1591c345a0515e71657a6503bb0f3e8ca6e45f5b', 'local', '0', '1500444067', '1500444067', '100', '1');
INSERT INTO `uk_attachment` VALUES ('36', '1', 'toplogo.png', 'admin', 'images/20170719/057a708b48f7a2be31b07130b982aba6.png', '', '', 'image/png', 'png', '1417', '067cbd99f15c13f224e884d2cdf4b606', '9fb5c360160a5d33782f7701787ff422eb1fb448', 'local', '0', '1500452902', '1500452902', '100', '1');
INSERT INTO `uk_attachment` VALUES ('37', '1', 'k1.jpg', 'admin', 'images/20170720/79a078c48b971888df3d00ce5c55c7e0.jpg', 'images/20170720/thumb/79a078c48b971888df3d00ce5c55c7e0.jpg', '', 'image/jpeg', 'jpg', '84189', 'cd903bc8243ba32c5e7355b1a56833d3', '202cd993d4132783181f28a64a52590e91a7755e', 'local', '0', '1500512615', '1500512615', '100', '1');
INSERT INTO `uk_attachment` VALUES ('38', '1', 'k3.jpg', 'admin', 'images/20170720/a697a4004d618b40104ea0f688a63d95.jpg', 'images/20170720/thumb/a697a4004d618b40104ea0f688a63d95.jpg', '', 'image/jpeg', 'jpg', '64166', '8b5db4861550913d954dd6a4edff8fc3', '876df8cfb0cab82b493997307942a5a633b1407d', 'local', '0', '1500512615', '1500512615', '100', '1');
INSERT INTO `uk_attachment` VALUES ('39', '1', 'k2.jpg', 'admin', 'images/20170720/0a2bf64e7384824a36bb6ac29f88167b.jpg', 'images/20170720/thumb/0a2bf64e7384824a36bb6ac29f88167b.jpg', '', 'image/jpeg', 'jpg', '78087', '0937423667ab997aeb1bab4bd3b64760', '51e510b5a4c2a2f7b64bd642a7c9d6f287099ebc', 'local', '0', '1500512616', '1500512616', '100', '1');
INSERT INTO `uk_attachment` VALUES ('40', '1', 'b1.jpg', 'admin', 'images/20170720/2839de163a59f042bb8b9af83a0f8ba9.jpg', 'images/20170720/thumb/2839de163a59f042bb8b9af83a0f8ba9.jpg', '', 'image/jpeg', 'jpg', '58731', 'c3df756e4070c8e495fd82dee1831269', '9f3bcbd23bf42dacf575e8a9fb1efb1b33d600b1', 'local', '0', '1500512798', '1500512798', '100', '1');
INSERT INTO `uk_attachment` VALUES ('41', '1', 'b4.jpg', 'admin', 'images/20170720/025560fbf406c4ad68b359e0eba38d8a.jpg', 'images/20170720/thumb/025560fbf406c4ad68b359e0eba38d8a.jpg', '', 'image/jpeg', 'jpg', '53973', '43b949752bca709675099c81bf7c7014', 'f6de6a5ab9dbb8dd19cdf3720a45e16578c7263f', 'local', '0', '1500512798', '1500512798', '100', '1');
INSERT INTO `uk_attachment` VALUES ('42', '1', 'b3.jpg', 'admin', 'images/20170720/63cba4a898e87bc3dd26c588f25ea3a0.jpg', 'images/20170720/thumb/63cba4a898e87bc3dd26c588f25ea3a0.jpg', '', 'image/jpeg', 'jpg', '54960', '9f7beccafbe455c8b6f9b751879651fa', 'd33bc590a325d60a9413f58fee1e2119e2400966', 'local', '0', '1500512799', '1500512799', '100', '1');
INSERT INTO `uk_attachment` VALUES ('43', '1', 'm1.jpg', 'admin', 'images/20170720/95e9f8c667163d1c0573f302926b7ad6.jpg', 'images/20170720/thumb/95e9f8c667163d1c0573f302926b7ad6.jpg', '', 'image/jpeg', 'jpg', '58717', 'e2f80622b564325706f89671e9b685b4', '1cba2b6e2186dc63c86d371894632a39d2594e44', 'local', '0', '1500512875', '1500512875', '100', '1');
INSERT INTO `uk_attachment` VALUES ('44', '1', 'm3.jpg', 'admin', 'images/20170720/2306fbb4261ec050ac71555e20327f5f.jpg', 'images/20170720/thumb/2306fbb4261ec050ac71555e20327f5f.jpg', '', 'image/jpeg', 'jpg', '51866', 'fcd00f1d43075fc0eb99b6fb7936fe27', '63c06337688245de3e90ffa10b5c203b90e6d9f2', 'local', '0', '1500512875', '1500512875', '100', '1');
INSERT INTO `uk_attachment` VALUES ('45', '1', 'm2.jpg', 'admin', 'images/20170720/12f46b62947e7e4c4b2f66348f4c5d59.jpg', 'images/20170720/thumb/12f46b62947e7e4c4b2f66348f4c5d59.jpg', '', 'image/jpeg', 'jpg', '59861', '45d77e88fc18dd38bf3f6abea9979e64', '115a2efbb2ceef79a9fe959b20eda8fa9ac5f3f1', 'local', '0', '1500512876', '1500512876', '100', '1');
INSERT INTO `uk_attachment` VALUES ('46', '1', 'f1.jpg', 'admin', 'images/20170720/5b47dbba06bf2bcf7183a955be99185b.jpg', 'images/20170720/thumb/5b47dbba06bf2bcf7183a955be99185b.jpg', '', 'image/jpeg', 'jpg', '153574', '8f1e5ee3b1e1f94ca3e3c26943de990a', '50f01c0adb401826507b0cc203a40d0173dea1a0', 'local', '0', '1500513400', '1500513400', '100', '1');
INSERT INTO `uk_attachment` VALUES ('47', '1', 'f3.jpg', 'admin', 'images/20170720/647bcdda58c135c7bb66359b2e52f9f6.jpg', 'images/20170720/thumb/647bcdda58c135c7bb66359b2e52f9f6.jpg', '', 'image/jpeg', 'jpg', '194826', '476b73a7cb0a42b7631a995af66755fd', 'c0df02dd3d99ef03df9d1121a79b386c724f88f4', 'local', '0', '1500513401', '1500513401', '100', '1');
INSERT INTO `uk_attachment` VALUES ('48', '1', 'f2.jpg', 'admin', 'images/20170720/6cf8f5108ec738d7c683d377d7209991.jpg', 'images/20170720/thumb/6cf8f5108ec738d7c683d377d7209991.jpg', '', 'image/jpeg', 'jpg', '166422', '27c20c845ddf161b4b4b857633c506ec', '3755ba0ceeba55a74c480185ab409349191b0ce3', 'local', '0', '1500513401', '1500513401', '100', '1');

-- ----------------------------
-- Table structure for uk_behavior
-- ----------------------------
DROP TABLE IF EXISTS `uk_behavior`;
CREATE TABLE `uk_behavior` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(160) NOT NULL COMMENT '行为类名',
  `title` varchar(160) NOT NULL DEFAULT '' COMMENT '行为名称',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `ifalone` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否独立行为',
  `orders` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行为表';

-- ----------------------------
-- Records of uk_behavior
-- ----------------------------

-- ----------------------------
-- Table structure for uk_column
-- ----------------------------
DROP TABLE IF EXISTS `uk_column`;
CREATE TABLE `uk_column` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '模型类别1-单页 2-列表 3-自定义',
  `path` varchar(255) NOT NULL DEFAULT '0,' COMMENT '父级路径',
  `ext_model_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '拓展模型id',
  `model_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '英文标志',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT '网页标题',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '网页关键词',
  `meta_description` varchar(300) NOT NULL DEFAULT '' COMMENT '网页描述',
  `cover_picture` int(11) NOT NULL DEFAULT '0' COMMENT '栏目封面图',
  `template_list` varchar(50) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_content` varchar(50) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义链接',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` varchar(30) NOT NULL DEFAULT 'orders desc,id desc' COMMENT '列表栏目信息排序',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态:1显示0隐藏',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `path` (`path`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='前台栏目表';

-- ----------------------------
-- Records of uk_column
-- ----------------------------
INSERT INTO `uk_column` VALUES ('1', '1', '0,', '4', '0', '关于我们', 'aboutus', 'UkcmsWeb内容管理系统', 'ukcms,cms,建站系统,内容管理系统,web平台管理软件', 'UKcms是一款基于PHP7和mysql技术，简洁、灵活、强大的网站内容管理系统。底层使用面向对象的轻量级PHP开发框架，采用惰性加载，及路由、配置和自动加载的缓存机 制，确保系统高效运行，可使网站数据达到百万级负载。灵活强大的标签库可任意拓展，让您随心所欲。拥有建站各种实用功能，摒弃各种复杂繁琐的功能操作。卓越的用 户体验，让您使用起来方便明了。支持excel表格数据导入，让你摆脱逐条添加数据的辛苦。独创的模型字段关系定义，让你的模型关系更加丰富。遵循GPL开源协议，允许代码的开源/免费使用和引用/修改/衍生代码的开源/免费使用。', '2', 'page', '', '10', '', '1500022338', '1502846229', '', '1', '1');
INSERT INTO `uk_column` VALUES ('2', '2', '0,', '0', '2', 'WEB知识', 'web', 'WEB知识', 'web,html,javascript,php', 'WEB网站相关知识介绍', '0', 'list', 'content', '10', '', '1500022615', '1504403445', 'orders asc', '2', '1');
INSERT INTO `uk_column` VALUES ('3', '2', '0,2,', '0', '2', '前端知识', 'front', '前端', 'html,html5,javascript', '前端知识相关介绍', '0', 'list', 'content', '10', '', '1500022756', '1504403445', 'orders asc', '1', '1');
INSERT INTO `uk_column` VALUES ('4', '2', '0,2,', '0', '2', 'PHP介绍', 'php', 'php基础介绍', 'php,php7', '最新的php7对php语言发展起到很大促进作用', '0', 'list', 'content', '10', '', '1500022797', '1504403445', 'orders asc', '2', '1');
INSERT INTO `uk_column` VALUES ('5', '2', '0,2,3,', '0', '2', '前端框架', 'frontframe', '前端框架', 'bootstarp,妹子,vue', '前端框架常用框架介绍', '0', 'list', 'content', '10', '', '1500022899', '1504403445', 'orders asc', '2', '1');
INSERT INTO `uk_column` VALUES ('6', '2', '0,2,3,', '0', '2', '前端基础', 'frontbase', '前端基础', 'html,html5,javascript', '前端基础相关介绍', '0', 'list', 'content', '10', '', '1500079094', '1504403445', 'orders asc', '1', '1');
INSERT INTO `uk_column` VALUES ('7', '2', '0,2,3,5,', '0', '2', '主流框架', 'mainframe', '主流前端', 'bootstarp,Semantic', '主流前端框架介绍', '0', 'list', 'content', '10', '', '1500079377', '1504403445', 'orders asc', '1', '1');
INSERT INTO `uk_column` VALUES ('8', '2', '0,2,3,5,', '0', '2', '最新框架', 'newframe', '最新前端框架', 'React,vue.js', '最新前端框架展望', '0', 'list', 'content', '10', '', '1500079447', '1504403445', 'orders asc', '2', '1');
INSERT INTO `uk_column` VALUES ('9', '2', '0,2,', '0', '2', 'UKcms常见问题', 'ukcms', 'ukcms解答', 'ukcms,yxcms', '针对ukcms用户的疑惑提供常见解答', '0', 'list', 'content', '10', '', '1500080492', '1504403445', 'orders asc', '3', '1');
INSERT INTO `uk_column` VALUES ('10', '2', '0,', '0', '1', '传奇影星', 'legend', '传奇影星介绍', '欧美,影星', '以影星为例演示图集', '0', 'photolist', 'photocontent', '10', '', '1500080820', '1504403445', 'orders asc', '3', '1');
INSERT INTO `uk_column` VALUES ('11', '2', '0,', '0', '3', '产品演示', 'product', '汽车产品演示', '汽车,电动车,跑车', '以汽车为例演示产品', '0', 'productlist', 'productcontent', '10', 'year,color', '1500081209', '1504403445', 'orders asc', '4', '1');
INSERT INTO `uk_column` VALUES ('12', '2', '0,', '0', '5', '留言本', 'guestbook', '留言本', '留言本', '通过留言本演示模型在允许投稿下自定义字段的运用', '0', 'guestbook', 'content', '10', '', '1500960951', '1504403445', 'orders asc', '5', '1');

-- ----------------------------
-- Table structure for uk_comment
-- ----------------------------
DROP TABLE IF EXISTS `uk_comment`;
CREATE TABLE `uk_comment` (
  `id` int(11) unsigned AUTO_INCREMENT COMMENT '文档id',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `orders` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned DEFAULT '0' COMMENT '状态',
  `did` int(10) unsigned COMMENT '内容id',
  `commenter` varchar(128) DEFAULT '' COMMENT '留言者',
  `message` varchar(3000) DEFAULT '' COMMENT '留言内容',
  `mid` int(10) unsigned DEFAULT '0' COMMENT '模型ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='内容评论模型表';

-- ----------------------------
-- Records of uk_comment
-- ----------------------------

-- ----------------------------
-- Table structure for uk_config
-- ----------------------------
DROP TABLE IF EXISTS `uk_config`;
CREATE TABLE `uk_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `group` varchar(32) NOT NULL DEFAULT '' COMMENT '配置分组',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '类型',
  `value` varchar(10000) NOT NULL DEFAULT '' COMMENT '配置值',
  `options` varchar(10000) NOT NULL DEFAULT '' COMMENT '配置项',
  `remark` varchar(256) NOT NULL DEFAULT '' COMMENT '配置提示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(4) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='配置表';

-- ----------------------------
-- Records of uk_config
-- ----------------------------
INSERT INTO `uk_config` VALUES ('1', 'config_group', '配置分组', 'system', 'array', 'base:基础\r\nsystem:系统\r\nupload:上传\r\ndevelop:开发\r\napi:第三方API', '', '', '1494408414', '1494408414', '1', '1');
INSERT INTO `uk_config` VALUES ('2', 'app_cache', '缓存模式', 'develop', 'switch', '0', '', '开启后提高系统并发能力，但是会占用更多的存储空间', '1494408414', '1494408414', '1', '1');
INSERT INTO `uk_config` VALUES ('3', 'upload_image_thumb', '缩略图默认尺寸', 'upload', 'text', '300,300', '', '如需生成 <code class=\"text-warning\">300x300</code> 的缩略图，则填写 <code class=\"text-warning\">300,300</code> ，请注意，逗号必须是英文逗号', '1494408414', '1494408414', '5', '1');
INSERT INTO `uk_config` VALUES ('4', 'upload_image_thumb_type', '缩略图默认裁剪类型', 'upload', 'select', '3', '1:等比例缩放\r\n2:缩放后填充\r\n3:居中裁剪\r\n4:左上角裁剪\r\n5:右下角裁剪\r\n6:固定尺寸缩放', '该项配置只有在启用生成缩略图时才生效', '1494408414', '1494408414', '6', '1');
INSERT INTO `uk_config` VALUES ('5', 'upload_thumb_water', '添加水印', 'upload', 'switch', '1', '', '', '1494408414', '1494408414', '7', '1');
INSERT INTO `uk_config` VALUES ('6', 'upload_thumb_water_pic', '水印图片', 'upload', 'image', '9', '', '只有开启水印功能才生效', '1494408414', '1494408414', '8', '1');
INSERT INTO `uk_config` VALUES ('7', 'upload_thumb_water_position', '水印位置', 'upload', 'select', '9', '1:左上角\n2:上居中\n3:右上角\n4:左居中\n5:居中\n6:右居中\n7:左下角\n8:下居中\n9:右下角', '', '1494408414', '1494408414', '9', '1');
INSERT INTO `uk_config` VALUES ('8', 'upload_thumb_water_alpha', '水印透明度', 'upload', 'text', '40', '', '请输入0~100之间的数字，数字越小，透明度越高', '1494408414', '1494408414', '10', '1');
INSERT INTO `uk_config` VALUES ('9', 'upload_file_size', '文件上传大小限制', 'upload', 'text', '0', '', '0为不限制大小，单位：kb', '1494408414', '1494408414', '1', '1');
INSERT INTO `uk_config` VALUES ('10', 'upload_file_ext', '允许上传的文件后缀', 'upload', 'tags', 'doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip,gz,bz2,7z,flv,mp4,mp3,swf', '', '多个后缀用逗号隔开，不填写则不限制类型', '1494408414', '1494408414', '2', '1');
INSERT INTO `uk_config` VALUES ('11', 'upload_image_size', '图片上传大小限制', 'upload', 'text', '0', '', '0为不限制大小，单位：kb', '1494408414', '1494408414', '3', '1');
INSERT INTO `uk_config` VALUES ('12', 'upload_image_ext', '允许上传的图片后缀', 'upload', 'tags', 'gif,jpg,jpeg,bmp,png', '', '多个后缀用逗号隔开，不填写则不限制类型', '1494408414', '1494408414', '4', '1');
INSERT INTO `uk_config` VALUES ('13', 'web_site_status', '站点开关', 'base', 'switch', '1', '', '站点关闭后前台将不能访问', '1494408414', '1494408414', '1', '1');
INSERT INTO `uk_config` VALUES ('14', 'web_site_title', '站点标题', 'base', 'text', 'UKcms网站管理系统', '', '', '1494408414', '1494408414', '2', '1');
INSERT INTO `uk_config` VALUES ('15', 'web_site_keywords', '站点关键词', 'base', 'text', '建站,网站定制,二次开发,微信接口开发,移动端功能定制,seo优化', '', '', '1494408414', '1494408414', '3', '1');
INSERT INTO `uk_config` VALUES ('16', 'web_site_description', '站点描述', 'base', 'textarea', 'UKcms是一款集PC端、移动端和微信为一体，同时结合了seo优化功能的网站管理系统', '', '', '1494408414', '1494408414', '4', '1');
INSERT INTO `uk_config` VALUES ('17', 'web_site_logo', '站点LOGO', 'base', 'image', '36', '', '', '1494408414', '1494408414', '5', '1');
INSERT INTO `uk_config` VALUES ('18', 'web_site_icp', '备案信息', 'base', 'text', '', '', '', '1494408414', '1494408414', '6', '1');
INSERT INTO `uk_config` VALUES ('19', 'web_site_statistics', '站点代码', 'base', 'textarea', '<script>\r\n(function(){\r\n    var bp = document.createElement(\'script\');\r\n    var curProtocol = window.location.protocol.split(\':\')[0];\r\n    if (curProtocol === \'https\') {\r\n        bp.src = \'https://zz.bdstatic.com/linksubmit/push.js\';        \r\n    }\r\n    else {\r\n        bp.src = \'http://push.zhanzhang.baidu.com/push.js\';\r\n    }\r\n    var s = document.getElementsByTagName(\"script\")[0];\r\n    s.parentNode.insertBefore(bp, s);\r\n})();\r\n</script>', '', '', '1494408414', '1494408414', '7', '1');
INSERT INTO `uk_config` VALUES ('20', 'captcha_signin', '后台登录验证码', 'system', 'switch', '1', '', '', '1494408414', '1494408414', '3', '1');
INSERT INTO `uk_config` VALUES ('21', 'baidu_translate_id', '百度翻译AppID', 'api', 'text', '', '', '申请地址:<a target=\"blank\" href=\"http://api.fanyi.baidu.com/api/\">http://api.fanyi.baidu.com/api/</a>', '1503804700', '1503809732', '1', '1');
INSERT INTO `uk_config` VALUES ('22', 'baidu_translate_secret', '百度翻译App秘钥', 'api', 'text', '', '', '', '1503804790', '1503804790', '2', '1');
INSERT INTO `uk_config` VALUES ('23', 'captcha_signin_model', '模型投稿验证码', 'system', 'switch', '1', '', '', '1505003804', '1505003804', '4', '1');

-- ----------------------------
-- Table structure for uk_domain
-- ----------------------------
DROP TABLE IF EXISTS `uk_domain`;
CREATE TABLE `uk_domain` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '域名',
  `view_directory` varchar(64) NOT NULL DEFAULT '' COMMENT '模板目录',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='域名信息表';

-- ----------------------------
-- Records of uk_domain
-- ----------------------------

-- ----------------------------
-- Table structure for uk_field_type
-- ----------------------------
DROP TABLE IF EXISTS `uk_field_type`;
CREATE TABLE `uk_field_type` (
  `name` varchar(32) NOT NULL COMMENT '字段类型',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '中文类型名',
  `orders` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `default_define` varchar(128) NOT NULL DEFAULT '' COMMENT '默认定义',
  `ifoption` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要设置选项',
  `ifstring` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否自由字符',
  `vrule` varchar(256) NOT NULL DEFAULT '' COMMENT '验证规则',
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='字段类型表';

-- ----------------------------
-- Records of uk_field_type
-- ----------------------------
INSERT INTO `uk_field_type` VALUES ('text', '输入框', '1', 'varchar(128) DEFAULT \'\'', '0', '1', '');
INSERT INTO `uk_field_type` VALUES ('checkbox', '复选框', '2', 'varchar(32) DEFAULT \'\'', '1', '0', 'isChsAlphaNum');
INSERT INTO `uk_field_type` VALUES ('textarea', '多行文本', '3', 'varchar(3000) DEFAULT \'\'', '0', '1', '');
INSERT INTO `uk_field_type` VALUES ('radio', '单选按钮', '4', 'varchar(32) DEFAULT \'\'', '1', '0', 'isChsAlphaNum');
INSERT INTO `uk_field_type` VALUES ('switch', '开关', '5', 'tinyint(2) UNSIGNED DEFAULT \'0\'', '0', '0', 'isBool');
INSERT INTO `uk_field_type` VALUES ('array', '数组', '6', 'varchar(512) DEFAULT \'\'', '0', '0', '');
INSERT INTO `uk_field_type` VALUES ('select', '下拉框', '7', 'varchar(64) DEFAULT \'\'', '1', '0', 'isChsAlphaNum');
INSERT INTO `uk_field_type` VALUES ('image', '单张图', '8', 'int(5) UNSIGNED DEFAULT \'0\'', '0', '0', 'isNumber');
INSERT INTO `uk_field_type` VALUES ('tags', '标签', '10', 'varchar(256) DEFAULT \'\'', '0', '1', '');
INSERT INTO `uk_field_type` VALUES ('number', '数字', '11', 'int(10) UNSIGNED DEFAULT \'0\'', '0', '0', 'isNumber');
INSERT INTO `uk_field_type` VALUES ('datetime', '日期和时间', '12', 'int(11) UNSIGNED DEFAULT \'0\'', '0', '0', '');
INSERT INTO `uk_field_type` VALUES ('Ueditor', '百度编辑器', '13', 'text', '0', '1', '');
INSERT INTO `uk_field_type` VALUES ('images', '多张图', '9', 'varchar(256) DEFAULT \'\'', '0', '0', '');
INSERT INTO `uk_field_type` VALUES ('color', '颜色值', '16', 'varchar(7) DEFAULT \'\'', '0', '0', '');
INSERT INTO `uk_field_type` VALUES ('files', '多文件', '15', 'varchar(256) DEFAULT \'\'', '0', '0', '');
INSERT INTO `uk_field_type` VALUES ('summernote', '简洁编辑器', '14', 'text', '0', '1', '');

-- ----------------------------
-- Table structure for uk_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `uk_guestbook`;
CREATE TABLE `uk_guestbook` (
  `id` int(11) unsigned AUTO_INCREMENT COMMENT '文档id',
  `cname` varchar(64) DEFAULT '' COMMENT '栏目标识',
  `ifextend` tinyint(2) DEFAULT '0' COMMENT '是否栏目拓展',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `places` varchar(64) DEFAULT '' COMMENT '推荐位',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `orders` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned DEFAULT '0' COMMENT '状态',
  `hits` int(11) DEFAULT '0' COMMENT '点击量',
  `name` varchar(128) DEFAULT '' COMMENT '姓名',
  `telephone` varchar(11) DEFAULT '' COMMENT '手机号码',
  `sex` varchar(32) DEFAULT '' COMMENT '性别',
  `content` varchar(3000) DEFAULT '' COMMENT '留言内容',
  `first` tinyint(2) unsigned DEFAULT '0' COMMENT '是否第一次使用',
  `impression` varchar(32) DEFAULT '' COMMENT '使用印象',
  `evaluate` varchar(64) DEFAULT '' COMMENT '评价',
  `usetime` int(11) unsigned DEFAULT '0' COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='留言本模型表';

-- ----------------------------
-- Records of uk_guestbook
-- ----------------------------

-- ----------------------------
-- Table structure for uk_hook
-- ----------------------------
DROP TABLE IF EXISTS `uk_hook`;
CREATE TABLE `uk_hook` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL COMMENT '钩子名称',
  `title` varchar(255) NOT NULL COMMENT '钩子中文说明',
  `ifsystem` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统钩子',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `orders` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='钩子表 ';

-- ----------------------------
-- Records of uk_hook
-- ----------------------------
INSERT INTO `uk_hook` VALUES ('1', 'app_begin', '应用开始标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('2', 'module_init', '模块初始化标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('3', 'action_begin', '控制器开始标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('4', 'view_filter', '视图输出过滤标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('5', 'app_end', '应用结束标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('6', 'response_end', '输出结束标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('7', 'log_write', '日志write方法标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('8', 'log_write_done', '日志写入完成标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('9', 'response_send', '响应发送标签位', '1', '1509593470', '1509593470', '0', '1');
INSERT INTO `uk_hook` VALUES ('10', 'home_begin', '前台控制器初始化', '1', '1510015090', '1510015090', '0', '1');

-- ----------------------------
-- Table structure for uk_hook_behavior
-- ----------------------------
DROP TABLE IF EXISTS `uk_hook_behavior`;
CREATE TABLE `uk_hook_behavior` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `hook` varchar(80) NOT NULL COMMENT '钩子名称',
  `behavior` varchar(80) NOT NULL COMMENT '行为名称',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `orders` int(8) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钩子行为关系表';

-- ----------------------------
-- Records of uk_hook_behavior
-- ----------------------------

-- ----------------------------
-- Table structure for uk_link
-- ----------------------------
DROP TABLE IF EXISTS `uk_link`;
CREATE TABLE `uk_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(32) NOT NULL DEFAULT '' COMMENT '分组英文标识',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '链接名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接跳转地址',
  `picture` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `content` varchar(10000) NOT NULL DEFAULT '' COMMENT '描述',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '生效时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '失效时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='广告位表 ';

-- ----------------------------
-- Records of uk_link
-- ----------------------------
INSERT INTO `uk_link` VALUES ('1', 'indexbanner', 'UKcms简洁灵活强大', '', '34', '', '0', '0', '1500262080', '1500262080', '101');
INSERT INTO `uk_link` VALUES ('2', 'indexbanner', 'UKcms从零开始', '', '35', '', '0', '0', '1500444070', '1500444560', '100');

-- ----------------------------
-- Table structure for uk_link_group
-- ----------------------------
DROP TABLE IF EXISTS `uk_link_group`;
CREATE TABLE `uk_link_group` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '分组英文标识',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '分组名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='广告位分组表 ';

-- ----------------------------
-- Records of uk_link_group
-- ----------------------------
INSERT INTO `uk_link_group` VALUES ('1', 'indexbanner', '首页banner', '1500262041', '1500262041');

-- ----------------------------
-- Table structure for uk_model
-- ----------------------------
DROP TABLE IF EXISTS `uk_model`;
CREATE TABLE `uk_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '模型标题',
  `table` varchar(64) NOT NULL DEFAULT '' COMMENT '表名',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '模型类别：1-独立表，2-主附表',
  `purpose` varchar(32) NOT NULL DEFAULT '' COMMENT '模型用途',
  `orders` int(4) NOT NULL DEFAULT '100' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `ifsub` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许投稿',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='内容模型表';

-- ----------------------------
-- Records of uk_model
-- ----------------------------
INSERT INTO `uk_model` VALUES ('1', '图集', 'photo', '2', 'column', '100', '1500017712', '1500017712', '0', '1');
INSERT INTO `uk_model` VALUES ('2', '文章', 'article', '2', 'column', '100', '1500017779', '1500017779', '0', '1');
INSERT INTO `uk_model` VALUES ('3', '产品', 'product', '2', 'column', '100', '1500017841', '1500017841', '0', '1');
INSERT INTO `uk_model` VALUES ('4', '单页', 'page', '1', 'column', '100', '1500018204', '1500023940', '0', '1');
INSERT INTO `uk_model` VALUES ('5', '留言本', 'guestbook', '1', 'column', '100', '1500018312', '1500018312', '1', '1');
INSERT INTO `uk_model` VALUES ('6', '内容评论', 'comment', '1', 'independence', '100', '1501223252', '1501223252', '1', '1');

-- ----------------------------
-- Table structure for uk_model_field
-- ----------------------------
DROP TABLE IF EXISTS `uk_model_field`;
CREATE TABLE `uk_model_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段名称',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属文档模型id',
  `name` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '字段标题',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '字段类型',
  `define` varchar(128) NOT NULL DEFAULT '' COMMENT '字段定义',
  `value` varchar(10000) NOT NULL DEFAULT '' COMMENT '默认值',
  `options` varchar(10000) NOT NULL DEFAULT '' COMMENT '额外选项',
  `jsonrule` varchar(1000) NOT NULL DEFAULT '' COMMENT '关联规则',
  `remark` varchar(256) NOT NULL DEFAULT '' COMMENT '提示说明',
  `ifmain` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否主表字段',
  `ifeditable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可以编辑',
  `ifsearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支持搜索',
  `ifrequire` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `iffixed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否固定不可修改',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='模型字段表';

-- ----------------------------
-- Records of uk_model_field
-- ----------------------------
INSERT INTO `uk_model_field` VALUES ('1', '1', 'id', '文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '1', '1500017712', '1500017712', '100', '1');
INSERT INTO `uk_model_field` VALUES ('2', '1', 'cname', '栏目标识', 'text', 'varchar(64)', '0', '', '', '', '1', '0', '0', '1', '1', '1500017712', '1500017712', '100', '1');
INSERT INTO `uk_model_field` VALUES ('3', '1', 'uid', '用户id', 'number', 'int(10) UNSIGNED', '1', '', '', '', '1', '0', '0', '0', '1', '1500017712', '1500017712', '100', '1');
INSERT INTO `uk_model_field` VALUES ('4', '1', 'places', '推荐位', 'checkbox', 'varchar(64)', '', '', '', '', '1', '0', '0', '0', '1', '1500017712', '1500017712', '100', '1');
INSERT INTO `uk_model_field` VALUES ('5', '1', 'title', '标题', 'text', 'varchar(256)', '', '', '', '', '1', '1', '1', '1', '0', '1500017712', '1500017712', '101', '1');
INSERT INTO `uk_model_field` VALUES ('6', '1', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500017712', '1500017712', '200', '1');
INSERT INTO `uk_model_field` VALUES ('7', '1', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '0', '0', '0', '1', '1500017712', '1500017712', '200', '1');
INSERT INTO `uk_model_field` VALUES ('8', '1', 'orders', '排序', 'number', 'int(10) UNSIGNED', '100', '', '', '', '1', '1', '0', '0', '1', '1500017712', '1500017712', '200', '1');
INSERT INTO `uk_model_field` VALUES ('9', '1', 'status', '状态', 'radio', 'tinyint(2)', '1', '0:禁用\r\n1:启用', '', '', '1', '1', '0', '0', '1', '1500017712', '1500017712', '200', '1');
INSERT INTO `uk_model_field` VALUES ('10', '1', 'hits', '点击量', 'number', 'int(10) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500017712', '1500017712', '200', '1');
INSERT INTO `uk_model_field` VALUES ('11', '1', 'did', '附表文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '0', '0', '0', '0', '1', '1500017712', '1500017712', '100', '1');
INSERT INTO `uk_model_field` VALUES ('12', '2', 'id', '文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '1', '1500017779', '1500017779', '100', '1');
INSERT INTO `uk_model_field` VALUES ('13', '2', 'cname', '栏目标识', 'text', 'varchar(64)', '0', '', '', '', '1', '0', '0', '1', '1', '1500017779', '1500017779', '100', '1');
INSERT INTO `uk_model_field` VALUES ('14', '2', 'uid', '用户id', 'number', 'int(10) UNSIGNED', '1', '', '', '', '1', '0', '0', '0', '1', '1500017779', '1500017779', '100', '1');
INSERT INTO `uk_model_field` VALUES ('15', '2', 'places', '推荐位', 'checkbox', 'varchar(64)', '', '', '', '', '1', '0', '0', '0', '1', '1500017779', '1500017779', '100', '1');
INSERT INTO `uk_model_field` VALUES ('16', '2', 'title', '标题', 'text', 'varchar(256)', '', '', '', '', '1', '1', '1', '1', '0', '1500017779', '1500017779', '100', '1');
INSERT INTO `uk_model_field` VALUES ('17', '2', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500017779', '1500017779', '200', '1');
INSERT INTO `uk_model_field` VALUES ('18', '2', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '0', '0', '0', '1', '1500017779', '1500017779', '200', '1');
INSERT INTO `uk_model_field` VALUES ('19', '2', 'orders', '排序', 'number', 'int(10) UNSIGNED', '100', '', '', '', '1', '1', '0', '0', '1', '1500017779', '1500017779', '200', '1');
INSERT INTO `uk_model_field` VALUES ('20', '2', 'status', '状态', 'radio', 'tinyint(2)', '1', '0:禁用\r\n1:启用', '', '', '1', '1', '0', '0', '1', '1500017779', '1500017779', '200', '1');
INSERT INTO `uk_model_field` VALUES ('21', '2', 'hits', '点击量', 'number', 'int(10) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500017779', '1500017779', '200', '1');
INSERT INTO `uk_model_field` VALUES ('22', '2', 'did', '附表文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '0', '0', '0', '0', '1', '1500017779', '1500017779', '100', '1');
INSERT INTO `uk_model_field` VALUES ('23', '3', 'id', '文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '1', '1500017841', '1500017841', '100', '1');
INSERT INTO `uk_model_field` VALUES ('24', '3', 'cname', '栏目标识', 'text', 'varchar(64)', '0', '', '', '', '1', '0', '0', '1', '1', '1500017841', '1500017841', '100', '1');
INSERT INTO `uk_model_field` VALUES ('25', '3', 'uid', '用户id', 'number', 'int(10) UNSIGNED', '1', '', '', '', '1', '0', '0', '0', '1', '1500017841', '1500017841', '100', '1');
INSERT INTO `uk_model_field` VALUES ('26', '3', 'places', '推荐位', 'checkbox', 'varchar(64)', '', '', '', '', '1', '0', '0', '0', '1', '1500017841', '1500017841', '100', '1');
INSERT INTO `uk_model_field` VALUES ('27', '3', 'title', '标题', 'text', 'varchar(256)', '', '', '', '', '1', '1', '1', '1', '0', '1500017841', '1500017841', '101', '1');
INSERT INTO `uk_model_field` VALUES ('28', '3', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500017841', '1500017841', '200', '1');
INSERT INTO `uk_model_field` VALUES ('29', '3', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '0', '0', '0', '1', '1500017841', '1500017841', '200', '1');
INSERT INTO `uk_model_field` VALUES ('30', '3', 'orders', '排序', 'number', 'int(10) UNSIGNED', '100', '', '', '', '1', '1', '0', '0', '1', '1500017841', '1500017841', '200', '1');
INSERT INTO `uk_model_field` VALUES ('31', '3', 'status', '状态', 'radio', 'tinyint(2)', '1', '0:禁用\r\n1:启用', '', '', '1', '1', '0', '0', '1', '1500017841', '1500017841', '200', '1');
INSERT INTO `uk_model_field` VALUES ('32', '3', 'hits', '点击量', 'number', 'int(10) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500017841', '1500017841', '200', '1');
INSERT INTO `uk_model_field` VALUES ('33', '3', 'did', '附表文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '0', '0', '0', '0', '1', '1500017841', '1500017841', '100', '1');
INSERT INTO `uk_model_field` VALUES ('34', '4', 'id', '文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '1', '1500018204', '1500018204', '100', '1');
INSERT INTO `uk_model_field` VALUES ('35', '4', 'cname', '栏目标识', 'text', 'varchar(64)', '0', '', '', '', '1', '0', '0', '1', '1', '1500018204', '1500018204', '100', '1');
INSERT INTO `uk_model_field` VALUES ('36', '4', 'uid', '用户id', 'number', 'int(10) UNSIGNED', '1', '', '', '', '1', '0', '0', '0', '1', '1500018204', '1500018204', '100', '1');
INSERT INTO `uk_model_field` VALUES ('37', '4', 'places', '推荐位', 'checkbox', 'varchar(64)', '', '', '', '', '1', '0', '0', '0', '1', '1500018204', '1500018204', '100', '1');
INSERT INTO `uk_model_field` VALUES ('39', '4', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500018204', '1500018204', '200', '1');
INSERT INTO `uk_model_field` VALUES ('40', '4', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '0', '0', '0', '1', '1500018204', '1500018204', '200', '1');
INSERT INTO `uk_model_field` VALUES ('41', '4', 'orders', '排序', 'number', 'int(10) UNSIGNED', '100', '', '', '', '1', '1', '0', '0', '1', '1500018204', '1500018204', '200', '1');
INSERT INTO `uk_model_field` VALUES ('42', '4', 'status', '状态', 'radio', 'tinyint(2)', '1', '0:禁用\r\n1:启用', '', '', '1', '1', '0', '0', '1', '1500018204', '1500018204', '200', '1');
INSERT INTO `uk_model_field` VALUES ('43', '4', 'hits', '点击量', 'number', 'int(10) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500018204', '1500018204', '200', '1');
INSERT INTO `uk_model_field` VALUES ('44', '5', 'id', '文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '1', '1500018311', '1500018311', '100', '1');
INSERT INTO `uk_model_field` VALUES ('45', '5', 'uid', '用户id', 'number', 'int(10) UNSIGNED', '1', '', '', '', '1', '0', '0', '0', '1', '1500018311', '1500018311', '100', '1');
INSERT INTO `uk_model_field` VALUES ('46', '5', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1500018311', '1510821338', '200', '1');
INSERT INTO `uk_model_field` VALUES ('47', '5', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '0', '0', '0', '1', '1500018311', '1500018311', '200', '1');
INSERT INTO `uk_model_field` VALUES ('48', '5', 'orders', '排序', 'number', 'int(10) UNSIGNED', '100', '', '', '', '1', '1', '0', '0', '1', '1500018311', '1500018311', '200', '1');
INSERT INTO `uk_model_field` VALUES ('49', '5', 'status', '状态', 'radio', 'tinyint(2)', '1', '0:禁用\r\n1:启用', '', '', '1', '1', '0', '0', '1', '1500018311', '1500018311', '200', '1');
INSERT INTO `uk_model_field` VALUES ('50', '1', 'cover', '封面图', 'image', 'int(5) UNSIGNED', '', '', '', '', '1', '1', '0', '1', '0', '1500018462', '1501055972', '102', '1');
INSERT INTO `uk_model_field` VALUES ('51', '1', 'pictures', '图片集', 'images', 'varchar(128)', '', '', '{\"thumb\":{\"ifon\":\"1\",\"size\":\"300,300\",\"type\":\"3\"}}', '', '0', '1', '0', '1', '0', '1500018860', '1512005672', '103', '1');
INSERT INTO `uk_model_field` VALUES ('52', '1', 'description', 'SEO摘要', 'textarea', 'varchar(3000) DEFAULT \'\'', '', '', '', '', '1', '1', '1', '0', '0', '1500019150', '1513401446', '106', '1');
INSERT INTO `uk_model_field` VALUES ('53', '1', 'content', '图集介绍', 'Ueditor', 'text', '', '', '', '', '0', '1', '0', '0', '0', '1500019175', '1500024292', '104', '1');
INSERT INTO `uk_model_field` VALUES ('54', '2', 'source', '文章来源', 'text', 'varchar(128)', '原创', '', '', '', '1', '1', '0', '0', '0', '1500019359', '1500024357', '102', '1');
INSERT INTO `uk_model_field` VALUES ('55', '2', 'content', '文章内容', 'Ueditor', 'text', '', '', '', '', '0', '1', '0', '1', '0', '1500019439', '1501056003', '104', '1');
INSERT INTO `uk_model_field` VALUES ('56', '3', 'price', '价格', 'text', 'varchar(128)', '', '', '', '', '1', '1', '0', '1', '0', '1500019559', '1501056017', '106', '1');
INSERT INTO `uk_model_field` VALUES ('57', '4', 'content', '内容', 'Ueditor', 'text', '', '', '', '', '1', '1', '0', '1', '0', '1500019647', '1501056104', '101', '1');
INSERT INTO `uk_model_field` VALUES ('58', '3', 'model', '型号', 'text', 'varchar(128)', '', '', '', '', '0', '1', '0', '0', '0', '1500020181', '1500511013', '107', '1');
INSERT INTO `uk_model_field` VALUES ('59', '3', 'brand', '品牌', 'text', 'varchar(128)', '', '', '', '', '0', '1', '0', '0', '0', '1500020234', '1500510946', '108', '1');
INSERT INTO `uk_model_field` VALUES ('60', '3', 'content', '详细介绍', 'Ueditor', 'text', '', '', '', '', '0', '1', '0', '0', '0', '1500020579', '1500025262', '109', '1');
INSERT INTO `uk_model_field` VALUES ('61', '5', 'name', '姓名', 'text', 'varchar(128)', '', '', '', '', '1', '1', '0', '0', '0', '1500020703', '1501032477', '103', '1');
INSERT INTO `uk_model_field` VALUES ('62', '5', 'telephone', '手机号码', 'text', 'varchar(11)', '', '', '', '', '1', '1', '0', '0', '0', '1500020744', '1505609089', '104', '1');
INSERT INTO `uk_model_field` VALUES ('63', '5', 'sex', '性别', 'radio', 'varchar(32)', '', '1:男\r\n2:女', '', '', '1', '1', '0', '0', '0', '1500020807', '1501032699', '105', '1');
INSERT INTO `uk_model_field` VALUES ('64', '5', 'content', '留言内容', 'textarea', 'varchar(3000) DEFAULT \'\'', '', '', '', '', '1', '1', '1', '1', '0', '1500020860', '1513401677', '102', '1');
INSERT INTO `uk_model_field` VALUES ('96', '2', 'keywords', 'SEO关键词', 'tags', 'varchar(256)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '', '1', '1', '0', '0', '0', '1502092804', '1502428928', '105', '1');
INSERT INTO `uk_model_field` VALUES ('66', '2', 'description', 'SEO摘要', 'textarea', 'varchar(3000) DEFAULT \'\'', '', '', '', '', '1', '1', '1', '0', '0', '1500023214', '1513401473', '106', '1');
INSERT INTO `uk_model_field` VALUES ('95', '1', 'keywords', 'SEO关键词', 'tags', 'varchar(256)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '', '1', '1', '0', '0', '0', '1502092625', '1502092714', '105', '1');
INSERT INTO `uk_model_field` VALUES ('97', '3', 'keywords', 'SEO关键词', 'tags', 'varchar(256)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '', '1', '1', '0', '0', '0', '1502092922', '1502092940', '110', '1');
INSERT INTO `uk_model_field` VALUES ('69', '3', 'description', 'SEO摘要', 'textarea', 'varchar(3000) DEFAULT \'\'', '', '', '', '', '1', '1', '1', '0', '0', '1500023535', '1513401494', '111', '1');
INSERT INTO `uk_model_field` VALUES ('70', '2', 'cover', '封面图', 'image', 'int(5) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '0', '1500023619', '1500024391', '103', '1');
INSERT INTO `uk_model_field` VALUES ('71', '3', 'cover', '封面图', 'image', 'int(5) UNSIGNED', '', '', '', '', '1', '1', '0', '1', '0', '1500023836', '1501056037', '104', '1');
INSERT INTO `uk_model_field` VALUES ('72', '3', 'pictures', '产品图集', 'images', 'varchar(128)', '', '', '{\"thumb\":{\"ifon\":\"1\",\"size\":\"300,200\",\"type\":\"1\"}}', '', '0', '1', '0', '1', '0', '1500023897', '1512005814', '105', '1');
INSERT INTO `uk_model_field` VALUES ('76', '5', 'cname', '栏目标识', 'text', 'varchar(64)', '', '', '', '', '1', '0', '0', '1', '1', '1500020860', '1500020860', '100', '1');
INSERT INTO `uk_model_field` VALUES ('78', '5', 'places', '推荐位', 'checkbox', 'varchar(64)', '', '', '', '', '1', '0', '0', '0', '1', '1500020860', '1500020860', '100', '1');
INSERT INTO `uk_model_field` VALUES ('79', '5', 'title', '标题', 'text', 'varchar(256)', '', '', '', '', '1', '1', '1', '1', '0', '1500020860', '1500020860', '101', '1');
INSERT INTO `uk_model_field` VALUES ('80', '5', 'hits', '点击量', 'number', 'int(10) UNSIGNED', '', '', '', '', '1', '0', '0', '0', '1', '1500020860', '1510821331', '200', '1');
INSERT INTO `uk_model_field` VALUES ('81', '5', 'first', '是否第一次使用', 'switch', 'tinyint(2) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '0', '1501032638', '1501032650', '106', '1');
INSERT INTO `uk_model_field` VALUES ('82', '5', 'impression', '使用印象', 'checkbox', 'varchar(32)', '', '1:好看\r\n2:简洁\r\n3:灵活\r\n4:强大', '', '', '1', '1', '0', '0', '0', '1501033547', '1501033649', '107', '1');
INSERT INTO `uk_model_field` VALUES ('83', '5', 'evaluate', '评价', 'select', 'varchar(64)', '', '1:很一般\r\n2:凑合能用吧\r\n3:挺好很喜欢', '', '', '1', '1', '0', '0', '0', '1501033871', '1501034144', '108', '1');
INSERT INTO `uk_model_field` VALUES ('84', '5', 'usetime', '使用时间', 'datetime', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '0', '1501034126', '1501034152', '109', '1');
INSERT INTO `uk_model_field` VALUES ('85', '6', 'id', '文档id', 'hidden', 'int(11) UNSIGNED', '', '', '', '', '1', '1', '0', '0', '1', '1501223252', '1501223252', '100', '1');
INSERT INTO `uk_model_field` VALUES ('86', '6', 'uid', '用户id', 'number', 'int(10) UNSIGNED', '1', '', '', '', '1', '0', '0', '0', '1', '1501223252', '1501223252', '100', '1');
INSERT INTO `uk_model_field` VALUES ('87', '6', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '1', '0', '0', '1', '1501223252', '1501223252', '200', '1');
INSERT INTO `uk_model_field` VALUES ('88', '6', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED', '0', '', '', '', '1', '0', '0', '0', '1', '1501223252', '1501223252', '200', '1');
INSERT INTO `uk_model_field` VALUES ('89', '6', 'orders', '排序', 'number', 'int(10) UNSIGNED', '100', '', '', '', '1', '1', '0', '0', '1', '1501223252', '1501223252', '200', '1');
INSERT INTO `uk_model_field` VALUES ('90', '6', 'status', '状态', 'radio', 'tinyint(2)', '1', '0:禁用\r\n1:启用', '', '', '1', '1', '0', '0', '1', '1501223252', '1501223252', '200', '1');
INSERT INTO `uk_model_field` VALUES ('91', '6', 'did', '内容id', 'number', 'int(10) UNSIGNED', '', '', '', '', '1', '1', '0', '1', '0', '1501223339', '1501403079', '102', '1');
INSERT INTO `uk_model_field` VALUES ('92', '6', 'commenter', '留言者', 'text', 'varchar(128)', '', '', '', '', '1', '1', '0', '1', '0', '1501223463', '1501403027', '101', '1');
INSERT INTO `uk_model_field` VALUES ('93', '6', 'message', '留言内容', 'textarea', 'varchar(3000) DEFAULT \'\'', '', '', '', '', '1', '1', '1', '1', '0', '1501224724', '1513401527', '100', '1');
INSERT INTO `uk_model_field` VALUES ('94', '6', 'mid', '模型ID', 'number', 'int(10) UNSIGNED', '', '', '', '', '1', '1', '0', '1', '0', '1501231006', '1501403087', '103', '1');
INSERT INTO `uk_model_field` VALUES ('98', '3', 'year', '年份', 'radio', 'varchar(32)', '', '1:2014\r\n2:2015\r\n3:2016\r\n4:2017', '', '', '1', '1', '0', '0', '0', '1504142118', '1504142118', '102', '1');
INSERT INTO `uk_model_field` VALUES ('99', '3', 'color', '颜色', 'checkbox', 'varchar(32)', '', '1:黑色\r\n2:白色\r\n3:银色', '', '', '1', '1', '0', '0', '0', '1504142199', '1504142199', '103', '1');
INSERT INTO `uk_model_field` VALUES ('100', '1', 'ifextend', '是否栏目拓展', 'number', 'tinyint(2)', '0', '', '', '', '1', '0', '0', '0', '1', '1509439230', '1511169522', '100', '1');
INSERT INTO `uk_model_field` VALUES ('101', '2', 'ifextend', '是否栏目拓展', 'number', 'tinyint(2)', '0', '', '', '', '1', '0', '0', '0', '1', '1509439230', '1511169707', '100', '1');
INSERT INTO `uk_model_field` VALUES ('102', '3', 'ifextend', '是否栏目拓展', 'number', 'tinyint(2)', '0', '', '', '', '1', '0', '0', '0', '1', '1509439230', '1511169713', '100', '1');
INSERT INTO `uk_model_field` VALUES ('103', '4', 'ifextend', '是否栏目拓展', 'number', 'tinyint(2)', '0', '', '', '', '1', '0', '0', '0', '1', '1509439230', '1511169717', '100', '1');
INSERT INTO `uk_model_field` VALUES ('104', '5', 'ifextend', '是否栏目拓展', 'number', 'tinyint(2)', '0', '', '', '', '1', '0', '0', '0', '1', '1509439230', '1511169731', '100', '1');
INSERT INTO `uk_model_field` VALUES ('105', '2', 'color', '标题颜色', 'color', 'varchar(7) DEFAULT \'\'', '', '', '', '', '1', '1', '0', '0', '0', '1521436172', '1521436172', '101', '1');
-- ----------------------------
-- Table structure for uk_page
-- ----------------------------
DROP TABLE IF EXISTS `uk_page`;
CREATE TABLE `uk_page` (
  `id` int(11) unsigned AUTO_INCREMENT COMMENT '文档id',
  `cname` varchar(64) DEFAULT '' COMMENT '栏目标识',
  `ifextend` tinyint(2) DEFAULT '0' COMMENT '是否栏目拓展',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `places` varchar(64) DEFAULT '' COMMENT '推荐位',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `orders` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned DEFAULT '0' COMMENT '状态',
  `hits` int(11) unsigned DEFAULT '0' COMMENT '点击量',
  `content` text COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='单页介绍模型表';

-- ----------------------------
-- Records of uk_page
-- ----------------------------
INSERT INTO `uk_page` VALUES ('1', 'aboutus', '1', '1', '', '1500022338', '1502846229', '100', '1', '0', '<p>UKcms是一款基于PHP7和mysql技术，简洁、灵活、强大的网站内容管理系统。底层使用面向对象的轻量级PHP开发框架，采用惰性加载，及路由、配置和自动加载的缓存机\r\n\r\n制，确保系统高效运行，可使网站数据达到百万级负载。灵活强大的标签库可任意拓展，让您随心所欲。拥有建站各种实用功能，摒弃各种复杂繁琐的功能操作。卓越的用\r\n\r\n户体验，让您使用起来方便明了。支持excel表格数据导入，让你摆脱逐条添加数据的辛苦。独创的模型字段关系定义，让你的模型关系更加丰富。遵循GPL开源协议，允许代码的开源/免费使用和引用/修改/衍生代码的开源/免费使用。</p>');

-- ----------------------------
-- Table structure for uk_photo
-- ----------------------------
DROP TABLE IF EXISTS `uk_photo`;
CREATE TABLE `uk_photo` (
  `id` int(11) unsigned AUTO_INCREMENT COMMENT '文档id',
  `cname` varchar(64) DEFAULT '' COMMENT '栏目标识',
  `ifextend` tinyint(2) DEFAULT '0' COMMENT '是否栏目拓展',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `places` varchar(64) DEFAULT '' COMMENT '推荐位',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `orders` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned DEFAULT '0' COMMENT '状态',
  `hits` int(11) unsigned DEFAULT '0' COMMENT '点击量',
  `cover` int(5) unsigned DEFAULT '0' COMMENT '封面图',
  `description` varchar(3000) DEFAULT '' COMMENT 'SEO摘要',
  `keywords` varchar(256) DEFAULT '' COMMENT 'SEO关键词',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='图集模型表';

-- ----------------------------
-- Records of uk_photo
-- ----------------------------
INSERT INTO `uk_photo` VALUES ('1', 'legend', '0', '1', ',1,', '斯嘉丽·约翰逊', '1500182633', '1502094627', '100', '1', '0', '13', '1984年11月22日', '性感女神,钢铁侠,复仇者联盟');
INSERT INTO `uk_photo` VALUES ('2', 'legend', '0', '1', ',', '艾玛·沃特森', '1500186436', '1502094570', '100', '1', '0', '17', '1990年4月15日', '哈利波特,美女与野兽');
INSERT INTO `uk_photo` VALUES ('3', 'legend', '0', '1', ',1,', '盖尔·加朵', '1500188356', '1502094507', '100', '1', '0', '21', '1985年04月30日', '以色列,神奇女侠,Wonder Woman');
INSERT INTO `uk_photo` VALUES ('4', 'legend', '0', '1', ',', '基努·里维斯', '1500189957', '1502094471', '100', '1', '0', '23', '1964年9月2日', '狼孩,生死时速,黑客帝国');
INSERT INTO `uk_photo` VALUES ('5', 'legend', '0', '1', ',', '休·杰克曼', '1500190483', '1502094388', '100', '1', '0', '28', '1968年10月12日', 'X战警,致命魔术,金刚狼');
INSERT INTO `uk_photo` VALUES ('6', 'legend', '0', '1', ',', '小罗伯特·唐尼', '1500191554', '1502094345', '100', '1', '0', '33', '1965年4月4日', '动作明星,钢铁侠,怪医杜立德');

-- ----------------------------
-- Table structure for uk_photo_data
-- ----------------------------
DROP TABLE IF EXISTS `uk_photo_data`;
CREATE TABLE `uk_photo_data` (
  `did` int(11) unsigned DEFAULT '0' COMMENT '文档id',
  `pictures` varchar(128) COMMENT '图片集',
  `content` text COMMENT '图集介绍',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='图集模型扩展表';

-- ----------------------------
-- Records of uk_photo_data
-- ----------------------------
INSERT INTO `uk_photo_data` VALUES ('1', '10,11,12,13', '<p>1994年，斯嘉丽出演了她第一部影片《北》。她在片中扮演了一个小角色，随后又演了两部名气不大的影片《合适的理由》和《如果露西倒掉》。</p><p>1996年，斯嘉丽在影片《曼尼和罗》中扮演了一个失去双亲的小女孩，获得了当年的“独立精神奖”最佳女演员提名。1997年，不满13岁的约翰逊在《曼妮姐妹》（Manny &amp; Lo）中扮演了一个失去双亲的小女孩，她的表演大获好评，并获得美国独立精神奖最佳女主角提名。1998年，她得到大导演罗伯特-雷德福的青睐，请她在其新片《马语者》中扮演女主角克丽思汀-司科特-托马斯的女儿——一个失去一条腿的女孩。尽管这部影片毁誉参半，斯嘉丽的表演却受到广泛的好评，获得“票房炸弹奖”最佳女配角提名和多项“希望之星”头衔。</p><p>2000年，斯嘉丽接连出演了《幽灵世界》、《美国狂想曲》等影片，大多是独立制片的作品。</p><p>2001年被柯恩兄弟选中出演《缺席的人》的“琴声少女”贝蒂 。</p><p>2002年，她出演了一部科幻恐怖片《八脚怪》。年，她主演了女导演索非亚·科波拉的影片《迷失东京》，并获得广泛好评。2004年，她又主演了《戴珍珠耳环的女孩》 。这两部影片让她同时入围金球奖的剧情片和喜剧片最佳女主角。</p><p>2007年，斯嘉丽主演的《保姆日记》上映。在影片中，斯嘉丽饰演一个在纽约念儿童教育的大学生，为曼哈顿一家有钱人家当4岁男孩保姆的故事。</p><p>2008年，斯嘉丽发表了向摇滚大师汤姆·威兹致敬的首张专辑《Anywhere I Lay My Head》 。同年她还出演了影片《闪灵侠》。8月4日，斯嘉丽出席了《午夜巴塞罗那》举行的洛杉矶首映礼。影片由伍迪·艾伦执导，斯嘉丽·约翰逊、哈维尔·巴登和佩内洛普·克鲁兹主演 。</p><p>2009年2月，斯嘉丽出席了爱情文艺片《其实你不懂他的心》在洛杉矶举行的首映式 &nbsp;。斯嘉丽还与列维·施瑞博尔合作主演了阿瑟·米勒的剧作《桥上一瞥》。凭借剧作中的演绎，斯嘉丽获得了第64届托尼奖戏剧类最佳女配角奖 &nbsp;。9月，她与另类摇滚歌手皮特·约恩合作，共同录制了一张名为《Break Up》的专辑。</p><p>2010年，斯嘉丽出演的《钢铁侠2》上映，影片在北美票房突破3亿美元 &nbsp;。</p><p>2011年，斯嘉丽出演了《复仇者联盟》，并因此与其他主演一同获得了第22届MTV电影奖最佳打斗 。</p><p>2012年，她出演了《希区柯克》[17] &nbsp;。1月25日，互联网电影数据库Pro的十周年纪念日之际，公布了在过去十年里IMDB搜索排名前十的演员，斯嘉丽排行第七 。2月4日，她凭借在《我家买了动物园》中的表演而被授予金摄影机奖“最佳国际女演员”奖。4月，她出席了《复仇者联盟》在好莱坞El Capitan剧院举行的首映礼。5月，斯嘉丽留名好莱坞星光大道 。7月22日，11月，她登上《V》杂志封面。</p><p>2013年，斯嘉丽出演了《美国队长：冬日战士》 。6月，斯嘉丽登上法国版《名利场》杂志的7月刊将封面 。9月，斯嘉丽·约翰逊主演的《皮囊之下》在威尼斯举行了首映仪式 ，她主演的《情圣囧色夫》在多伦多电影节举行了首映礼 。10月，斯嘉丽登上Esquire十一月刊的封面，同时也获颁该杂志评选出的年度最性感女人头衔[26] &nbsp;。11月，斯嘉丽凭借《皮囊之下》获得第16届英国独立电影奖最佳女演员的提名，并凭借《她》获得罗马电影节的最佳女主角，成为罗马电影节上首位凭借声音获奖的最佳女主角 。12月18日，影片《她》在美国公映，斯嘉丽在片中用声音扮演了人工智能软件“莎曼珊”，与华金·菲尼克斯饰演的男主角演绎了一段“触不到的恋情” 。12月，斯嘉丽凭借在《她》中的演出获得了底特律影评人协会奖最佳女配角 。</p><p>2014年2月28日，第39届法国电影凯撒奖颁奖典礼在巴黎夏特莱剧场举行，斯嘉丽·约翰逊获颁法国电影凯撒奖荣誉奖[20] &nbsp;。在《美国队长2》于中国国内上映之际，斯嘉丽·约翰逊第一次来到了中国。3月24日，斯嘉丽与克里斯·埃文斯、塞缪尔·杰克逊一同出席了《美国队长2》在北京举行的见面会 。4月，斯嘉丽加盟改编自同名故事集的《森林王子》 。此外，她还为安迪·瑟金斯执导的《丛林之书：起源》配音 。7月25日，斯嘉丽·约翰逊担纲女主角的科幻动作影片《超体》在北美上映，以4402万美金的成绩问鼎该周票房冠军 ，获得4.6亿美元的全球票房，超越了安吉丽娜·朱莉保持多年的纪录。</p><p>2015年4月12日，第24届MTV电影奖在洛杉矶揭晓，斯嘉丽·约翰逊作为颁奖嘉宾亮相。5月1日，主演的电影《复仇者联盟2：奥创纪元》在北美上映 。</p><p>2016年4月15日，迪士尼出品的真人冒险动画片《奇幻森林》上映，斯嘉丽·约翰逊为片中的巨蟒配音。5月6日，《美国队长3》在中国与美国同步上映，斯嘉丽·约翰逊在片中再度饰演“黑寡妇”一角。</p><p>2016年8月，斯嘉丽·约翰逊以2.5千万美元年收入，获得2016年《福布斯》全球十大最高收入女星第三名。</p><p>2017年，主演的科幻巨制《攻壳机动队》3月31日北美上映</p><p><br/></p>');
INSERT INTO `uk_photo_data` VALUES ('2', '14,15,16,17', '<p>1999年，9岁的艾玛获得了《哈利波特》中某个角色的试镜机会。2000年8月，10岁的艾玛在经历了8轮试镜后，被选中出演赫敏·格兰杰，并陆续拍摄了8部哈利波特系列影片。</p><p>2001年上映的《哈利·波特与魔法石》是艾玛的银幕处女作，艾玛因在该片中的表演获得了5个奖项提名，并最终夺得青年艺术家奖的青年女演员奖。</p><p>2007年，《哈利·波特》系列的第五部电影《哈利·波特与凤凰社》上映，创下首周收入达3.327亿美元的票房纪录。艾玛也获得了英国国家电影奖最佳女演员奖。同年7月9日，艾玛与丹尼尔·拉德克利夫和鲁伯特·格林特一起在好莱坞的中国戏院前留下了各自的手印、脚印和魔杖印 &nbsp;。同年，艾玛接受了哈利波特之外的首个角色，在BBC拍摄的电视电影《芭蕾舞鞋》中扮演波琳。</p><p>2008年，艾玛第一次参与动画片配音。她在环球影业出品的《浪漫鼠佩德罗》中为豌豆公主配音。</p><p>2009年2月8日，艾玛·沃特森作为颁奖嘉宾出席英国电影学院奖颁奖礼。同年。艾玛以straight A的成绩就读于布朗大学。</p><p>2011年，艾玛·沃特森获得第13届青少年选择奖最佳科幻电影女演员和最佳夏日电影女星。4月，从布朗大学暂时休学。7月，在纽约宣布她将转学至牛津大学。</p><p>2012年9月8日，艾玛·沃特森出席《壁花少年》在多伦多影节举行的首映礼。</p><p>2013年3月，艾玛确认在《美女与野兽》中扮演女主角。6月11日，艾玛出席电影《珠光宝气》首映礼。6月14日，艾玛主演的《亮闪闪的戒指》上映。9月3日，艾玛亮相2013GQ年度男性大奖红毯。9月底，艾玛登上英国版《GQ》十月份封面。</p><p>2014年1月12日晚，第71届美国电影电视金球奖颁奖礼在洛杉矶举行，艾玛·沃特森作为颁奖嘉宾出席了颁奖礼。3月2日晚，第86届美国奥斯卡金像奖颁奖礼在洛杉矶举行，艾玛·沃特森与约瑟夫·高登-莱维特作为颁奖嘉宾为《地心引力》颁发最佳视觉效果奖。</p><p>2015年1月，艾玛·沃特森签约主演迪士尼真人版电影《美女与野兽》，在片中饰演贝儿公主。</p><p><br/></p>');
INSERT INTO `uk_photo_data` VALUES ('3', '18,19,20,21', '<p>2007年，盖尔·加朵应杂志Maxim之邀，为其拍摄了专题&quot;在以色列军中的女人&quot;的封面。其专题内容为曾参过军的以色列模特。作为这个事件的后续，盖尔·加朵被邀请参加其在纽约的开幕派对。当然这些照片出现在活动中时，在以色列和美国挑起了一些争议。但在争议普及前，照片里的盖尔·加朵已登上了纽约邮报的头版。</p><p>2008年，盖尔·加朵也成为了以色列最负盛名的服装品牌之一的Castro的首席模特。随着演艺事业的发展，盖尔·加朵来到了好莱坞，在由华裔导演林诣彬执导的《速度与激情4》中出演了角色Gisele。同年，盖尔·加朵参加《量子危机》女主角选拔，但败给Olga Kurylenko。</p><p>2009年，盖尔·加朵出演了喜剧《约会之夜》。</p><p>2010年夏，盖尔·加朵出现在汤姆·克鲁斯的动作喜剧片《危情谍战》中。</p><p>2011年，盖尔·加朵在《速度与激情5》中再次出演了Gisele的角色。</p><p>2013年，盖尔·加朵出演《速度与激情6》，这是她第三次饰演角色Gisele。影片由范·迪塞尔、保罗·沃克、道恩·强森、乔丹娜·布鲁斯特等群星联袂出演。截止到2013年11月20日，根据票房网站mojo的数据，该片全球票房已超过7.8亿美元。</p><p>2013年12月，华纳公司称盖尔·加朵加盟《蝙蝠侠大战超人：正义黎明》，出演超级女英雄神奇女侠（Wonder Woman）。神奇女侠是DC漫画的一位超级女英雄，真实身份是Themyscira的戴安娜公主。神奇女侠是众多漫画英雄之中，最受欢迎的超级英雄之一，也是正义联盟的创立人之一。这是神奇女侠这一角色首次亮相大银幕。</p><p>2016年3月25日，盖尔·加朵加盟的《蝙蝠侠大战超人：正义黎明》在全美上映；同年，其主演的动作喜剧片《邻家大贱谍》和犯罪动作片《超脑48小时》全面上映 。</p><p>2017年6月2日，根据DC漫画改编的奇幻动作电影《神奇女侠》在美国、中国同步上映，加朵继续饰演主角神奇女侠。</p><p><br/></p>');
INSERT INTO `uk_photo_data` VALUES ('4', '22,23,24,25', '<p>16岁那年，他以一支可口可乐广告进入演艺圈，然后他在舞台剧《狼孩》中得到了平生第一个角色，并参加了几部加拿大电视剧的拍摄。当罗伯·洛来此拍摄《热血男儿》时，他在片中露了一面。然后，1985年，也就是在他20岁那年，他就带着身上只有的3000美元，开着自己买的第一辆车—1969年产的Volvo向着洛杉矶出发了，前往他所向往的好莱坞发展。</p><p>1987年以《大河边缘》在美国市场引起注意，两年后《阿比阿弟的冒险》中极具喜感的角色，成为美国校园青少年偶像。1994年，他达到了事业的顶峰，他主演的《生死时速》的票房达一亿二千一百万美元。继《生死时速》之后，基努在《捍卫机密》扮演了一个电脑资料员。</p><p>1999年，《黑客帝国》找到了他。2003年，第2、3部续集连环推出。之后，基努·里维斯并没有趁热打铁地去接演一些片酬很高的大片，却热衷于接一些低成本制作的小片。除了演艺以外，他热衷于摩托车和音乐，并在“天狼星”乐队中担任贝司手。</p><p>2003年的喜剧片《爱是妥协》让他转型。2005年的《地狱神探》中，他演绎了一名具有超能力的侦探。2006年的独立电影《吮拇指的人》中，他客串了怪牙医一角；之后，理查德·林克莱特执导的科幻真人动画片《黑暗扫描仪》中，他则扮演备受困扰、无法入眠的卧底警察。</p><p>2008年，他又拿起枪，在根据犯罪小说作家詹姆士-埃尔罗伊原著改编的《街头之王》中饰演洛杉矶警署的探员，在正义理想和警局黑幕之间苦苦周旋。</p><p>2013年，他执导的《太极侠》和他主演的《47浪人》在北美上映。</p><p>2014年9月，由基努·里维斯主演的“复仇杀手”动作片《疾速追杀》放出首款预告片，里维斯在片中饰演的约翰·威克是一名退休的杀手。《疾速追杀》选择在美国独立电影节“奇幻电影节”上亮相，于10月24日北美上映。</p><p>2015年2月，加盟尼古拉斯·雷弗恩的电影《霓虹灯魔头》。秋天，动作大片《疾速追杀2》开拍，基努·里维斯回归主演，2016年下半年登陆北美院线。</p><p><br/></p>');
INSERT INTO `uk_photo_data` VALUES ('5', '26,27,28,29', '<p>2000年，32岁的休·杰克曼参加了《X战警》的试镜，经过了福斯高层电影公司鲁珀特·莫多克的认可，得到金刚狼这一角色。该影片使休·杰克曼成为好莱坞明星。</p><p>2004年，凭借《来自奥兹的男孩》中同性恋艺人的角色拿到一座托尼奖。随后，他连续三年担任托尼奖颁奖晚会的主持人。</p><p>2006年，与克里斯蒂安·贝尔联袂出演由克里斯托弗·诺兰执导的《致命魔术》。</p><p>2008年，与约翰·帕勒莫以及老婆德博拉·李佛尼斯创立了Seed制片公司，制作了惊悚片《桃色名单》。</p><p>2009年，在第81届奥斯卡金像奖颁奖礼担任主持人，并与碧昂丝·诺利斯合作在颁奖礼上表演节目&quot;Musical is back&quot;。</p><p>2011年，出演电影《钢甲铁拳》，与小演员达科塔·高尤在影片中演绎父子情。他饰演的查理·坎顿曾经是一名人类拳击手，生活潦倒的他靠在机器人拳击的地下黑市打比赛赚钱过活。拳击冠军舒格·雷·伦纳德担任了影片的拳击动作设计，同时也是休·杰克曼的拳击动作与拳台表现的向导。</p><p>2012年，休·杰克曼饰演了音乐剧电影《悲惨世界》中冉·阿让一角。该角色使他获得第70届金球奖音乐/喜剧类最佳男主角奖，并使获得第66届英国电影学院奖最佳男主角提名和第85届奥斯卡金像奖最佳男主角提名。</p><p>2013年9月，西班牙圣塞巴斯蒂安电影节授予其终身成就奖。同年，休·杰克曼出演《金刚狼2》。影片中，90%的动作都是由他亲自完成。为了呈现出一个加强版的金刚狼，他决定要让他的体能提升到新的境界。在拍摄前展开了一段最密集、最有纪律的训练课程，包括严格的饮食、高强度的体能训练以及专业的武术指导课程。</p><p>2014年，领衔主演的《X战警：逆转未来》于5月23日以3D格式登陆中国内地影院，与北美同步上映。从2000年的《X战警》到2014年的《X战警：逆转未来》，十四年间他已经先后七次出演金刚狼这一角色，这也创下了同一演员扮演同一漫画英雄角色的纪录。12月19日，友情客串《博物馆奇妙夜3》。7月28日，与舒淇携手出席活动并在台上比V卖萌，还贴心为舒淇递话筒，台下变身摄影师拿手机拍不停。</p><p>2015年，与西格妮·韦弗主演的科幻动作电影《超能查派》于3月6日北美上映。3月15日，新开机体育传记片《飞鹰埃迪》饰演其入门导师查克·伯霍恩。5月7日，他宣布电影《金刚狼3》将会是他最后一次出演金刚狼。 7月17日，由华纳兄弟影业出品的奇幻冒险大作《彼得·潘》在北美上映，他在片中饰演黑胡子船长。</p><p>2016年，领衔主演的电影《飞鹰艾迪》于4月7日在韩国上映。出演的《X战警：天启》于6月3日内地上映。</p><p>2017年3月3日，主演的《金刚狼3：殊死一战》北美/中国大陆同步上映。加盟《朱诺》导演贾森·雷特曼执导的新片《领先者》（The Frontrunner），饰演政治家加里·哈特。主演的新片《马戏之王》（The Greatest Showman）北美12月25日上映。</p><p><br/></p>');
INSERT INTO `uk_photo_data` VALUES ('6', '30,31,32,33', '<p>1987年，22岁的小罗伯特·唐尼主演了喜剧片《泡妞专家》，该影片是他主演的第一部电影。同年，他还在《零下的激情》中演一个瘾君子。</p><p>1992年，小罗伯特·唐尼在《卓别林》中饰演了卓别林一角，并获得英国电影学院奖最佳男主角奖和奥斯卡金像奖最佳男主角的提名。</p><p>1997年，他因吸毒被逮捕并强制戒毒，两年后，他再次入狱。</p><p>2000年，参加《甜心俏佳人》的拍摄工作。</p><p>2001年，他凭借《甜心俏佳人》中的演出获得第58届美国电影电视金球奖。</p><p>2005年前后，小罗伯特·唐尼彻底戒毒，他出演了《晚安，好运》、《亲亲，撞撞》和《第6场》三部电影。</p><p>2007年，他主演了由大卫·芬奇执导的《十二宫》，饰演了一位压力巨大、沉沦烟酒的调查记者。</p><p>2008年，他饰演了《钢铁侠》中的钢铁侠托尼·斯塔克。影片根据Marvel20世纪60年代推出的同名漫画改编的。同年，他出演了《热带惊雷》，并凭借该片获得奥斯卡男配角的提名。他还出演了《大侦探福尔摩斯》，影片根据莱昂纳尔·威格拉姆的同名漫画改编，故事原型来自英国小说家阿瑟·柯南道尔破案侦探系列小说的主人公。</p><p>2010年，他出演了《钢铁侠2》和《预产期》，并且凭《大侦探福尔摩斯》获得第67届金球奖音乐喜剧类最佳男演员奖 。</p><p>2012年，由他主演的《复仇者联盟》凭借15.2亿美元成为全球年度票房冠军。</p><p>2013年，他主演的《钢铁侠3》上映。1月份他凭《复仇者联盟》获得第39届人民选择奖最受欢迎男演员奖。4月6日，小罗伯特·唐尼出席《钢铁侠3》在北京的首映礼。影片于同年5月16日全球突破10亿美元票房。6月21日，已经正式签下《复仇者联盟2》《复仇者联盟3》两部片约。</p><p>2014年1月10日，小罗伯特·唐尼获得第40届人民选择奖最受欢迎动作明星奖，其主演的《钢铁侠3》获得最受欢迎电影奖。1月12日，他作为金球奖颁奖嘉宾出席第71届金球奖颁奖礼。10月10日，小罗伯特·唐尼主演的《法官老爹》在北美上映，他在片中饰演一名犀利律师，此外，小罗伯特·唐尼还与妻子苏珊·唐尼联合监制此片；10月14日，将加盟《美国队长3》，在片中再度饰演钢铁侠。</p><p>2015年4月12日，第24届MTV电影奖在洛杉矶揭晓，小罗伯特·唐尼获得时代大奖；4月14日，主演的漫威超级英雄大作《复仇者联盟2》今日（当地时间4月12日）在洛杉矶举行了盛大的全球首映礼，影片将于今年5月1日2D/3D/IMAX 3D全球震撼上映，并已定于5月12日全格式在中国内地上映；8月，小罗伯特唐尼在全球男演员富豪榜排名第一，累积收入8000万美元。11月25日，主演的漫威第三阶段的开篇之作《美国队长3》首曝预告，影片将于2016年5月6日在北美上映。</p><p>2016年4月13日，小罗伯特·唐尼主演《美国队长3》在洛杉矶举办了全球首映礼；4月22日，加盟《蜘蛛侠》；8月17日，联手“真探”制片人皮佐拉托，参演HBO剧《佩里·梅森》，讲述一个犯罪与法律的故事；8月26日，小罗伯特·唐尼以3300万美元排《福布斯》全球十大最高收入男星第八位；11月14日，客串杰米福克斯自编自导的喜剧电影，以西部牛仔形象登场；17日，监制并编导美剧《奇点》；12月13日，《福布斯》杂志评选了一个“好莱坞票房回报率最高演员”的名单，小罗伯特·唐尼排行第8。</p><p>2017年2月14日，参与制作《少年时代》导演理查德拍纪实片。3月21日，加盟新片扮演怪医杜立德，定档于2019年5月24日北美上映。</p><p><br/></p>');

-- ----------------------------
-- Table structure for uk_place
-- ----------------------------
DROP TABLE IF EXISTS `uk_place`;
CREATE TABLE `uk_place` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目模型id',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '推荐位名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='推荐位表';

-- ----------------------------
-- Records of uk_place
-- ----------------------------
INSERT INTO `uk_place` VALUES ('1', '0', '推荐', '1500272764', '1500519504', '100');

-- ----------------------------
-- Table structure for uk_product
-- ----------------------------
DROP TABLE IF EXISTS `uk_product`;
CREATE TABLE `uk_product` (
  `id` int(11) unsigned AUTO_INCREMENT COMMENT '文档id',
  `cname` varchar(64) DEFAULT '' COMMENT '栏目标识',
  `ifextend` tinyint(2) DEFAULT '0' COMMENT '是否栏目拓展',
  `uid` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `places` varchar(64) DEFAULT '' COMMENT '推荐位',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `orders` int(11) DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned DEFAULT '0' COMMENT '状态',
  `hits` int(11) unsigned DEFAULT '0' COMMENT '点击量',
  `price` varchar(128) DEFAULT '' COMMENT '价格',
  `description` varchar(3000) DEFAULT '' COMMENT 'SEO摘要',
  `cover` int(5) unsigned DEFAULT '0' COMMENT '封面图',
  `keywords` varchar(256) DEFAULT '' COMMENT 'SEO关键词',
  `year` varchar(32) DEFAULT '' COMMENT '年份',
  `color` varchar(32) DEFAULT '' COMMENT '颜色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='产品模型表';

-- ----------------------------
-- Records of uk_product
-- ----------------------------
INSERT INTO `uk_product` VALUES ('1', 'product', '0', '1', ',,,', '艾康尼克7系', '1500512675', '1504142315', '100', '1', '0', '950万', '艾康尼克7系列的续航里程可达400公里以上,电能容量超过78千瓦时,电机功率为165千瓦以上', '39', '功率165千瓦,续航400公里', '1', ',1,3,');
INSERT INTO `uk_product` VALUES ('2', 'product', '0', '1', ',,1,,', 'EXP 12 Speed 6e', '1500512808', '1504142301', '100', '1', '0', '2800万', 'EXP 12 Speed 6e纯电动概念车代表了宾利下一步和未来的设计语言', '40', '纯电动,网状格栅', '4', ',2,');
INSERT INTO `uk_product` VALUES ('3', 'product', '0', '1', ',,,', 'Mission E', '1500512886', '1504142277', '100', '1', '0', '1900万', '保时捷Mission E电动概念跑车', '43', '600马力,单次310英里', '2', ',2,');
INSERT INTO `uk_product` VALUES ('4', 'product', '0', '1', ',,1,,', 'Fenyr SuperSport', '1500513416', '1504142238', '100', '1', '0', '2312万', 'Fenyr Supersport将全球限量生产25辆', '46', '百公里2.7s', '3', ',1,3,');

-- ----------------------------
-- Table structure for uk_product_data
-- ----------------------------
DROP TABLE IF EXISTS `uk_product_data`;
CREATE TABLE `uk_product_data` (
  `did` int(11) unsigned DEFAULT '0' COMMENT '文档id',
  `model` varchar(128) DEFAULT '' COMMENT '型号',
  `brand` varchar(128) DEFAULT '' COMMENT '品牌',
  `content` text COMMENT '详细介绍',
  `pictures` varchar(128) COMMENT '产品图集',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='产品模型扩展表';

-- ----------------------------
-- Records of uk_product_data
-- ----------------------------
INSERT INTO `uk_product_data` VALUES ('1', '电动 mpv', '艾康尼克', '<p>将于2019年交付的艾康尼克7系列主打两个版本，一个是顶级配置VIP 版，另一个是高端配置的尊贵版，适合不同集团和个人客户的需求。</p><p>顶级配置的VIP 版旨在为乘客提供头等舱般的乘车享受。其后排设置了两个商务舱专座，并搭载了40寸电视屏幕和9寸的触屏控制中心，具备视频、音频播放和视频会议的功能，同时还有驾驶舱和后座乘客区域声音隔离设计，以确保后排乘客的隐私。</p><p>尊贵版车型设定为家庭和公司都可负担的享受型用车，有7人乘坐空间，其后排搭载了两个17寸的触摸屏，根据用户需求座椅可以旋转，同样具备视频、音频播放和视频会议的功能。</p><p>对于电动车的核心部分电池和电控系统，艾康尼克表示，艾康尼克7系列的续航里程可达400公里以上。电能容量超过78千瓦时。电机功率为165千瓦以上。</p><p><br/></p>', '37,38,39');
INSERT INTO `uk_product_data` VALUES ('2', 'Speed 6e', '宾利', '<p>EXP 12 Speed 6e纯电动概念车代表了宾利下一步和未来的设计语言，这样的设计语言也将会传承到其他新的车型上，代表了我们创新的理念。</p><p>车前端采用了宾利标志性的网状格栅，呈现更有立体感的几何形状。如同大家知道的一样，宾利EXP 12 Speed 6e是一台电动车，“纯电动”的元素体现在这辆车的许多细</p><p><br/></p><p>节上。“铜”元素由于导电能力强，经常被用于电线等电力配件，我们以“铜”为设计元素，为了强化EXP 12 Speed 6e的“电动车”特性，其在这辆车的各个部分都有所</p><p><br/></p><p>体现，例如在进气格栅、轮毂中心的“B字徽标”及内饰的有些部件是铜制的。</p><p>除了快速感应充电功能，EXP 12 Speed 6e也在车辆后部号牌下，隐藏了接口，可连接至交流电源进行充电，可以看到充电的整个过程。</p><p><br/></p>', '40,41,42');
INSERT INTO `uk_product_data` VALUES ('3', '2015款 Concept', '保时捷', '<p>2015年9月14日，在法兰克福车展开幕前夕，保时捷展示了Mission E电动概念跑车，这款跑车的动力预计为600马力(采用保时捷 919 赛车改造的电发动机)派生，单次行驶里程接近310英里(500 公里)。可在3.5秒内加速到 62 英里/小时。</p>', '43,44,45');
INSERT INTO `uk_product_data` VALUES ('4', '2016款 基本型', 'W Motors', '<p>Fenyr Supersport将全球限量生产25辆，该车将搭载一台水平对置六缸的4.0T双涡轮发动机，功率可以达到662kW(900PS)附近，最大扭矩直接升至1200Nm。另外，大量轻质材料的应用让该车重量将低于1400kg。</p><p>该车百公里加速能在2.7s内完成，极速可超过400km/h，指标信仰值足够。可能头款产品的受欢迎程度很好，W Motors给新产品定了25辆的量产计划。</p><p><br/></p>', '46,47,48');

-- ----------------------------
-- Table structure for uk_tag
-- ----------------------------
DROP TABLE IF EXISTS `uk_tag`;
CREATE TABLE `uk_tag` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `mid` int(4) NOT NULL COMMENT '模型ID',
  `title` varchar(256) NOT NULL COMMENT 'tag',
  `weight` int(4) NOT NULL DEFAULT '0' COMMENT '权重',
  `url` varchar(2000) NOT NULL DEFAULT '' COMMENT '指定链接',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='标签表';

-- ----------------------------
-- Records of uk_tag
-- ----------------------------
INSERT INTO `uk_tag` VALUES ('1', '0', 'ukcms', '99', 'http://www.ukcms.com');
INSERT INTO `uk_tag` VALUES ('2', '0', 'yxcms', '99', 'htttp://www.yxcms.net');
