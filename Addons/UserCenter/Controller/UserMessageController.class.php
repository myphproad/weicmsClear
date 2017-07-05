<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;

class UserMessageController extends AddonsController {
	var $syc_wechat = true;
	// 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
	function _initialize() {
		$this->model = $this->getModel('UserMessage');
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}
	
	/**
	 * 显示微信用户列表数据
	 */
	public function lists() {
		$list_data = $this->_get_model_list($this->model);
		$data1 = $this->nickname();
	    foreach ($list_data['list_data'] as $key => $value) {
	    	$list_data['list_data'][$key]['user_id'] = $data1[$value['user_id']];
	    }
		$this->assign($list_data);
        $this->display();
	}
}