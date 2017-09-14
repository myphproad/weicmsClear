<?php

namespace Addons\Job\Controller;
use Home\Controller\AddonsController;

class JobController extends AddonsController{
	var $model;
	 function _initialize()
    {
        $this->model = $this->getModel('Job');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);
    	
    	$map['token']  = get_token();
    	/***职位名称*****/
    	$jobName = M('JobName')->where($map)->field('id,name')->select();
    	$data    = array();
    	foreach ($jobName as $key => $value) {
    		$data[$value['id']] = $value['name'];
    	}
        
    	/***职位名称*****/
    	foreach ($list_data['list_data'] as $key => $value) {
    		$list_data['list_data'][$key]['jname_id'] = $data[$value['jname_id']];//商家名称
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
        $this->assign($list_data);
        $this->display();
    }

	// 通用插件的增加模型

	public function add() {

		$model = $this->model;
		$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
		if (IS_POST) {
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $id = $Model->add ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				$this->success ( '添加' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			$this->assign ( 'fields', $fields );
			$this->meta_title = '新增' . $model ['title'];
			$this->display ();

		}

	}


}
