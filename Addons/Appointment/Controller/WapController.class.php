<?php
/*
 *资讯管理模块
 * 手机端控制接口
 */
namespace Addons\Appointment\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {
//http://localhost/weicmsClear/index.php?s=/addon/News/wap/lists.html
	function getList(){
		set_time_limit(0);
		$limit = I('limit', 10, 'intval');

		$lastid = I('lastid', 0, 'intval');
		if($lastid>0){
			$map['id'] = array('lt', $lastid);
		}

//        $list = M('cms')->where($map)->order('id desc')->field('id,title,img,cTime')->limit($limit)->select();
		$list=array();
		$list['img']="http://localhost/weicmsClear/index.php?s=/addon/News/wap/lists.html";
		$list['name']="ceshi";
		/*foreach ($list as &$vo) {
            $vo['img'] = get_cover_url($vo['img']);
            $vo['cTime'] = time_format($vo['cTime']);
        }*/

		//dump($list);
		$this->ajaxReturn($list);
	}
	function getDetail(){
		$map['id'] = I('id', 0, 'intval');
		$info = M('cms')->where($map)->find();

		$info['img'] = get_cover_url($info['img']);
		$info['cTime'] = time_format($info['cTime']);
		//dump($info);
		$this->ajaxReturn($info);
	}
}
