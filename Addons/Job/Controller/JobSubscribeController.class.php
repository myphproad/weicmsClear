<?php

namespace Addons\Job\Controller;
use Home\Controller\AddonsController;

class JobSubscribeController extends AddonsController{

	 function _initialize()
    {
        $this->model = $this->getModel('JobSubscribe');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);
        //dump($list_data);
       
        $this->assign($list_data);
        $this->display();
    }
}
