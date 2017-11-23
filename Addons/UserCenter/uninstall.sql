DELETE FROM `bxlm_attribute` WHERE `model_name`='user_tag';
DELETE FROM `bxlm_model` WHERE `name`='user_tag' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_tag`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='user_tag_link';
DELETE FROM `bxlm_model` WHERE `name`='user_tag_link' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_tag_link`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='user_collect';
DELETE FROM `bxlm_model` WHERE `name`='user_collect' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_collect`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='user_salary_logs';
DELETE FROM `bxlm_model` WHERE `name`='user_salary_logs' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_salary_logs`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='user_cash_logs';
DELETE FROM `bxlm_model` WHERE `name`='user_cash_logs' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_cash_logs`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='user_bond_logs';
DELETE FROM `bxlm_model` WHERE `name`='user_bond_logs' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_bond_logs`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='user_message';
DELETE FROM `bxlm_model` WHERE `name`='user_message' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_user_message`;


