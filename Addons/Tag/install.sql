CREATE TABLE IF NOT EXISTS `bxlm_tag` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`tname`  varchar(255) NULL  COMMENT '标签名称',
`token`  varchar(255) NULL  COMMENT 'token',
`status`  tinyint(2) NULL  DEFAULT 1 COMMENT '是否有效',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('tag','标签表','0','','1','["tname","status"]','1:基础','','','','','tname:标签名称\r\nstatus|get_name_by_status:是否有效\r\nid:操作:[EDIT]&id=[id]|编辑,del?model=reply&id=[id]|删除','10','','','1498804789','1499675752','1','MyISAM','Tag');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('tname','标签名称','varchar(255) NULL','string','','标签名称','1','','0','','1','1','1498804866','1498804866','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1498804924','1498804924','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','是否有效','tinyint(2) NULL','bool','1','1有效 0无效','1','1:有效\r\n0:无效','0','','0','1','1499673525','1499673525','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


