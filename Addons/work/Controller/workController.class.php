<?php

namespace Addons\Work\Controller;
use Home\Controller\AddonsController;

class WorkController extends AddonsController{
    // 通用插件的列表模型
    public function lists($model = null, $page = 0) {
        // 通用表单的控制开关
        $this->assign ( 'add_button', false );
        $this->assign ( 'del_button', true );
        $this->assign ( 'check_all', true );
        is_array ( $model ) || $model = $this->getModel ( $model );
        $templateFile = $this->getAddonTemplate ( $model ['template_list'] );
        parent::common_lists ( $model, $page, $templateFile );
    }
}
