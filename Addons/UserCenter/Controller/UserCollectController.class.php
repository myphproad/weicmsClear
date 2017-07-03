<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;

class UserCollectController extends AddonsController {
	var $syc_wechat = true;
	// 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
	function _initialize() {
		$this->model = $this->getModel('UserCollect');
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}
	
	/**
	 * 显示微信用户列表数据
	 */
	public function lists(){
	    $list_data = $this->_get_model_list($this->model);
		$this->assign($list_data);
        $this->display();
  }
}