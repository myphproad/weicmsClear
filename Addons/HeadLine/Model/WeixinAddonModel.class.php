<?php
        	
namespace Addons\HeadLine\Model;
use Home\Model\WeixinModel;
        	
/**
 * HeadLine的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'HeadLine' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	