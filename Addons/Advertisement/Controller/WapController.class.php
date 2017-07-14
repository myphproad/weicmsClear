<?php

namespace Addons\Advertisement\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {

	function _initialize() {
      // $this->checkToken();
	}
	//职位图片轮播 advertisementList
	public function advertisementList(){
		$posts = $this->getData();
		$type  = intval($posts['type']);

		$where['status']=1;
		$where['type']  = $type;
		$advInfo = M('advertisement')->where($where)->limit(6)->field('id,token,title,jump_url,img_url')->order('sort_order desc')->select();
		
		$data['advInfo'] = $advInfo;
		//dump($data);
		$this->returnJson('成功',1,$data);
	}

}