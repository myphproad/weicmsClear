<?php
        	
namespace Addons\News_user\Model;
use Home\Model\WeixinModel;
        	
/**
 * News_user的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'News_user' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	