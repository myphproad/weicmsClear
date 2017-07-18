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

		public function add($model = null)
		{

			is_array($model) || $model = $this->model;

			$Model = D(parse_name(get_table_name($model ['id']), 1));


			if (IS_POST) {

				// 获取模型的字段信息

				$Model = $this->checkAttr($Model, $model ['id']);

				if ($Model->create() && $id = $Model->add()) {

					$this->success('添加' . $model ['title'] . '成功！', U('lists?model=' . $model ['name'], $this->get_param));

				} else {

					$this->error($Model->getError());

				}

			} else {

				// 要先填写appid

				$map ['token'] = get_token();


				// 获取一级菜单

				$map ['pid'] = 0;

				$list = $Model->where($map)->select();

				foreach ($list as $v) {

					$extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";

				}


				$fields = get_model_attribute($model ['id']);

				if (!empty ($extra)) {

					foreach ($fields as &$vo) {

						if ($vo ['name'] == 'pid') {

							$vo ['extra'] .= "\r\n" . $extra;

						}

					}

				}
				$this->assign('fields', $fields);
				//dump($fields);die();

				$this->meta_title = '新增' . $model ['title'];


				$this->display();

			}
	}

}
