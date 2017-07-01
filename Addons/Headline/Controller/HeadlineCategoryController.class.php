<?php

namespace Addons\Headline\Controller;

use Home\Controller\AddonsController;

class HeadlineCategoryController extends AddonsController
{
    var $model;

    function _initialize()
    {
        $this->model = $this->getModel('HeadlineCategory');
        parent::_initialize();
    }
    public function lists()
    {

        /*$this->assign('normal_tips','我要报孝链接:'.addons_url('Weisite://Baoxiao/indexAdd',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('WeiSite://Baoxiao/indexAdd',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');*/
        $list_data = $this->_get_model_list($this->model);
        $this->assign($list_data);
        $this->display();

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

            $this->meta_title = '新增' . $model ['title'];


            $this->display();

        }

    }


    // 通用插件的删除模型

    public function del()
    {

        parent::common_del($this->model);

    }
    //审核
    public function checkTopics($model = null, $id = 0){
        $id = I('id');
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
