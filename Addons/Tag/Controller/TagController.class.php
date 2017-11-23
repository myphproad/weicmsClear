<?php

namespace Addons\Tag\Controller;
use Home\Controller\AddonsController;

class TagController extends AddonsController{
    function _initialize()
    {
        $this->model = $this->getModel('Tag');
        parent::_initialize();
    }

    //标签列表
    public function lists(){
        $list_data = $this->_get_model_list($this->model);

        $this->assign($list_data);
        $this->display();
    }

    //修改标签
    public function save(){
        echo 'like';
    }
}
