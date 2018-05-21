/*
Navicat MySQL Data Transfer

Source Server         : test
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : ukcms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-12-23 15:44:51
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
) ENGINE=MyISAM AUTO_INCREMENT=140 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of uk_admin_menu
-- ----------------------------
INSERT INTO `uk_admin_menu` VALUES ('1', '0', '基础设置', 'ion-android-share-alt', '1', '', '_self', '0', '1514014188', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('2', '35', '结构管理', 'fa fa-code-fork', '1', '', '_self', '0', '1514014359', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('3', '1', '后台首页', 'fa fa-home', '1', 'admin/index/index', '_self', '0', '1511681732', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('4', '1', '系统管理', 'fa fa-recycle', '1', '', '_self', '0', '1511681732', '3', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('5', '4', '后台菜单', 'fa fa-newspaper-o', '1', 'admin/menu/index', '_self', '0', '1514014206', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('6', '1', '个人设置', 'fa fa-user-o', '1', 'admin/index/userInfo', '_self', '0', '1511681732', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('7', '4', '数据库管理', 'fa fa-database', '1', 'admin/database/index', '_self', '0', '1514014206', '2', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('8', '7', '数据库备份', 'fa fa-folder', '1', 'admin/database/export', '_self', '0', '0', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('9', '7', '数据库还原', 'fa fa-exchange', '1', 'admin/database/import', '_self', '0', '0', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('10', '7', '优化表', 'fa fa-gear', '1', 'admin/database/optimize', '_self', '0', '0', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('11', '7', '修复表', 'fa fa-wrench', '1', 'admin/database/repair', '_self', '0', '0', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('12', '7', '删除备份', 'fa fa-close', '1', 'admin/database/delete', '_self', '0', '0', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('13', '5', '添加菜单', 'fa fa-plus-circle', '1', 'admin/menu/add', '_self', '0', '0', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('14', '5', '菜单编辑', 'fa fa-pencil', '1', 'admin/menu/edit', '_self', '0', '0', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('15', '5', '菜单显隐', 'fa fa-eye', '1', 'admin/menu/visible', '_self', '0', '0', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('16', '5', '删除菜单', 'fa fa-times-circle', '1', 'admin/menu/delete', '_self', '0', '1495952539', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('17', '1', '后台权限', 'fa fa-unlock-alt', '1', '', '_self', '0', '1511681732', '4', '1', '1');
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
INSERT INTO `uk_admin_menu` VALUES ('29', '125', '参数设置', 'fa fa-gear', '1', 'admin/config/index', '_self', '0', '1511681820', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('30', '4', '附件管理', 'fa fa-folder-open', '1', 'admin/filemanage/index', '_self', '0', '1514014206', '3', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('31', '30', '上传', 'fa fa-cloud-upload', '1', 'admin/filemanage/upload', '_self', '0', '1496641096', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('32', '30', '查看文件夹文件', 'fa fa-folder', '1', 'admin/filemanage/getfiles', '_self', '0', '1496641096', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('33', '30', '附件状态设置', 'fa fa-file', '1', 'admin/filemanage/setstate', '_self', '0', '1496641096', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('34', '30', '删除附件', 'fa fa-times', '1', 'admin/filemanage/delete', '_self', '0', '1496641096', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('35', '0', '结构内容', 'fa fa-file-text-o', '1', '', '_self', '0', '1509441206', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('36', '35', '模型管理', 'fa fa-cube', '1', 'admin/model/index', '_self', '0', '1514014359', '0', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('37', '2', '栏目管理', 'fa fa-sitemap', '1', 'admin/column/index', '_self', '0', '1497771172', '1', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('38', '36', '添加模型', 'fa fa-plus-square-o', '1', 'admin/model/add', '_self', '0', '1504450011', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('39', '36', '编辑模型', 'fa fa-pencil-square-o', '1', 'admin/model/edit', '_self', '0', '1504450011', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('40', '36', '删除模型', 'fa fa-close', '1', 'admin/model/delete', '_self', '0', '1504450011', '4', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('41', '36', '模型字段', 'fa fa-bars', '1', 'admin/field/index', '_self', '1495592456', '1504450011', '5', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('42', '36', '添加字段', 'fa fa-plus-square', '1', 'admin/field/add', '_self', '1495592525', '1504450011', '6', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('43', '36', '编辑字段', 'fa fa-edit', '1', 'admin/field/edit', '_self', '1495592647', '1504450011', '7', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('44', '36', '字段状态设置', 'fa fa-toggle-off', '1', 'admin/field/setstate', '_self', '1495592746', '1504450011', '10', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('45', '36', '字段隐显设置', 'fa fa-eye', '1', 'admin/field/setvisible', '_self', '1495592852', '1504450011', '11', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('46', '36', '删除字段', 'fa fa-close', '1', 'admin/field/delete', '_self', '1495850874', '1504450011', '9', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('47', '37', '添加栏目', 'fa fa-plus-circle', '1', 'admin/column/add', '_self', '1496037441', '1503901646', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('48', '37', '编辑栏目', 'fa fa-edit', '1', 'admin/column/edit', '_self', '1496037554', '1503901646', '1', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('49', '37', '删除栏目', 'fa fa-close', '1', 'admin/column/delete', '_self', '1496037649', '1503901646', '7', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('50', '37', '栏目排序', 'fa fa-sort-alpha-asc', '1', 'admin/column/changeorder', '_self', '1496037727', '1503901646', '8', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('51', '37', '栏目批量添加', 'fa fa-plus-square', '1', 'admin/column/addAll', '_self', '1496041478', '1503901646', '3', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('55', '37', '栏目状态设置', 'fa fa-eye', '1', 'admin/column/setstate', '_self', '1496129249', '1503901646', '9', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('56', '37', '栏目拓展内容', 'fa fa-tasks', '1', 'admin/column/extfields', '_self', '1496300432', '1503901646', '2', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('57', '35', '栏目内容', 'fa fa-clone', '1', '', '_self', '1496640683', '1514014446', '2', '1', '1');
INSERT INTO `uk_admin_menu` VALUES ('58', '37', '栏目批量移动', 'fa fa-reply', '1', 'admin/column/move', '_self', '1496742824', '1503901646', '10', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('59', '37', '栏目批量编辑', 'fa fa-file-text', '1', 'admin/column/editAll', '_self', '1496742900', '1503901646', '11', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('60', '57', '添加模型内容', 'fa fa-plus', '1', 'admin/content/add', '_self', '1496992960', '1523773580', '0', '1', '0');
INSERT INTO `uk_admin_menu` VALUES ('61', '57', '编辑模型内容', 'fa fa-edit', '1', 'admin/content/edit', '_self', '1496993528', '1523773580', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('62', '57', '删除模型内容', 'fa fa-close', '1', 'admin/content/delete', '_self', '1496993730', '1523773580', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('63', '57', '设置模型内容状态', 'fa fa-toggle-on', '1', 'admin/content/setstate', '_self', '1496993816', '1523773580', '7', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('64', '57', '模型内容排序', 'fa fa-sort-numeric-desc', '1', 'admin/content/changeorder', '_self', '1496993920', '1523773580', '9', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('65', '2', '推荐位管理', 'fa fa-dot-circle-o', '1', 'admin/place/index', '_self', '1497768076', '1497771172', '2', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('66', '65', '添加推荐位', 'fa fa-plus', '1', 'admin/place/add', '_self', '1497768219', '1497774456', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('67', '65', '编辑推荐位', 'fa fa-edit', '1', 'admin/place/edit', '_self', '1497768262', '1497774468', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('68', '65', '删除推荐位', 'fa fa-remove', '1', 'admin/place/delete', '_self', '1497768361', '1497774482', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('69', '35', '广告管理', 'fa fa-photo', '1', '', '_self', '1497771040', '1514014359', '4', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('70', '69', '链接管理', 'fa fa-link', '1', 'admin/link/index', '_self', '1497771084', '1498009133', '1', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('71', '69', '分组管理', 'fa fa-window-maximize', '1', 'admin/linkgroup/index', '_self', '1497771145', '1514014738', '0', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('72', '71', '添加分组', 'fa fa-plus', '1', 'admin/linkgroup/add', '_self', '1498008871', '1498009140', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('73', '71', '编辑分组', 'fa fa-edit', '1', 'admin/linkgroup/edit', '_self', '1498008936', '1498009140', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('74', '71', '删除分组', 'fa fa-times-circle-o', '1', 'admin/linkgroup/delete', '_self', '1498009007', '1498009140', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('75', '70', '添加链接', 'fa fa-plus', '1', 'admin/link/add', '_self', '1498009187', '1498009187', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('76', '70', '编辑链接', 'fa fa-edit', '1', 'admin/link/edit', '_self', '1498009222', '1498009222', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('77', '70', '删除链接', 'fa fa-times-circle-o', '1', 'admin/link/delete', '_self', '1498009266', '1498009266', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('78', '70', '设置链接状态', 'fa fa-toggle-on', '1', 'admin/link/setstate', '_self', '1498009327', '1498009327', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('79', '70', '设置链接排序', 'fa fa-sort-numeric-desc', '1', 'admin/link/changeorder', '_self', '1498009393', '1498009393', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('80', '36', '模型状态设置', 'fa fa-toggle-on', '1', 'admin/model/setstate', '_self', '1498894295', '1504450011', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('81', '57', '模型内容移动栏目', 'fa fa-reply-all', '1', 'admin/content/move', '_self', '1498895686', '1523773580', '6', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('82', '36', '模型是否可投稿', 'fa fa-circle-o', '1', 'admin/model/setsub', '_self', '1499416148', '1504450011', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('83', '36', '导入模型内容', 'fa fa-exchange', '1', 'admin/model/importdata', '_self', '1501653553', '1523773580', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('84', '36', '导入格式下载', 'fa fa-file-text-o', '1', 'admin/model/importexample', '_self', '1501739963', '1523773580', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('85', '3', '清空缓存', 'fa fa-trash-o', '1', 'admin/index/clear', '_self', '1501837809', '1501837809', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('86', '2', 'TAG标签管理', 'fa fa-tags', '1', 'admin/tag/index', '_self', '1502153759', '1502153759', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('87', '86', '添加TAG', 'fa fa-plus', '1', 'admin/tag/add', '_self', '1502153780', '1502154092', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('88', '86', '编辑TAG', 'fa fa-edit', '1', 'admin/tag/edit', '_self', '1502153799', '1502154092', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('89', '86', '导入TAG', 'fa fa-plus-circle', '1', 'admin/tag/importdata', '_self', '1502153821', '1502154133', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('90', '86', 'AJAX设置TAG值', 'fa fa-toggle-on', '1', 'admin/tag/setvalue', '_self', '1502154055', '1502154092', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('91', '86', '删除TAG', 'fa fa-close', '1', 'admin/tag/delete', '_self', '1502154076', '1502154092', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('92', '125', '参数管理', 'fa fa-wrench', '1', 'admin/configset/index', '_self', '1503461284', '1511681810', '0', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('93', '92', '新增参数', 'fa fa-plus', '1', 'admin/configset/add', '_self', '1503461342', '1511681830', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('94', '92', '编辑参数', 'fa fa-edit', '1', 'admin/configset/edit', '_self', '1503461434', '1511681841', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('95', '92', '删除参数', 'fa fa-times', '1', 'admin/configset/delete', '_blank', '1503461464', '1511681855', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('96', '92', '参数排序', 'fa fa-sort-numeric-asc', '1', 'admin/configset/changeorder', '_self', '1503461488', '1511681864', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('97', '92', '参数状态', 'fa fa-eye', '1', 'admin/configset/setstate', '_self', '1503461504', '1511681875', '4', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('98', '37', '列表栏目筛选条件选项字段', 'fa fa-arrows-v', '1', 'admin/column/getoptionfield', '_self', '1503901633', '1503901646', '12', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('99', '36', '字段排序', 'fa fa-sort-numeric-asc', '1', 'admin/field/changeorder', '_self', '1504449938', '1504450011', '8', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('100', '35', '独立模型内容', 'fa fa-suitcase', '1', '', '_self', '1506914011', '1511681732', '3', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('101', '1', '插件管理', 'fa fa-code', '1', '', '_self', '1506914011', '1511681732', '6', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('102', '1', '应用管理', 'fa fa-hdd-o', '1', 'admin/app/index', '_self', '1506914336', '1514015058', '5', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('103', '102', '应用状态', 'fa fa-toggle-on', '1', 'admin/app/setstate', '_self', '1506914560', '1509441246', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('104', '102', '应用安装', 'fa fa-plus', '1', 'admin/app/install', '_self', '1506915151', '1509441246', '1', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('105', '102', '应用卸载', 'fa fa-close', '1', 'admin/app/uninstall', '_self', '1506915217', '1509441246', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('107', '35', 'SEO优化', 'fa fa-paw', '1', '', '_self', '1509086034', '1514014359', '5', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('108', '107', 'SiteMap', 'fa fa-map', '1', 'admin/sitemap/index', '_self', '1509086579', '1509086579', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('109', '36', '字段必填设置', 'fa fa-dot-circle-o', '1', 'admin/field/setrequire', '_self', '1509352076', '1509352088', '12', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('110', '101', '钩子管理', 'fa fa-anchor', '1', 'admin/hook/index', '_self', '1509441440', '1509441440', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('111', '101', '行为管理', 'fa fa-plane', '1', 'admin/behavior/index', '_self', '1509441521', '1509441521', '100', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('112', '110', '添加钩子', 'fa fa-plus', '1', 'admin/hook/add', '_self', '1509591455', '1509591495', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('113', '110', '编辑钩子', 'fa fa-edit', '1', 'admin/hook/edit', '_self', '1509591550', '1509591550', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('114', '110', '钩子排序', 'fa fa-long-arrow-down', '1', 'admin/hook/changeorder', '_self', '1509591630', '1509591630', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('115', '110', '删除钩子', 'fa fa-times', '1', 'admin/hook/delete', '_self', '1509591695', '1509591695', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('116', '111', '行为安装', 'fa fa-wrench', '1', 'admin/behavior/install', '_self', '1509592668', '1509592668', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('117', '111', '行为卸载', 'fa fa-close', '1', 'admin/behavior/uninstall', '_self', '1509592887', '1509592887', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('118', '111', '行为排序', 'fa fa-long-arrow-down', '1', 'admin/behavior/edichangeorder', '_self', '1509593073', '1509593073', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('119', '111', '添加行为', 'fa fa-plus', '1', 'admin/behavior/add', '_self', '1509687746', '1509687874', '0', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('120', '111', '删除行为', 'fa fa-times', '1', 'admin/behavior/delete', '_self', '1509687833', '1509687874', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('121', '110', '钩子状态', 'fa fa-toggle-on', '1', 'admin/hook/setstate', '_self', '1509763006', '1509763021', '3', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('122', '36', '字段设置搜索', 'fa fa-search', '1', 'admin/field/setsearch', '_self', '1510206709', '1510206728', '13', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('123', '30', '图片本地化', 'fa fa-arrow-circle-down', '1', 'admin/filemanage/geturlfile', '_self', '1510889582', '1510889590', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('124', '86', '替换内容TAG添加链接', 'fa fa-link', '1', 'admin/tag/gettaglink', '_self', '1511144419', '1511144430', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('125', '1', '系统参数', 'fa fa-cogs', '1', '', '_self', '1511681712', '1511681732', '2', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('126', '4', '域名管理', 'fa fa-globe', '1', 'admin/domain/index', '_self', '1511685612', '1514014206', '0', '0', '1');
INSERT INTO `uk_admin_menu` VALUES ('127', '126', '添加域名', 'fa fa-plus', '1', 'admin/domain/add', '_self', '1511685861', '1511685861', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('128', '126', '编辑域名', 'fa fa-edit', '1', 'admin/domain/edit', '_self', '1511685926', '1511685926', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('129', '126', '删除域名', 'fa fa-close', '1', 'admin/domain/delete', '_self', '1511686015', '1511686015', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('130', '126', '域名状态设置', 'fa fa-toggle-on', '1', 'admin/domain/setstate', '_self', '1511686423', '1511686423', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('131', '30', 'ajax通过ID获取附件信息', 'fa fa-file-archive-o', '1', 'admin/filemanage/ajaxgetfileinfo', '_self', '1511835064', '1511835076', '5', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('132', '57', '复制模型内容', 'fa fa-copy', '1', 'admin/content/copy', '_self', '1512630859', '1523773580', '8', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('133', '30', 'ajax通过类型获取附件列表', 'fa fa-reorder', '1', 'admin/filemanage/showfilelist', '_self', '1515914039', '1515915509', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('134', '36', '导出模型内容', 'fa fa-mail-reply-all', '1', 'admin/model/exportdata', '_self', '1523772657', '1523773602', '2', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('135', '100', '添加独立内容', 'fa fa-plus', '1', 'admin/independent/add', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('136', '100', '编辑独立内容', 'fa fa-edit', '1', 'admin/independent/edit', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('137', '100', '删除独立内容', 'fa fa-close', '1', 'admin/independent/delete', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('138', '100', '独立内容状态设置', 'fa fa-toggle-on', '1', 'admin/independent/setstate', '_self', '1500017712', '1523772682', '100', '0', '0');
INSERT INTO `uk_admin_menu` VALUES ('139', '100', '独立模型内容排序', 'fa fa-long-arrow-down', '1', 'admin/independent/changeorder', '_self', '1500017712', '1523772682', '100', '0', '0');
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of uk_admin_role
-- ----------------------------
INSERT INTO `uk_admin_role` VALUES ('1', '0,', '超级管理员', '拥有所有权限', '-1', '0', '0', '200', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of uk_attachment
-- ----------------------------
INSERT INTO `uk_attachment` VALUES ('1', '1', '1.jpg', 'admin', 'images/20170614/3301b085279f819c91906763f343273a.jpg', '', '', 'image/jpeg', 'jpg', '2170', '4bd3b079d4fd99d0bcfe4b51c441ce96', 'bca848acb3d828908b90d1e38a39dd570fd74a49', 'local', '0', '1497421718', '1497421718', '100', '1');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='前台栏目表';

-- ----------------------------
-- Records of uk_column
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
INSERT INTO `uk_config` VALUES ('2', 'app_cache', '缓存模式', 'develop', 'switch', '0', '', '开启后提高系统并发能力，但是会占用更多的存储空间', '1494408414', '1494408414', '100', '1');
INSERT INTO `uk_config` VALUES ('3', 'upload_image_thumb', '缩略图默认尺寸', 'upload', 'text', '300,300', '', '如需生成 <code class=\"text-warning\">300x300</code> 的缩略图，则填写 <code class=\"text-warning\">300,300</code> ，请注意，逗号必须是英文逗号', '1494408414', '1494408414', '5', '1');
INSERT INTO `uk_config` VALUES ('4', 'upload_image_thumb_type', '缩略图默认裁剪类型', 'upload', 'select', '3', '1:等比例缩放\r\n2:缩放后填充\r\n3:居中裁剪\r\n4:左上角裁剪\r\n5:右下角裁剪\r\n6:固定尺寸缩放', '该项配置只有在启用生成缩略图时才生效', '1494408414', '1494408414', '6', '1');
INSERT INTO `uk_config` VALUES ('5', 'upload_thumb_water', '添加水印', 'upload', 'switch', '0', '', '', '1494408414', '1494408414', '7', '1');
INSERT INTO `uk_config` VALUES ('6', 'upload_thumb_water_pic', '水印图片', 'upload', 'image', '', '', '只有开启水印功能才生效', '1494408414', '1494408414', '8', '1');
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
INSERT INTO `uk_config` VALUES ('17', 'web_site_logo', '站点LOGO', 'base', 'image', '', '', '', '1494408414', '1494408414', '5', '1');
INSERT INTO `uk_config` VALUES ('18', 'web_site_icp', '备案信息', 'base', 'text', '', '', '', '1494408414', '1494408414', '6', '1');
INSERT INTO `uk_config` VALUES ('19', 'web_site_statistics', '站点代码', 'base', 'textarea', '<script>\r\n(function(){\r\n    var bp = document.createElement(\'script\');\r\n    var curProtocol = window.location.protocol.split(\':\')[0];\r\n    if (curProtocol === \'https\') {\r\n        bp.src = \'https://zz.bdstatic.com/linksubmit/push.js\';        \r\n    }\r\n    else {\r\n        bp.src = \'http://push.zhanzhang.baidu.com/push.js\';\r\n    }\r\n    var s = document.getElementsByTagName(\"script\")[0];\r\n    s.parentNode.insertBefore(bp, s);\r\n})();\r\n</script>', '', '', '1494408414', '1494408414', '7', '1');
INSERT INTO `uk_config` VALUES ('20', 'captcha_signin', '后台登录验证码', 'system', 'switch', '1', '', '', '1494408414', '1494408414', '2', '1');
INSERT INTO `uk_config` VALUES ('21', 'baidu_translate_id', '百度翻译AppID', 'api', 'text', '', '', '申请地址:<a target=\"blank\" href=\"http://api.fanyi.baidu.com/api/\">http://api.fanyi.baidu.com/api/</a>', '1503804700', '1503809732', '1', '1');
INSERT INTO `uk_config` VALUES ('22', 'baidu_translate_secret', '百度翻译App秘钥', 'api', 'text', '', '', '', '1503804790', '1503804790', '2', '1');
INSERT INTO `uk_config` VALUES ('23', 'captcha_signin_model', '模型投稿验证码', 'system', 'switch', '1', '', '', '1505004158', '1505004158', '3', '1');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位表 ';

-- ----------------------------
-- Records of uk_link
-- ----------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位分组表 ';

-- ----------------------------
-- Records of uk_link_group
-- ----------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容模型表';

-- ----------------------------
-- Records of uk_model
-- ----------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型字段表';

-- ----------------------------
-- Records of uk_model_field
-- ----------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推荐位表';

-- ----------------------------
-- Records of uk_place
-- ----------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='标签表';