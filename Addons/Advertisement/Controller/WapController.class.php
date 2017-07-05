<?php

namespace Addons\Advertisement\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {
	//职位图片轮播
	public function advertiementList(){
		$advertiementInfo = M('advertisement')->where('status=1')->limit('6')->field('id,token,title,jump_url,img_url')->select();
		//$this->ajaxReturn($advertiementInfo);
		dump($data);
	}

	//职位列表
	public function jobList(){
		$jobInfo = M('job')->where('1=1')->select();
		dump($jobInfo);
	}
}