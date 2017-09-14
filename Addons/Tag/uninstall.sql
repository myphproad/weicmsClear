DELETE FROM `bxlm_attribute` WHERE `model_name`='tag';
DELETE FROM `bxlm_model` WHERE `name`='tag' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `bxlm_tag`;


