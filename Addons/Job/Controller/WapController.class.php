<?php

namespace Addons\Job\Controller;

use Home\Controller\AddonsController;

header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');

class WapController extends AddonsController
{

    function _initialize()
    {

    }

    //职位列表
    public function jobList()
    {
        $posts = $this->getData();
        $page = intval($posts['page']) ? intval($posts['page']) : 1;
        $limit = intval($posts['limit']) ? intval($posts['limit']) : 20;
        $area_id = $posts['area_id'];
        $city_id = $posts['city_id'];
        $job_type = $posts['job_type'];
        $job_name_id = I('job_name_id');
        $jname_arr = [];
        if (isset($job_type)) {
            //具体的入口类别
            $mapJob['job_type'] = $job_type;
        }
        if (!empty($job_name_id)) {
            if (!is_array($job_name_id)) {
                foreach ($job_name_id as $k => $v) {
                    $jname_arr[] = $v;
                }
                $mapJob['jname_id'] = array('in', $jname_arr);
            } else {
                $mapJob['jname_id'] = $job_name_id;
            }
        }
        if (!empty($area_id)) {
            $mapJob['area_id'] = $area_id;
        }
        //职位信息
        $jobInfo = M('job')
            ->where($mapJob)
            ->field('id,title,jname_id,ctime,area_id,start_time,end_time,salary,is_recommend,is_jp')
            ->page($page, $limit)
            ->select();
        foreach ($jobInfo as $key => $value) {
//			$jobInfo[$key]['img_url']  = get_picture_url($value['img_url']);
            $job_name_id = get_about_name($value['jname_id'], 'job_name', 'thumb');
            $jobInfo[$key]['thumb'] = get_picture_url($job_name_id);
            $jobInfo[$key]['start_time'] = get_month_day($value['start_time']);
            $jobInfo[$key]['end_time'] = get_month_day($value['end_time']);
            $jobInfo[$key]['ctime'] = friend_date($value['ctime']);
            $jobInfo[$key]['area_str'] = get_about_name($value['area_id'], 'area');
        }
        $data['jobInfo'] = $jobInfo;
        if ($jobInfo) {
            $this->returnJson('获取职位列表成功', 1, $data);
        } else {
            $this->returnJson('职位信息列表为空', 0);
        }
    }

    public function chooseJobInfo()
    {
        //地区
        $posts = $this->getData();
        $city_id = intval($posts['city_id']);
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
        $job_name_arr = M('job_name')->where($mapJobName)->order('sort_order desc')->select();
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
        $field = 'title,salary,is_bond,is_profile,start_time,end_time,address,pay_type,content,number';
        $jobInfo = $this->jobInfo($field, $id);

        if (0 == $jobInfo['pay_type']) {
            $jobInfo['pay_type'] = '日结';
        } elseif (1 == $jobInfo['pay_type']) {
            $jobInfo['pay_type'] = '周结';
        } elseif (2 == $jobInfo['pay_type']) {
            $jobInfo['pay_type'] = '月结';
        } else {
            $jobInfo['pay_type'] = '项目结';
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

        $jobInfo['start_time'] = get_month_day($jobInfo['start_time']);
        $jobInfo['end_time'] = get_month_day($jobInfo['end_time']);

        $jobInfo['content'] = filter_line_tab($jobInfo['content']);
        $data['jobInfo'] = empty($jobInfo) ? '' : $jobInfo;
        $this->returnJson('成功', 1, $data);
    }

    //商家信息
    public function businesInfo()
    {
        $posts = $this->getData();
        $bid = intval($posts['bid']);
        $businesInfo = M('business_info')->where('id=' . $bid)->find();
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
        $data['businesInfo'] = $businesInfo;
        $this->returnJson('成功', 1, $data);
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