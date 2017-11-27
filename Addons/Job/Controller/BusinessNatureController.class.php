<?php

namespace Addons\Job\Controller;

use Home\Controller\AddonsController;

class BusinessNatureController extends AddonsController{
	var $model;

    function _initialize()
    {
        $this->model = $this->getModel('BusinessNature');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
        $posts = I('');
        $map = array();
        //行业名称搜索
        if(!empty($posts['title'])){
            $map['name'] = array('like','%'.trim($posts['title']).'%');
        }
        session('common_condition', $map);
    	$list_data = $this->_get_model_list($this->model);
        $this->assign($list_data);
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'title');
        $this->assign ( 'placeholder', '请输入公司性质');
        $this->display();
    }
}