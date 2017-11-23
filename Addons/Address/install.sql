CREATE TABLE IF NOT EXISTS `bxlm_province` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '名称',
`lng`  varchar(255) NULL  COMMENT '经度',
`lat`  varchar(255) NULL  COMMENT '维度',
`pinyin`  varchar(255) NULL  COMMENT '拼音',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('province','省级','0','','1','["name","lng","lat"]','1:基础','','','','','','10','','','1499134699','1499335123','1','MyISAM','Address');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','名称','varchar(255) NULL','string','','名称','1','','0','','0','1','1499135163','1499135163','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('lng','经度','varchar(255) NULL','string','','经度','1','','0','','0','1','1499333213','1499135269','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('lat','维度','varchar(255) NULL','baidumap','','维度','1','','0','','0','1','1500032773','1499135321','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('pinyin','拼音','varchar(255) NULL','string','','','1','','0','','0','1','1499335873','1499335812','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_area` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '名称',
`city_id`  int(10) NULL  DEFAULT 0 COMMENT '所属市',
`orderby`  tinyint(1) NULL  DEFAULT 0 COMMENT '排序',
`is_hot`  tinyint(1)  DEFAULT 0 COMMENT '是否热门',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('area','区级','0','','1','["name","city_id","orderby"]','1:基础','','','','','','10','','','1499135510','1499335112','1','MyISAM','Address');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','名称','varchar(255) NULL','string','','名称','1','','0','','0','1','1499135534','1499135534','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('city_id','所属市','int(10) NULL','num','0','所属市','1','','0','','0','1','1499135584','1499135584','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('orderby','排序','tinyint(1) NULL','num','0','排序','1','','0','','0','1','1499135720','1499135720','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('is_hot','是否热门','tinyint(1)','num','0','','1','','0','','0','1','1499335709','1499335639','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_city` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`pid`  smallint(5) UNSIGNED  DEFAULT 0 COMMENT '所属省级',
`name`  varchar(32) NULL  COMMENT '市名称',
`sort`  tinyint(3)  DEFAULT 100 COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('city','市级','0','','1','','1:基础','','','','','','10','','','1499335154','1499335154','1','MyISAM','Address');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('pid','所属省级','smallint(5) UNSIGNED','num','0','','1','','0','','0','1','1500454398','1499335277','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','市名称','varchar(32) NULL','string','','','1','','0','','0','1','1499335389','1499335389','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('sort','排序','tinyint(3)','num','100','排序','1','','0','','0','1','1500454371','1499335504','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


