<?php

namespace Addons\Advertisement\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {

	function _initialize() {
        $checkToken = $this->checkToken(I('token'));
	}
	//职位图片轮播
	public function advertiementList(){
		$posts = $this->getData();
		$type  = intval($posts['type']);

		$where['status']=1;
		$where['type']  = $type;
		$advInfo = M('advertisement')->where($where)->limit(6)->field('id,token,title,jump_url,img_url')->order('sort_order desc')->select();
		
		$data['advInfo'] = $advInfo;
		//dump($data);
		$this->returnJson('成功',1,$data);
	}

	//职位列表
	public function jobList(){
		$posts = $this->getData();
        $page  = intval($posts['page'])?intval($posts['page']):1;
        $limit = intval($posts['limit']);
   	    $mapJob['area_id']  = intval($posts['area_id']);
		$mapJob['jname_id'] =  intval($posts['jname_id']);
		$mapJob['job_type'] =  intval($posts['job_type']);
        //职位信息
		$jobInfo = M('job')
		         ->where($mapJob)
		         ->field('id,img_url,title,area_id,start_time,end_time,salary,is_recommend,is_jp')
		         ->page($page,$limit)
		         ->select();
		//职位名称
		$mapJobName['status'] = 1;
        $jobName = M('job_name')->where($mapJobName)->order('sort_order desc')->select();
        //地区
        //$areaStr = M('area')->where('city_id='.$city_id)->select();
        $areaStr = array(
        	  array('id'=>1,'name'=>'西夏区'),
        	  array('id'=>2,'name'=>'兴庆区'),
        	  array('id'=>3,'name'=>'金凤区'),
        	);
	    $data['areaStr'] = $areaStr;
	    $data['jobName'] = $jobName;
		$data['jobInfo'] = $jobInfo;

		$this->returnJson('成功',1,$data);
	}



	//职位详情
	public function jobDetails(){
		$posts   = $this->getData($map);
		$id      = intval($posts['id']);
			$id      = intval($posts['id']);
		//$jobInfo = M('job')->where($map)->find();
		$jobInfo = array(
			'title'=>'职位简介',
			'salary'=>'200元/天',
			'start_time'=>1499149993,
			'end_time'=>1499236393,
			'address'=>'银川市-西夏区-801',
			'pay_type'=>'日结',
			'content'=>'职位详细描述'
			);
		$data['jobInfo'] = empty($jobInfo)?'':$jobInfo;
		//dump($data);die();
		$this->returnJson($data);
	}

	//商家信息
	public function businesInfo(){
		$posts = $this->getData();
		$bid   = intval($posts['bid']);
		//$businesInfo = M('business_info')->where('id='.$bid)->find();
	
		$businesInfo = array(
			'company_name'=>'异次元',
			'scale'=>'1-20人',
			'nature'=>'私营',
			'industry'=>'软件开发',
			'introduction'=>'异次元 为黑科技而生',
			);
		$data['businesInfo'] = $businesInfo;
		//dump($data);die();
		$this->returnJson($data);
	}

	//用户添加信息
	public function userAdd(){
		$data = array(
			'message'=>'成功',
			'statusCode'=>1,
			);
		$this->returnJson($data);
	}
}