CREATE TABLE IF NOT EXISTS `bxlm_user_tag` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`title`  varchar(50) NULL  COMMENT '标签名称',
`token`  varchar(100) NULL  COMMENT 'token',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_tag','用户标签','0','','1','["title"]','1:基础','','','','','id:标签编号\r\ntitle:标签名称\r\nids:操作:[EDIT]|编辑,[DELETE]|删除','10','title:请输入标签名称搜索','','1463990100','1463993574','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('title','标签名称','varchar(50) NULL','string','','','1','','0','user_tag','1','1','1463990154','1463990154','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(100) NULL','string','','','0','','0','user_tag','0','1','1463990184','1463990184','','3','','regex','get_token','1','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_user_tag_link` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`uid`  int(10) NULL  COMMENT 'uid',
`tag_id`  int(10) NULL  COMMENT 'tag_id',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_tag_link','用户标签关系表','0','','1','','1:基础','','','','','','10','','','1463992911','1463992911','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','uid','int(10) NULL','num','','','0','','0','user_tag_link','0','1','1463992933','1463992933','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('tag_id','tag_id','int(10) NULL','num','','','0','','0','user_tag_link','0','1','1463992951','1463992951','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_user_collect` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id`  int(10) NULL  COMMENT '用户ID',
`about_id`  int(10) NULL  DEFAULT 0 COMMENT '所属收藏',
`status`  tinyint(2) NULL  DEFAULT 0 COMMENT '收藏状态',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`token`  varchar(255) NULL  COMMENT 'token',
`ctype`  char(50) NULL  DEFAULT 0 COMMENT '收藏类型',
`openid`  varchar(255) NULL  DEFAULT 0 COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_collect','收藏列表','0','','1','["user_id","status","about_id","ctype"]','1:基础','','','','','user_id:用户名称\r\nabout_id:收藏名称\r\nctime|time_format:创建时间\r\nctype|get_name_by_status:收藏类型\r\nid:操作:del?model=reply&id=[id]|删除','10','','','1499053778','1499678468','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','用户ID','int(10) NULL','num','','用户ID','1','','0','','0','1','1499053814','1499053814','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('about_id','所属收藏','int(10) NULL','num','0','职位ID/文章ID','1','','0','','0','1','1499072107','1499053834','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','收藏状态','tinyint(2) NULL','bool','0','收藏状态 0收藏成功 1取消收藏','1','0:收藏成功\r\n1:取消收藏','0','','0','1','1499053919','1499053919','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','datetime','0','创建时间','0','','0','','0','1','1499070469','1499053959','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1499053990','1499053978','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctype','收藏类型','char(50) NULL','select','0','0职位 1文章','1','0:职位\r\n1:文章','0','','0','1','1499140748','1499072024','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','0','openid','1','','0','','0','1','1500264948','1500264948','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_user_salary_logs` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id`  int(10) NULL  DEFAULT 0 COMMENT '所属用户',
`job_id`  int(10) NULL  DEFAULT 0 COMMENT '所属职位',
`token`  varchar(255) NULL  COMMENT 'token',
`salary`  decimal(10,2) NULL  DEFAULT 0 COMMENT '工资',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`openid`  varchar(255) NULL  COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_salary_logs','工资列表','0','','1','["user_id","job_id","salary"]','1:基础','','','','','user_id:用户\r\njob_id:职位\r\nsalary:工资\r\nctime|time_format:创建时间\r\nid:操作:del?model=reply&id=[id]|删除','10','','','1499054091','1499678415','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','所属用户','int(10) NULL','num','0','用户ID','1','','0','','0','1','1499068413','1499065984','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('job_id','所属职位','int(10) NULL','num','0','职位ID','1','table=job&value_field=id&title_field=jname','0','','0','1','1499138686','1499066050','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1499066106','1499066095','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('salary','工资','decimal(10,2) NULL','num','0','工资','1','','0','','0','1','1499066196','1499066196','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','datetime','0','创建时间','0','','0','','0','1','1499066664','1499066254','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','','','1','','0','','0','1','1500782723','1500782723','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_user_cash_logs` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id`  int(10) NULL  DEFAULT 0 COMMENT '所属用户',
`job_id`  int(10) NULL  DEFAULT 0 COMMENT '所属职位',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`token`  varchar(255) NULL  COMMENT 'token',
`money`  decimal(10,2) NULL  DEFAULT 0 COMMENT '提取工资',
`status`  char(50) NULL  DEFAULT 0 COMMENT '提取工资状态',
`openid`  varchar(255) NULL  COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_cash_logs','用户提现日志','0','','1','["user_id","job_id","money","status"]','1:基础','','','','','user_id:用户\r\njob_id:所属职位\r\nmoney:提取工资\r\nstatus|get_name_by_status:状态\r\nctime|time_format:创建时间\r\nid:操作:checkTopics?model=reply&id=[id]|审核,del?model=reply&id=[id]|删除','10','','','1499068379','1499071103','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','所属用户','int(10) NULL','num','0','用户ID','1','','0','','0','1','1499068452','1499068452','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('job_id','所属职位','int(10) NULL','num','0','职位ID','1','','0','','0','1','1499068481','1499068481','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','num','0','创建时间','0','','0','','0','1','1499068563','1499068512','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1499068542','1499068542','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('money','提取工资','decimal(10,2) NULL','num','0','提取工资','1','','0','','0','1','1499068680','1499068680','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','提取工资状态','char(50) NULL','select','0','0申请提现 1提现成功 2 提现失败','1','0:申请提现\r\n1:提现成功\r\n2:提现失败','0','','0','1','1499068982','1499068982','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','','','1','','0','','0','1','1500782747','1500782747','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_user_bond_logs` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id`  int(10) NULL  DEFAULT 0 COMMENT '所属 ',
`bond`  int(10) NULL  DEFAULT 0 COMMENT '保证金',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`status`  char(50) NULL  DEFAULT 0 COMMENT '保证金状态',
`token`  varchar(255) NULL  COMMENT 'token',
`openid`  varchar(255) NULL  COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_bond_logs','用户保证金','0','','1','["user_id","bond","status"]','1:基础','','','','','user_id:用户\r\nbond:保证金(金额)\r\nstatus|get_name_by_status:状态\r\nctime|time_format:创建时间\r\nid:操作:checkTopics?model=reply&id=[id]|审核,del?model=reply&id=[id]|删除','10','','','1499069118','1499071055','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','所属 ','int(10) NULL','num','0','用户ID','1','','0','','0','1','1499069155','1499069155','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('bond','保证金','int(10) NULL','num','0','保证金','1','','0','','0','1','1499069265','1499069265','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','num','0','创建时间','0','','0','','0','1','1499069313','1499069300','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','保证金状态','char(50) NULL','select','0','0未缴纳 1已缴纳 2:申请退保证金 3:退出保证金','1','0:未缴纳\r\n1:已缴纳\r\n2:申请退保证金\r\n3:退出保证金','0','','0','1','1499071635','1499069411','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1499677574','1499677574','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','','','1','','0','','0','1','1500782692','1500782692','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_user_message` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id`  int(10) NULL  DEFAULT 0 COMMENT '所属用户',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`type`  char(50) NULL  DEFAULT 0 COMMENT '消息类型',
`comment`  text  NULL  COMMENT '消息内容',
`token`  varchar(255) NULL  COMMENT 'token',
`status`  tinyint(2) NULL  DEFAULT 0 COMMENT '消息状态',
`openid`  varchar(255) NULL  DEFAULT 0 COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('user_message','消息通知','0','','1','["user_id","type","comment"]','1:基础','','','','','user_id:用户名称\r\ntype|get_name_by_status:消息类型\r\nstatus|get_name_by_status:消息状态\r\nctime|time_format:创建时间\r\nid:操作:del?model=reply&id=[id]|删除','10','','','1499132676','1499678510','1','MyISAM','UserCenter');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','所属用户','int(10) NULL','num','0','用户ID','1','','0','','0','1','1499133579','1499133579','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','num','0','创建时间','0','','0','','0','1','1499134044','1499134044','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('type','消息类型','char(50) NULL','select','0','0 用户站内信 1 平台通知消息','1','0:用户站内信\r\n1:平台通知消息','0','','0','1','1499134125','1499134125','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('comment','消息内容','text  NULL','editor','','消息内容','1','','0','','0','1','1499134164','1499134164','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1499134208','1499134208','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','消息状态','tinyint(2) NULL','bool','0','0未读 1已读','0','0:未读\r\n1:已读','0','','0','1','1499134558','1499134311','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','0','openid','1','','0','','0','1','1500266116','1500266116','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


