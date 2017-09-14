<?php

namespace Addons\Advertisement\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {

	function _initialize() {

	}
	//职位图片轮播
	public function advertisementList(){
		$posts = $this->getData();
		$type  = intval($posts['type']);

		$where['status']=1;
		$where['type']  = $type;
		$advInfo = M('advertisement')->where($where)->limit(6)->field('id,token,title,jump_url,img_url')->order('sort_order desc')->select();
		foreach($advInfo as $key=>$value){
			$img_url = get_cover($value['img_url']);
			$advInfo[$key]['img_url'] = 'https://'.$_SERVER['SERVER_NAME'].$img_url['path'];
		}
		$data['advInfo'] = $advInfo;
		if($data){
			$this->returnJson('操作成功',1,$data);
		}else{
			$this->returnJson('操作成功',0);
		}
	}

}