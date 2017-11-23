<?php
        	
namespace Addons\Headline\Model;
use Home\Model\WeixinModel;
        	
/**
 * Headline的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Headline' ); // 获取后台插件的配置参数
		//dump($config);
	}
}
        	