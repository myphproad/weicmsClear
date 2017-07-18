<?php

namespace Addons\Job\Controller;

use Home\Controller\AddonsController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController {

	function _initialize() {

	}
	//职位图片轮播
	/*public function advertiementList(){
		//$order=arrray();
		//create_time asc ;id desc ;sort_order desc;

		$where['status']=1;
		$where['type']=1;
		$advInfo = M('advertisement')->where($where)->limit(6)->field('id,token,title,jump_url,img_url')->order('sort_order desc')->select();
		$data['advInfo'] = $advInfo;
		//dump($data);
		$this->returnJson('成功',1,$data);
	}*/

	//职位列表
	public function jobList(){
		$posts = $this->getData();
        $page  = intval($posts['page'])?intval($posts['page']):1;
        $limit = intval($posts['limit']);
		if($posts['area_id']) $mapJob['area_id']  = intval($posts['area_id']);
		if($posts['jname_id'])$mapJob['jname_id'] =  intval($posts['jname_id']);
		if($posts['area_id']) $mapJob['job_type'] =  intval($posts['job_type']);

        //职位信息
		$jobInfo = M('job')
		         ->where($mapJob)
		         ->field('id,img_url,title,area_id,start_time,end_time,salary,is_recommend,is_jp')
		         ->page($page,$limit)
		         ->select();

		foreach ($jobInfo as $key => $value) {
			$jobInfo[$key]['img_url']  = get_picture_url($value['img_url']);
			$jobInfo[$key]['area_str'] = get_about_name($value['area_id'],'area');
			$jobInfo[$key]['start_time'] = date('m.d',$value['start_time']);
			$jobInfo[$key]['end_time']   = date('m.d',$value['end_time']);
		}
		$data['jobInfo'] = $jobInfo;

		$this->returnJson('成功',1,$data);
	}

	public function chooseJobInfo(){
		//地区
		$posts   = $this->getData();
		$city_id = intval($posts['city_id']);
		if(empty($city_id))$this->returnJson('城市ID不能为空',0);
		//$city_id = 270;
        $area_arr = M('area')->where('city_id='.$city_id)->field('id,name')->select();

		$work_time_arr = C('WORK_TIME_TYPE');
        //职位名称
		$mapJobName['status'] = 1;
        $job_name_arr = M('job_name')->where($mapJobName)->field('id,name')->order('sort_order desc')->select();
        $data['area_arr']      = $area_arr;
	    $data['job_name_arr']  = $job_name_arr;
	    $data['work_time_arr'] = $work_time_arr;
		if($data){
			$this->returnJson('成功',1,$data);
		}else{
			$this->returnJson('操作失败',0);
		}

	}



	//职位详情
	public function jobDetails(){
		$posts = $this->getData();
		$id    = intval($posts['id']);
		$field = 'title,salary,start_time,end_time,address,pay_type,content';
		$jobInfo = M('job')->where('id='.$id)->field($field)->find();

		if(0 == $jobInfo['pay_type']){
			$jobInfo['pay_type'] = '日结';
		}elseif (1 == $jobInfo['pay_type']) {
			$jobInfo['pay_type'] = '周结';
		}elseif (2 == $jobInfo['pay_type']) {
			$jobInfo['pay_type'] = '月结';
		}else{
			$jobInfo['pay_type'] = '项目结';
		}

		$jobInfo['content']    =  filter_line_tab($jobInfo['content']);
		$jobInfo['start_time'] = date('m.d',$jobInfo['start_time']);
		$jobInfo['end_time']   = date('m.d',$jobInfo['end_time']);
		$data['jobInfo']   = empty($jobInfo)?'':$jobInfo;

		$this->returnJson('成功',1,$data);
	}

	//商家信息
	public function businesInfo(){
		$posts = $this->getData();
		$bid   = intval($posts['bid']);
		if(empty($bid))$this->ajaxReturn('商家ID不能为空',0);
		$businesInfo = M('business_info')
			           ->where('id='.$bid)
			           ->field('company_name,scale,nature_id,industry_id,address,introduction')
			           ->find();
		if($businesInfo){
			$businesInfo['nature']   = get_about_name($businesInfo['nature_id'],'business_nature');
			$businesInfo['industry'] = get_about_name($businesInfo['industry_id'],'business_industry');
			$businesInfo['introduction'] = filter_line_tab($businesInfo['introduction']);
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
		}
		$data['businesInfo'] = $businesInfo;
		if($data){
			$this->returnJson('操作成功',1,$data);
		}else{
			$this->returnJson('操作失败',0);
		}


	}

	
}