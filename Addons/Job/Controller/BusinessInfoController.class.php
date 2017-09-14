<?php
namespace Addons\Job\Controller;

use Home\Controller\AddonsController;

class BusinessInfoController extends AddonsController
{
    var $model;

    function _initialize()
    {
        $this->model = $this->getModel('BusinessInfo');
        parent::_initialize();
    }

    //商家列表
    public function lists()
    {
        $list_data = $this->_get_model_list($this->model);
        // dump($list_data);die();
        /****商家性质******/
        $businessNature = M('BusinessNature')->where('status=1')->field('id,name')->select();
        //  dump($businessNature);
        $data = array();
        foreach ($businessNature as $key => $value) {
            $data[$value['id']] = $value['name'];
        }
        /****商家性质******/
        /*****行业类型******/
        $businessIndustry = M('BusinessIndustry')->field('id,name')->select();
        $arr = array();
        foreach ($businessIndustry as $key => $value) {
            $arr[$value['id']] = $value['name'];
        }
        /*****行业类型******/
        foreach ($list_data['list_data'] as $key => $value) {
            //   dump($value);
            $list_data['list_data'][$key]['nature'] = $data[$value['nature']];//公司性质
            $list_data['list_data'][$key]['industry'] = $arr[$value['industry']];//公司行业

            if ($value['scale'] == 0) {
                $list_data['list_data'][$key]['scale'] = '1-20人';
            } elseif ($value['scale'] == 1) {
                $list_data['list_data'][$key]['scale'] = '20-50人';
            } elseif ($value['scale'] == 2) {
                $list_data['list_data'][$key]['scale'] = '50-100人';
            } elseif ($value['scale'] == 3) {
                $list_data['list_data'][$key]['scale'] = '100-500人';
            } elseif ($value['scale'] == 4) {
                $list_data['list_data'][$key]['scale'] = '500以上';//公司规模
            }
        }

        //dump($list_data);die();
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
            $data = $Model->create();
            $img_url = $_POST['img_url'];
            if ($img_url) {
                $img_url_str = implode(',', $img_url);
                $data['img_url'] = $img_url_str;
            }
            $res = $Model->save($data);
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
            $data = $Model->create();
            $img_url = $_POST['img_url'];
            if ($img_url) {
                $img_url_str = implode(',', $img_url);
                $data['img_url'] = $img_url_str;
            }
            if ($data && $id = $Model->add($data)) {
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
}