<?php

namespace Addons\Job\Controller;

use Home\Controller\AddonsController;

class BusinessInfoController extends AddonsController{
	var $model;

    function _initialize()
    {
        $this->model = $this->getModel('BusinessInfo');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);

    	/****商家性质******/
    	$map ['token']  = get_token ();
    	$BusinessNature = M('BusinessNature')->where($map)->field('id,name')->select();
    	$data = array();
    	foreach ($BusinessNature as $key => $value) {
    		$data[$value['id']] = $value['name'];
    	}
    	/****商家性质******/
       /*****行业类型******/
    	$BusinessIndustry = M('BusinessIndustry')->where($map)->field('id,name')->select();
    	$arr = array();
    	foreach ($BusinessIndustry as $key => $value) {
    		$arr[$value['id']] = $value['name'];
    	}
    	/*****行业类型******/
    	foreach ($list_data['list_data'] as $key => $value) {
    		$list_data['list_data'][$key]['nature']   = $data[$value['nature']];//公司性质
    		$list_data['list_data'][$key]['industry'] = $arr[$value['industry']];//公司行业
    	
			if($value['scale'] == 0){
				$list_data['list_data'][$key]['scale']   = '1-20人';
			}elseif($value['scale'] == 1){
				$list_data['list_data'][$key]['scale']   = '20-50人';
			}elseif($value['scale'] == 2){
				$list_data['list_data'][$key]['scale']   = '50-100人';
			}elseif($value['scale'] == 3){
				$list_data['list_data'][$key]['scale']   = '100-500人';
			}elseif($value['scale'] == 4){
				$list_data['list_data'][$key]['scale']   = '500以上';//公司规模
			}

    	}
    	
    	//dump($list_data);die();
        $this->assign($list_data);
        $this->display();
    }
}