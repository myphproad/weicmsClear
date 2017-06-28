<?php
        	
namespace Addons\Hot_News\Model;
use Home\Model\WeixinModel;
        	
/**
 * Hot_News的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Hot_News' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	