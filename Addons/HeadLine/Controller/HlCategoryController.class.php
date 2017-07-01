<?php

namespace Addons\HeadLine\Controller;
use Home\Controller\AddonsController;

//头条分类
class HlCategoryController extends AddonsController{

   function _initialize() {
		parent::_initialize ();
		$this->syc_wechat = C ( 'USER_LIST' );
	}

   //头条分类列表
	 // 通用插件的列表模型
    public function lists() {
       $model = $this->getModel ();
       $list_data = $this->_get_model_list($model);

       dump($list_data);die();
        // 通用表单的控制开关
        $this->assign ( 'add_button', false );
        $this->assign ( 'del_button', true );
        $this->assign ( 'check_all', true );
  //dump($model);die();

        is_array ( $model ) || $model = $this->getModel ( $model );
        $templateFile = $this->getAddonTemplate ( $model ['template_list'] );
        parent::common_lists ( $model, $page, $templateFile );

    }

    //头条分类添加
    public function add(){

    	$this->display();
    }
}
