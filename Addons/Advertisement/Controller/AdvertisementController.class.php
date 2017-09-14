<?php

namespace Addons\Advertisement\Controller;
use Home\Controller\AddonsController;

class AdvertisementController extends AddonsController{
    var $model;
    function _initialize()
    {
        $this->model = $this->getModel('Advertisement');
        parent::_initialize();
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
