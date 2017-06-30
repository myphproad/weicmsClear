<?php
        	
namespace Addons\Tag\Model;
use Home\Model\WeixinModel;
        	
/**
 * Tag的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Tag' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	