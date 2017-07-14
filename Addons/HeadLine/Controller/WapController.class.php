<?php

namespace Addons\Headline\Controller;

use Home\Controller\AddonsController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController {

	function _initialize() {
        $this->checkToken(I('token'));
	}

	//头条列表
	public function headlineList(){
		//头条简介 图片 标签
		$a = 2;
	}
}