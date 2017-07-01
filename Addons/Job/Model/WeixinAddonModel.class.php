<?php
        	
namespace Addons\Job\Model;
use Home\Model\WeixinModel;
        	
/**
 * Job的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Job' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	