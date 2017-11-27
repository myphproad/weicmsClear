<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;

class UserCashLogsController extends AddonsController {
	var $syc_wechat = true;
	// 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
	function _initialize() {
		$this->model = $this->getModel('UserCashLogs');
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}
	
	/**
	 * 显示微信用户列表数据
	 */
	public function lists() {
        $posts = I();
        $map = array();
        if(!empty($posts['name'])){
            $user_map['truename'] = array('like','%'.trim($posts['name']).'%');
            $openid = M('user')->where($user_map)->getField('openid');
            if(empty($openid))$this->error('数据为空!');
            $map['openid'] = $openid;
        }
        session('common_condition', $map);
		$list_data = $this->_get_model_list($this->model);
		/*****所属职位*******/
	    $jobTitle = $this->jobInfo('id,title','','id desc');
	    $data     = array();
	    foreach($jobTitle as $key => $value) {
	    	$data[$value['id']] = $value['title'];
	    }
	    /*****所属职位*******/

	    foreach ($list_data['list_data'] as $key => $value) {
	    	$list_data['list_data'][$key]['job_id']  = $data[$value['job_id']];
	    	$list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
	    }
        $this->assign($list_data);
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'name');
        $this->assign ( 'placeholder', '请输入用户名');
        $this->display();
	}

	//审核
    public function checkTopics($model = null, $id = 0){
        $id  = I('id');
        $ids = I('ids');
        if(empty($id) && empty($ids)){
            $this->error('请勾选要通过审核的内容');
        }
        $token = get_token();
        if(is_array($ids)){
            $id = $ids;
            $id = implode(',',$id);
            $where = "token = '$token' AND id in($id)";
        }else{
            $where = "token = '$token' AND id = $id";
        }
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        $result = $Model->where( $where )->setField('status',1);
        if($result !== false){
            $this->success('审核成功');
        }else{
            $this->error('审核失败');
        }

    }
}