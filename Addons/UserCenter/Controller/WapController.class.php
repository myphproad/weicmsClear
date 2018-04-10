<?php
namespace Addons\UserCenter\Controller;
use Home\Controller\AddonsController;
use Addons\Payment\Controller\MiniProgramController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
class WapController extends AddonsController
{
    function curl_data($url, $param)
    {
        set_time_limit(0);
        $ch = curl_init();
        if (class_exists('/CURLFile')) { // php5.5跟php5.6中的CURLOPT_SAFE_UPLOAD的默认值不同
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $flat = curl_errno($ch);
        if ($flat) {
            $data = curl_error($ch);
            addWeixinLog($flat, 'post_data flat');
            addWeixinLog($data, 'post_data msg');
        }
        curl_close($ch);
        return $res;
    }
    function check_res_ajax()
    {
        $map ['token'] = I('token');
        $list = M('public_check')->where($map)->order('id asc')->select();
        foreach ($list as $vo) {
            $res [$vo ['na']] ['msg'] = $vo ['msg'];
        }
        if (empty ($res)) {
            echo 0;
        } else {
            echo json_encode($res);
        }
    }
    //我赚的钱
    public function mySalary()
    {
        $limit = I('limit', 10, 'intval');
        $start = I('start', 0, 'intval');
        if ($start > 0) {
            $where['id'] = array('lt', $start);
        }
        $posts = $this->getData();
        $openid = $posts['openid'];
        $where['openid'] = $openid;
        $map['openid'] = $openid;
        $salary = M('user')->where($map)->getField('salary');
        $salaryInfo = M('user_salary_logs')->order('id desc')->where($where)->limit($limit)->field('id,job_id,salary,ctime')->select();
//		echo M('user_salary_logs')->getLastSql();
        foreach ($salaryInfo as $key => $val) {
            $salaryInfo[$key]['job_id'] = get_about_name($val['job_id'], 'job_name');
            $salaryInfo[$key]['ctime'] = date("Y-m-d", $val['ctime']);
        }
        $data['salary'] = $salary;
        $data['salaryInfo'] = $salaryInfo;
//		dump($data);
        if ($salaryInfo) {
            $this->returnJson('获取记录成功', 1, $data);
        } else {
            $this->returnJson('获取记录为空', 0);
        }
    }
    //我的申请
    public function myApply()
    {
        $posts = $this->getData();
        $page = empty(intval($posts['page'])) ? 1 : intval($posts['page']);
        $limit = empty(intval($posts['limit'])) ? 10 : intval($posts['limit']);
        $map['openid'] = $posts['openid'];
        $px = C('DB_PREFIX');//表前缀
        $data = M('job_apply as a')
            ->join($px . 'job as j ON a.job_id = j.id')
            ->where($map)
            ->field('a.status,a.id,j.ctime,j.title,j.area_id,j.jname_id,a.job_id,j.city_id,j.salary,j.work_time_type,j.img_url')
            ->page($page, $limit)
            ->order('j.id desc')
            ->select();
        $work_time_type = C('WORK_TIME_TYPE');
        foreach ($data as $key => $value) {
            $data[$key]['area'] = get_area_str($value['city_id'], $value['area_id']);
            $data[$key]['ctime'] =  friend_date($value['ctime']);
            $job_name_id = get_about_name($value['jname_id'], 'job_name', 'thumb');
            $data[$key]['thumb'] = get_picture_url($job_name_id);
            foreach ($work_time_type as $kk => $val) {
                if ($value['work_time_type'] == $val['id']) {
                    $data[$key]['work_time_type'] = $val['name'];
                }
            }
            /* if (0 == $value['status']) {
                $data[$key]['status'] = '审核中';
            } elseif (1 == $value['status']) {
                $data[$key]['status'] = '申请通过';
            } else {
                $data[$key]['status'] = '申请不通过';
            }*/
        }
        $arr['jobInfo'] = $data;
        if ($data) {
            $this->returnJson('获取列表信息成功', 1, $arr);
        } else {
            $this->returnJson('获取列表信息为空', 0);
        }
    }
//添加我的收藏
    public function addMyCollect()
    {
        /* $info = M('user_collect')->select();
         dump($info);
         die('like');*/
        $posts = $this->getData();
        $openid = $posts['openid'];
        $about_id = intval($posts['about_id']);
        $ctype = intval($posts['type']);
        $user_id = intval($posts['user_id']);
        if (empty($openid) || empty($about_id)) {
            $this->returnJson('openid或者收藏对象必须填写', 0);
        } else {
            $arr['openid'] = $openid;
            //如果存在 就是删除 我的收藏
//            $map['user_id'] = $user_id;
            $map['about_id'] = $about_id;
            $map['ctype'] = $ctype;
            $id = M('user_collect')->where($map)->find();
            if ($id) {
                //取消收藏
                $info = M('user_collect')->where($map)->delete();
                if ($info) {
                    $this->returnJson('取消收藏成功', 1);
                } else {
                    $this->returnJson('取消收藏失败', 0);
                }
            } else {
                $arr['user_id'] = $user_id;
                $arr['about_id'] = $about_id;
                $arr['ctype'] = $ctype;
                $arr['ctime'] = time();
                $arr['token'] = get_token();
                $info = M('user_collect')->add($arr);
                if ($info) {
                    $this->returnJson('收藏成功', 1, $arr);
                } else {
                    $this->returnJson('收藏失败', 0);
                }
            }
        }
    }
    /*
     * 预约申请职位
     */
    public function addApply()
    {
        $openid = I('openid');
        $user_id = I('user_id');
        $job_id = I('job_id');
        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        $data['openid'] = $openid;
        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['ctime'] = time();
        $data['token'] = get_token();
        $Info = M('job_apply')->add($data);
        $dataReturn['Info'] = $Info;
        if ($Info) {
            $this->returnJson('职位详情预约成功', 1, $dataReturn);
        } else {
            $this->returnJson('职位详情预约失败', 0);
        }
    }
    /*
    * 预约申请职位删除
     * $param:openid;id:申请id
    */
    public function delApply()
    {
        $openid = I('openid');
        $id = I('id');
        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        if (!$this->checkMemberOpenid($openid)) {
            $this->returnJson('该用户不存在', 0);
        }
        $Info = M('job_apply')->delete($id);
        $dataReturn['Info'] = $Info;
        if ($Info) {
            $this->returnJson('删除申请记录成功', 1, $dataReturn);
        } else {
            $this->returnJson('删除申请记录失败', 0);
        }
    }
    //我的收藏
    public function myCollect()
    {
        $posts = $this->getData();
        $ctype = $posts['type'];
        $openid = I('openid');
        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        $page = empty(intval($posts['page'])) ? 1 : intval($posts['page']);
        $limit = empty(intval($posts['limit'])) ? 10 : intval($posts['limit']);
        $map['openid'] = $openid;
        $map['ctype'] = $ctype;
        $collectInfo = M('user_collect')->where($map)->field('about_id,ctype')->page($page, $limit)->select();
        foreach ($collectInfo as $key => $value) {
            if (0 == $ctype) {
                //职位
                $job_result = M('job')->where('id=' . $value['about_id'])
                    ->field('id,img_url,title,area_id,start_time,end_time,salary,city_id,jname_id')
                    ->order('id desc')->find();
                $data[$key]['area'] = get_area_str($job_result['city_id'], $job_result['area_id']);
                $job_name_id = get_about_name($job_result['jname_id'], 'job_name', 'thumb');
                $collectInfo[$key]['thumb'] = get_picture_url($job_name_id);
                $collectInfo[$key]['area_str'] = get_area_str($job_result['city_id'], $job_result['area_id']);
                $collectInfo[$key]['start_time'] =  day_format_tool($job_result['start_time'], '/');
                $collectInfo[$key]['end_time'] = day_format_tool($job_result['end_time'], '/');
                $collectInfo[$key]['title'] = $job_result['title'];
                $collectInfo[$key]['salary'] = $job_result['salary'];
            } elseif (1 == $ctype) {
                //头条
                $article = M('headline')->where('id=' . $value['about_id'])
                    ->field('id,img_url,title')
                    ->order('id desc')->find();
                $collectInfo[$key]['cate_name'] = get_about_name($article['tag_id'], 'headline_category');
                $collectInfo[$key]['title'] = $article['title'];
                $collectInfo[$key]['img_url'] = get_picture_url($article['img_url']);
            }
//            $arr[$key]['about_id'] = $value['about_id'];
        }
        $data['Info'] = $collectInfo;
        if ($collectInfo) {
            $this->returnJson('获取收藏信息成功', 1, $data);
        } else {
            $this->returnJson('获取收藏信息为空', 0);
        }
    }
    //我的消息
    public function myMessage()
    {
        //消息标题 消息内容 消息时间
        $posts = $this->getData();
        $ctype = $posts['type'];
        $page = empty(intval($posts['page'])) ? 1 : intval($posts['page']);
        $limit = empty(intval($posts['limit'])) ? 10 : intval($posts['limit']);
        //$openid = $posts['openid'];
        //$map['openid'] = $openid;
        $map['ctype'] = $ctype;
        $messageInfo = M('user_message')->where($map)
            ->field('id,name,ctime,comment,status')
            ->page($page, $limit)
            ->order('id desc')->select();
        foreach ($messageInfo as $key => $value) {
            //过滤非法html标签 去掉换行符
            $messageInfo[$key]['comment'] = filter_line_tab($value['comment']);
            $messageInfo[$key]['ctime'] = date('Y.m.d', $value['ctime']);
        }
        $data['Info'] = $messageInfo;
        if ($messageInfo) {
            $this->returnJson('获取成功', 1, $data);
        } else {
            $this->returnJson('数据为空', 0);
        }
    }
    //消息详情
    public function messageDetails()
    {
        $posts = $this->getData();
        $id = intval($posts['id']);
        if (empty($id)) $this->returnJson('消息ID不能为空', 0);
        $map['id'] = $id;
        $messageInfo = M('user_message')->where($map)->field('id,name,ctime,comment')->find();
        $messageInfo['ctime'] = date('Y.m.d', $messageInfo['ctime']);
        $data['messageInfo'] = $messageInfo;
        if ($data) {
            $this->returnJson('操作成功', 1, $data);
        } else {
            $this->returnJson('操作失败', 0);
        }
    }
    //消息详情
    public function delMessage()
    {
        $posts = $this->getData();
        $id = intval($posts['id']);
        $openid = $posts['openid'];
        if (empty($openid)) $this->returnJson('openID不能为空', 0);
        if (empty($id)) $this->returnJson('消息ID不能为空', 0);
        $map['id'] = $id;
        $messageInfo = M('user_message')->where($map)->delete();
        if ($messageInfo) {
            $this->returnJson('删除成功', 1);
        } else {
            $this->returnJson('删除失败', 0);
        }
    }
    //短信验证
    public function sendCode()
    {
        $posts = $this->getData();
        $mobile = $posts['mobile'];
//        $type = $posts['type'];//1 注册，2忘记密码
        if (empty($mobile)) $this->returnJson('手机号为空', 0);
        $rs = $this->sms($mobile);
    }
    /**
     * @Name:初始化数据
     * @User: 云清(sean)ma.running@foxmail.com
     * @Date: ${DATE}
     * @Time: ${TIME}
     * @param:
     */
    public function init_user_info()
    {
        $posts = $this->getData();
        $posts['token'] = get_token();
        if (empty($posts['code'])) $this->returnJson('授权码不能为空', 0);
        $miniProgram = new MiniProgramController();
        $result = $miniProgram->getSession($posts['code'], $posts);//获取session
        if ($result['error']==0) {
            $this->returnJson('初始化信息失败', 0);
        } else {
            $wap_address=new \Addons\Address\Controller\WapController();
            $result['now_position']=$wap_address->use_map_point_get_area($posts['latitude'],$posts['longitude']);
            $this->returnJson('初始化信息成功', 1,$result);
        }
    }

    //用户添加信息
    public function user_info()
    {
        $posts = $this->getData();
        $posts['token'] = get_token();
        $openid = I('openid');
        $mobile = $posts['mobile'];
        $type = intval($posts['type']);
        if (empty($openid)) $this->returnJson('用户id必须填写', 0);
        $condition['openid'] = $openid;
        //判断用户是否存在
        $exit_user = M('user')->where($condition)->find();
        if(empty($exit_user)) return $this->returnJson('用户不存在');
        $field = 'truename,sex,start_time,end_time,mobile,height,weight,birthday,school,uid';
        //存在用户
        if (1 == $type) {
            //编辑资料提交
            if (empty($posts['truename'])) $this->returnJson('姓名不能为空', 0);
            if (empty($posts['mobile'])) $this->returnJson('手机号不能为空', 0);
//            if (empty($posts['code'])) $this->returnJson('验证码不能为空', 0);
            /*$userInfo['height'] = empty($value['height'])?'':$value['height'];
            $userInfo['weight'] = empty($value['weight'])?'':$value['weight'];
            $userInfo['school'] = empty($value['school'])?'':$value['school'];
            $userInfo['birthday'] = empty($value['birthday'])?'':$value['birthday'];*/
            $checkMobile = $this->isMobile($mobile);
            if ($checkMobile === false) {
                $this->returnJson('手机格式错误', 0);
            }
//            $codeInfo = $this->checkCode($mobile);
//            if ($codeInfo['code'] != $posts['code'] || $codeInfo['mobile'] != $mobile) {
//                $this->returnJson('手机验证码错误', 0);
//            }
            $arr = array(
                'truename' => $posts['truename'],
                'sex' => $posts['sex'],
                'mobile' => $mobile,
                'height' => $posts['height'],
                'weight' => $posts['weight'],
                'birthday' => $posts['birthday'],
                'school' => $posts['school'],
                'start_time' => $posts['start_time'],
                'end_time' => $posts['end_time'],
            );
            $addInfo = M('user')->where($condition)->save($arr);
            if ($addInfo) {
                $this->returnJson('编辑成功', 1);
            } else {
                $this->returnJson('编辑失败', 0);
            }
        } else {
            //编辑资料获取数据
            $height = C('HEIGHT');
            $weight = C('WEIGHT');
            $userInfo = M('user')->where($condition)->field($field)->find();
            foreach ($height as $key => $valueHeight) {
                if ($valueHeight['id'] == (int)$userInfo['height']) {
                    $height[$key]['is_choose'] = 1;//已选择
                } else {
                    $height[$key]['is_choose'] = 0;//否
                }
            }
            $userInfo['height'] = $height;
            foreach ($weight as $key => $value) {
                if ($value['id'] == $userInfo['weight']) {
                    $weight[$key]['is_choose'] = 1;//已选择
                } else {
                    $weight[$key]['is_choose'] = 0;//否
                }
            }
            if($userInfo['school']){
                $university=M('University')->find($userInfo['school']);
                $schoolInfo['title']=$university['title'];
                $schoolInfo['school_id']=$userInfo['school'];
                $schoolInfo['city_id']=$university['city_id'];
                $schoolInfo['province_id']=$university['province_id'];
                $userInfo['school']=$schoolInfo;
            }else{
                $userInfo['school']='';
            }
//            $university=M('University')->field('title,id')->select();
//            foreach($university as $key=>$value){
//                if ($value['id'] == $userInfo['school']) {
//                    $university[$key]['is_choose'] = 1;//已选择
//                } else {
//                    $university[$key]['is_choose'] = 0;//否
//                }
//            }
//            $userInfo['university'] = $university;

            $userInfo['weight'] = $weight;
            $data['userInfo'] = $userInfo;
            if ($data) {
                $this->returnJson('获取数据成功', 1, $data);
            } else {
                $this->returnJson('获取数据操作失败', 0);
            }
        }
    }
    //更换手机号 或者绑定手机号码
    public function bind_mobile(){
        //编辑资料提交
        $posts = $this->getData();
        $openid = I('openid');
        if (empty($openid)) $this->returnJson('用户id必须填写', 0);

        if (empty($posts['mobile'])) $this->returnJson('手机号不能为空', 0);
        if (empty($posts['code'])) $this->returnJson('验证码不能为空', 0);

        $condition['openid'] = $openid;

        $mobile=$posts['mobile'];

        $checkMobile = $this->isMobile($mobile);
        if ($checkMobile === false) {
            $this->returnJson('手机格式错误', 0);
        }
        $whereMobile['mobile']=$mobile;
        $exitMobile=M('user')->where($whereMobile)->find();
        if($exitMobile){
            $this->returnJson('手机号已经存在', 0);
        }
        $codeInfo = $this->checkCode($mobile);
        if ($codeInfo['code'] != $posts['code'] || $codeInfo['mobile'] != $mobile) {
            $this->returnJson('手机验证码错误', 0);
        }
        $arr = array(
            'mobile' => $mobile,
        );

        $addInfo = M('user')->where($condition)->save($arr);
        if ($addInfo) {
            $this->returnJson('绑定成功', 1);
        } else {
            $this->returnJson('绑定失败', 0);
        }
    }
    //添加我的预约===已经弃用
    public function addSubscribe()
    {
        $posts = $this->getData();
        $openid = $posts['openid'];
        //$city_id = intval($posts['city_id']);
        if (IS_POST) {
            //添加
            if (empty($openid)) $this->returnJson('用户id必须填写', 0);
            $add['job_type'] = $posts['job_type'];
            $add['area_id'] = $posts['area_id'];
            $add['user_id'] = $posts['user_id'];
            $add['openid'] = $posts['openid'];
            $add['token'] = get_token();
            $add['work_time_type'] = $posts['work_time_type'];
            $add['ctime'] = time();
            $rs = M('job_subscribe')->add($add);
            if ($rs) {
                $this->returnJson('预约成功', 1);
            } else {
                $this->returnJson('预约失败', 0);
            }
        } else {
            $city_id = 270;
            $map['openid'] = $openid;
            $jobType = M('job_name')->where('status=1')->field('id,name')->select();
            $workTimeType = C('WORK_TIME_TYPE');
            $areaInfo = M('area')->where('city_id=' . $city_id)->field('id,name')->select();
            $data['jobType'] = $jobType;
            $data['workTimeType'] = $workTimeType;
            $data['areaInfo'] = $areaInfo;
            //dump($data);die();
            if ($data) {
                $this->returnJson('预约新增成功', 1, $data);
            } else {
                $this->returnJson('预约新增失败', 0);
            }
        }
    }
    //修改我的预约 +获取详细情况===已经弃用
    public function saveSubscribe()
    {
        $posts = $this->getData();
        $openid = I('openid');
        $type = I('type');
        if (empty($openid)) $this->returnJson('用户id必须填写', 0);
        if ($type == 1) {
            //修改
            $arr['job_type'] = $posts['job_type'];
            $arr['area_id'] = $posts['area_id'];
            $arr['user_id'] = $posts['user_id'];
            $arr['openid'] = $openid;
            $arr['token'] = get_token();
            $arr['work_time_type'] = $posts['work_time_type'];
            $map['openid'] = $openid;
            $rs = M('job_subscribe')->where($map)->save($arr);
            if ($rs) {
                $this->returnJson('预约编辑成功', 1);
            } else {
                $this->returnJson('预约编辑失败', 0);
            }
        } else {
            $city_id = 270;
            $map['openid'] = $openid;
            //我的预约
            $subscribeInfo = M('job_subscribe')->where($map)->find();
            if ($subscribeInfo) {
                $data['is_has'] = 1;
            } else {
                $data['is_has'] = 0;
            }
            $jobType = M('job_name')->where('status=1')->field('id,name')->select();
            $workTimeType = C('WORK_TIME_TYPE');
            $areaInfo = M('area')->where('city_id=' . $city_id)->field('id,name')->select();
            $job_type = explode(',', $subscribeInfo['job_type']);
            $work_time_type = explode(',', $subscribeInfo['work_time_type']);
            $area_id = explode(',', $subscribeInfo['area_id']);
            /*$jobType        = $this->is_choose($jobType,$job_type);
            $workTimeType   = $this->is_choose($workTimeType,$work_time_type);
            $areaInfo       = $this->is_choose($areaInfo,$area_id);*/
            foreach ($jobType as $key => $value) {
                if (in_array($value['id'], $job_type)) {
                    $jobType[$key]['is_choose'] = 1;//已选择
                } else {
                    $jobType[$key]['is_choose'] = 0;//否
                }
            }
            foreach ($workTimeType as $key => $value) {
                if (in_array($value['id'], $work_time_type)) {
                    $workTimeType[$key]['is_choose'] = 1;//已选择
                } else {
                    $workTimeType[$key]['is_choose'] = 0;//否
                }
            }
            foreach ($areaInfo as $key => $value) {
                if (in_array($value['id'], $area_id)) {
                    $areaInfo[$key]['is_choose'] = 1;//已选择
                } else {
                    $areaInfo[$key]['is_choose'] = 0;//否
                }
            }
            $data['jobType'] = $jobType;
            $data['workTimeType'] = $workTimeType;
            $data['areaInfo'] = $areaInfo;
            $data['subscribeInfo'] = $subscribeInfo;
            if ($data) {
                $this->returnJson('获取预约信息成功', 1, $data);
            } else {
                $this->returnJson('获取预约信息失败', 0);
            }
        }
    }
    //我的预约列表
    public function subscribeInfo()
    {
        $posts = $this->getData();
        $openid = I('openid');
        if (empty($openid)) $this->returnJson('用户id必须填写', 0);
        $page = empty(intval($posts['page'])) ? 1 : intval($posts['page']);
        $limit = empty(intval($posts['limit'])) ? 10 : intval($posts['limit']);
        $mapInfo['openid'] = $openid;
        $subscribeInfo = M('job_subscribe')->where($mapInfo)->find();
        if (empty($subscribeInfo)) {
            $this->returnJson('获取信息失败', 0);
        }
        $map['jname_id'] = array('in', $subscribeInfo['job_type']);
        $map['work_time_type'] = array('in', $subscribeInfo['work_time_type']);
        $map['area_id'] = array('in', $subscribeInfo['area_id']);
        $subscribeInfo = M('job')->where($map)
            ->field('id,img_url,title,area_id,start_time,end_time,salary,work_time_type')
            ->page($page, $limit)
            ->order('id desc')->select();
        foreach ($subscribeInfo as $key => $value) {
            $subscribeInfo[$key]['img_url'] = get_picture_url($value['img_url']);
            $subscribeInfo[$key]['area_str'] = get_about_name($value['area_id'], 'area');
            $subscribeInfo[$key]['work_time_type'] = get_work_time_type($value['work_time_type']);
        }
        $data['subscribeInfo'] = $subscribeInfo;
        $this->returnJson('获取信息成功', 1, $data);
    }
    //获取标签信息
    public function mySubscribe()
    {
        if (empty(I('city_id'))) {
            $city_id = 270;
        } else {
            $city_id = I('city_id');
        }
        $jobType = M('job_name')->where('status=1')->field('id,name')->select();
        $workTimeType = C('WORK_TIME_TYPE');
        $areaInfo = M('area')->where('city_id=' . $city_id)->field('id,name')->select();
        $data['jobType'] = $jobType;
        $data['workTimeType'] = $workTimeType;
        $data['areaInfo'] = $areaInfo;
        if ($data) {
            $this->returnJson('获取标签成功', 1, $data);
        } else {
            $this->returnJson('获取标签失败', 0);
        }
    }
    //个人中心
    public function personally()
    {
        $openid = I('openid');
        if (empty($openid)) {
            $this->returnJson('openid不能为空', 0);
        }
        $whereSalary['openid'] = $openid;
        //我已经赚到的钱
//        $count_salary = M('userSalaryLogs')->find();
        $count_salary = M('user_salary_logs')->where($whereSalary)->sum('salary');//已经赚过钱
        //可以提现
        $userInfo = M('user')->where($whereSalary)->field('salary,bond')->find();//可提现salary bond 保证金
        if (empty($userInfo)) {
            $data['data']['salary'] = 0.00;
            $data['data']['bond'] = 0.00;
        } else {
            $data['data']['salary'] = $userInfo['salary'];
            $data['data']['bond'] = $userInfo['bond'];
        }
        $messageInfo = M('user_message')->where($whereSalary)->count();
        //系统保证金
        $db_config = D('Common/AddonConfig')->get('UserCenter');
        $bond = $db_config['set_bond'];

        $data['data']['sys_bond'] = $bond;
        $data['data']['count_salary'] = $count_salary;
        $data['data']['message_count'] = $messageInfo;
        if ($data) {
            $this->returnJson('获取信息成功', 1, $data);
        } else {
            $this->returnJson('获取信息失败', 0);
        }
    }
    //领工资
    public function salary()
    {
        $posts = $this->getData();
        $openid = $posts['openid'];
        if (empty($openid)) {
            $this->returnJson('openid不能为空', 0);
        }
        $where['openid'] = $openid;
        //可以提现
        $user_info = M('user')->where($where)->find();
        $data['salary']   = $user_info['salary'];
        $data['truename']       = empty($user_info['truename'])?'':$user_info['truename'];
        $data['bank_number']    = empty($user_info['bank_number'])?'':$user_info['bank_number'];
        $data['bank_subbranch'] = empty($user_info['bank_subbranch'])?'':$user_info['bank_subbranch'];
        $data['service_charge'] = C('service_charge');
        if ($data) {
            $this->returnJson('操作成功', 1, $data);
        } else {
            $this->returnJson('操作失败', 0);
        }
    }
    /**
     * 反馈信息
     */
    function feedBack()
    {
        $content = I('content');
        $openid = I('openid');
        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        if (empty($content)) $this->returnJson('内容不能为空', 0);
        if (!$this->checkMemberOpenid($openid)) {
            $this->returnJson('该用户不存在', 0);
        }
        $where['openid'] = $openid;
        $data['ctime'] = time();
        $data['token'] = get_token();
        $data['content'] = $content;
        $result = M('feedback')->where($where)->add($data);
        $addData = M('feedback')->find($result);
        if ($result) {
            $this->returnJson('新增成功', 1, $addData);
        } else {
            $this->returnJson('新增失败', 0);
        }
    }
    /**
     *添加保证金日志
     * sean
     */
    function addBond()
    {
        $openid = I('openid');
        $user_id = I('user_id');
        $map ['name'] = 'UserCenter';
        $db_config = D('Common/AddonConfig')->get('UserCenter');
        $bond = $db_config['set_bond'];

        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        if (!$this->checkMemberOpenid($openid)) {
            $this->returnJson('该用户不存在', 0);
        }
        $token = get_token();
        // 获取模型信息
        $configPayment = M("payment_set")->where(array(
            "token" => $token
        ))->find();
        $data['user_id'] = $user_id;
        $data['openid'] = $openid;
        $data['ctime'] = time();
        $data['status'] = 0;//支付状态0 没有支付
        $data['bond'] = $bond;
        $data['token'] = $token;
        $data['ip'] = $_SERVER['SERVER_ADDR'];
        $data['order_number'] = $configPayment ['mch_id'] . date(Ymd) . $this->getRandStr(); // 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
        $result = M('UserBondLogs')->add($data);
        if ($result) {
//            $field='';
//            $return_data['data']['orderInfo'] = M('UserBondLogs')->setField($field)->find($result);
            $dataWeixin = new MiniProgramController();
            $return_data['data']['paymentInfo'] = $dataWeixin->to_pay($bond, $openid, $data['order_number']);
            $this->returnJson('新增成功，请及时支付', 1, $return_data);
        } else {
            $this->returnJson('新增失败', 0);
        }
    }
    /**
     * @name:退保证金
     * @author:sean
     * @param:openid;
     */
    function backBond()
    {
        $openid = I('openid');
        $user_id = I('user_id');
        $map ['name'] = 'UserCenter';
        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        if (empty($openid)) $this->returnJson('用户不能为空', 0);
        if (!$this->checkMemberOpenid($openid)) {
            $this->returnJson('该用户不存在', 0);
        }

        $whereBond['user_id']=$user_id;
        $whereBond['status']=0;//申请中
        $whereBond['type']=1;//退回

        $result_bond = M('UserBondLogs')->where($whereBond)->find();
        if($result_bond){
            $this->returnJson('已经提交审核了，不能重复提交', 0);
        }

        $data['openid'] = $openid;
        $data['ctime'] = time();
        $data['status'] = 0;//支付状态0 没有支付
        $data['bond'] = $result_bond['bond'];
        $data['token'] = get_token();
        $data['user_id'] = $user_id;
        $data['type'] = 1;
        $data['ip'] = get_client_ip();
        $data['order_number'] = date(Ymd) . $this->getRandStr(); // 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
        $result = M('UserBondLogs')->add($data);
        if ($result) {
            $this->returnJson('申请成功，请耐心等待', 1);
        } else {
            $this->returnJson('申请失败', 0);
        }
    }
    function getRandStr()
    {
        $arr = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z'
        );
        $key = array_rand($arr);
        return substr(time(), -5) . substr(microtime(), 2, 4) . $arr [$key];
    }

