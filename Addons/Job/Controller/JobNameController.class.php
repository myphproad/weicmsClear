<?php

namespace Addons\Job\Controller;
use Home\Controller\AddonsController;

class JobNameController extends AddonsController{
	 function _initialize()
    {
        $this->model = $this->getModel('JobName');
        parent::_initialize();
    }

    //商家列表
    public function lists(){
    	$list_data = $this->_get_model_list($this->model);
        /***职位名称*****/
        foreach ($list_data['list_data'] as $key => $value) {
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
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'name');
        $this->assign ( 'placeholder', '请输入职位名称');
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
            $fields = get_model_attribute($model ['id']);
            $this->assign('fields', $fields);
            $this->meta_title = '新增' . $model ['title'];
            $this->display();
        }
    }
    public function edit($model = null, $id = 0)
    {
        is_array($model) || $model = $this->model;
        $Model = D(parse_name(get_table_name($model ['id']), 1));
        $id || $id = I('id');
        if (IS_POST) {
            //编辑提交
            if ($_POST['pid'] == $id) {
                $_POST['pid'] = 0;
            }
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model ['id']);
            $res = false;
            $Model->create() && $res = $Model->save();
            if ($res !== false) {
                $this->success('保存' . $model ['title'] . '成功！', U('lists?model=' . $model ['name'], $this->get_param));
            } else {
                $this->error($Model->getError());
            }
        } else {
            //编辑显示
            // 获取一级菜单
            $map ['token'] = get_token();
            $map ['pid'] = 0;
            $map ['id'] = array(
                'not in',
                $id
            );
            $list = $Model->where($map)->select();
            foreach ($list as $v) {
                $extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
            }
            //获取数据模型属性
            $fields = get_model_attribute($model ['id']);
            if (!empty ($extra)) {
                foreach ($fields as &$vo) {
                    if ($vo ['name'] == 'pid') {
                        $vo ['extra'] .= "\r\n" . $extra;
                    }
                }
            }
            // 获取数据
            $data = M(get_table_name($model ['id']))->find($id);
            $data || $this->error('数据不存在！');
            $token = get_token();
            if (isset ($data ['token']) && $token != $data ['token'] && defined('ADDON_PUBLIC_PATH')) {
                $this->error('非法访问！');
            }
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $tmpImg = ONETHINK_ADDON_PATH . 'WeiSite/View/default/TemplateSubcate/' . $data['template'] . '/icon.png';
            if (file_exists($tmpImg)) {
                $this->assign('tmp_img', $tmpImg);
            }
            //dump($fields);
            $this->meta_title = '编辑' . $model ['title'];
            $this->display();
        }
    }

}
