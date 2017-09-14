<?php
        	
namespace Addons\University\Model;
use Home\Model\WeixinModel;
        	
/**
 * University的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'University' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	