<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;

class UserBondLogsController extends AddonsController {
	var $syc_wechat = true;
	// 是否需要与微信端同步，目前只有认证的订阅号和认证的服务号可以同步
	function _initialize() {
		$this->model = $this->getModel('UserBondLogs');
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}
	
	/**
	 * 显示微信用户列表数据
	 */
	public function lists() {
	    $posts = I();
	    $map = array();
	    //用户名搜索
        if(!empty($posts['nickname'])){
            $user_map['nickname'] = array('like','%'.trim($posts['nickname']).'%');
            $user_id = M('user')->where($user_map)->getField('uid');
            if(empty($user_id)) $this->error('数据为空!');
            $this->assign('nickname', $posts['nickname']);
            $map['user_id'] = $user_id;
        }
        //时间搜索
        if(!empty($posts['start_time']) && !empty($posts['end_time'])){
            $start_time = strtotime($posts['start_time']);
            $end_time   = strtotime($posts['end_time']);
            $map['ctime'][] = array('EGT',$start_time);
            $map['ctime'][] = array('ELT',$end_time);
            $this->assign('start_time', $posts['start_time']);
            $this->assign('end_time', $posts['end_time']);
        }elseif(!empty($posts['start_time']) && empty($posts['end_time'])){
            $start_time = strtotime($posts['start_time']);
            $end_time   = time();
            $map['ctime'][] = array('EGT',$start_time);
            $map['ctime'][] = array('ELT',$end_time);
            $this->assign('start_time', $posts['start_time']);
        }
        session('common_condition', $map);
		$list_data = $this->_get_model_list($this->model);

	    foreach ($list_data['list_data'] as $key => $value) {
	    	$list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
	    }
		$this->assign($list_data);
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'nickname');
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