    //申请提取工资
    public function applyMoney()
    {
        $posts = $this->getData();
        if(empty(I('openid')))              $this->returnJson('openid不能为空', 0);
        if(empty($posts['bank_number']))    $this->returnJson('银行账号不能为空', 0);
        if(empty($posts['bank_subbranch'])) $this->returnJson('开户行名称不能为空', 0);
        $openid = I('openid');
        //验证银行卡号
        if(regular_verify($posts['bank_number'],'bank')) $this->returnJson('请输入正确的银行卡号', 0);
        if(!$this->checkMemberOpenid($openid))           $this->returnJson('该用户不存在', 0);

        //判断该用户是否有正在申请的提现
        $map['openid'] = $openid;
        $map['status'] = array('in','0,1');
        $cash_info     = M('user_cash_logs')->where($map)->find();
        if($cash_info) $this->returnJson('您申请的提现正在处理中 请耐心等待', 0);
        //姓名 银行账号bank_number 开户行bank_subbranch
        $user_map['openid'] = $openid;
        $user_info = M('user')->where($user_map)->find();
        //判断申请金额是否大于系统金额
        if($posts['money'] > $user_info['salary']) $this->returnJson('您申请的金额大于 系统应有金额', 0);
        if(empty($user_info['bank_number']) || empty($user_info['bank_subbranch']) || empty($user_info['truename'])){
            //如果不存在  添加信息
            $user_arr = array(
                'bank_number'    => $posts['bank_number'],
                'bank_subbranch' => trim($posts['bank_subbranch']),
                'truename'       => trim($posts['truename']),
            );
            M('user')->where($user_map)->save($user_arr);
        }
        $cash_arr['openid']  = $openid;
        $cash_arr['user_id'] = $user_info['uid'];
        $cash_arr['ctime']  = time();
        $cash_arr['token']  = -1;
        $cash_arr['money']  = $posts['money'];
        $cash_arr['status'] = 0;
        $cash_arr['remark'] = '用户openid为'.$openid.' 申请提现:'.$posts['money'];
        $res = M('user_cash_logs')->add($cash_arr);
        if($res){
            //信息提交成功 则减去该用户工资
            M('user')->where($user_map)->setDec('salary',$posts['money']);
            $this->returnJson('申请成功，请耐心等待', 1);
        }else{
            $this->returnJson('申请失败', 0);
        }
    }

    //申请提取工资记录
    public function applyMoneyList(){
        if(empty(I('openid'))) $this->returnJson('openid不能为空', 0);
        $openid = I('openid');
        if (!$this->checkMemberOpenid($openid)) {
            $this->returnJson('该用户不存在', 0);
        }
        $map['openid'] = $openid;
        $cash_info = M('user_cash_logs')->where($map)->select();
        foreach($cash_info as $key=>$value){
            $cash_info[$key]['ctime'] = date('Y-m-d H:i:s',$value['ctime']);
        }
        if($cash_info){
            $data['cash_info'] = $cash_info;
            $this->returnJson('获取数据成功', 1,$data);
        }else{
            $this->returnJson('获取数据失败', 0);
        }
    }




}
