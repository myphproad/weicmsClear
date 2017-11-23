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
        $name = trim(I('get.title'));
        $page = I ( 'p', 1, 'intval' );
        $row  = 10;
        if(!empty($name)){
            $map['name'] = $name;
            $data  = M('city')->where($map)->page($page,$row)->select();
            $count = M('city')->where ($map)->count();
        }else{
            $data = M('city')->where('1=1')->page($page,$row)->select();
            $count = M('city')->where ('1=1')->count();
        }
        $list_data['list_data'] = $data;
        // 分页
        unset($list_data['_page']);
        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $list_data['_page'] = $page->show ();
        }
        $province = M('province')->where('1=1')->select();
        $arr = array();
        foreach($province as $key=>$value){
            $arr[$value['id']] = $value['name'];
        }
        foreach($list_data['list_data'] as $key=>$value){
            $list_data['list_data'][$key]['pid'] = $arr[$value['pid']];
        }
        $this->assign($list_data);
        $this->display();
    }
}
