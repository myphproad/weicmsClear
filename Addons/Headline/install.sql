CREATE TABLE IF NOT EXISTS `bxlm_headline` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`title`  varchar(255)NULL  COMMENT '头条标题',
`tag_id`  varchar(100) NULL  COMMENT '标签ID',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`status`  tinyint(2) NULL  DEFAULT 1 COMMENT '状态',
`img_url`  int(10) UNSIGNED NULL  COMMENT '缩略图',
`comment`  text  NULL  COMMENT '头条内容',
`category_id`  varchar(100) NULL  COMMENT '所属分类',
`token`  varchar(255) NULL  COMMENT 'token',
`click`  int(10) NULL  DEFAULT 0 COMMENT '点击量',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('headline','头条信息','0','','1','["title","tag_id","status","img_url","content","category_id"]','1:基础','','','','','title:标题\r\ncategory_id:所属分类\r\nstatus|get_name_by_status:状态\r\nctime|time_format:新建时间\r\nids:操作:[EDIT]|编辑,[DELETE]|删除','10','','','1498803793','1499678630','1','MyISAM','Headline');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('title','头条标题','varchar(255)NULL','string','','','1','','0','','1','1','1498804225','1498803828','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('tag_id','标签ID','varchar(100) NULL','dynamic_checkbox','','标签ID','1','table=tag&value_field=id&title_field=tname','0','','1','1','1498805888','1498803893','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','datetime','0','创建时间','0','','0','','0','1','1499135901','1498803963','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','状态','tinyint(2) NULL','bool','1','状态','1','0:隐藏\r\n1:显示','0','','1','1','1498888337','1498804081','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('img_url','缩略图','int(10) UNSIGNED NULL','picture','','图片地址','1','','0','','0','1','1498886560','1498804125','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('comment','头条内容','text  NULL','editor','','头条内容','1','','0','','1','1','1500013942','1498804158','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('category_id','所属分类','varchar(100) NULL','dynamic_select','','','1','table=headline_category&value_field=id&title_field=name','0','','0','1','1498889577','1498886322','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','','0','','0','','1','1','1498888956','1498888824','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('click','点击量','int(10) NULL','num','0','点击量','0','','0','','0','1','1499131694','1499131694','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_headline_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '分类名称',
`intro`  varchar(255) NULL  COMMENT '分类描述',
`is_show`  tinyint(2) NULL  COMMENT '是否显示',
`sort_order`  int(10) NULL  COMMENT '排序',
`token`  varchar(255) NULL  COMMENT 'token',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('headline_category','头条分类','0','','1','["intro","is_show","sort_order","name"]','1:基础','','','','','name:名称\r\nintro:描述\r\nis_show|get_name_by_status:显示\r\nsort_order:排序\r\nid:20%操作:[EDIT]|编辑,[DELETE]|删除','10','','','1498817070','1499678581','1','MyISAM','Headline');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','分类名称','varchar(255) NULL','string','','分类名称','1','','0','','0','1','1498889227','1498817533','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('intro','分类描述','varchar(255) NULL','string','','分类描述','1','','0','','0','1','1498817628','1498817628','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('is_show','是否显示','tinyint(2) NULL','bool','','是否显示','1','0:不显示\r\n1:显示','0','','0','1','1498817707','1498817707','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('sort_order','排序','int(10) NULL','num','','数字越小越靠前','1','','0','','0','1','1498877045','1498877045','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','','0','','0','','1','1','1498889287','1498889287','','3','','regex','get_token','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


