<?php

namespace Addons\Job\Controller;
use Home\Controller\AddonsController;

class JobApplyController extends AddonsController{
	 function _initialize()
    {
        $this->model = $this->getModel('JobApply');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);
        $jobTitle  = $this->jobInfo('id,title','','id desc');
        $data = array();
        foreach ($jobTitle as $key => $value) {
            $data[$value['id']] = $value['title'];
        }
        foreach ($list_data['list_data'] as $key => $value) {
            $list_data['list_data'][$key]['user_id'] = get_nickname($value['user_id']);
            $list_data['list_data'][$key]['job_id']  = $data[$value['job_id']];
        }
        $this->assign($list_data);
        $this->display();
    }
}
