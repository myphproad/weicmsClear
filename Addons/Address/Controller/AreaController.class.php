<?php

namespace Addons\Address\Controller;
use Home\Controller\AddonsController;

class AreaController extends AddonsController{

    function _initialize()
    {
        $this->model = $this->getModel('Area');
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
        $province = M('province')->where('is_open=1')->select();
        $arr = array();
        foreach($province as $key=>$value){
            $arr[$value['id']] = $value['name'];
        }
        foreach($list_data['list_data'] as $key=>$value){
            $list_data['list_data'][$key]['pid'] = $arr[$value['pid']];
        }
        $map['token']  = get_token();
        $this->assign($list_data);
        $this->display();
    }

}
