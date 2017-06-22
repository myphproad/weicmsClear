<?php
        	
namespace Addons\Appointment\Model;
use Home\Model\WeixinModel;
        	
/**
 * Appointment的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Appointment' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	