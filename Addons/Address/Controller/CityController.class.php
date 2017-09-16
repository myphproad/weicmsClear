<?php

namespace Addons\Address\Controller;
use Home\Controller\AddonsController;

class CityController extends AddonsController{

    function _initialize()
    {
        $this->model = $this->getModel('city');
        parent::_initialize();
    }
    /**
     * @author:like
     * @remark:城市站点;
     * @date:2017年9月16日
     * @returnType: Json
     */
    public function lists(){
        //ID 站点名称 站点拼音 是否开启
        $list_data = $this->_get_model_list($this->model);
        $map['token']  = get_token();
        $this->assign($list_data);
        $this->display();
    }
    public function area_list(){

    }
}
