<?php

namespace Addons\Headline\Controller;

use Home\Controller\AddonsController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController {

	function _initialize() {
        $checkToken = $this->checkToken(I('token'));
	}

	//头条列表
	public function headlineList(){
		
	}