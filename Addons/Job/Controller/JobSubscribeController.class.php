<?php

namespace Addons\Job\Controller;
use Home\Controller\AddonsController;

class JobSubscribeController extends AddonsController{

	 function _initialize()
    {
        $this->model = $this->getModel('JobSubscribe');
        parent::_initialize();
    }

    //用户预约列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);

        foreach($list_data['list_data'] as $key=>$value){
            $list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
            $list_data['list_data'][$key]['job_type'] = get_about_name($value['user_id'],'job_name');
            $list_data['list_data'][$key]['area_id'] = get_about_name($value['area_id'],'area');
            if(0 == $value['work_time_type']){
                $list_data['list_data'][$key]['work_time_type'] = '每天';
            }elseif(1 == $value['work_time_type']){
                $list_data['list_data'][$key]['work_time_type'] = '周末';
            }elseif(2 == $value['work_time_type']){
                $list_data['list_data'][$key]['work_time_type'] = '工作日';
            }elseif(3 == $value['work_time_type']){
                $list_data['list_data'][$key]['work_time_type'] = '暑假';
            }elseif(4 == $value['work_time_type']){
                $list_data['list_data'][$key]['work_time_type'] = '寒假';
            }else{
                $list_data['list_data'][$key]['work_time_type'] = '其他';
            }

        }
      //  dump($list_data);
        $this->assign($list_data);
        $this->display();
    }

}
