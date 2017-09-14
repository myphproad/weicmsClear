DELETE FROM `bxlm_attribute` WHERE `model_name`='province';
DELETE FROM `bxlm_model` WHERE `name`='province' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_province`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='area';
DELETE FROM `bxlm_model` WHERE `name`='area' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_area`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='city';
DELETE FROM `bxlm_model` WHERE `name`='city' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_city`;


