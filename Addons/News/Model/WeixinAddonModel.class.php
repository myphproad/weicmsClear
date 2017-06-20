<?php
        	
namespace Addons\News\Model;
use Home\Model\WeixinModel;
        	
/**
 * News的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'News' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	