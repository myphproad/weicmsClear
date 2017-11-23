<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;


//用户工资
class UserSalaryLogsController extends AddonsController {
	var $syc_wechat = true;
	// 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
	function _initialize() {
		$this->model = $this->getModel('UserSalaryLogs');
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}
	
	/**
	 * 显示微信用户列表数据
	 */
	public function lists() {
		$list_data = $this->_get_model_list($this->model);

		/*****所属职位*******/
	    $jobTitle = $this->jobInfo('id,title','','id desc');
		//dump($jobTitle);
	    $data     = array();
	    foreach($jobTitle as $key => $value) {
	    	$data[$value['id']] = $value['title'];
	    }
	    /*****所属职位*******/
	    foreach ($list_data['list_data'] as $key => $value) {
			//dump($value);
	    	$list_data['list_data'][$key]['job_id']  = $data[$value['job_id']];
	    	$list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
	    }//die();
		$this->assign($list_data);
        $this->display();
	}
}