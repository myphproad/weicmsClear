DELETE FROM `bxlm_attribute` WHERE `model_name`='headline';
DELETE FROM `bxlm_model` WHERE `name`='headline' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_headline`;


DELETE FROM `bxlm_attribute` WHERE `model_name`='headline_category';
DELETE FROM `bxlm_model` WHERE `name`='headline_category' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_headline_category`;


