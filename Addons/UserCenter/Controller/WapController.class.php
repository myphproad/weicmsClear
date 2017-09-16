<?php
namespace Addons\UserCenter\Controller;
use Home\Controller\AddonsController;
use Addons\Payment\Controller\MiniProgramController;
header("Content-Type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
class WapController extends AddonsController
{
    // 一键绑定
    function bind()
    {
        if ((defined('IN_WEIXIN') && IN_WEIXIN) || isset ($_GET ['is_stree']) || !C('USER_OAUTH'))
            return false;
        $isWeixinBrowser = isWeixinBrowser();
        if (!$isWeixinBrowser) {
            $this->error('请在微信里打开');
        }
        $info = get_token_appinfo();
        $param ['appid'] = $info ['appid'];
        $callback = U('bind');
        if ($_GET ['state'] != 'weiphp') {
            $param ['redirect_uri'] = $callback;
            $param ['response_type'] = 'code';
            $param ['scope'] = 'snsapi_userinfo';
            $param ['state'] = 'weiphp';
            $info ['is_bind'] && $param ['component_appid'] = C('COMPONENT_APPID');
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query($param) . '#wechat_redirect';
            redirect($url);
        } elseif ($_GET ['state'] == 'weiphp') {
            if (empty ($_GET ['code'])) {
                exit ('code获取失败');
            }
            $param ['code'] = I('code');
            $param ['grant_type'] = 'authorization_code';
            if ($info ['is_bind']) {
                $param ['appid'] = I('appid');
                $param ['component_appid'] = C('COMPONENT_APPID');
                $param ['component_access_token'] = D('Addons://PublicBind/PublicBind')->_get_component_access_token();
                $url = 'https://api.weixin.qq.com/sns/oauth2/component/access_token?' . http_build_query($param);
            } else {
                $param ['secret'] = $info ['secret'];
                $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query($param);
            }
            $content = file_get_contents($url);
            $content = json_decode($content, true);
            if (!empty ($content ['errmsg'])) {
                exit ($content ['errmsg']);
            }
            $suburl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . get_access_token() . '&openid=' . $content ['openid'] . '&lang=zh_CN';
            $data = file_get_contents($suburl);
            $data = json_decode($data, true);
            $subscribe = $data ['subscribe'];
            if (!empty ($data ['errmsg'])) {
                exit ($data ['errmsg']);
            }
            $data ['status'] = 2;
            empty ($data ['headimgurl']) && $data ['headimgurl'] = ADDON_PUBLIC_PATH . '/default_head.png';
            $uid = D('Common/Follow')->init_follow($content ['openid'], $info ['token']);
            D('Common/User')->updateInfo($uid, $data);
            if ($subscribe) {
                D('Common/Follow')->set_subscribe($content ['openid'], 1);
            }
            $url = Cookie('__forward__');
            if ($url) {
                Cookie('__forward__', null);
            } else {
                $url = U('userCenter');
            }
            redirect($url);
        }
    }
    // 绑定领奖信息
    function bind_prize_info()
    {
        // dump ( $this->mid );
        $map ['id'] = $this->mid;
        // dump($this->mid);
        if (IS_POST) {
            $data ['mobile'] = I('mobile');
            $data ['truename'] = I('truename');
            D('Common/Follow')->update($this->mid, $data);
            $url = Cookie('__forward__');
            if ($url) {
                Cookie('__forward__', null);
            } else {
                $url = U('userCenter');
            }
            redirect($url);
        } else {
            $info = get_followinfo($this->mid);
            $this->assign('info', $info);
            $this->assign('meta_title', '领奖联系信息');
            $this->display();
        }
    }
    // 第一步绑定手机号
    function bind_mobile()
    {
        if (IS_POST) {
            $map ['mobile'] = I('mobile');
            $dao = D('Common/Follow');
            // 判断是否已经注册过
            $user = $dao->where($map)->find();
            if (!$user) {
                $uid = $dao->init_follow_by_mobile($map ['mobile']);
                $dao->where($map)->setField('status', 0);
                $user = $dao->where($map)->find();
            }
            $map2 ['openid'] = get_openid();
            if ($map2 ['openid'] != -1) {
                $map2 ['token'] = get_token();
                $uid2 = M('public_follow')->where($map2)->getField('uid');
                if (!$uid2) {
                    $map2 ['mobile'] = $map ['mobile'];
                    $map2 ['uid'] = $user ['id'];
                    M('public_follow')->add($map2);
                }
            } else {
                $uid = M('public_follow')->where($map)->getField('uid');
                if (!$uid) {
                    $data ['mobile'] = $map ['mobile'];
                    $data ['uid'] = $user ['id'];
                    M('public_follow')->add($data);
                }
            }
            session('mid', $user ['id']);
            if ($user ['status'] == 1) {
                $url = Cookie('__forward__');
                if ($url) {
                    Cookie('__forward__', null);
                } else {
                    $url = U('userCenter');
                }
            } else {
                $url = U('bind_info');
            }
            $this->success('绑定成功', $url);
        } else {
            if ($this->mid > 0) {
                redirect(U('userCenter'));
            }
            $this->assign('meta_title', '绑定手机');
            $this->display();
        }
    }
    // 第二步初始化资料
    function bind_info()
    {
        $model = $this->getModel('user');
        if (IS_POST) {
            $map ['id'] = $this->mid;
            $url = Cookie('__forward__');
            if ($url) {
                Cookie('__forward__', null);
            } else {
                $url = U('userCenter');
            }
            $save ['nickname'] = I('nickname');
            $save ['sex'] = I('sex');
            $save ['email'] = I('email');
            $save ['status'] = 2;
            $res = D('Common/User')->updateInfo($this->mid, $save);
            if ($res) {
                $this->success('保存成功！', $url);
            } else {
                $this->error($Model->getError());
            }
        } else {
            $fields = get_model_attribute($model ['id']);
            // dump($fields);
            $fieldArr = array(
                'nickname',
                'sex',
                'email'
            ); // headimgurl
            foreach ($fields as $k => $vo) {
                if (!in_array($vo ['name'], $fieldArr))
                    unset ($fields [$k]);
            }
            // 获取数据
            $data = M(get_table_name($model ['id']))->find($this->mid);
            // 自动从微信接口获取用户信息
            empty ($openid) || $info = getWeixinUserInfo($openid, $token);
            if (is_array($info)) {
                if (empty ($data ['headimgurl']) && !empty ($info ['headimgurl'])) {
                    // 把微信头像转到WeiPHP的通用图片ID保存 TODO
                    $data ['headimgurl'] = $info ['headimgurl'];
                }
                $data = array_merge($info, $data);
            }
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->assign('meta_title', '填写资料');
            $this->display();
        }
    }
    /**
     * 显示微信用户列表数据
     */
    function userCenter()
    {
        // dump ( $this->mid );
        if ($this->mid < 0) {
            Cookie('__forward__', $_SERVER ['REQUEST_URI']);
            redirect(U('bind'));
        }
        // 商城版的直接在商城个人中心里
        if (is_install('Shop')) {
            redirect(addons_url('Shop://Wap/user_center'));
        }
        $info = get_followinfo($this->mid);
        $this->assign('info', $info);
        // dump ( $info );
        // 插件扩展
        $dirs = array_map('basename', glob(ONETHINK_ADDON_PATH . '*', GLOB_ONLYDIR));
        if ($dirs === FALSE || !file_exists(ONETHINK_ADDON_PATH)) {
            $this->error('插件目录不可读或者不存在');
        }
        $arr = array();
        $_REQUEST ['doNotInit'] = 1;
        foreach ($dirs as $d) {
            require_once ONETHINK_ADDON_PATH . $d . '/Model/WeixinAddonModel.class.php';
            $model = D('Addons://' . $d . '/WeixinAddon');
            if (!method_exists($model, 'personal'))
                continue;
            $lists = $model->personal($data, $keywordArr);
            if (empty ($lists) || !is_array($lists))
                continue;
            if (isset ($lists ['url'])) {
                $arr [] = $lists;
            } else {
                $arr = array_merge($arr, $lists);
            }
        }
        foreach ($arr as $vo) {
            if (empty ($vo ['group'])) {
                $default_link [] = $vo;
            } else {
                $list_data [$vo ['group']] [] = $vo;
            }
        }
        $this->assign('default_link', $default_link);
        $this->assign('list_data', $list_data);
        // 会员页
        $this->display();
    }
    // 积分记录
    function credit()
    {
        $model = $this->getModel('credit_data');
        $map ['token'] = get_token();
        session('common_condition', $map);
        parent::common_lists($model, 0, 'Addons/lists');
    }
    function storeCenter()
    {
        if (!is_login()) {
            Cookie('__forward__', $_SERVER ['REQUEST_URI']);
            redirect(U('home/user/login', array(
                'from' => 2
            )));
        }
        $this->mid = 382;
        $info = get_userinfo($this->mid);
        $this->assign('info', $info);
        // dump ( $info );
        // 取优惠券
        $map ['shop_uid'] = $this->mid;
        $list = M('coupon')->where($map)->select();
        $this->assign('coupons', $list);
        // dump($list);
        // 商家中心
        $this->display();
    }
    // 检查公众号基础功能
    function check()
    {
        $map ['token'] = I('token');
        $info = M('public')->where($map)->find();
        $type = $info ['type'];
        // 获取微信权限节点
        $map2 ['type_' . $type] = 1;
        $auth = M('public_auth')->where($map2)->getFields('name,title');
        $res ['msg'] = '';
        // 获取access_token
        $access_token = get_access_token($token);
        if (empty ($access_token)) {
            addAutoCheckLog('access_token', 'access_token获取失败', $info ['token']);
        } else {
            addAutoCheckLog('access_token', '', $info ['token']);
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $access_token;
        $res = wp_file_get_contents($url);
        $res = json_decode($res, true);
        if ($res ['errcode'] == '40001') {
            addAutoCheckLog('access_token_check', $res ['errcode'] . ': ' . $res ['errmsg'], $info ['token']);
        } else {
            addAutoCheckLog('access_token_check', '', $info ['token']);
        }
        // 收发消息
        $xml = '<xml><ToUserName><![CDATA[' . $info ['token'] . ']]></ToUserName>
<FromUserName><![CDATA[oikassyZe6bdupvJ2lq-majc_rUg]]></FromUserName>
<CreateTime>1464254617</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[自动检测]]></Content>
<MsgId>6288925693437624122</MsgId>
</xml>';
        $param ['id'] = $info ['id'];
        $param ['signature'] = 'd3e1ce50d26db638e6a03e1c8bf6b23b4fdbdd87';
        $param ['timestamp'] = '1464254754';
        $param ['nonce'] = '407622025';
        $url = U('home/weixin/index', $param);
        $res = $this->curl_data($url, $xml);
        if (strpos($res, 'auto_check')) {
            addAutoCheckLog('massage', '', $info ['token']);
        } else {
            addAutoCheckLog('massage', '收发消息失败', $info ['token']);
        }
        $nextUrl = U('check2', array(
            'token' => $info ['token']
        ));
        $this->assign('nextUrl', $nextUrl);
        $this->display();
    }
    function check2()
    {
        $token = I('token');
        // get_openid
        $callback = GetCurUrl();
        $openid = OAuthWeixin($callback, $token, true);
        if (empty ($openid) || $openid == '-1' || $openid == '-2') {
            addAutoCheckLog('openid', '获取openid失败', $token);
        } else {
            addAutoCheckLog('openid', '', $token);
        }
        addAutoCheckLog('jsapi', '', $token);
        $this->display();
    }
    function check3()
    {
        $token = I('token');
        $msg = I('msg');
        addAutoCheckLog('jsapi', $msg, $token);
    }
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
            ->field('a.status,a.id,j.ctime,j.title,j.area_id,j.salary,j.work_time_type,j.img_url')
            ->page($page, $limit)
            ->order('j.id desc')
            ->select();
        $work_time_type = C('WORK_TIME_TYPE');
        foreach ($data as $key => $value) {
            $data[$key]['area'] = get_about_name($value['area_id'], 'area');
            $data[$key]['ctime'] = date('Y-m-d', $value['ctime']);
            $data[$key]['img_url'] = get_picture_url($value['img_url']);
            foreach ($work_time_type as $kk => $val) {
                if ($value['work_time_type'] == $val['id']) {
                    $data[$key]['work_time_type'] = $val['name'];
                }
            }/* if (0 == $value['status']) {
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
                    ->field('id,img_url,title,area_id,start_time,end_time,salary')
                    ->order('id desc')->find();
                $collectInfo[$key]['img_url'] = get_picture_url($job_result['img_url']);
                $collectInfo[$key]['area_str'] = get_about_name($job_result['area_id'], 'area');
                $collectInfo[$key]['start_time'] = get_month_day($job_result['start_time']);
                $collectInfo[$key]['end_time'] = get_month_day($job_result['end_time']);
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
        if ($data) {
            $this->returnJson('操作成功', 1, $data);
        } else {
            $this->returnJson('操作失败', 0);
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
        $messageInfo['comment'] = filter_line_tab($messageInfo['comment']);
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
        $field = 'truename,sex,mobile,height,weight,birthday,school,uid';
        //存在用户
        if (1 == $type) {
            //编辑资料提交
            if (empty($posts['truename'])) $this->returnJson('姓名不能为空', 0);
            if (empty($posts['mobile'])) $this->returnJson('手机号不能为空', 0);
            if (empty($posts['code'])) $this->returnJson('验证码不能为空', 0);
            /*$userInfo['height'] = empty($value['height'])?'':$value['height'];
            $userInfo['weight'] = empty($value['weight'])?'':$value['weight'];
            $userInfo['school'] = empty($value['school'])?'':$value['school'];
            $userInfo['birthday'] = empty($value['birthday'])?'':$value['birthday'];*/
            $checkMobile = $this->isMobile($mobile);
            if ($checkMobile === false) {
                $this->returnJson('手机格式错误', 0);
            }
            $codeInfo = $this->checkCode($mobile);
            if ($codeInfo['code'] != $posts['code'] || $codeInfo['mobile'] != $mobile) {
                $this->returnJson('手机验证码错误', 0);
            }
            $arr = array(
                'truename' => $posts['truename'],
                'sex' => $posts['sex'],
                'mobile' => $mobile,
                'height' => $posts['height'],
                'weight' => $posts['weight'],
                'birthday' => $posts['birthday'],
                'school' => $posts['school'],
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
            $university=M('University')->field('title,id')->select();
            foreach($university as $key=>$value){
                if ($value['id'] == $userInfo['school']) {
                    $university[$key]['is_choose'] = 1;//已选择
                } else {
                    $university[$key]['is_choose'] = 0;//否
                }
            }
            $userInfo['university'] = $university;
            $userInfo['weight'] = $weight;
            $data['userInfo'] = $userInfo;
            if ($data) {
                $this->returnJson('获取数据成功', 1, $data);
            } else {
                $this->returnJson('获取数据操作失败', 0);
            }
        }
    }
    //添加我的预约
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
    //修改我的预约 +获取详细情况
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
        $salary = M('user')->where($where)->getField('salary');
        $data['salary'] = $salary;
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
        $map ['name'] = 'UserCenter';
        if (empty($openid)) $this->returnJson('openid不能为空', 0);
        if (!$this->checkMemberOpenid($openid)) {
            $this->returnJson('该用户不存在', 0);
        }
        $where['openid'] = $openid;
        $where['status'] = 1;
        $result_bond = M('UserBondLogs')->where($where)->order('ctime desc')->limit(1)->select();
        $token = get_token();
        $data['openid'] = $openid;
        $data['ctime'] = time();
        $data['status'] = 0;//支付状态0 没有支付
        $data['bond'] = $result_bond['bond'];
        $data['token'] = $token;
        $data['type'] = 1;
        $data['ip'] = get_client_ip();
        $data['order_number'] = date(Ymd) . $this->getRandStr(); // 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
        $result = M('UserBondLogs')->add($data);
        if ($result) {
            $this->returnJson('申请成功，请耐心等待', 1, $result);
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
}
