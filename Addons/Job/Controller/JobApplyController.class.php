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

    //审核
    public function checkTopics($model = null, $id = 0){
        $id  = I('id');
        $ids = I('ids');
        if(empty($id) && empty($ids)){
            $this->error('请勾选要通过审核的内容');
        }
        $token = get_token();
        if(is_array($ids)){
            $id = $ids;
            $id = implode(',',$id);
            $where = "token = '$token' AND id in($id)";
        }else{
            $where = "token = '$token' AND id = $id";
        }
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        $result = $Model->where( $where )->setField('status',1);
        if($result !== false){
            $this->success('审核成功');
        }else{
            $this->error('审核失败');
        }

    }
}
