<?php

namespace Addons\Headline\Controller;
use Home\Controller\AddonsController;

class HeadlineCategoryController extends AddonsController{
    var $model;

    function _initialize() {

        $this->model = $this->getModel ( 'HeadlineCategory' );

        parent::_initialize ();

    }

    public function lists() {

        /*$this->assign('normal_tips','我要报孝链接:'.addons_url('Weisite://Baoxiao/indexAdd',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('WeiSite://Baoxiao/indexAdd',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');*/
        $list_data = $this->_get_model_list ( $this->model );
        $this->assign ( $list_data );
        $this->display ();

    }
}
