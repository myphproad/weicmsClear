DELETE FROM `bxlm_attribute` WHERE `model_name`='advertisement';
DELETE FROM `bxlm_model` WHERE `name`='advertisement' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_advertisement`;


