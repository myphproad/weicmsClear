<?php

namespace Addons\Job\Controller;

use Home\Controller\AddonsController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController {

	function _initialize() {
        $checkToken = $this->checkToken(I('token'));
	}
	//职位图片轮播
	public function advertiementList(){
		//$order=arrray();
		//create_time asc ;id desc ;sort_order desc;

		$where['status']=1;
		$where['type']=1;
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

		foreach ($jobInfo as $key => $value) {
			$jobInfo[$key]['img_url']  = get_picture_url($value['img_url']);
			$jobInfo[$key]['area_str'] = get_about_name($value['area_id'],'area');
		}

		
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
        
		$data['jobInfo'] = $jobInfo;

		$this->returnJson('成功',1,$data);
	}

	public function chooseJobInfo(){
		//地区
        //$area_arr = M('area')->where('city_id='.$city_id)->select();
        $area_arr = array(
        	  array('id'=>1,'name'=>'西夏区'),
        	  array('id'=>2,'name'=>'兴庆区'),
        	  array('id'=>3,'name'=>'金凤区'),
        	);
        $work_time_arr = array(
        	array('id'=>0,'name'=>'每天'),
        	array('id'=>1,'name'=>'周末'),
        	array('id'=>2,'name'=>'工作日'),
        	array('id'=>3,'name'=>'暑假'),
        	array('id'=>4,'name'=>'寒假'),
        	array('id'=>5,'name'=>'其他'),
        	);
        //职位名称
		$mapJobName['status'] = 1;
        $job_name_arr = M('job_name')->where($mapJobName)->order('sort_order desc')->select();
        $data['area_arr']      = $area_arr;
	    $data['job_name_arr']  = $job_name_arr;
	    $data['work_time_arr'] = $work_time_arr;
	    $this->returnJson('成功',1,$data);
	}



	//职位详情
	public function jobDetails(){
		$posts = $this->getData();
		$id    = intval($posts['id']);
		$field = 'title,salary,start_time,end_time,address,pay_type,content';
		$jobInfo = $this->jobInfo($field,$id);
		if(0 == $jobInfo['pay_type']){
			$jobInfo['pay_type'] = '日结';
		}elseif (1 == $jobInfo['pay_type']) {
			$jobInfo['pay_type'] = '周结';
		}elseif (2 == $jobInfo['pay_type']) {
			$jobInfo['pay_type'] = '月结';
		}else{
			$jobInfo['pay_type'] = '项目结';
		}

		$jobInfo['content']=  filter_line_tab($jobInfo['content']);
		$data['jobInfo']   = empty($jobInfo)?'':$jobInfo;
		//dump($data);die();
		$this->returnJson('成功',1,$data);
	}

	//商家信息
	public function businesInfo(){
		$posts = $this->getData();
		$bid   = intval($posts['bid']);
		$businesInfo = M('business_info')->where('id='.$bid)->find();
		
		$businesInfo['nature']   = get_about_name($businesInfo['nature'],'business_nature');
		$businesInfo['industry'] = get_about_name($businesInfo['industry'],'business_industry');
		if(0 == $businesInfo['scale']){
			$businesInfo['scale'] = '1-20人';
		}elseif (1 == $businesInfo['scale']) {
			$businesInfo['scale'] = '20-50人';
		}elseif (2 == $businesInfo['scale']) {
			$businesInfo['scale'] = '50-100人';
		}elseif (3 == $businesInfo['scale']) {
			$businesInfo['scale'] = '100-500人';
		}elseif (4 == $businesInfo['scale']) {
			$businesInfo['scale'] = '500以上';
		}
		
		$data['businesInfo'] = $businesInfo;
		$this->returnJson('成功',1,$data);
	}

	//用户添加信息
	public function user_info(){
		$posts = $this->getData();
		$token = $posts['user_token'];
		$type  = intval($posts['type']);
		if(IS_POST && 1 == $type){
			$arr = array(
				'truename'=>$posts['truename'],
				'sex'=>$posts['sex'],
				'mobile'=>$posts['mobile'],
				'height'=>$posts['height'],
				'weight'=>$posts['weight'],
				'birthday'=>$posts['birthday'],
				'school'=>$posts['school'],
				);
			$addInfo = M('user')->where('token='.$token)->save($arr);
			if($addInfo){
				$this->returnJson('操作成功',1);
			}else{
				$this->returnJson('操作失败',0);
			}
		}else{
			$field    = 'truename,sex,mobile,height,weight,birthday,school';
			$userInfo = $this->userInfo($field,$token);
			foreach ($userInfo as $key => $value) {
				$userInfo[$key]['height'] = empty($value['height'])?'':$value['height'];
				$userInfo[$key]['weight'] = empty($value['weight'])?'':$value['weight'];
				$userInfo[$key]['school'] = empty($value['school'])?'':$value['school'];
				$userInfo[$key]['birthday'] = empty($value['birthday'])?'':$value['birthday'];
			}
			$data['userInfo'] = $userInfo;
			if($data){
				$this->returnJson('操作成功',1,$data);
			}else{
				$this->returnJson('操作失败',0);
			}
		}
	}
}