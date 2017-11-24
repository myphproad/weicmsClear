<?php

namespace Addons\Job\Controller;

use Addons\Address\Model\AddressModel;
use Home\Controller\AddonsController;

class JobController extends AddonsController
{
    var $model;

    function _initialize()
    {
        $this->model = $this->getModel('Job');
        parent::_initialize();
    }

    //商家列表
    public function lists()
    {

        // 搜索条件
        $map=array();//公共搜索条件
        if(is_numeric(I('category_id'))&& I('category_id')!=110){
            $category_id         = I('category_id');
            $map['job_type'] = $category_id;

            $this->assign('category_id', $category_id);
        }else{
            $this->assign('category_id', 110);
        }

        //下拉选择-数据分配
        $category_data=array(
            array(
                'id'=>0,
                'title'=>'日常兼职'
            ),
            array(
                'id'=>1,
                'title'=>'假期实践'
            ),
            array(
                'id'=>2,
                'title'=>'自主学习'
            ),
            array(
                'id'=>3,
                'title'=>'就业安置'
            )
        );

        $this->assign('category_data', $category_data);
        session ( 'common_condition', $map );//session传递 只需要$map传递参数即可

        $list_data = $this->_get_model_list($this->model);

        $map['token'] = get_token();

        /***职位名称*****/
        $jobName = M('JobName')->where($map)->field('id,name')->select();
        $data = array();
        foreach ($jobName as $key => $value) {
            $data[$value['id']] = $value['name'];
        }

        /***职位名称*****/
        foreach ($list_data['list_data'] as $key => $value) {
            $list_data['list_data'][$key]['jname_id'] = $data[$value['jname_id']];//商家名称
            if (0 == $value['pay_type']) {
                $list_data['list_data'][$key]['pay_type'] = '日结';
            } elseif (1 == $value['pay_type']) {
                $list_data['list_data'][$key]['pay_type'] = '周结';
            } elseif (2 == $value['pay_type']) {
                $list_data['list_data'][$key]['pay_type'] = '月结';
            } elseif (3 == $value['pay_type']) {
                $list_data['list_data'][$key]['pay_type'] = '项目结';//工资发放类型
            }

            if (0 == $value['job_type']) {
                $list_data['list_data'][$key]['job_type'] = '日常兼职';
            } elseif (1 == $value['job_type']) {
                $list_data['list_data'][$key]['job_type'] = '假期实践';
            } elseif (2 == $value['job_type']) {
                $list_data['list_data'][$key]['job_type'] = '自主学习';
            } elseif (3 == $value['job_type']) {
                $list_data['list_data'][$key]['job_type'] = '就业安置';//职位类型
            }

        }
        $this->assign($list_data);
        $this->display();
    }

    // 通用插件的增加模型

    public function add()
    {
        $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        if (IS_POST) {
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model ['id']);
            if ($Model->create() && $id = $Model->add()) {
                D('Common/Keyword')->set($_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news');
                $this->success('添加' . $model ['title'] . '成功！', U('lists?model=' . $model ['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $address_model = new AddressModel();
            $province_data = $address_model->get_area_list();
            $this->assign('province_data', $province_data['data']);

            $fields = get_model_attribute($model ['id']);
            $this->assign('fields', $fields);
            $this->meta_title = '新增' . $model ['title'];
            $this->display();

        }

    }

    // 通用插件的编辑模型
    public function edit($model = null, $id = 0)
    {
        is_array($model) || $model = $this->getModel($model);
        $id || $id = I('id');

        // 获取数据
        $data = M(get_table_name($model ['id']))->find($id);
        $data || $this->error('数据不存在！');

        $token = get_token();
        if (isset ($data ['token']) && $token != $data ['token'] && defined('ADDON_PUBLIC_PATH')) {
            $this->error('非法访问！');
        }

        if (IS_POST) {
            $Model = D(parse_name(get_table_name($model ['id']), 1));
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model ['id']);
            if ($Model->create() && $Model->save()) {
                $this->_saveKeyword($model, $id);

                // 清空缓存
                method_exists($Model, 'clear') && $Model->clear($id, 'edit');

                $this->success('保存' . $model ['title'] . '成功！', U('lists?model=' . $model ['name'], $this->get_param));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $address_model = new AddressModel();
            $province_data = $address_model->get_area_list();
            $this->assign('province_data', $province_data['data']);

            $fields = get_model_attribute($model ['id']);
            $this->assign('fields', $fields);

            $this->assign('data', $data);

            $templateFile || $templateFile = $model ['template_edit'] ? $model ['template_edit'] : '';
            $this->display($templateFile);
        }

    }

    public function get_area()
    {
        $modelName = I('model');
        $pid = I('pid');
        $address_model = new AddressModel();
        $province_data = $address_model->get_area_list($modelName, $pid);
        $this->ajaxReturn($province_data['data']);
    }


}
