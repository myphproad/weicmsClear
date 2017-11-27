<?php

namespace Addons\UserCenter\Controller;

use Home\Controller\AddonsController;

//用户收藏
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
	    $posts = I('');

        //下拉选择-数据分配
        $ctype_data=array(
            array(
                'id'=>0,
                'title'=>'职位'
            ),
            array(
                'id'=>1,
                'title'=>'文章'
            ),
        );
        $this->assign('ctype_data', $ctype_data);
	    $map = array();
	    //用户名搜索
	    if(!empty($posts['nickname'])){
	        $user_map['nickname'] = array('like','%'.trim($posts['nickname']).'%');
	        $user_id = M('user')->where($user_map)->getField('uid');
	        if(empty($user_id)) $this->error('数据为空!');
            $this->assign('nickname', $posts['nickname']);
	        $map['user_id'] = $user_id;
        }
        //职位类型搜索
        if(is_numeric(I('ctype')) && I('ctype')!=110){
	        $map['ctype'] = intval($posts['ctype']);
            $this->assign('ctype', intval($posts['ctype']));
        }
        session('common_condition', $map);
	    $list_data = $this->_get_model_list($this->model);

		$map['token']  = get_token();
	    /*****所属职位*******/
	    $jobTitle = $this->jobInfo('id,title','','id desc');
	    $data = array();
	    foreach ($jobTitle as $key => $value) {
	    	$data[$value['id']] = $value['title'];
	    }
	    /*****所属职位*******/

	    /*****文章名称*******/
	    $heandlineName = M('headline')->field('id,title')->select();

	    $arr = array();
	    foreach ($heandlineName as $key => $value) {
	    	$arr[$value['id']] = $value['title'];
	    }
	    /*****文章名称*******/

	    foreach ($list_data['list_data'] as $key => $value) {
	    	if(1 == $value['ctype']){
	    		//文章
	    		$list_data['list_data'][$key]['about_id'] = $arr[$value['about_id']];
	    	}else{
	    		//职位
	    		$list_data['list_data'][$key]['about_id'] = $data[$value['about_id']];
	    	}
	    	$list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
	    }
		//dump($list_data);die();
		$this->assign($list_data);
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'nickname');
        $this->assign ( 'placeholder', '请输入用户名');
        $this->display();
  }

   
}