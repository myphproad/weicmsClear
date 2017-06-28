<?php
        	
namespace Addons\Job_list\Model;
use Home\Model\WeixinModel;
        	
/**
 * Job_list的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Job_list' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	