DELETE FROM `bxlm_attribute` WHERE `model_name`='job';
DELETE FROM `bxlm_model` WHERE `name`='job' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_job`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='business_info';
DELETE FROM `bxlm_model` WHERE `name`='business_info' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_business_info`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='business_nature';
DELETE FROM `bxlm_model` WHERE `name`='business_nature' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_business_nature`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='business_industry';
DELETE FROM `bxlm_model` WHERE `name`='business_industry' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_business_industry`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='job_name';
DELETE FROM `bxlm_model` WHERE `name`='job_name' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_job_name`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='job_apply';
DELETE FROM `bxlm_model` WHERE `name`='job_apply' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_job_apply`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='job_subscribe';
DELETE FROM `bxlm_model` WHERE `name`='job_subscribe' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_job_subscribe`;


