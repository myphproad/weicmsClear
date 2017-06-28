<?php

namespace Addons\Appointment\Controller;
use Home\Controller\AddonsController;

class AppointmentController extends AddonsController{


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
