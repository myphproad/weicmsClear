<?php
        	
namespace Addons\work\Model;
use Home\Model\WeixinModel;
        	
/**
 * work的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'work' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	