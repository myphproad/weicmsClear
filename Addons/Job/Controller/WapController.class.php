<?php

namespace Addons\Job\Controller;

use Home\Controller\AddonsController;

header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController
{
    //职位列表
    public function jobList($mapJob = array())
    {
        $posts = $this->getData();
        $page = intval($posts['page']) ? intval($posts['page']) : 1;
        $limit = intval($posts['limit']) ? intval($posts['limit']) : 20;
        $sort = 'is_recommend desc,ctime desc,id desc';
        $area_id = $posts['area_id'];
        $city_id = $posts['city_id'];
        $job_type = $posts['job_type'];//职位类型
        $work_time_id = $posts['work_time_id'];//时间类型
        $job_name_id = I('job_name_id');//所属职位名称
        if (isset($job_type)) {
            //具体的入口类别
            $mapJob['job_type'] = $job_type;
        }
        if (!empty($job_name_id)) {
            $mapJob['jname_id'] = $job_name_id;
        }
        if (!empty($city_id)) {
            $mapJob['city_id'] = $city_id;
        }
        /*if (!empty($area_id)) {
            $mapJob['area_id'] = $area_id;
        }*/
        //work_time_type 工作时间类型
        if (!empty($work_time_id)) {
            $mapJob['work_time_type'] = $work_time_id;
        }

        //职位信息
        $jobInfo = M('job')
            ->where($mapJob)
            ->field('id,title,jname_id,ctime,need_people,area_id,work_time_type,city_id,address,start_time,ctime,end_time,salary,is_recommend,is_jp,gender')
            ->page($page, $limit)
            ->order($sort)
            ->select();
        if ($jobInfo) {
            $jobInfo = $this->handle_job_info($jobInfo);//处理具体逻辑
            $data['jobInfo'] = $jobInfo;
            $this->returnJson('获取职位列表成功', 1, $data);
        } else
            if($page!=1) {
                $this->returnJson('职位信息列表为空', 0);
            }
            $result = $this->use_default_area_get_job($mapJob, $page, $limit, $sort);
            if ($result) {
                $data['jobInfo'] = $result;
                $this->returnJson('获取默认站点职位列表成功', 1, $data);
            } else {
                $this->returnJson('职位信息列表为空', 0);
            }
    }

    /**
     * @param $where
     */
    public function use_default_area_get_job($mapJob, $page, $limit, $sort)
    {
        //职位信息
        if($mapJob['city_id']){
            unset($mapJob['city_id']);
        }else{
            $configMessage = get_addon_config('Address');
            $mapJob['city_id']=$configMessage['defaultCityId'];
        }
        $jobInfo = M('job')
            ->where($mapJob)
            ->field('id,title,jname_id,ctime,need_people,area_id,work_time_type,city_id,address,start_time,ctime,end_time,salary,is_recommend,is_jp,gender')
            ->page($page, $limit)
            ->order($sort)
            ->select();
        if ($jobInfo) {
            $jobInfo = $this->handle_job_info($jobInfo);//处理具体逻辑
            return $jobInfo;
        } else {
            return false;
        }
    }

    /**
     * @param $jobInfo
     * @return mixed
     */
    public function handle_job_info($jobInfo)
    {
        $job_apply_controller = new JobApplyController();

        foreach ($jobInfo as $key => $value) {
            $job_name_id = get_about_name($value['jname_id'], 'job_name', 'thumb');
            $jobInfo[$key]['thumb'] = get_picture_url($job_name_id);
            $jobInfo[$key]['start_time'] = day_format_tool($value['start_time'], '/');
//            $jobInfo[$key]['start_time'] = get_month_day($value['start_time']);
            $jobInfo[$key]['end_time'] = day_format_tool($value['end_time'], '/');
//            $jobInfo[$key]['end_time'] = get_month_day($value['end_time']);
            $jobInfo[$key]['ctime'] = friend_date($value['ctime']);
            $jobInfo[$key]['area_str'] = get_area_str($value['city_id'], $value['area_id']);
            //判断过期
            if ($value['end_time'] < time()) {
                $jobInfo[$key]['is_due'] = 1;
            } else {
                $jobInfo[$key]['is_due'] = 0;
            }
            //取出已經報名人數
            $count_apply = $job_apply_controller->get_apply_job_count($value['id']);
            //剩下人数
            $jobInfo[$key]['due_need_people'] = $count_apply;
        }
        $flag = array();

        foreach ($jobInfo as $v) {
            $flag[] = $v['is_due'];
        }

        array_multisort($flag, SORT_ASC, $jobInfo);

        return $jobInfo;
    }

    public function chooseJobInfo()
    {
        //地区
        $posts = $this->getData();
        $city_id = intval($posts['city_id']);
        $job_type = intval($posts['job_type']);
        //$city_id = 270;
        $area_arr = M('area')->where('city_id=' . $city_id)->field('id,name')->select();
        $work_time_arr = array(
            array('id' => 0, 'name' => '每天'),
            array('id' => 1, 'name' => '周末'),
            array('id' => 2, 'name' => '工作日'),
            array('id' => 3, 'name' => '暑假'),
            array('id' => 4, 'name' => '寒假'),
            array('id' => 5, 'name' => '其他'),
        );
        //职位名称
        $mapJobName['status'] = 1;
        $mapJobName['job_type'] = $job_type;
        $job_name_arr = M('job_name')->where($mapJobName)->field('id,name')->order('sort_order desc')->select();
        $data['area_arr'] = $area_arr;
        $data['job_name_arr'] = $job_name_arr;
        $data['work_time_arr'] = $work_time_arr;
        $this->returnJson('成功', 1, $data);
    }

    //职位详情
    public function jobDetails()
    {
        $id = intval(I('id'));
        $openid = I('openid');
        if (empty($id)) $this->returnJson('id必须填写', 0);
        $field = 'title,salary,is_bond,is_profile,bid,start_time,end_time,address,pay_type,gender,content,need_people';
        $jobInfo = $this->jobInfo($field, $id);

        if (0 == $jobInfo['pay_type']) {
            $jobInfo['pay_type'] = '日结';
        } elseif (1 == $jobInfo['pay_type']) {
            $jobInfo['pay_type'] = '周结';
        } elseif (2 == $jobInfo['pay_type']) {
            $jobInfo['pay_type'] = '月结';
        } else {
            $jobInfo['pay_type'] = '项目结算';
        }
//		判断是否收藏过ÏÏ
        if ($this->checkCollect(0, $id, $openid)) {
            // 0职位 1头条type:Number
            $jobInfo['is_collect'] = 1;
        } else {
            $jobInfo['is_collect'] = 0;
        }
//		判断是否申请过
        if ($this->checkJobApply($id, $openid)) {
            // 0职位 1头条type:Number
            $jobInfo['is_apply'] = 1;
        } else {
            $jobInfo['is_apply'] = 0;
        }
        if ($this->checkUserOver($openid)) {
            $jobInfo['is_user_over'] = 1;
        } else {
            $jobInfo['is_user_over'] = 0;
        }
        //判断过期
        if ($jobInfo['end_time'] < time()) {
            $jobInfo['is_due'] = 1;
        } else {
            $jobInfo['is_due'] = 0;
        }
//        $jobInfo['start_time'] = get_month_day($jobInfo['start_time']);
        $jobInfo['start_time'] = day_format_tool($jobInfo['start_time'], '/');
        $jobInfo['end_time'] = day_format_tool($jobInfo['end_time'], '/');
//        $jobInfo['content'] = filter_line_tab($jobInfo['content']);
        //取出已經報名人數
        $job_apply_controller = new JobApplyController();

        $count_apply = $job_apply_controller->get_apply_job_count($id);
        //剩下人数
        $jobInfo['due_need_people'] = $count_apply;

        $data['jobInfo'] = empty($jobInfo) ? '' : $jobInfo;
        $data['business_info'] = $this->businesInfo($jobInfo['bid']);
        $this->returnJson('成功', 1, $data);
    }

    //判断用户完善
    Public function checkUserOver($openid)
    {
        $where['openid'] = $openid;
        $result = M('user')->where($where)->find();
        if ($result['mobile']) {
            return true;
        } else {
            return false;
        }
    }

    //商家信息
    public function businesInfo($bid)
    {
        $businesInfo = M('business_info')->find($bid);
        $businesInfo['nature'] = get_about_name($businesInfo['nature'], 'business_nature');
        $businesInfo['industry'] = get_about_name($businesInfo['industry'], 'business_industry');
        $businesInfo['introduction'] = filter_line_tab($businesInfo['introduction']);
        if (0 == $businesInfo['scale']) {
            $businesInfo['scale'] = '1-20人';
        } elseif (1 == $businesInfo['scale']) {
            $businesInfo['scale'] = '20-50人';
        } elseif (2 == $businesInfo['scale']) {
            $businesInfo['scale'] = '50-100人';
        } elseif (3 == $businesInfo['scale']) {
            $businesInfo['scale'] = '100-500人';
        } elseif (4 == $businesInfo['scale']) {
            $businesInfo['scale'] = '500以上';
        }
        if ($businesInfo['img_url']) {
            $img_url_array = explode(',', $businesInfo['img_url']);
            foreach ($img_url_array as $key => $value) {
                $businesInfo['company_img'][] = get_cover_url($value);
            }
        } else {
            $businesInfo['company_img'] = '';
        }
        unset($businesInfo['img_url']);
        $businesInfo = empty($businesInfo) ? '' : $businesInfo;
        return $businesInfo;
    }

    /**
     * @Name:微信支付显示页面
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     */
    public function initIndex()
    {
        $this->display();
    }

    protected function checkJobApply($id, $openid)
    {
        if (empty($id)) return false;
        $where['job_id'] = $id;
        $where['openid'] = $openid;
        $result = M('JobApply')->where($where)->find();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}