CREATE TABLE IF NOT EXISTS `bxlm_job` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`jname_id`  varchar(100) NULL  DEFAULT 0 COMMENT '所属职位',
`salary`  varchar(125)NULL  COMMENT '薪资',
`number`  int(10) NULL  COMMENT '招聘人数',
`start_time`  int(10) NULL  COMMENT '工作开始时间',
`end_time`  int(10) NULL  COMMENT '工作结束时间',
`address`  varchar(255) NULL  COMMENT '工作地址',
`pay_type`  char(50) NULL  DEFAULT 0 COMMENT '工资发放类型',
`job_type`  char(50) NULL  DEFAULT 0 COMMENT '职位类型',
`title`  text NULL  COMMENT '职位简介',
`content`  text  NULL  COMMENT '职位描述',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '职位创建时间',
`bid`  varchar(100) NULL  DEFAULT 0 COMMENT '商家信息',
`token`  varchar(255) NULL  COMMENT 'token',
`province_id`  varchar(255) NULL  COMMENT '省',
`city_id`  smallint(5) NULL  COMMENT '市',
`area_id`  smallint(5) NULL  COMMENT '区',
`longitude`  varchar(15) NULL  COMMENT '经度',
`latitude`  varchar(15) NULL  COMMENT '维度',
`is_recommend`  tinyint(2) NULL  DEFAULT 0 COMMENT '是否推荐',
`is_jp`  tinyint(2) NULL  DEFAULT 0 COMMENT '是否急聘',
`click`  int(10) NULL  DEFAULT 0 COMMENT '点击量',
`img_url`  int(10) UNSIGNED NULL  COMMENT '缩略图',
`font_icon`  varchar(255) NULL  COMMENT '图标字体',
`work_time_type`  char(50) NULL  DEFAULT 0 COMMENT '工作时间类型',
`status`  tinyint(2) NULL  DEFAULT 1 COMMENT '是否有效 ',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('job','职位表','0','','1','["salary","number","start_time","end_time","address","pay_type","job_type","title","content","bid","jname_id","province_id","city_id","area_id","longitude","latitude","is_recommend","is_jp","img_url","font_icon","work_time_type"]','1:基础','','','','','jname_id:职位名称\r\nsalary:薪资\r\nnumber:招聘人数\r\nstart_time|time_format:工作开始时间\r\nend_time|time_format:工作结束时间\r\naddress:地址\r\npay_type:工资发放类型\r\njob_type:职位类型\r\nid:20%操作:[EDIT]|编辑,[DELETE]|删除','10','','','1498879041','1499678954','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('jname_id','所属职位','varchar(100) NULL','dynamic_select','0','职位名称','1','table=job_name&value_field=id&title_field=name','0','','0','1','1499327071','1498879081','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('salary','薪资','varchar(125)NULL','string','','','1','','0','','0','1','1498888837','1498879755','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('number','招聘人数','int(10) NULL','num','','招聘人数','1','','0','','0','1','1498879854','1498879854','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('start_time','工作开始时间','int(10) NULL','date','','工作开始时间','1','','0','','0','1','1498880204','1498880204','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('end_time','工作结束时间','int(10) NULL','datetime','','工作结束时间','1','','0','','0','1','1498881521','1498881521','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('address','工作地址','varchar(255) NULL','string','','工作地址','1','','0','','0','1','1498882053','1498881703','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('pay_type','工资发放类型','char(50) NULL','select','0','工作发放类型 0 日结 1周结 2月结 3项目结','1','0:日结\r\n1:周结\r\n2:月结\r\n3:项目结','0','','0','1','1498888362','1498882233','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('job_type','职位类型','char(50) NULL','select','0','职位类型 0日常兼职 1假期实践 2自主学习 3就业安置','1','0:日常兼职\r\n1:假期实践\r\n2:自主学习\r\n3:就业安置','0','','0','1','1498888351','1498882359','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('title','职位简介','text NULL','textarea','','职位简介','1','','0','','0','1','1498890677','1498888506','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('content','职位描述','text  NULL','editor','','职位描述','1','','0','','0','1','1498888576','1498888576','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','职位创建时间','int(10) NULL','datetime','0','','0','','0','','0','1','1498888627','1498888613','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('bid','商家信息','varchar(100) NULL','dynamic_select','0','商家ID','1','table=business_info&value_field=id&title_field=company_name','0','','0','1','1498902394','1498900202','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1498902423','1498902423','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('province_id','省','varchar(255) NULL','cascade','','省','1','type=db&table=province&module=city&value_field=id&custom_field=id,name&pid=0','0','','1','1','1500454188','1499047265','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('city_id','市','smallint(5) NULL','num','','市','1','','0','','1','1','1500363200','1499047302','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('area_id','区','smallint(5) NULL','num','','区','1','','0','','1','1','1499047638','1499047622','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('longitude','经度','varchar(15) NULL','string','','经度','1','','0','','0','1','1499047856','1499047856','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('latitude','维度','varchar(15) NULL','baidumap','','维度','1','','0','','0','1','1500033176','1499047890','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('is_recommend','是否推荐','tinyint(2) NULL','bool','0','是否推荐 1是 0 否','1','0:否\r\n1:是','0','','0','1','1499131261','1499131261','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('is_jp','是否急聘','tinyint(2) NULL','bool','0','是否急聘 1是 0否','1','0:否\r\n1:是','0','','0','1','1499131565','1499131565','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('click','点击量','int(10) NULL','num','0','点击量','0','','0','','0','1','1499131638','1499131638','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('img_url','缩略图','int(10) UNSIGNED NULL','picture','','缩略图','1','','0','','0','1','1499132294','1499132294','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('font_icon','图标字体','varchar(255) NULL','string','','图标字体','1','','0','','0','1','1499132337','1499132337','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('work_time_type','工作时间类型','char(50) NULL','select','0','0:每天 1:周末 2:工作日 3:暑假 4:寒假 5:其他','1','0:每天\r\n1:周末\r\n2:工作日\r\n3:暑假\r\n4:寒假\r\n5:其他','0','','0','1','1499326902','1499326902','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','是否有效 ','tinyint(2) NULL','bool','1','1是 0否','0','','0','','0','1','1499389031','1499388984','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_business_info` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '商家姓名',
`company_name`  varchar(255) NULL  COMMENT '公司名称',
`phone`  varchar(255) NULL  COMMENT '商家联系方式',
`scale`  char(50) NULL  DEFAULT 0 COMMENT '公司规模',
`nature_id`  int(10)   DEFAULT 0 COMMENT '公司性质',
`industry_id`  int(10)   DEFAULT 0 COMMENT '公司行业',
`introduction`  text  NULL  COMMENT '公司简介',
`token`  varchar(255) NULL  COMMENT 'token',
`address`  varchar(255) NULL  COMMENT '公司地址',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('business_info','商家信息表','0','','1','["name","address","company_name","phone","scale","introduction","nature_id","industry_id"]','1:基础','','','','','company_name:公司名称\r\nscale:公司规模\r\nnature_id:公司性质\r\nindustry_id:公司行业\r\naddress:公司地址\r\nid:20%操作:[EDIT]|编辑,[DELETE]|删除','10','','','1498891164','1500360859','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','商家姓名','varchar(255) NULL','string','','商家姓名','1','','0','','0','1','1498892386','1498892386','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('company_name','公司名称','varchar(255) NULL','string','','公司名称','1','','0','','1','1','1498893661','1498892441','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('phone','商家联系方式','varchar(255) NULL','string','','商家联系方式','1','','0','','0','1','1498893788','1498893788','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('scale','公司规模','char(50) NULL','select','0','公司规模 0:1-20人 1:20-50人 2:50-100人 3:100-500人 4:500以上','1','0:1-20人\r\n1:20-50人\r\n2:50-100人\r\n3:100-500人\r\n4:500以上','0','','1','1','1498894029','1498893972','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('nature_id','公司性质','int(10) ','dynamic_select','0','公司性质','1','table=business_nature&value_field=id&title_field=name','0','','1','1','1499314096','1498894389','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('industry_id','公司行业','int(10) ','dynamic_select','0','公司行业','1','table=business_industry&value_field=id&title_field=name','0','','1','1','1500362011','1498895761','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('introduction','公司简介','text  NULL','editor','','公司简介','1','','0','','1','1','1498896255','1498896255','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1498902371','1498902332','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('address','公司地址','varchar(255) NULL','string','','地址','1','','0','','1','1','1500360918','1500360161','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_business_nature` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '公司性质名称',
`token`  varchar(255) NULL  COMMENT 'token',
`sort_order`  int(10) NOT NULL  DEFAULT 0 COMMENT '排序',
`status`  tinyint(1) NOT NULL  DEFAULT 1 COMMENT '是否有效 1是0否',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('business_nature','公司性质表','0','','1','["name","sort_order","status"]','1:基础','','','','','name:公司性质\r\nstatus|get_name_by_status:是否有效\r\nid:10%操作:[EDIT]|编辑,del?model=comment&id=[id]|删除','10','','','1498894525','1499679181','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','公司性质名称','varchar(255) NULL','string','','公司性质名称','1','','0','','1','1','1498894603','1498894603','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1498894700','1498894700','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('sort_order','排序','int(10) NOT NULL','num','0','值越大越靠前','1','','0','','0','1','1499312347','1499312347','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','是否有效 1是0否','tinyint(1) NOT NULL','bool','1','','1','1:有效\r\n0:无效','0','','0','1','1499668792','1499668295','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_business_industry` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '行业名字',
`token`  varchar(255) NULL  COMMENT 'token',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('business_industry','公司行业','0','','1','["name"]','1:基础','','','','','name:行业名称\r\nid:10%操作:[EDIT]|编辑,del?model=comment&id=[id]|删除','10','','','1498895393','1499679195','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','行业名字','varchar(255) NULL','string','','行业名字','1','','0','','1','1','1498896908','1498895419','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1498895596','1498895445','','3','','regex','get_token','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_job_name` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '职位名称',
`token`  varchar(255) NULL  COMMENT 'token',
`sort_order`  int(10) NOT NULL  DEFAULT 0 COMMENT '排序',
`status`  tinyint(2) NULL  DEFAULT 1 COMMENT '是否有效',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('job_name','职位名称','0','','1','["name","sort_order","status"]','1:基础','','','','','name:职位名称\r\nstatus|get_name_by_status:是否有效\r\nid:10%操作:[EDIT]|编辑,del?model=comment&id=[id]|删除','10','','','1498899470','1499679441','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','职位名称','varchar(255) NULL','string','','职位名称','1','','0','','1','1','1498899496','1498899496','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1498899715','1498899522','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('sort_order','排序','int(10) NOT NULL','num','0','值越大越靠前','1','','0','','0','1','1499311985','1499311985','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','是否有效','tinyint(2) NULL','bool','1','1是 0否','1','1:有效\r\n0:无效','0','','0','1','1499679472','1499679371','','3','','regex','','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_job_apply` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id`  int(10) NULL  COMMENT '用户ID',
`job_id`  int(10) NULL  COMMENT '职位ID',
`status`  char(50) NULL  DEFAULT 0 COMMENT '申请状态',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '申请时间',
`token`  varchar(255) NULL  DEFAULT -1 COMMENT 'token',
`openid`  varchar(255) NULL  DEFAULT 0 COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('job_apply','用户申请表','0','','1','["user_id","job_id","status"]','1:基础','','','','','user_id:用户名称\r\njob_id:职位名称\r\nstatus|get_name_by_status:申请状态\r\nctime|time_format:创建时间\r\nid:操作:[EDIT]|编辑,checkTopics?model=reply&id=[id]|审核,del?model=reply&id=[id]|删除','10','','','1499052185','1499679825','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','用户ID','int(10) NULL','num','','用户ID','1','','0','','0','1','1499052462','1499052462','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('job_id','职位ID','int(10) NULL','num','','职位ID','1','','0','','0','1','1499052504','1499052504','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('status','申请状态','char(50) NULL','select','0','0审核中 1申请通过 2申请不通过','1','0:审核中\r\n1:申请通过\r\n2:申请不通过','0','','0','1','1499074284','1499052647','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','申请时间','int(10) NULL','datetime','0','申请时间','0','','0','','0','1','1499074308','1499052686','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','-1','token','0','','0','','0','1','1499679914','1499679914','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','0','openid','1','','0','','0','1','1500265544','1500265533','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


CREATE TABLE IF NOT EXISTS `bxlm_job_subscribe` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`job_type`  varchar(100) NULL  COMMENT '职位类型（职位名称）',
`work_time_type`  varchar(100) NULL  DEFAULT 0 COMMENT '工作时间类型',
`area_id`  varchar(100) NULL  COMMENT '工作地点',
`user_id`  int(10) NULL  DEFAULT 0 COMMENT '所属用户',
`ctime`  int(10) NULL  DEFAULT 0 COMMENT '创建时间',
`token`  varchar(255) NULL  COMMENT 'token',
`openid`  varchar(255) NULL  COMMENT 'openid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `bxlm_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('job_subscribe','职位预约','0','','1','["job_type","work_time_type","area_id","user_id"]','1:基础','','','','','user_id:所属用户\r\njob_type:职位类型（职位名称）\r\nwork_time_type:时间类型\r\narea_id:工作地点\r\nctime|time_format:创建时间\r\nid:操作:checkTopics?model=reply&id=[id]|审核,del?model=reply&id=[id]|删除','10','','','1499404805','1499671474','1','MyISAM','Job');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('job_type','职位类型（职位名称）','varchar(100) NULL','checkbox','','职位类型','1','table=job_name&value_field=id&title_field=name','0','','0','1','1500281442','1499405558','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('work_time_type','工作时间类型','varchar(100) NULL','checkbox','0','','1','0:每天\r\n1:周末\r\n2:工作日\r\n3:暑假\r\n4:寒假\r\n5:其他','0','','0','1','1499405665','1499405665','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('area_id','工作地点','varchar(100) NULL','checkbox','','','1','','0','','0','1','1499406628','1499405765','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('user_id','所属用户','int(10) NULL','num','0','','1','','0','','0','1','1499406784','1499406784','','3','','regex','','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','num','0','','0','','0','','0','1','1499406866','1499406866','','3','','regex','time','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1499409693','1499409693','','3','','regex','get_token','3','function');
INSERT INTO `bxlm_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('openid','openid','varchar(255) NULL','string','','','1','','0','','0','1','1500782927','1500782927','','3','','regex','get_openid','3','function');
UPDATE `bxlm_attribute` a, bxlm_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;


