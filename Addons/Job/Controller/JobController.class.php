<?php

namespace Addons\Job\Controller;
use Home\Controller\AddonsController;

class JobController extends AddonsController{

	 function _initialize()
    {
        $this->model = $this->getModel('Job');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);
    	
    	$map['token']  = get_token();
    	/***商家名称*****/ 
    	$jobName = M('JobName')->where($map)->field('id,name')->select();
     
    	$data    = array();
    	foreach ($jobName as $key => $value) {
    		$data[$value['id']] = $value['name'];
    	}
       // dump($data);
        
    	/***商家名称*****/ 

    	foreach ($list_data['list_data'] as $key => $value) {
            //dump($data[$value['id']]);
    		$list_data['list_data'][$key]['jname'] = $data[$value['jname']];//商家名称
    		
			if(0 == $value['pay_type']){
				$list_data['list_data'][$key]['pay_type'] = '日结';
			}elseif(1 == $value['pay_type']){
				$list_data['list_data'][$key]['pay_type'] = '周结';
			}elseif(2 == $value['pay_type']){
				$list_data['list_data'][$key]['pay_type'] = '月结';
			}elseif(3 == $value['pay_type']){
				$list_data['list_data'][$key]['pay_type'] = '项目结';//工资发放类型
			}

			if(0 == $value['job_type']){
				$list_data['list_data'][$key]['job_type'] = '日常兼职';
			}elseif(1 == $value['job_type']){
				$list_data['list_data'][$key]['job_type'] = '假期实践';
			}elseif(2 == $value['job_type']){
				$list_data['list_data'][$key]['job_type'] = '自主学习';
			}elseif(3 == $value['job_type']){
				$list_data['list_data'][$key]['job_type'] = '就业安置';//职位类型
			}

    	}
    	//dump($list_data);die();
        $this->assign($list_data);
        $this->display();
    }

}
