<?php
        	
namespace Addons\Address\Model;
use Home\Model\WeixinModel;
        	
/**
 * Address的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Address' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	