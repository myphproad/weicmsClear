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
    	$list_data = $this->_get_model_list($this->model);
        $this->assign($list_data);
        $this->display();
    }
}