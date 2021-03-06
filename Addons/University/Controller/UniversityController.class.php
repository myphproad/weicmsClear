<?php

namespace Addons\University\Controller;
use Addons\Address\Model\AddressModel;
use Home\Controller\AddonsController;

class UniversityController extends AddonsController{
    var $model;
    function _initialize() {
        $this->model = $this->getModel ( 'University' );
        parent::_initialize ();
    }
    // 通用插件的列表模型
    public function lists($model = null, $page = 0)
    {
        $list_data = $this->_get_model_list($this->model);
        /***职位名称*****/
        foreach ($list_data['list_data'] as $key => $value) {
            if($value['province_id']){
                $list_data['list_data'][$key]['province_id'] = $this->use_id_get_area($value['province_id']);
            }
            if($value['city_id']){
                $list_data['list_data'][$key]['city_id'] = $this->use_id_get_area($value['city_id'],'city');
            }

        }
        $this->assign($list_data);
        $this->assign ( 'search_button', true);
        $this->assign ( 'search_key', 'name');
        $this->assign ( 'placeholder', '请输入学校名称');
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
            $address_model=new AddressModel();
            $province_data=$address_model->get_area_list();
            $this->assign ( 'province_data', $province_data['data'] );

            $fields = get_model_attribute ( $model ['id'] );
            $this->assign ( 'fields', $fields );
            $this->meta_title = '新增' . $model ['title'];
            $this->display ();

        }

    }
    // 通用插件的编辑模型
    public function edit($model = null, $id = 0) {
        is_array ( $model ) || $model = $this->getModel ( $model );
        $id || $id = I ( 'id' );

        // 获取数据
        $data = M ( get_table_name ( $model ['id'] ) )->find ( $id );
        $data || $this->error ( '数据不存在！' );

        $token = get_token ();
        if (isset ( $data ['token'] ) && $token != $data ['token'] && defined ( 'ADDON_PUBLIC_PATH' )) {
            $this->error ( '非法访问！' );
        }

        if (IS_POST) {
            $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );
            if ($Model->create () && $Model->save ()) {
                $this->_saveKeyword ( $model, $id );

                // 清空缓存
                method_exists ( $Model, 'clear' ) && $Model->clear ( $id, 'edit' );

                $this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'], $this->get_param ) );
            } else {
                $this->error ( $Model->getError () );
            }
        } else {
            $address_model=new AddressModel();
            $province_data=$address_model->get_area_list();
            $this->assign ( 'province_data', $province_data['data'] );

            $fields = get_model_attribute ( $model ['id'] );
            $this->assign ( 'fields', $fields );

            $this->assign ( 'data', $data );

            $templateFile || $templateFile = $model ['template_edit'] ? $model ['template_edit'] : '';
            $this->display ( $templateFile );
        }

    }
    public function get_area(){
        $modelName=I('model');
        $pid=I('pid');
        $address_model=new AddressModel();
        $province_data=$address_model->get_area_list($modelName,$pid);
        $this->ajaxReturn($province_data['data']);
    }
    public function use_id_get_area($id=0,$modelName='province'){
        $data=M($modelName)->find($id);
        if($data){
           return $data['name'];
        }else{
            return false;
        }
    }

}
